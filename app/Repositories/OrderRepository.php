<?php

namespace App\Repositories;

use App\Commons\Consts\OrderCategorize;
use App\Commons\Consts\OrderType;
use App\Commons\Consts\PaymentStatus;
use App\Exceptions\NotFoundException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderLog;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\VariationValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    //Dropdown list
    public function getCategories()
    {
        $categroies = Category::withCount("products")->with("mediaManager")->get();
        return $categroies->map(function ($category) use ($categroies) {
            $category->all_products_count = $this->getTotalProducts($categroies, $category);
            return $category;
        });
    }
    public function getBrands()
    {
        return Brand::where("status", 1)->withCount("products")->with("mediaManager")->get();
    }
    public function getLocations()
    {
        return Location::where("published", 1)->get();
    }
    public function getProducts($text, $page_size, $category_id, $brand_id, $location_id)
    {
        $products =  Product::where("published", 1)->where(function ($q) use ($text) {
            $q
                ->where("name_ar", "like", "%" . strtolower($text) . '%')
                ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
        })
            ->when($category_id, function ($q) use ($category_id) {
                $q->whereRelation("categories", "category_id", $category_id);
            })
            ->when($brand_id, function ($q) use ($brand_id) {
                $q->where("brand_id", $brand_id);
            })
            ->with(["variations" => function ($q) {
                $q->whereNull("location_id");
            }])
            ->with(["locationVariations" => function ($q) use ($location_id) {
                $q->where("location_id", $location_id);
            }])
            ->with(["mediaManager", "variationIds.variation"])
            ->orderBy("id", "desc")
            ->paginate($page_size);

        $products->transform(function ($product) {
            $product->max_price = $product->variations->max(function ($variation) use ($product) {
                $discount = $product->percent == 1 ? ($product->discount / 100) * $variation->price
                    : $product->discount;
                return $variation->price - $discount;
            });
            $product->variationIds = $product->variationIds->map(function ($varId) {
                $varId->variation_values = VariationValue::whereIn("id", $varId->variation_values_ids)->get();
                return $varId;
            });
            $product->min_price = $product->variations->min(function ($variation) use ($product) {
                $discount = $product->percent == 1 ? ($product->discount / 100) * $variation->price
                    : $product->discount;
                return $variation->price - $discount;
            });
            $product->locationVariations = $product->locationVariations->map(function ($variation) use ($product) {
                $discount = $product->percent == 1 ? ($product->discount / 100) * $variation->price
                    : $product->discount;
                $variation->price = $variation->price - $discount;
                return $variation;
            });
            $product->setHidden(["variations"]);
            return $product;
        });
        return $products;
    }
    public function storeOrder($input)
    {
        return DB::transaction(function () use ($input) {
            $code = $this->getCode();
            $input["code_counter"] = $code["code_counter"];
            $input["code"] = $code["code"];
            $input["categorize"] = OrderCategorize::POS;
            $input["type"] = OrderType::REGULAR;
            $input["payment_status"] = $input["status"] == "DELIVERED" ? PaymentStatus::PAID : PaymentStatus::UNPAID;
            $stocks = $this->getStocks($input["order_details"]);
            $input["tax"] = $this->getTotalTax($stocks, $input["order_details"]);
            $input["discount"] = $this->getTotalDiscount($stocks, $input["order_details"]);
            $input["sub_total"] = $this->getSubTotal($input["order_details"], $stocks);
            $additionalDiscountValue = $this->getAdditionalDiscount($input);
            $input["grand_total"] = $input["sub_total"] + $input["tax"] + $input["shipping_charge"]
                - $input["discount"] - $additionalDiscountValue;
            $this->updateStocksQuantity($stocks, $input["order_details"]);
            $order = Order::create($input);
            //Store order details
            $orderDetailsInput = $this->mapOderDetails($input, $stocks, $order);
            OrderDetail::insert($orderDetailsInput);
            //Set some information for invoice pdf
            $order->location = Location::find($input["location_id"]);
            $stocksIds = array_map(function ($o) {
                return $o["stock_id"];
            }, $orderDetailsInput);
            $stocks = Stock::whereIn("id", $stocksIds)->with("product")->get();
            $orderDetailsInput = collect($orderDetailsInput)->map(function ($orderDetail) use ($stocks) {
                $orderDetail["product"] = $stocks->filter(function ($stock) use ($orderDetail) {
                    return $stock->id == $orderDetail["stock_id"];
                })->first()->product;
                return $orderDetail;
            })->toArray();
            return ["order" => $order, "order_details" => $orderDetailsInput];
        });
    }
    public function updateStocksQuantity($stocks, $orderDetails)
    {
        foreach ($stocks as $stock) {
            $quantity = 0;
            foreach ($orderDetails as $orderDetail) {
                if ($orderDetail["stock_id"] == $stock->id) {
                    $quantity = $orderDetail["quantity"];
                    break;
                }
            }
            Stock::where("id", $stock->id)->update(["stock" => $stock->stock - $quantity]);
        }
    }
    public function getOrders($page_size, $code, $payment_status, $status, $location_id)
    {
        return Order::when($code, function ($q) use ($code) {
            $q->where("code", "like", "%$code%");
        })
            ->when($payment_status, function ($q) use ($payment_status) {
                $q->where("payment_status", $payment_status);
            })->when($status, function ($q) use ($status) {
                $q->where("status", $status);
            })->when($location_id, function ($q) use ($location_id) {
                $q->whereRelation("orderDetails.stock.location", "id", $location_id);
            })
            ->withCount("orderDetails as items")
            ->with(["orderDetails.stock.location", "customer", "location","orderDetails.stock.product"])
            ->paginate($page_size);
    }
    public function getOrder($id)
    {
        return Order::with(["customer", "orderDetails.stock.product.mediaManager"])->find($id);
    }
    public function updateOrder($input)
    {
        Order::find($input["id"])->update($input);
    }
    public function logOrderStatus($userId, $status, $statusType)
    {
        $input = ["admin_id" => $userId];
        if ($statusType == "payment_status") {
            $input["payment_status"] = $status;
        } else {
            $input["status"] = $status;
        }
        OrderLog::create($input);
    }
    public function getOrderLogs()
    {
        return OrderLog::with("admin")->get();
    }
    //Commons
    private function getTotalProducts($categories, $category, $sum = 0)
    {
        $sum += $category->products_count;
        $childCat = $categories->filter(function ($__category) use ($category) {
            return $__category->base_category_id == $category->id;
        })->first();
        if ($childCat) {
            return $this->getTotalProducts($categories, $childCat, $sum);
        }
        return $sum;
    }

    private function getAdditionalDiscount($input)
    {
        return $input["additional_discount_percent"] ? ($input["additional_discount"] / 100) * $input["sub_total"]
            : $input["additional_discount"];
    }
    private function getTotalTax($stocks, $orderDetails)
    {

        $sum = 0;
        foreach ($stocks as $stock) {
            foreach ($stock->product->taxes as $tax) {
                $taxValue = $tax->pivot->percent ? ($tax->pivot->value / 100) * $stock->price : $tax->pivot->value;
                $quantity = 0;
                foreach ($orderDetails as $orderDetail) {
                    if ($orderDetail["stock_id"] == $stock->id) {
                        $quantity = $orderDetail["quantity"];
                        break;
                    }
                }
                $sum += $taxValue * $quantity;
            }
        }
        return $sum;
    }
    private function getTotalDiscount($stocks, $orderDetails)
    {
        $sum = 0;
        foreach ($stocks as $stock) {
            if (
                Carbon::now()->gte($stock->product->from_date) &&
                Carbon::now()->lte($stock->product->to_date)
            ) {
                $discount = $stock->product->percent ? ($stock->product->discount / 100) * $stock->price
                    : $stock->product->discount;
                $quantity = 0;
                foreach ($orderDetails as $orderDetail) {
                    if ($orderDetail["stock_id"] == $stock->id) {
                        $quantity = $orderDetail["quantity"];
                        break;
                    }
                }
                $sum += $discount * $quantity;
            }
        };
        return $sum;
    }

    private function getStocks($orderDetails)
    {
        $stockIds = collect($orderDetails)->map(function ($orderDetail) {
            return $orderDetail["stock_id"];
        })->toArray();
        return Stock::with("product.taxes")->whereIn("id", $stockIds)->get();
    }
    private function mapOderDetails($input, $stocks, $order)
    {
        return  collect($input["order_details"])->map(function ($orderDetail) use ($stocks, $order) {
            $stock = $stocks->filter(function ($stock) use ($orderDetail) {
                return $stock->id == $orderDetail["stock_id"];
            })->first();

            $orderDetail["price"] =  $stock->price;
            $orderDetail["total_price"] =  $stock->price * $orderDetail["quantity"];
            $orderDetail["order_id"] = $order->id;
            return $orderDetail;
        })->toArray();
    }
    private function getSubTotal($orderDetails, $stocks)
    {
        $subTotal = 0;
        foreach ($orderDetails as $orderDetail) {
            $stock = $stocks->filter(function ($stock) use ($orderDetail) {
                return $stock->id == $orderDetail["stock_id"];
            })->first();
            $subTotal += $orderDetail["quantity"] * $stock->price;
        }
        return $subTotal;
    }
    private function getCode()
    {
        $setting = Setting::first();
        $last_order = Order::orderByDesc("id")->first();
        $codePrefix = $setting ? $setting->code_prefix : "";
        $code_start_from = $setting ? $setting->code_start_form : 1;
        $code = $code_start_from;
        if ($last_order) {
            $code = $last_order->code_counter + 1;
        }
        return [
            "code_counter" => $code,
            "code" => $codePrefix . $code
        ];
    }
}
