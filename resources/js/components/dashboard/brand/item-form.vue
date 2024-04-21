<template>
  <div class="brand-form">
    <div class="page-header border" style="margin-top: 10px">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            {{ $t("UPDATE_BRAND") }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div
        class="col-lg-9 order-lg-1 order-2"
        :style="
          $i18n.locale == 'en' ? 'padding-right:10px' : 'padding-left:10px'
        "
      >
        <div class="form-container bg-white border border-radius-4 mb-30">
          <div class="create-item">
            <div class="title mb-4">{{ $t("BASIC_INFORMATION") }}</div>
            <form>
              <div class="row">
                <div v-if="isArabic" class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("NAME") }}
                      <a class="locale" href="" @click.prevent="toggleLocale">
                        {{ $t("ARABIC") }}
                      </a>
                    </label>
                    <input
                      :placeholder="$t('TYPE') + ' ' + $t('NAME')"
                      type="text"
                      class="form-control"
                      v-model="v$.name_ar.$model"
                      :class="{
                        'is-invalid': v$.name_ar.$error || titleArExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name_ar.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name_ar.$invalid && titleArExist">
                        {{ $t("NAME") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("NAME") }}
                      <a class="locale" href="" @click.prevent="toggleLocale">
                        {{ $t("ENGLISH") }}
                      </a>
                    </label>
                    <input
                      :placeholder="$t('TYPE') + ' ' + $t('NAME')"
                      type="text"
                      class="form-control"
                      v-model="v$.name_en.$model"
                      :class="{
                        'is-invalid': v$.name_en.$error || titleArExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name_en.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name_en.$invalid && titleArExist">
                        {{ $t("NAME") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label>{{ $t("BRAND_SLUG") }}</label>
                    <input
                      :placeholder="$t('TYPE') + ' ' + $t('BRAND_SLUG')"
                      type="text"
                      class="form-control"
                      v-model="v$.slug.$model"
                      :class="{
                        'is-invalid': v$.slug.$error || titleEnExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.slug.$errors" :key="error">
                        {{ $t("BRAND_SLUG") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.slug.$invalid && titleEnExist">
                        {{ $t("BRAND_SLUG") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 mb-2">
                  <Image
                  :id="1"
                    title="BRAND_IMAGE"
                    subTitle="CHOOSE_BRAND_THUMBNAIL"
                    :selectedMedia="selectedMedia"
                    @selectedMedia="selectedMedia = $event"
                    :singleSelect="true"
                  />
                </div>
              </div>
            </form>
          </div>
        </div>
        <button @click="save" class="btn btn-primary save-submit">
          <i class="fa fa-save"></i>
          {{ $t("SAVE") }}
        </button>
      </div>
      <div class="col-lg-3 order-lg-2 order-1">
        <div class="bg-white border border-radius-4 mb-30">
          <div class="information">
            <div class="title">{{ $t("BRAND_INFORMATION") }}</div>
            <ul>
              <li class="active all-taxes">{{ $t("BASIC_INFORMATION") }}</li>
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

import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
import brandClient from "../../../http-clients/admin/brand-client";
import { computed, inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import Image from "../../../shared/components/image.vue";
export default {
  components: {
    Image,
  },
  setup(props, context) {
    const route = useRoute();
    const { t, locale } = useI18n({ useScope: "global" });
    const item_store = inject("item_store");
    const data = reactive({
      isArabic: false,
      uploadedImage: null,
      selectedMedia: [],
      previewImage: "",
      titleArExist: false,
      titleEnExist: false,
    });
    const form = reactive({
      name_ar: "",
      name_en: "",
      slug: "",
    });
    const rules = {
      name_ar: { required },
      name_en: { required },
      slug: { required },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      brandClient.getItem(route.params.id).then((res) => {
        form.name_ar = res.data.name_ar;
        form.name_en = res.data.name_en;
        form.slug = res.data.slug;
        data.selectedMedia = res.data.media_manager
          ? [res.data.media_manager]
          : [];
          
      });
    });
    //Methods
    function toggleLocale() {
      data.isArabic = !data.isArabic;
    }
    function uploadImage(e) {
      const image = e.target.files[0];
      data.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        data.previewImage = e.target.result;
      };
    }
    function deleteImage() {
      data.uploadedImage = null;
      data.previewImage = props.selectedItem ? props.selectedItem.image : "";
    }

    function addItem() {
      form.list.push(getElement());
    }

    function removeItem(index) {
      if (form.list.length > 1) {
        form.list.splice(index, 1);
      }
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      update();
    }
    //Commons
    function store() {
      data.titleArExist = false;
      data.titleEnExist = false;
      brandClient
        .store(getForm())
        .then((response) => {
          context.emit("created", response.data);
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.name_ar ? true : false;
          data.titleEnExist = error.response.data.errors.name_en ? true : false;
        });
    }
    function update() {
      data.titleArExist = false;
      data.titleEnExist = false;
      brandClient
        .update(getForm())
        .then((response) => {
          data.isArabic = false;
          toast.success(t("UPDATED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });
          context.emit("updated", response.data);
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.name_ar ? true : false;
          data.titleEnExist = error.response.data.errors.name_en ? true : false;
        });
    }
    function touchlist() {
      form.list.forEach((item) => {
        item.name_ar_dirty = true;
        item.name_en_dirty = true;
      });
    }
    function getElement() {
      return { name_ar: "", name_en: "" };
    }
    function getForm() {
      return {
        id: route.params.id,
        media_manager_id: data.selectedMedia.length
          ? data.selectedMedia[0].id
          : null,
        name_ar: form.name_ar,
        name_en: form.name_en,
        slug: form.slug,
      };
    }
    function setlistToFormData(formData) {
      form.list.forEach((item, index) => {
        formData.append(`list[${index}][name_ar]`, item.name_ar);
        formData.append(`list[${index}][name_en]`, item.name_en);
      });
    }
    function setForm() {
      v$.value.$reset();
      form.list = props.selectedItem
        ? _.clone(props.selectedItem.list)
        : [getElement()];
      form.name_ar = props.selectedItem ? props.selectedItem.name_ar : "";
      form.name_en = props.selectedItem ? props.selectedItem.name_en : "";
      data.previewImage = props.selectedItem ? props.selectedItem.image : "";
      data.uploadedImage = null;
      data.titleArExist = false;
      data.titleEnExist = false;
    }
    //Watchers
    // watch(
    //   () => {
    //     item_store.onFormShow;
    //   },
    //   (value) => {
    //     setForm();
    //   },
    //   { deep: true }
    // );
    return {
      toggleLocale,
      ...toRefs(data),
      ...toRefs(form),
      v$,
      uploadImage,
      deleteImage,
      addItem,
      removeItem,
      save,
    };
  },
  props: ["selectedItem"],
};
</script>

<style lang="scss">
.brand-form {
  * {
    font-size: 13px !important;
  }

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
      th,
      td {
        font-size: 14px !important;
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
        top: 3px;
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
        top: 2px;
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

  .page-header {
    box-shadow: none;
    margin-bottom: 19px !important;
  }
  .save-submit {
    margin-bottom: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 110px;
    gap: 12px;
    justify-content: center;
    padding: 10px 0;
    border-radius: 5px;
  }
  .form-container {
    padding: 20px 20px 40px 20px;
    border-radius: 5px;
  }
  .form-control {
    font-size: 13px !important;
    padding: 21px 10px !important;
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

    li {
      position: relative;
      margin-bottom: 15px;
    }
    padding: 15px 15px 20px 15px;
  }
}
</style>