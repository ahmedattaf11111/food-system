<template>
  <div class="p-3 item-container">
    <DeleteConfirmation @confirm="deleteItem" @closed="selectedItem = null" />
    <ItemForm @created="onCreated" @updated="onUpdated" :selectedItem="selectedItem" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("ITEM") }}</b>, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("ITEM") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
        <div class="controls">
          <div class="search">
            <input v-model="text" type="text" :placeholder="$t('SEARCH')" ref="search" />
            <i class="fa fa-search"></i>
          </div>
          <div class="actions my-2">
            <button @click="onAddClicked()" data-toggle="modal" data-target="#itemFormModal"
              class="border text-secondary">
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
        <div class="table-responsive">
          <table id="printMe" class="table">
            <thead>
              <tr>
                <th scope="col">{{ $t("CODE") }}</th>
                <th class="actions-header" scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item) in items" :key="item.id">
                <td>{{ item.id }}</td>
                <td class="actions-cell">
                  <div class="actions">
                    <button @click="onEditClicked(item)" data-toggle="modal" data-target="#itemFormModal"
                      class="border text-secondary">
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </button>
                    <button @click="onDeleteClicked(item)" data-toggle="modal" data-target="#deleteConfirmationModal"
                      class="border text-secondary">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-1">
          <paginate v-model="page" :pageCount="pageCounts" :clickHandler="getItems" :prevText="$t('PREV')"
            :nextText="$t('NEXT')">
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import manyImageClient from "../../../http-clients/admin/many-image-client";
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
      manyImageClient
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
      manyImageClient.getAllItems().then((res) => {
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
      manyImageClient
        .delete(data.selectedItem.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          if (data.page > 1 && data.items.length == 1) {
            data.page--;
          }
          getItems();
          data.selectedItem = null;
        })
        .catch((error) => { });
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
</style>
