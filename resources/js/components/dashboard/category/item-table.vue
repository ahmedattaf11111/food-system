<template>
  <div class="category-container">
    <DeleteConfirmation @confirm="deleteItem" />
    <div class="page-header border" style="margin-top: 10px">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            {{ $t("CATEGORIES") }}
          </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <router-link
            class="btn create-btn date-header"
            :to="`/admin/categories-form/create`"
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
          </router-link>
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
            <div style="padding: 0 8px; width: 88%">
              <div class="search d-flex">
                <i class="fa fa-search"></i>

                <input
                  class="form-control"
                  v-model="text"
                  type="text"
                  :placeholder="$t('SEARCH')"
                  ref="search"
                />
              </div>
            </div>
            <div style="padding: 0 8px; width: 12%">
              <button
                type="button"
                @click="searchItems"
                class="btn search-btn"
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
                <th style="width: 6%" scope="col">{{ $t("S/L") }}</th>
                <th style="width: 30%" scope="col">
                  {{ $t("CATEGORY_NAME") }}
                </th>
                <th style="width: 25%" scope="col">
                  {{ $t("BASE_CATEGORY") }}
                </th>
                <th style="width: 13%" scope="col">{{ $t("BRANDS") }}</th>
                <th style="width: 19%" scope="col">{{ $t("PRIORITY") }}</th>
                <th class="actions-header" scope="col">
                  {{ $t("ACTIONS") }}
                </th>
              </tr>
            </thead>
            <tbody v-if="items.length">
              <tr v-for="(item, index) in items" :key="item.id">
                <td>{{ index + 1 + (page - 1) * pageSize }}</td>
                <td>
                  <div class="img-name">
                    <img
                      class="item-image"
                      :src="
                        item.media_manager
                          ? `/uploads/${item.media_manager.hash_name}`
                          : '/images/placeholder-thumb.png'
                      "
                    />
                    {{ $i18n.locale == "ar" ? item.name_ar : item.name_en }}
                  </div>
                </td>
                <td>
                  <span class="normal" v-if="item.base_category">
                    {{
                      $i18n.locale == "ar"
                        ? item.base_category.name_ar
                        : item.base_category.name_en
                    }}
                  </span>
                  <span v-else class="chip">
                    {{ $t("N/A") }}
                  </span>
                </td>
                <td>
                  <template v-if="item.brands.length">
                    <span
                      v-for="brand in item.brands"
                      class="chip"
                      :key="brand.id"
                    >
                      {{ $i18n.locale == "ar" ? brand.name_ar : brand.name_en }}
                    </span>
                  </template>
                  <span v-else class="chip">
                    {{ $t("N/A") }}
                  </span>
                </td>
                <td>
                  <span class="normal">{{ item.priority }}</span>
                </td>
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
                      <router-link
                        class="dropdown-item"
                        :to="`/admin/categories-form/${item.id}`"
                        >{{ $t("EDIT") }}</router-link
                      >
                      <a
                        @click="onDeleteClicked(item)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="dropdown-item"
                        href=""
                        >{{ $t("DELETE") }}</a
                      >
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td class="no-result" colspan="6">{{ $t("No results") }}</td>
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
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import itemStore from "./item-store";
import categoryClient from "../../../http-clients/admin/category-client";
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
    onMounted(() => {});
    //Methods
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
      categoryClient
        .getItems(data.page, data.pageSize, data.text)
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
      categoryClient.getAllItems().then((res) => {
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
      categoryClient
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
      ...toRefs(data),
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
.category-container {
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
    .entries {
      font-size: 12.5px;
    }

    td {
      font-size: 12px;
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
    .entries {
      font-size: 13px;
    }
    td {
      font-size: 12.5px;
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
    background: none !important;
    border-color: #4eb529 !important;
    color: #4eb529 !important;
    &:hover {
      color: #4eb529 !important;
    }
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
      padding: 21px 10px;
      font-size: 13px !important;
    }
  }
  .create-item {
    padding: 20px 20px 40px 20px !important;
  }
  .search-btn {
    padding: 11px 0;
    width: 100%;
    background: #f4f6f8 !important;
    border-color: #dfe3e8 !important;
    color: #454f5b !important;
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
