<?php

namespace App\Http\Controllers\Admin;

use App\Commons\Consts\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdatePaymentStatusOrderRequest;
use App\Http\Requests\UpdateStatusOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware('auth:admin');
        $this->orderRepository = $orderRepository;
    }
    //Dropdown list
    public function getCategories()
    {
        return [
            "categories" => $this->orderRepository->getCategories(),
            "all_products" => Product::where("published", 1)->count()
        ];
    }
    public function getBrands()
    {
        return [
            "brands" => $this->orderRepository->getBrands(),
            "all_products" => Product::where("published", 1)->count()
        ];
    }
    public function getLocations()
    {
        return $this->orderRepository->getLocations();
    }
    public function getProducts()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->orderRepository->getProducts(
            $text,
            request()->page_size,
            request()->category_id,
            request()->brand_id,
            request()->location_id,
        );
    }
    public function storeOrder(OrderRequest $orderRequest)
    {
        $order = $this->orderRepository->storeOrder($orderRequest->validated());
        return response()->json("Item created successfully with id :" . $order["order"]->id);
    }
    public function getOrders()
    {
        return $this->orderRepository->getOrders(
            request()->page_size,
            request()->code,
            request()->payment_status,
            request()->status,
            request()->location_id,
        );
    }
    public function getOrder($id)
    {
        return $this->orderRepository->getOrder($id);
    }
    public function updateOrderPaymentStatus(UpdatePaymentStatusOrderRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        $this->orderRepository->updateOrder($request->validated());
        $this->orderRepository->logOrderStatus(
            $authUserId,
            $request->input("payment_status"),
            "payment_status"
        );
        return response()->json("Order's payment status updated and logged successfully");
    }
    public function updateOrderStatus(UpdateStatusOrderRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        DB::transaction(function () use ($request, $authUserId) {
            $this->orderRepository->updateOrder($request->validated());
            $this->orderRepository->logOrderStatus(
                $authUserId,
                $request->input("status"),
                "status"
            );
        });
        return response()->json("Order's status updated and logged successfully");
    }
    public function getOrderLogs()
    {
        return $this->orderRepository->getOrderLogs();
    }
    public function getCustomers()
    {
        return Customer::get();
    }
    public function createCustomer(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
    }
    public function completeOrder($id)
    {
        $order = Order::find($id);
        $order->status = "DELIVERED";
        $order->payment_status = PaymentStatus::PAID;
        $order->save();
    }
}
