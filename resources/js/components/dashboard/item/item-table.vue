<template>
  <div class="item-container">
    <ItemForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedItem="selectedItem"
    />
    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>{{ $t("ITEM") }}</h4>
          </div>
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/">{{ $t("HOME") }}</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ $t("ITEM") }}
              </li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <div class="dropdown">
            <a
              class="btn btn-primary dropdown-toggle"
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
          </div>
        </div>
      </div>
    </div>
    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
      <div class="">
        <div class="table-container">
          <div class="controls">
            <h5 class="table-header">{{ $t("Data Table Simple") }}</h5>
            <div class="d-flex options">
              <div>{{ $t("you can add more entries") }}</div>
              <a
                @click.prevent="onAddClicked()"
                data-toggle="modal"
                data-target="#itemFormModal"
                class="outer"
              >
                {{ $t("CLICK_HERE") }}
              </a>
            </div>
            <div class="d-flex justify-content-between pageSize">
              <div class="d-flex">
                <span>{{ $t("SHOW") }}</span>
                <select
                  v-model="pageSize"
                  @change="onPageSizeChanged"
                  style="height: 39px; width: 61px"
                  class="custom-select"
                >
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="30">30</option>
                </select>
                <span>{{ $t("ENTRIES") }}</span>
              </div>
              <div class="search d-flex">
                <label>{{ $t("SEARCH") }} : </label>
                <input
                  style="width: 220px"
                  class="form-control"
                  v-model="text"
                  type="text"
                  :placeholder="$t('SEARCH')"
                  ref="search"
                />
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped" id="printMe">
              <thead>
                <tr>
                  <th scope="col">{{ $t("NAME_AR") }}</th>
                  <th scope="col">{{ $t("NAME_EN") }}</th>
                  <th class="actions-header" scope="col">
                    {{ $t("ACTIONS") }}
                  </th>
                </tr>
              </thead>
              <tbody v-if="items.length">
                <tr v-for="(item, index) in items" :key="item.id">
                  <td>{{ item.name_ar }}</td>
                  <td>{{ item.name_en }}</td>

                  <td class="actions-cell">
                    <div class="dropdown">
                      <a
                        class="btn"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                      >
                        <i class="dw dw-more"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-left">
                        <a
                          @click.prevent="onEditClicked(item)"
                          data-toggle="modal"
                          data-target="#itemFormModal"
                          class="dropdown-item"
                          href=""
                          >{{ $t("EDIT") }}</a
                        >
                        <a
                          @click.prevent="onDeleteClicked(item)"
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
                  <td
                    class="text-center"
                    style="padding: 15px !important"
                    colspan="120"
                  >
                    {{ $t("NO_DATA") }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div
            v-if="items.length"
            class="page-container d-flex justify-content-between"
          >
            <div class="entries">
              {{ pageSize * (page - 1) + 1 }} -
              {{ (page - 1) * pageSize + items.length }} {{ $t("OF") }}
              {{ totalItems }} {{ $t("ENTRIES") }}
            </div>
            <div>
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
  </div>
</template>
<script>
import itemClient from "../../../http-clients/admin/item-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import ItemForm from "../tax/item-form.vue";
import itemStore from "../tax/item-store";
import { inject, provide, reactive, ref, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    ItemForm,
  },
  setup() {
    const data = reactive({
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
    const toast = inject("toast");
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("item_store", itemStore);
    created();
    //Methods
    function getMounthName() {
      let date=new Date();
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
      swal
        .fire({
          title: t("Are you sure"),
          text: t("You will not be able to revert this"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: t("YES"),
          cancelButtonText: t("NO"),
        })
        .then((res) => {
          if (res.value) {
            deleteItem();
          }
        });
    }
    function onPageSizeChanged() {
      data.page = 1;
      getItems();
    }
    function getItems() {
      itemClient
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
      itemClient.getAllItems().then((res) => {
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
      itemClient
        .delete(data.selectedItem.id)
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("DELETED_SUCCESSFULLY"),
          });
          if (data.page > 1 && data.items.length == 1) {
            data.page--;
          }
          getItems();
        })
        .catch((error) => {});
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getItems();
      }, 500);
    }
    watch(
      () => {
        data.text;
      },
      (value) => {
        search();
      },
      { deep: true }
    );
    //Commons
    function created() {
      getItems();
    }
    return {
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
      search,
      print,
    };
  },
};
</script>

<style lang="scss">
body[dir="rtl"] {
  .actions-cell .dropdown-menu {
    position: absolute !important;
    transform: translate3d(103px, -43px, 0px) !important;
    top: 0px !important;
    left: 0px !important;
    width:100px;
    will-change: transform !important;
  }
  .breadcrumb-item + .breadcrumb-item::before {
    font-family: "FontAwesome";
    content: "\f104" !important;
  }
}
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
.item-container {
  .options {
    margin: 9px 0 20px 0px;
    align-items: center;
    a.outer {
      margin: 0 6px;
      cursor: pointer;
      color: #1b00ff;
    }
  }
  .page-container {
    .entries {
      margin-top: 10px;
    }
  }
  .page-link {
    padding: 0.4rem 0.75rem !important;
  }
  .table {
    td {
      padding: 0 1rem !important;
    }
    th,
    td {
      white-space: nowrap;
    }
  }
  .actions-cell .dropdown .btn {
    color: #1b00ff;
    font-size: 23px;
  }
  .table-header {
    color: #1b00ff;
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
    label {
      margin: 0 5px;
      position: relative;
      top: 7px;
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
