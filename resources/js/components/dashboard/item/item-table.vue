<template>
  <div class="p-3 item-container">
    <DeleteConfirmation @confirm="deleteItem" @closed="selectedItem = null" />
    <ItemForm @created="onCreated" @updated="onUpdated" :selectedItem="selectedItem" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("ITEM") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("ITEM") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
          <div class="table-responsive">
            <div class="controls">
              <div class="search">
                <input
                  v-model="text"
                  type="text"
                  :placeholder="$t('SEARCH')"
                  ref="search"
                />
                <i class="fa fa-search"></i>
              </div>
              <div class="actions my-2">
                <button
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#itemFormModal"
                  class="border text-secondary"
                >
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
                <button @click="downloadExcelFile" class="border text-secondary">
                  <i class="fa fa-download" aria-hidden="true"></i>
                </button>
                <button @click="print" class="border text-secondary">
                  <i class="fa fa-print" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <table id="printMe" class="table">
              <thead>
                <tr>
                  <th scope="col">{{ $t("IMAGE") }}</th>
                  <th scope="col">{{ $t("TITLE") }}</th>
                  <th scope="col">{{ $t("TITLE") }}</th>
                  <th class="actions-header" scope="col">{{ $t("ACTIONS") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in items" :key="item.id">
                  <td><img :src="item.image" /></td>
                  <td>{{ item.title_ar }}</td>
                  <td>{{ item.title_en }}</td>
                  <td class="actions-cell">
                    <div class="actions">
                      <button
                        @click="onEditClicked(item)"
                        data-toggle="modal"
                        data-target="#itemFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-edit" aria-hidden="true"></i>
                      </button>
                      <button
                        @click="onDeleteClicked(item)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        <div class="mt-1">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getItems"
            :prevText="$t('PREV')"
            :nextText="$t('NEXT')"
          >
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import itemClient from "../../../shared/http-clients/item-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import ItemForm from "./item-form.vue";
import itemStore from "./item-store";
import { inject, provide, reactive, ref, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    ItemForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      items: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedItem: null,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("item_store", itemStore);
    created();
    //Methods
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
    function getItems() {
      itemClient
        .getItems(data.page, data.pageSize, data.text)
        .then((response) => {
          data.items = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
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
          toast.success(t("DELETED_SUCCESSFULLY"));
          if (data.page > 1 && data.items.length==1) {
            data.page--;
          }
          getItems();
          data.selectedItem = null;
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
  td {
    img {
      width: 70px;
      height: 70px;
      border-radius: 3px;
      padding: 5px;
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
        rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }
  }
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 30px;
    .welcome {
      padding-top: 9px;
    }
    .title {
      * {
        color: #6c757d !important;
      }
      a {
        text-decoration: none;
        color: #868e96 !important;
        &:hover {
          color: #6c757d !important;
        }
      }
    }
  }
  .table-container {
    background: #ffffff;
    box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
    padding: 30px;
    .controls {
      display: flex;
      justify-content: space-between;
      @media (max-width: 500px) {
        flex-direction: column;
      }
      body[dir="ltr"] & {
        .search {
          i {
            right: 25px;
          }
        }
      }
      body[dir="rtl"] & {
        .search {
          i {
            left: 25px;
          }
        }
      }
      .search {
        margin-bottom: 10px;
        i {
          position: relative;
          top: 1px;
          color: #888888;
        }
        input {
          padding: 4px 15px;
          border: 1px solid #dee2e6 !important;
          border-radius: 5px;
        }
      }
    }
    .actions {
      display: flex;
      a:hover {
        cursor: text;
      }
      button {
        width: 34px;
        height: 34px;
        background: none;
        margin: 3px 5px;
        border-radius: 5px;
      }
    }
    a:hover {
      cursor: pointer;
    }
    .active {
      a {
        color: #fff !important;
        background-color: #6d85fb !important;
        border-color: #dbdbdb !important;
      }
    }
    .page-link {
      padding: 3px 18px !important;
    }
    table {
      td,
      th {
        width: 25%;
      }
    }
  }
}
</style>
