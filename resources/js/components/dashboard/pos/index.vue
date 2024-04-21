<template>
  <div class="pos-container container-fluid">
    <!-- Modal -->
    <div
      class="modal fade"
      id="variationModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div
            v-if="currentProduct && currentProduct.has_variations == 1"
            class="modal-body"
          >
            <div class="modal-image mb-3" style="padding: 0 !important">
              <div class="prod-image">
                <img
                  :src="
                    currentProduct.media_manager
                      ? `/uploads/${currentProduct.media_manager.hash_name}`
                      : '/images/placeholder-thumb.png'
                  "
                />
              </div>
              <div class="info">
                <div class="title">
                  {{
                    $i18n.locale == "ar"
                      ? currentProduct.name_ar
                      : currentProduct.name_en
                  }}
                </div>
              </div>
            </div>
            <div class="variations mb-3">
              <div
                v-for="variation in currentProduct.variation_ids"
                :key="variation.id"
                class="variation"
              >
                <div class="title">
                  {{
                    $i18n.locale == "ar"
                      ? variation.variation.name_ar
                      : variation.variation.name_en
                  }}
                </div>
                <div class="var-values">
                  <div
                    @click="selectVarValue(variation, value)"
                    :style="value.selected ? 'background:#f4f6f8' : ''"
                    v-for="value in variation.variation_values"
                    :key="value.id"
                    class="var-value"
                  >
                    {{ $i18n.locale == "ar" ? value.name_ar : value.name_en }}
                  </div>
                </div>
              </div>
            </div>
            <button
              type="button"
              class="cls border btn btn-secondary mt-3"
              data-dismiss="modal"
            >
              {{ $t("CLOSE") }}
            </button>
            <button
              @click="addThisItem"
              type="button"
              class="btn mx-2 select-var btn-primary mt-3"
            >
              {{ $t("Add this item") }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="customerModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12 mb-2">
                <input
                  class="form-control"
                  :placeholder="$t('TYPE_CUSTOMER_NAME')"
                  v-model="name"
                />
              </div>
              <div class="col-lg-12 mb-2">
                <input
                  class="form-control"
                  v-model="email"
                  :placeholder="$t('TYPE_CUSTOMER_EMAIL')"
                />
              </div>
              <div class="col-lg-12 mb-2">
                <input
                  class="form-control"
                  v-model="phone"
                  :placeholder="$t('TYPE_CUSTOMER_PHONE')"
                />
              </div>
            </div>
            <button
              type="button"
              class="cls border btn btn-secondary mt-3"
              data-dismiss="modal"
            >
              {{ $t("CLOSE") }}
            </button>
            <button
              @click="createCustomer"
              :disabled="!email || !phone || !name"
              type="button"
              class="btn mx-2 select-var btn-primary mt-3"
            >
              {{ $t("SUBMIT") }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="tab">
          <div
            class="d-flex justify-content-between mb-3 mt-2"
            style="margin: 0 10px"
          >
            <ul class="nav nav-pills" role="tablist">
              <li class="nav-item">
                <a
                  @click.prevent="onTabClicked"
                  class="nav-link active normal"
                  data-toggle="tab"
                  href="#home5"
                  role="tab"
                  aria-selected="true"
                  >{{ $t("CATEGORIES") }}</a
                >
              </li>
              <li class="nav-item" style="margin: 0 10px">
                <a
                  @click.prevent="onTabClicked"
                  class="nav-link normal"
                  data-toggle="tab"
                  href="#profile5"
                  role="tab"
                  aria-selected="false"
                  >{{ $t("BRANDS") }}</a
                >
              </li>
            </ul>
            <div class="dropdown">
              <button
                class="btn dropdown-toggle location"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                {{
                  selectedLocation
                    ? $i18n.locale == "ar"
                      ? selectedLocation.name_ar
                      : selectedLocation.name_en
                    : ""
                }}
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a
                  v-for="location in locations"
                  :key="location.id"
                  @click="onLocationSelected(location)"
                  class="dropdown-item"
                  href="#"
                >
                  {{
                    $i18n.locale == "ar" ? location.name_ar : location.name_en
                  }}
                </a>
              </div>
            </div>
          </div>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="home5" role="tabpanel">
              <carousel :dir="dir" v-bind="settings">
                <slide
                  @click="onCategorySelected(null)"
                  :class="{ active__slide: !currentCategory }"
                >
                  <img class="item-image" src="/images/placeholder-thumb.png" />
                  <div class="slide-title">{{ $t("ALL_CATEGORIES") }}</div>
                  <div class="count">
                    {{ `${allCategoriesItems} ${$t("ITEMS")}` }}
                  </div>
                </slide>
                <slide
                  @click="onCategorySelected(item)"
                  :class="{
                    active__slide:
                      currentCategory && currentCategory.id == item.id,
                  }"
                  v-for="item in categories"
                  :key="item.id"
                >
                  <img
                    class="item-image"
                    :src="
                      item.media_manager
                        ? `/uploads/${item.media_manager.hash_name}`
                        : '/images/placeholder-thumb.png'
                    "
                  />
                  <div class="slide-title">
                    {{ $i18n.locale == "ar" ? item.name_ar : item.name_en }}
                  </div>
                  <div class="count">
                    {{ `${item.all_products_count} ${$t("ITEMS")}` }}
                  </div>
                </slide>

                <template #addons>
                  <navigation />
                </template>
              </carousel>
            </div>
            <div class="tab-pane fade" id="profile5" role="tabpanel">
              <div class="tab-pane fade show active" id="home5" role="tabpanel">
                <carousel :dir="dir" v-bind="settings">
                  <slide
                    @click="onBrandSelected(null)"
                    :class="{ active__slide: !currentBrand }"
                  >
                    <img
                      class="item-image"
                      src="/images/placeholder-thumb.png"
                    />
                    <div class="slide-title">{{ $t("ALL_BRANDS") }}</div>
                    <div class="count">
                      {{ `${allBrandsItems} ${$t("ITEMS")}` }}
                    </div>
                  </slide>
                  <slide
                    @click="onBrandSelected(item)"
                    :class="{
                      active__slide: currentBrand && currentBrand.id == item.id,
                    }"
                    v-for="item in brands"
                    :key="item.id"
                  >
                    <img
                      class="item-image"
                      :src="
                        item.media_manager
                          ? `/uploads/${item.media_manager.hash_name}`
                          : '/images/placeholder-thumb.png'
                      "
                    />
                    <div class="slide-title">
                      {{ $i18n.locale == "ar" ? item.name_ar : item.name_en }}
                    </div>
                    <div class="count">
                      {{ `${item.products_count} ${$t("ITEMS")}` }}
                    </div>
                  </slide>

                  <template #addons>
                    <navigation />
                  </template>
                </carousel>
              </div>
            </div>
          </div>
        </div>
        <div class="top-products-sec">
          <div class="first">{{ $t("All Listed Products") }}</div>
          <div class="second">
            <div class="search-inpt">
              <i class="fa fa-search"></i>
              <input
                v-model="text"
                :placeholder="$t('SEARCH')"
                class="form-control"
              />
            </div>
            <button @click="onSearchClicked" class="search-btn">
              {{ $t("SEARCH") }}
            </button>
            <button class="add-item-btn">
              <i class="fa fa-plus"></i>{{ $t("Add Item by Code") }}
            </button>
          </div>
        </div>

        <div class="products row">
          <div v-for="product in products" :key="product.id" class="col-lg-3">
            <div @click="openVariationModal(product)" class="product">
              <div class="prod-image">
                <img
                  :src="
                    product.media_manager
                      ? `/uploads/${product.media_manager.hash_name}`
                      : '/images/placeholder-thumb.png'
                  "
                />
              </div>
              <div class="info">
                <div class="title">
                  {{ $i18n.locale == "ar" ? product.name_ar : product.name_en }}
                </div>
                <div class="price">
                  <span>{{ product.min_price }}$</span
                  ><span v-if="product.has_variations == 1">
                    - {{ product.max_price }}$</span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 text-center my-4">
            <button @click="loadMore" class="btn btn-primary load-more">
              <i class="fa fa-refresh" aria-hidden="true"></i>

              {{ $t("LOAD_MORE") }}
            </button>
          </div>
        </div>
      </div>
      <div class="col-lg-4" style="padding: 20px; background: #fff">
        <div class="order-header row">
          <div class="col-lg-4">
            <h1 style="font-size: 15px !important">
              {{ $t("Billing Section") }}
            </h1>
          </div>
          <div class="col-lg-4">
            <Multiselect
              :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
              :searchable="true"
              v-model="status"
              valueProp="value"
              :options="statuses"
            />
          </div>
          <div class="col-lg-4">
            <button @click="openCustomerModal" class="add-customer border">
              <i class="fa fa-plus"></i>
              {{ $t("Customer") }}
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 mt-4">
            <div class="form-group">
              <Multiselect
                :placeholder="$t('SELECT_CUSTOMER')"
                :label="'name'"
                :searchable="true"
                v-model="customer_id"
                valueProp="id"
                :options="customers"
              />
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">{{ $t("ITEM") }}</th>
                <th scope="col">{{ $t("QTY") }}</th>
                <th scope="col">{{ $t("PRICE") }}</th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody v-if="orderDetails.length">
              <tr v-for="item in orderDetails" :key="item.id">
                <td>
                  <div
                    class="modal-image mb-3"
                    style="padding: 0 !important; background: none !important"
                  >
                    <div class="info">
                      <div class="title" style="font-size: 12px !important">
                        {{ $i18n.locale == "ar" ? item.name_ar : item.name_en }}
                      </div>
                      <div
                        class="title"
                        v-if="item.variation_name_ar"
                        style="font-size: 12px !important; color: #727272"
                      >
                        {{
                          $i18n.locale == "ar"
                            ? item.variation_name_ar.replace(" | ", " , ")
                            : item.variation_name_en.replace(" | ", " , ")
                        }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <input
                    @blur="onQuantityBlur(item)"
                    style="width: 70%; height: 33px"
                    class="form-control"
                    v-model="item.quantity"
                    type="number"
                  />
                </td>
                <td>{{ item.price }}</td>
                <td>
                  <button
                    @click="removeOrderItem(index)"
                    style="background: none; border: none"
                  >
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr class="text-center">
                <td colspan="10">
                  {{ $t("No results") }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <input
              style="height: 43px"
              class="form-control"
              :placeholder="$t('TYPE_ADDITIONAL_DISCOUNT')"
              v-model="additional_discount"
              type="number"
            />
          </div>
          <div class="col-lg-6">
            <Multiselect
              :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
              :searchable="true"
              v-model="additional_discount_percent"
              valueProp="value"
              :options="discounts"
            />
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-lg-6">
            <input
              type="number"
              style="height: 43px"
              class="form-control"
              :placeholder="$t('TYPE_SHIPPING_CHARGE')"
              v-model="shipping_charge"
            />
          </div>
          <div class="col-lg-6">
            <div class="total-order">{{ $t("TOTAL") }} {{ getTotal() }}</div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-lg-12">
            <button
              :disabled="
                !customer_id ||
                status == null ||
                orderDetails.length == 0 ||
                additional_discount_percent == null ||
                !selectedLocation
              "
              @click="createOrder"
              class="btn btn-primary"
              style="width: 100%"
            >
              {{ $t("SUBMIT_ORDER") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { inject, onMounted, reactive, toRefs } from "vue";
import orderClient from "../../../http-clients/admin/order-client";
import Multiselect from "@vueform/multiselect";
import { toast } from "vue3-toastify";
import { useI18n } from "vue-i18n";
import { useRouter } from 'vue-router';

export default {
  setup() {
    const store = inject("store");
    const { t, locale } = useI18n({ useScope: "global" });
    const router=useRouter();
    let data = reactive({
      page: 1,
      customers: [],
      shipping_charge: null,
      additional_discount_percent: 0,
      additional_discount: null,
      currentProduct: null,
      text: "",
      pageSize: 4,
      allBrandsItems: 0,
      products: [],
      orderDetails: [],
      currentCategory: null,
      currentBrand: null,
      allCategoriesItems: 0,
      customer_id: null,
      settings: {
        itemsToShow: 6.5,
        snapAlign: "start",
      },
      status: "DELIVERED",
      statuses: [
        { value: "DELIVERED", name_ar: "تم التوصيل", name_en: "Delivered" },
        { value: "ORDER_PLACED", name_ar: "تم الطلب", name_en: "Order Placed" },
      ],
      discounts: [
        { value: 1, name_ar: "نسبة", name_en: "Percent" },
        { value: 0, name_ar: "ثابت", name_en: "Fixed" },
      ],
      locations: [],
      brands: [],
      categories: [],
      selectedLocation: null,
      name: "",
      email: "",
      phone: "",
    });
    onMounted(() => {
      getLocations();
      getBrands();
      getCategories();
      getCustomers();
    });
    //Methods
    function onQuantityBlur(item) {
      item.quantity = item.quantity <= 0 ? 1 : item.quantity;
      item.quantity = item.quantity > item.stock ? item.stock : item.quantity;
    }
    function createOrder() {
      orderClient
        .store({
          shipping_charge: data.shipping_charge ? data.shipping_charge : 0,
          additional_discount_percent: data.additional_discount_percent,
          additional_discount: data.additional_discount
            ? data.additional_discount
            : 0,
          status: data.status,
          customer_id: data.customer_id,
          location_id: data.selectedLocation.id,
          order_details: data.orderDetails.map((orderDetail) => {
            return {
              quantity: orderDetail.quantity,
              stock_id: orderDetail.id,
            };
          }),
        })
        .then(() => {
          router.push("/admin/orders");
        });
    }
    function getTotal() {
      let total = 0;
      let discount = 0;
      data.orderDetails.forEach((orderDetail) => {
        total += orderDetail.price * orderDetail.quantity;
      });
      if (data.additional_discount == null) {
        discount = 0;
      } else {
        discount =
          data.additional_discount_percent == 1
            ? total * (data.additional_discount / 100)
            : data.additional_discount;
      }

      return (total - discount - data.shipping_charge).toFixed(2);
    }
    function removeOrderItem(index) {
      data.orderDetails.splice(index, 1);
    }
    function selectVarValue(variation, value) {
      variation.variation_values.forEach((element) => {
        if (element.id == value.id) {
          element.selected = true;
        } else {
          element.selected = false;
        }
      });
    }
    function addThisItem() {
      let counter = 0;
      data.currentProduct.variation_ids.forEach((var_id) => {
        let res = var_id.variation_values.filter((var_value) => {
          return var_value.selected;
        });
        if (res.length) {
          counter++;
        }
      });

      if (counter != data.currentProduct.variation_ids.length) {
        toast.error(t("SELECT_ALL_POSSIBLE_VALUES"), {
          autoClose: 2000,
          position: "top-center",
        });
        return;
      }
      let name_ar = "";
      let name_en = "";
      data.currentProduct.variation_ids.forEach((var_id) => {
        var_id.variation_values.forEach((var_value, index) => {
          if (var_value.selected) {
            name_ar = `${name_ar}${var_value.name_ar} | `;
            name_en = `${name_en}${var_value.name_en} | `;
          }
        });
      });
      let location_var = data.currentProduct.location_variations.filter(
        (variation) => {
          let counter = 0;
          variation.variation_name_en.split(" | ").forEach((_var) => {
            if (
              name_en
                .slice(0, -1)
                .slice(0, -1)
                .slice(0, -1)
                .split(" | ")
                .includes(_var)
            ) {
              counter++;
            }
          });
          return counter == variation.variation_name_en.split(" | ").length;
        }
      )[0];
      if (!location_var || location_var.stock == 0) {
        toast.warning(t("OUT_OF_STOCK"), {
          autoClose: 2000,
          position: "top-center",
        });
        return;
      }
      let check = false;
      data.orderDetails.forEach((order) => {
        if (location_var.id == order.id) {
          check = true;
          return;
        }
      });
      if (check) {
        toast.error(t("ITEM_SELECTED_BEFORE"), {
          autoClose: 2000,
          position: "top-center",
        });
        return;
      }
      data.orderDetails.push({
        variation_name_ar: location_var.variation_name_ar,
        variation_name_en: location_var.variation_name_en,
        quantity: 1,
        media_manager: data.currentProduct.media_manager,
        price: location_var.price,
        name_ar: data.currentProduct.name_ar,
        name_en: data.currentProduct.name_en,
        id: location_var.id,
        stock: location_var.stock,
      });
      $("#variationModal").modal("hide");
      data.currentProduct.variation_ids.forEach((var_id) => {
        var_id.variation_values.forEach((var_value) => {
          var_value.selected = undefined;
        });
      });
    }
    function createCustomer() {
      orderClient
        .createCustomer({
          name: data.name,
          email: data.email,
          phone: data.phone,
        })
        .then((category) => {
          data.name = "";
          data.email = "";
          data.phone = "";
          toast.success(t("CREATED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });
          $("#customerModal").modal("hide");
          getCustomers();
        })
        .catch((error) => {});
    }
    function openVariationModal(product) {
      data.currentProduct = product;
      if (product.has_variations == 0) {
        let check = false;
        data.orderDetails.forEach((order) => {
          if (product.id == order.id) {
            check = true;
            return;
          }
        });
        if (check) {
          toast.error(t("ITEM_SELECTED_BEFORE"), {
            autoClose: 2000,
            position: "top-center",
          });
          return;
        }
        if (
          product.location_variations.length == 0 ||
          product.location_variations[0].stock == 0
        ) {
          toast.warning(t("OUT_OF_STOCK"), {
            autoClose: 2000,
            position: "top-center",
          });
          return;
        }
        data.orderDetails.push({
          media_manager: product.media_manager,
          name_ar: product.name_ar,
          name_en: product.name_en,
          price: product.min_price,
          quantity: 1,
          id: product.id,
          stock: product.location_variations[0].stock,
        });
        return;
      }
      if (product.has_variations == 1) {
        $("#variationModal").modal("show");
      }
    }
    function openCustomerModal() {
      $("#customerModal").modal("show");
    }
    function onTabClicked() {
      data.currentBrand = null;
      data.currentCategory = null;
      data.page = 1;
      data.products = [];
      getProducts();
    }
    function onCategorySelected(item) {
      data.currentCategory = item;
      data.products = [];
      data.page = 1;
      getProducts();
    }
    function onBrandSelected(item) {
      data.currentBrand = item;
      data.products = [];
      data.page = 1;
      getProducts();
    }
    function onLocationSelected(item) {
      data.selectedLocation = item;
      data.page = 1;
      data.products = [];
      data.orderDetails = [];
      getProducts();
    }
    function onSearchClicked() {
      data.page = 1;
      data.products = [];
      getProducts();
    }
    function loadMore() {
      data.page++;
      getProducts();
    }
    function getLocations() {
      orderClient.getLocations().then((res) => {
        data.locations = res.data;
        let result = data.locations.filter((location) => {
          return location.default == 1;
        });
        data.selectedLocation = result.length ? result[0] : null;
        getProducts();
      });
    }

    function getBrands() {
      orderClient.getBrands().then((res) => {
        data.brands = res.data.brands;
        data.allBrandsItems = res.data.all_products;
      });
    }
    function getCategories() {
      orderClient.getCategories().then((res) => {
        data.categories = res.data.categories;
        data.allCategoriesItems = res.data.all_products;
      });
    }

    function getProducts() {
      orderClient
        .getProducts(
          data.page,
          data.pageSize,
          data.currentBrand ? data.currentBrand.id : null,
          data.currentCategory ? data.currentCategory.id : null,
          data.selectedLocation ? data.selectedLocation.id : null,
          data.text
        )
        .then((res) => {
          data.products = [...data.products, ...res.data.data];
        });
    }
    function getCustomers() {
      orderClient.getCustomers().then((res) => {
        data.customers = res.data;
      });
    }
    return {
      ...toRefs(data),
      onQuantityBlur,
      createOrder,
      getTotal,
      removeOrderItem,
      selectVarValue,
      addThisItem,
      createCustomer,
      onTabClicked,
      openVariationModal,
      openCustomerModal,
      onCategorySelected,
      onBrandSelected,
      onLocationSelected,
      onSearchClicked,
      loadMore,
      getLocations,
      ...toRefs(store),
    };
  },
  components: {
    Carousel,
    Multiselect,
    Slide,
    Pagination,
    Navigation,
  },
};
</script>
<style lang="scss">
.add-customer {
  border-radius: 7px;
  padding: 7px;
  border-color: rgba(78, 181, 41, 0.15);
  background-color: rgba(78, 181, 41, 0.15);
  color: #4eb529;
  font-size: 10px !important;
  align-items: center;
  gap: 9px;
  display: flex;
  border: none;
}

.nav.nav-pills {
  margin: 0 3px !important;
}
.nav-link {
  padding: 0.4rem 1rem !important;
}
.dropdown-toggle.location {
  &,
  &:hover {
    background: white !important;
  }
}
.nav-link.normal {
  border: 1px solid #4eb529;
  border-radius: 5px;
  color: #4eb529;
}
.nav-link.active {
  color: #fff;
  &:hover {
    color: #fff !important;
  }

  background-color: #4eb529 !important;
}
.pos-container {
  table {
    tbody {
      overflow: auto;
      max-height: 400px;
    }
    td,
    th {
      white-space: nowrap;
    }
  }
  .total-order {
    color: #ff7c08;
    font-size: 16px !important;
    margin-top: 12px !important;
  }
  .table-responsive {
    overflow: auto !important;
    -webkit-overflow-scrolling: touch;
    max-height: 300px;
  }
  #variationModal,
  #customerModal {
    .modal-body {
      padding: 50px;
    }
    .cls {
      background: #f4f6f8;
      color: #202342;
      padding: 10px 20px;
    }
    .select-var {
      padding: 10px 20px;
    }
    .title {
      font-weight: 600;
    }
  }
  .variations {
    .var-values {
      margin: 10px 0;
      display: flex;
      gap: 13px;
      .var-value {
        &:hover {
          cursor: pointer;
        }
        padding: 7px 19px;
        border: 1px solid #dfe3e8;
        border-radius: 3px;
      }
    }
  }
  .load-more {
    padding: 12px 27px;
  }

  .products {
    .col-lg-3 {
      padding: 0 10px 10px 0px;
    }
    margin: 0 10px;
    max-height: 400px;
    overflow: auto;
    .product {
      align-items: center;
      .info {
        padding-top: 14px;
        .price {
          color: #ff7c08;
        }
      }
      .title {
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 80%;
      }
      gap: 24px;
      display: flex;
      background: #fff;
      padding: 0 14px;
      .prod-image {
        img {
          object-fit: contain;
          width: 30px;
          height: 30px;
        }
      }
    }
  }
  .modal-image {
    align-items: center;
    .info {
      .price {
        color: #ff7c08;
      }
    }
    .title {
      margin-bottom: 4px;
      font-weight: 500;
      font-size: 16px !important;
    }
    gap: 24px;
    display: flex;
    background: #fff;
    padding: 0 14px;
    .prod-image {
      img {
        object-fit: contain;
        width: 40px;
        height: 40px;
      }
    }
  }
  .top-products-sec {
    align-items: center;
    margin: 26px 12px 8px 12px;
    justify-content: space-between;
    display: flex;
    .first {
      font-weight: 500;
      font-size: 18px !important;
    }
    .second {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .search-btn {
      border: 1px solid #dfe3e8;
      background: #f4f6f8;
      height: 42px;
      padding: 0 25px;
      border-radius: 5px;
    }
    .add-item-btn {
      border: 1px solid #dfe3e8;
      background: rgba(78, 181, 41, 0.15);
      height: 42px;
      padding: 0 25px;
      border-radius: 5px;
      gap: 10px;
      align-items: center;
      display: flex;
      color: #4eb529;
    }
    .search-inpt {
      i {
        position: relative;
        color: gray;
      }
      input {
        padding: 0 30px;
        height: 42px;
        margin-bottom: 18px;
      }
    }
  }

  body[dir="rtl"] & {
    .add-item-btn {
      i {
        position: relative;
        top: 2px;
      }
    }
    .search-inpt {
      i {
        right: 12px;
        top: 31px;
      }
    }
    * {
      font-size: 12.5px !important;
    }

    .slide-title {
      font-size: 12px !important;
      font-weight: 550 !important;
    }
    .carousel__prev,
    .carousel__next {
      border-radius: 50%;
      color: white;
      * {
        font-size: 20px !important;
      }
      background: #4eb529 !important;
    }
  }
  body[dir="ltr"] & {
    .search-inpt {
      i {
        top: 32px;

        left: 12px;
      }
    }
    * {
      font-size: 13px !important;
    }
    .slide-title {
      font-size: 12px !important;
    }
    .carousel__prev,
    .carousel__next {
      border-radius: 50%;
      color: white;
      * {
        font-size: 20px !important;
      }
      background: #4eb529 !important;
    }
  }
  .carousel__slide {
    &:hover {
      cursor: pointer;
    }
    .item-image {
      border: 5px solid white;
      -o-object-fit: contain;
      object-fit: contain;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      outline: 1px dashed #4eb529;
      margin-bottom: 15px;
    }

    .slide-title {
      font-weight: 600;
      margin-bottom: 4px;
    }
    .count {
      font-size: 11px !important;
    }
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 120px;
    margin: 0 10px !important;
    border-radius: 10px;
    background: white;
    &.active__slide {
      .item-image {
        outline: 1px dashed white;
        border: 5px solid #4eb529;
      }
      background: #4eb529 !important;
      color: white !important;
    }
  }
}
.product:hover {
  cursor: pointer;
}
</style>