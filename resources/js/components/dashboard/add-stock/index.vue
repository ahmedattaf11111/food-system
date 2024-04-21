<template>
  <div class="brand-container">
    <DeleteConfirmation @confirm="deleteItem" />
    <div class="page-header border" style="margin-top: 10px">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            {{ $t("ADD_STOCK") }}
          </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <!-- <div class="dropdown">
            <a
              class="btn btn-primary date-header dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
            >
              {{ `${getMounthName()} ${new Date().getFullYear()}` }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
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
    <div class="row">
      <div class="col-lg-9 order-lg-1 order-2 table-sec">
        <div id="add" class="bg-white border border-radius-4 mb-30">
          <div class="create-item">
            <div class="title mb-4">{{ $t("BASIC_INFORMATION") }}</div>
            <form>
              <div class="row">
                <div class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("LOCATION") }}
                    </label>
                    <Multiselect
                      @change="getStocks"
                      :placeholder="$t('SELECT_LOCATION')"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="location_id"
                      valueProp="id"
                      :options="locations"
                    />
                  </div>
                </div>
                <div class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("PRODUCT") }}
                    </label>
                    <Multiselect
                      @change="getStocks"
                      :placeholder="$t('SELECT_PRODUCT')"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="product_id"
                      valueProp="id"
                      :options="products"
                    />
                  </div>
                </div>
                <template v-for="stock in stocks" :key="stock.id">
                  <div v-if="stock.variation_name_ar" class="col-lg-4 mt-2">
                    <input
                      :disabled="true"
                      v-model="stock.variation_name_ar"
                      class="form-control"
                    />
                  </div>
                  <div v-if="stock.variation_name_en" class="col-lg-4 mt-2">
                    <input
                      :disabled="true"
                      v-model="stock.variation_name_en"
                      class="form-control"
                    />
                  </div>
                  <div
                    :class="
                      stock.variation_name_en
                        ? 'col-lg-4 mt-2'
                        : 'col-lg-12 mt-2'
                    "
                  >
                    <input
                      :placeholder="$t('TYPE_STOCK_VALUE')"
                      v-model="stock.stock"
                      class="form-control"
                    />
                  </div>
                </template>
              </div>
            </form>
          </div>
        </div>
        <button
          @click="save"
          type="button"
          class="btn btn-primary mb-30 save-submit"
        >
          <i class="fa fa-save"></i>
          {{ $t("SAVE") }}
        </button>
      </div>
      <div
        class="col-lg-3 order-lg-2 order-1 colmn"
        :style="
          $i18n.locale == 'ar' ? 'padding-right:10px' : 'padding-left:10px'
        "
      >
        <div
          class="bg-white border information-container border-radius-4 mb-30"
        >
          <div class="information">
            <div class="title">{{ $t("BASIC_INFORMATION") }}</div>
            <ul>
              <a id="a-table" @click="currentSection = 'table'" href="#table">
                <li :class="{ active: currentSection == 'table' }">
                  {{ $t("BASIC_INFORMATION") }}
                </li>
              </a>
            </ul>
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
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import itemStore from "./item-store";
import stockClient from "../../../http-clients/admin/stock-client";
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
    provide("item_store", itemStore);

    const data = reactive({
      isArabic: false,
      selectedMedia: [],

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
      location_id: null,
      stocks: [],
      product_id: null,
      products: [],
      locations: [],
      selectedItem: null,
      status: -1,
      statuses: [
        { value: -1, name_ar: "اختر الحالة", name_en: "Select Status" },
        { value: 1, name_ar: "مفعل", name_en: "Active" },
        { value: 0, name_ar: "غير مفعل", name_en: "Inactive" },
      ],
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
    const form = reactive({
      name_ar: "",
      name_en: "",
    });
    const rules = {
      name_ar: { required },
      name_en: { required },
    };
    const v$ = useVuelidate(rules, form);

    const { t, locale } = useI18n({ useScope: "global" });
    created();
    onMounted(() => {
      stockClient.getProducts().then((res) => {
        data.products = res.data;
      });
      stockClient.getLocations().then((res) => {
        data.locations = res.data;
      });
    });
    //Methods
    function getStocks() {
      setTimeout(() => {
        if (data.location_id && data.product_id) {
          stockClient
            .getStocks(data.location_id, data.product_id)
            .then((res) => {
              data.stocks = res.data;
            });
        } else {
          data.stocks = [];
        }
      }, 500);
    }
    function toggleLocale() {
      data.isArabic = !data.isArabic;
    }
    function toggleStatus(id) {
      brandClient.toggleStatus(id).then((res) => {
        toast.success(t("STATUS_UPDATED_SUCCESSFULLY"), {
          autoClose: 2000,
          position: "top-center",
        });
        getItems();
      });
    }
    function save() {
      store();
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
    }
    function getItems() {
      brandClient
        .getItems(data.page, data.pageSize, data.text, data.status)
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
      brandClient.getAllItems().then((res) => {
        let data = res.data;
        const fileName = "Items";
        const exportType = exportFromJSON.types.csv;
        if (data) exportFromJSON({ data, fileName, exportType });
      });
    }
    function print() {
      window.print();
    }
    function onCreated(event) {}
    function onUpdated(event) {}
    function deleteItem() {
      brandClient
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
    function store() {
      stockClient
        .store({
          stocks: data.stocks.map((stock) => {
            return {
              product_id: data.product_id,
              location_id: data.location_id,
              stock: stock.stock,
              variation_name_ar: stock.variation_name_ar
                ? stock.variation_name_ar
                : null,
              variation_name_en: stock.variation_name_en
                ? stock.variation_name_en
                : null,
            };
          }),
        })
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.name_ar ? true : false;
          data.titleEnExist = error.response.data.errors.name_en ? true : false;
        });
    }
    function created() {}
    return {
      toggleLocale,
      ...toRefs(data),
      getStocks,
      ...toRefs(form),
      toggleStatus,
      save,
      form,
      v$,
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
.brand-container {
  * {
    font-size: 13px !important;
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
    .locale {
      border-radius: 5px;
      padding: 3px 7px;
      position: relative;
      color: #fcac00;
      font-size: 10px !important;
      bottom: 1px;
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
    bottom: 1px;
    &:hover {
      color: #fcac00 !important;
      background: rgba(252, 172, 0, 0.15);
    }
  }

  .multiselect-option.is-selected.is-pointed,
  .multiselect-option.is-selected {
    background: #4eb529 !important;
  }
  .multiselect-wrapper {
    padding: 13px 0px;
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
      padding: 22px 10px;
      font-size: 13px !important;
    }
  }
  .create-item {
    padding: 20px 20px 40px 20px !important;
  }
  .search-btn {
    padding: 12px 0;
    width: 100%;
  }
  .page-header {
    box-shadow: unset !important;
    margin-bottom: 19px !important;
  }

  .controls {
    background: #f9fafb;
    padding: 25px 30px;
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
      top: 17px;
      font-size: 15px;
    }
    label {
      margin: 0 5px;
      position: relative;
      top: 7px;
    }
    .form-control {
      padding: 22px 39px;
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
