<template>
  <div class="order-container">
    <DeleteConfirmation @confirm="deleteItem" />
    <div
      class="modal fade"
      id="orderDetailsModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table" id="printMe">
                <thead>
                  <tr>
                    <th scope="col">{{ $t("S/L") }}</th>
                    <th scope="col">{{ $t("ITEM") }}</th>
                    <th scope="col">{{ $t("QTY") }}</th>
                    <th scope="col">{{ $t("PRICE") }}</th>
                    <th scope="col">{{ $t("TOTAL_PRICE") }}</th>
                    <th scope="col">{{ $t("LOCATION") }}</th>
                  </tr>
                </thead>
                <tbody v-if="orderDetails.length">
                  <tr v-for="(item, index) in orderDetails" :key="item.id">
                    <td>{{ index + 1 + (page - 1) * pageSize }}</td>
                    <td>
                      <div class="modal-image">
                        <div class="info">
                          <div class="title" style="font-size: 12px !important">
                            {{
                              $i18n.locale == "ar"
                                ? item.stock.product.name_ar
                                : item.stock.product.name_en
                            }}
                          </div>
                          <div
                            class="title"
                            v-if="item.stock.variation_name_ar"
                            style="font-size: 12px !important; color: #727272"
                          >
                            {{
                              $i18n.locale == "ar"
                                ? item.stock.variation_name_ar.replace(
                                    " | ",
                                    " , "
                                  )
                                : item.stock.variation_name_en.replace(
                                    " | ",
                                    " , "
                                  )
                            }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.price }}</td>
                    <td>{{ item.total_price }}</td>
                    <td>
                      <template v-if="item.stock.location">
                        {{
                          $i18n.locale == "ar"
                            ? item.stock.location.name_ar
                            : item.stock.location.name_en
                        }}
                      </template>
                      <template v-else>-</template>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td class="no-result" colspan="20">
                      {{ $t("No results") }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button
              type="button"
              class="cls border btn btn-secondary mt-3"
              data-dismiss="modal"
            >
              {{ $t("CLOSE") }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="page-header border" style="margin-top: 10px">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            {{ $t("ORDERS") }}
          </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <!-- <div class="dropdown">
            <a
              class="btn create-btn date-header"
              href="#"
              role="button"
              data-toggle="dropdown"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-plus"
              >
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>
                {{ $t("ADD_CATEGORY") }}
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <router-link
                class="dropdown-item"
                :to="`/admin/categories-form/create`"
                >{{ $t("ADD_CATEGORY") }}</router-link
              >
              <a
                @click.prevent="downloadExcelFile"
                class="dropdown-item"
                href=""
                >{{ $t("EXCEL") }}</a
              >
              <a @click.prevent="print" class="dropdown-item" href="">{{
                $t("PRINT")
              }}</a>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <div id="table" class="bg-white border border-radius-4 mb-30">
      <div class="table-container">
        <div class="controls">
          <div class="row">
            <div style="padding: 0 8px; width: 50%">
              <div class="search d-flex">
                <i class="fa fa-search"></i>

                <input
                  class="form-control"
                  v-model="text"
                  type="text"
                  :placeholder="$t('SEARCH_BY_CODE')"
                  ref="search"
                />
              </div>
            </div>
            <div style="padding: 0 8px; width: 20%">
              <Multiselect
                :placeholder="$t('SELECT_PAYMENT_STATUS')"
                :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                :searchable="true"
                v-model="payment_status"
                valueProp="value"
                :options="payment_statuses"
              />
            </div>
            <div style="padding: 0 8px; width: 15%">
              <Multiselect
                :placeholder="$t('SELECT_STATUS')"
                :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                :searchable="true"
                v-model="status"
                valueProp="value"
                :options="statuses"
              />
            </div>
            <div style="padding: 0 8px; width: 15%">
              <Multiselect
                :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                :placeholder="$t('SELECT_LOCATION')"
                :searchable="true"
                v-model="location_id"
                valueProp="id"
                :options="locations"
              />
            </div>
            <div style="padding: 0 8px; width: 12%">
              <button
                type="button"
                @click="searchItems"
                class="btn btn-primary search-btn"
                style="width: 100%"
              >
                <i class="fa fa-search"></i>
                {{ $t("SEARCH") }}
              </button>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table" id="printMe">
            <thead>
              <tr>
                <th scope="col">{{ $t("S/L") }}</th>
                <th scope="col">
                  {{ $t("ORDER_CODE") }}
                </th>
                <th scope="col">
                  {{ $t("Customer") }}
                </th>
                <th scope="col">{{ $t("PLACED_ON") }}</th>
                <th scope="col">{{ $t("ITEMS") }}</th>
                <th scope="col">{{ $t("PAYMENT") }}</th>
                <th scope="col">{{ $t("STATUS") }}</th>
                <th scope="col">{{ $t("LOCATION") }}</th>
                <th scope="col">{{ $t("SUB_TOTAL") }}</th>
                <th scope="col">{{ $t("TAX") }}</th>
                <th scope="col">{{ $t("DISCOUNT") }}</th>
                <th scope="col">{{ $t("SHIPPING_CHARGE") }}</th>
                <th scope="col">{{ $t("ADDITIONAL_DISCOUNT") }}</th>
                <th scope="col">{{ $t("GRAND_TOTAL") }}</th>
                <th class="actions-header" scope="col">
                  {{ $t("ACTIONS") }}
                </th>
              </tr>
            </thead>
            <tbody v-if="items.length">
              <tr v-for="(item, index) in items" :key="item.id">
                <td>{{ index + 1 + (page - 1) * pageSize }}</td>
                <td>{{ item.code }}</td>
                <td>
                  <span class="chip">
                    {{ item.customer.name }}
                  </span>
                </td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.items }}</td>
                <td>
                  <span class="chip">
                    {{ $t(item.payment_status) }}
                  </span>
                </td>
                <td>
                  <span class="chip">
                    {{ $t(item.status) }}
                  </span>
                </td>
                <td>
                  <span class="chip">
                    {{
                      $i18n.locale == "ar"
                        ? item.location.name_ar
                        : item.location.name_en
                    }}
                  </span>
                </td>
                <td>{{ item.sub_total }}</td>
                <td>{{ item.tax }}</td>
                <td>{{ item.discount }}</td>
                <td>{{ item.shipping_charge }}</td>
                <td>
                  {{ item.additional_discount
                  }}{{ item.additional_discount_percent == 1 ? "%" : "" }}
                </td>
                <td>{{ item.grand_total }}</td>
                <td class="actions-cell">
                  <div class="dropdown">
                    <a
                      class="btn"
                      href="#"
                      role="button"
                      data-toggle="dropdown"
                    >
                      <i
                        style="font-size: 17px !important"
                        class="dw dw-more"
                      ></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left">
                      <a
                        v-if="item.status == 'ORDER_PLACED'"
                        class="dropdown-item order-placed"
                        @click.prevent="completeOrder(item)"
                        to="/"
                        >{{ $t("COMPLETE_ORDER") }}</a
                      >
                      <a
                        @click.prevent="openOrderDetailsModal(item)"
                        class="dropdown-item"
                        href=""
                        >{{ $t("SHOW") }}</a
                      >
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td class="no-result" colspan="20">{{ $t("No results") }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="page-container border-top d-flex justify-content-between">
          <div class="entries">
            {{ pageSize * (page - 1) + 1 }} -
            {{ (page - 1) * pageSize + items.length }} {{ $t("OF") }}
            {{ totalItems }} {{ $t("ENTRIES") }}
          </div>
          <div v-if="items.length && pageCounts > 1">
            <paginate
              v-model="page"
              :pageCount="pageCounts"
              :clickHandler="getItems"
              :prevText="
                $i18n.locale == 'en'
                  ? `<i class='ion-chevron-left'></i>`
                  : `<i class='ion-chevron-right'></i>`
              "
              :nextText="
                $i18n.locale == 'en'
                  ? `<i class='ion-chevron-right'></i>`
                  : `<i class='ion-chevron-left'></i>`
              "
            >
            </paginate>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Image from "../../../shared/components/image.vue";

import Multiselect from "@vueform/multiselect";
import orderClient from "../../../http-clients/admin/order-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import {
  inject,
  onMounted,
  provide,
  reactive,
  ref,
  toRefs,
  watch,
} from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    Multiselect,
    DeleteConfirmation,
    Image,
  },
  setup() {
    const data = reactive({
      brands: [],
      orderDetails: [],
      isArabic: false,
      selectedMedia: [],
      status: "",
      payment_status: "",
      location_id: "",
      locations: [],
      brand: -1,
      statuses: [
        {
          value: "DELIVERED",
          name_ar: "تم توصيل الطلب",
          name_en: "Order Delivered",
        },
        { value: "ORDER_PLACED", name_ar: "تم الطلب", name_en: "Order Placed" },
      ],
      payment_statuses: [
        { value: "PAID", name_ar: "تم الدفع", name_en: "Paid" },
        { value: "UNPAID", name_ar: "غير مدفوع", name_en: "Unpaid" },
      ],
      currentSection: "table",
      titleArExist: false,
      titleEnExist: false,
      pageSize: 10,
      page: 1,
      totalItems: 0,
      items: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedItem: null,
      monthNames: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
    });

    const { t, locale } = useI18n({ useScope: "global" });
    created();
    onMounted(() => {
      orderClient.getLocations().then((res) => {
        data.locations = res.data;
      });
    });
    //Methods
    function openOrderDetailsModal(item) {
      data.orderDetails = item.order_details;
      $("#orderDetailsModal").modal("show");
    }
    function completeOrder(item) {
      orderClient.completeOrder(item.id).then(
        toast.success(t("UPDATED_SUCCESSFULLY"), {
          autoClose: 2000,
          position: "top-center",
        })
      );
      data.page = 1;
      getItems();
    }
    function togglePublished(id) {
      orderClient.togglePublished(id).then((res) => {
        toast.success(t("UPDATED_SUCCESSFULLY"), {
          autoClose: 2000,
          position: "top-center",
        });
        getItems();
      });
    }
    function toggleLocale() {
      data.isArabic = !data.isArabic;
    }

    function getMounthName() {
      let date = new Date();
      return data.monthNames[date.getMonth()];
    }
    function onAddClicked() {
      data.selectedItem = null;
      //Make little delay to ensure that watcher that found in item form component
      // catch selectedItem input prop
      setTimeout(() => {
        itemStore.onFormShow = !itemStore.onFormShow;
      }, 1);
    }
    function onEditClicked(item) {
      data.selectedItem = item;
      //Make little delay to ensure that watcher catch selectedItem input prop
      //in item form component
      setTimeout(() => {
        itemStore.onFormShow = !itemStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(item) {
      data.selectedItem = item;
    }
    function onPageSizeChanged() {
      data.page = 1;
      getItems();
    }
    function getItems() {
      orderClient
        .getItems(
          data.page,
          data.pageSize,
          data.text,
          data.payment_status,
          data.status,
          data.location_id
        )
        .then((response) => {
          data.items = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
          data.totalItems = response.data.total;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      orderClient.getAllItems().then((res) => {
        let data = res.data;
        const fileName = "Items";
        const exportType = exportFromJSON.types.csv;
        if (data) exportFromJSON({ data, fileName, exportType });
      });
    }
    function print() {
      window.print();
    }
    function onCreated(event) {
      data.page = 1;
      getItems();
    }
    function onUpdated(event) {
      getItems();
    }
    function deleteItem() {
      orderClient
        .delete(data.selectedItem.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });

          if (data.page > 1 && data.items.length == 1) {
            data.page--;
          }
          getItems();
        })
        .catch((error) => {});
    }
    function searchItems() {
      data.page = 1;
      // clear timeout variable
      // clearTimeout(data.timeout);
      // data.timeout = setTimeout(() => {
      getItems();
      // }, 500);
    }
    // watch(
    //   () => {
    //     data.text;
    //   },
    //   (value) => {
    //     search();
    //   },
    //   { deep: true }
    // );
    //Commons
    function getForm() {
      return {
        media_manager_id: data.selectedMedia.length
          ? data.selectedMedia[0].id
          : null,
        name_ar: form.name_ar,
        name_en: form.name_en,
      };
    }

    function created() {
      getItems();
    }
    return {
      toggleLocale,
      completeOrder,
      openOrderDetailsModal,
      ...toRefs(data),
      togglePublished,
      getMounthName,
      onPageSizeChanged,
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getItems,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteItem,
      searchItems,
      print,
    };
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>


<style lang="scss">
@media print {
  body * {
    visibility: hidden;
  }
  #printMe,
  #printMe * {
    visibility: visible;
  }
  .actions-header,
  .actions-cell {
    display: none !important;
  }
  #printMe {
    position: absolute;
    top: 0;
    left: 0;
  }
}
.order-container {
  .order-placed {
    &:hover {
      cursor: pointer;
    }
  }
  #orderDetailsModal {
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
    }
    .modal-body {
      padding: 50px;
    }
    .cls {
      font-size: 12px !important;
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
  .price {
    color: #ff7c08;
  }
  #table {
    margin-bottom: 173px;
  }
  .create-btn {
    width: 140px;
    background: #4eb529 !important;
    color: #fff;
    padding: 11px 0px;
    span {
      margin: 0 5px;
    }
  }
  .chip {
    font-size: 11px !important;
    margin: 0 3px;
    border-radius: 12px;
    padding: 5px 7px;
    color: #454f5b;
    background: rgb(244, 246, 248);
  }
  .normal {
    color: #454f5b;
  }
  .item-image {
    object-fit: contain;
    width: 25px;
    height: 25px;
    border-radius: 50%;
  }
  .img-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 550;
  }
  .date-header:hover {
    color: #fff !important;
  }
  .switch {
    position: relative;
    display: inline-block;
    width: 36px;
    height: 18px;
    margin-bottom: 0 !important;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: 0.4s;
    transition: 0.4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 10px;
    width: 10px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: 0.4s;
    transition: 0.4s;
  }

  input:checked + .slider {
    background-color: #4eb529;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #4eb529;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(19px);
    -ms-transform: translateX(19px);
    transform: translateX(19px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  } // centering
  body[dir="rtl"] & {
    .create-btn,
    .search .form-control,
    .search-btn,
    th,
    .multiselect,
    .multiselect-option,
    .entries {
      font-size: 12.5px;
    }
    td {
      font-size: 12px;
      .price {
        font-size: 12px;
      }
    }
    .create-btn {
      span {
        position: relative;
        top: 1px;
      }
    }
    td.no-result {
      font-weight: 500 !important;
      font-size: 12.5px !important;
    }
    .locale {
      border-radius: 5px;
      padding: 3px 7px;
      position: relative;
      color: #fcac00;
      font-size: 9px !important;
      bottom: 5px;
      background: rgba(252, 172, 0, 0.15);
      &:hover {
        color: #fcac00 !important;
        background: rgba(252, 172, 0, 0.15);
      }
    }
    .form-group {
      label {
        font-weight: 400 !important;
      }
    }
    table {
      th {
        font-weight: 500 !important;
      }
      td {
        font-weight: 400 !important;
      }
    }
    .title {
      font-size: 15px !important;
      color: #212b36;
      font-weight: 550;
    }
    .search {
      i {
        right: 17px !important;
      }
    }

    .actions-cell .dropdown-menu {
      position: absolute !important;
      transform: translate3d(103px, -43px, 0px) !important;
      top: 0px !important;
      left: 0px !important;
      width: 100px;
      will-change: transform !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
      font-family: "FontAwesome";
      content: "\f104" !important;
    }

    .information {
      a * {
        font-size: 14px !important;
      }
      ul {
        padding: 0 30px;
        position: relative;
        margin-top: 25px;
      }
      li::before {
        height: 12px;
        position: absolute;
        width: 12px;
        content: "";
        background: #dfe3e8;
        border-radius: 50%;
        top: 4px;
        right: -23px;
      }
      ul::before {
        position: absolute;
        display: block;
        content: "";
        top: 14px;
        height: 100%;
        width: 2px;
        right: 12px;
        background: #dfe3e8;
      }

      li.active::before {
        width: 10px;
        height: 10px;
        background: #4eb529;
        right: -25px;
        box-sizing: content-box;
        outline: 1px solid #4eb529;
        border: 3px solid #fff;
      }
      li.active {
        color: #4eb529;
      }

      li {
        position: relative;
        margin-bottom: 15px;
      }
      padding: 15px 15px 20px 15px;
    }
  }
  body[dir="ltr"] & {
    .create-btn,
    .search .form-control,
    .search-btn,
    th,
    .multiselect,
    .multiselect-option,
    .entries {
      font-size: 13px;
    }
    td {
      font-size: 12.5px;
      .price {
        font-size: 12.5px;
      }
    }
    td.no-result {
      font-weight: 600 !important;
    }

    .title {
      font-size: 17px !important;
      color: #212b36;
      font-weight: 600;
    }
    .search {
      i {
        left: 17px !important;
      }
    }
    .information {
      a * {
        font-size: 14px !important;
      }
      ul {
        padding: 0 30px;
        position: relative;
        margin-top: 25px;
      }
      li::before {
        height: 11px;
        position: absolute;
        width: 12px;
        content: "";
        background: #dfe3e8;
        border-radius: 50%;
        top: 4px;
        left: -23px;
      }
      ul::before {
        position: absolute;
        display: block;
        content: "";
        top: 14px;
        height: 100%;
        width: 2px;
        left: 12px;
        background: #dfe3e8;
      }

      li.active::before {
        width: 10px;
        height: 10px;
        background: #4eb529;
        left: -25px;
        box-sizing: content-box;
        outline: 1px solid #4eb529;
        border: 3px solid #fff;
      }
      li.active {
        color: #4eb529;
      }

      li {
        position: relative;
        margin-bottom: 15px;
      }
      padding: 15px 15px 20px 15px;
    }
  }
  .locale {
    border-radius: 5px;
    padding: 3px 7px;
    position: relative;
    color: #fcac00;
    font-size: 10px !important;
    bottom: 5px;
    background: rgba(252, 172, 0, 0.15);
    &:hover {
      color: #fcac00 !important;
      background: rgba(252, 172, 0, 0.15);
    }
  }

  .multiselect-option.is-selected.is-pointed,
  .multiselect-option.is-selected {
    background: #4eb529 !important;
  }
  .btn-primary {
    border-color: #4eb529 !important;
    background: #4eb529 !important;
    color: #4eb529 !important;
    &:hover {
      color: #4eb529 !important;
    }
  }
  .multiselect-wrapper {
    padding: 12px 0px;
  }
  .save-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 110px;
    gap: 12px;
    justify-content: center;
    padding: 10px 0;
    border-radius: 5px;
  }

  .information-container {
    position: sticky;
    top: 90px;
  }
  .table-sec {
    padding-bottom: 400px;
  }
  form {
    .form-control {
      padding: 21px 10px;
      font-size: 13px !important;
    }
  }
  .create-item {
    padding: 20px 20px 40px 20px !important;
  }
  .search-btn {
    color: #fff !important;
    &:hover {
      color: #fff !important;
    }
    padding: 11px 0;
    width: 100%;
    margin-top: 20px;
  }
  .page-header {
    box-shadow: unset !important;
    margin-bottom: 19px !important;
  }

  .controls {
    background: #f9fafb;
    padding: 21px 26px;
  }
  .options {
    margin: 9px 0 20px 0px;
    align-items: center;
    a.outer {
      margin: 0 6px;
      cursor: pointer;
      color: #4eb529;
    }
  }
  .page-container {
    align-items: center;
    padding: 12px 15px 16px 15px;
    .entries {
      margin-top: 6px;
    }
  }
  .page-link {
    align-items: center;
    justify-content: center;
    display: flex;
    border-radius: 5px;
    width: 30px;
    height: 30px;
    border: none;
  }
  .page-item.active .page-link {
    background-color: #4eb529;
  }
  .page-item {
    padding: 0 5px;
  }
  .table {
    margin-bottom: 0 !important;
    td {
      padding: 0 1rem !important;
    }
    th,
    td {
      white-space: nowrap;
    }
    td.no-result {
      color: #454f5b;
      font-size: 14px !important;
      text-align: center;
      padding: 9px !important;
    }
  }
  .actions-cell .dropdown .btn {
    color: #4eb529;
  }
  .table-header {
    color: #4eb529;
  }
  .form-control {
    height: 40px !important;
  }
  .pageSize {
    flex-direction: nowrap;
    margin: 11px 0px 15px 0;
    span {
      position: relative;
      top: 6px;
    }
    select {
      margin: 0 5px;
    }
  }
  .search {
    position: relative;
    i {
      color: gray;
      position: absolute;
      top: 16px;
      font-size: 15px;
    }
    label {
      margin: 0 5px;
      position: relative;
      top: 7px;
    }
    .form-control {
      padding: 21px 39px;
    }
  }

  @media (max-width: "660px") {
    .pageSize {
      flex-direction: column;
      align-items: center;
      gap: 10px;
      margin: 11px 0px 15px 0;
    }
  }
}
</style>
