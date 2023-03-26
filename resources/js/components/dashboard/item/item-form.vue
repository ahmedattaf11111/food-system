<template>
  <div class="item-form">
    <div
      class="modal fade"
      id="itemFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("FORM") + " " + $t("ITEM") }}
              </h5>
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-4 mb-3">
                  <div class="image">
                    <img
                      v-if="previewImage"
                      class="border-bottom"
                      :src="previewImage"
                    />
                    <img
                      v-else
                      class="border-bottom"
                      src="/assets/images/empty.jpg"
                    />
                    <div class="image-upload">
                      <label class="icon" for="image">
                        <i class="fa fa-camera"></i>
                      </label>
                      <label
                        @click="deleteImage"
                        v-if="uploadedImage"
                        class="icon text-secondary px-2"
                      >
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                      </label>
                      <input
                        @change="uploadImage"
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                        type="file"
                        id="image"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("TITLE_AR")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.title_ar.$model"
                          :class="{
                            'is-invalid': v$.title_ar.$error || titleArExist,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.title_ar.$errors"
                            :key="error"
                          >
                            {{ $t("TITLE_AR") + " " + $t(error.$validator) }}
                          </div>
                          <div v-if="!v$.title_ar.$invalid && titleArExist">
                            {{ $t("TITLE_AR") + " " + $t("EXIST") }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ $t("TITLE_EN") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.title_en.$model"
                          :class="{
                            'is-invalid': v$.title_en.$error || titleEnExist,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.title_en.$errors"
                            :key="error"
                          >
                            {{ $t("TITLE_EN") + " " + $t(error.$validator) }}
                          </div>
                          <div v-if="!v$.title_en.$invalid && titleEnExist">
                            {{ $t("TITLE_EN") + " " + $t("EXIST") }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr class="mt-3" />
                    <!--list-->
                    <div
                      class="list"
                      v-for="(item, index) in list"
                      :key="index"
                    >
                      <div class="item row">
                        <div class="col-lg-12 d-flex justify-content-end">
                          <button
                            @click="removeItem(index)"
                            class="increments border"
                            type="button"
                          >
                            -
                          </button>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label>{{ $t("TITLE_AR") }}</label>
                            <input
                              @input="item.title_ar_dirty = true"
                              type="text"
                              class="form-control"
                              v-model="item.title_ar"
                              :class="{
                                'is-invalid':
                                  item.title_ar_dirty &&
                                  v$.list.$each.$response.$errors[index]
                                    .title_ar.length,
                              }"
                            />
                            <div class="invalid-feedback">
                              <div
                                v-for="error in v$.list.$each.$response.$errors[
                                  index
                                ].title_ar"
                                :key="error"
                              >
                                {{
                                  $t("TITLE_AR") + " " + $t(error.$validator)
                                }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label>{{ $t("TITLE_EN") }}</label>
                            <input
                              @input="item.title_en_dirty = true"
                              type="text"
                              class="form-control"
                              v-model="item.title_en"
                              :class="{
                                'is-invalid':
                                  item.title_en_dirty &&
                                  v$.list.$each.$response.$errors[index]
                                    .title_en.length,
                              }"
                            />
                            <div class="invalid-feedback">
                              <div
                                v-for="error in v$.list.$each.$response.$errors[
                                  index
                                ].title_en"
                                :key="error"
                              >
                                {{
                                  $t("TITLE_EN") + " " + $t(error.$validator)
                                }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                      <button
                        @click="addItem"
                        class="increments border"
                        type="button"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">
                {{ $t("SUBMIT") }}
              </button>
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                {{ $t("CLOSE") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
import itemClient from "../../../shared/http-clients/item-client";
import { computed, inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const item_store = inject("item_store");
    const toast = inject("toast");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
      titleArExist: false,
      titleEnExist: false,
    });
    const form = reactive({
      title_ar: "",
      title_en: "",
      list: [getElement()],
    });
    const rules = {
      title_ar: { required },
      title_en: { required },
      list: {
        $each: helpers.forEach({
          title_ar: { required },
          title_en: { required },
        }),
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
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
        touchlist();
        return;
      }
      if (!props.selectedItem) {
        if (!data.uploadedImage) {
          toast.error(t("IMAGE") + " " + t("required"));
          return;
        }
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      data.titleArExist = false;
      data.titleEnExist = false;
      itemClient
        .store(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", response.data);
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.title_ar
            ? true
            : false;
          data.titleEnExist = error.response.data.errors.title_en
            ? true
            : false;
        });
    }
    function update() {
      data.titleArExist = false;
      data.titleEnExist = false;
      itemClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", response.data);
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.title_ar
            ? true
            : false;
          data.titleEnExist = error.response.data.errors.title_en
            ? true
            : false;
        });
    }
    function touchlist() {
      form.list.forEach((item) => {
        item.title_ar_dirty = true;
        item.title_en_dirty = true;
      });
    }
    function getElement() {
      return { title_ar: "", title_en: "" };
    }
    function getForm() {
      let formData = new FormData();
      if (props.selectedItem) {
        formData.append("id", props.selectedItem.id);
      }
      formData.append("title_ar", form.title_ar);
      formData.append("title_en", form.title_en);
      setlistToFormData(formData);
      if (data.uploadedImage) {
        formData.append("image", data.uploadedImage);
      }
      return formData;
    }
    function setlistToFormData(formData) {
      form.list.forEach((item, index) => {
        formData.append(`list[${index}][title_ar]`, item.title_ar);
        formData.append(`list[${index}][title_en]`, item.title_en);
      });
    }
    function setForm() {
      v$.value.$reset();
      form.list = props.selectedItem
        ? _.clone(props.selectedItem.list)
        : [getElement()];
      form.title_ar = props.selectedItem ? props.selectedItem.title_ar : "";
      form.title_en = props.selectedItem ? props.selectedItem.title_en : "";
      data.previewImage = props.selectedItem ? props.selectedItem.image : "";
      data.uploadedImage = null;
      data.titleArExist = false;
      data.titleEnExist = false;
    }
    //Watchers
    watch(
      () => {
        item_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
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

<style scoped lang="scss">
.item-form {
  .form-control {
    padding: 10px;
  }
  .form-group {
    margin-bottom: 10px;
    label {
      margin-bottom: 5px;
    }
  }
  .icons {
    i {
      font-size: 20px;
    }
    i:hover {
      cursor: pointer;
    }
  }
  .image {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    padding-bottom: 5px;
    img {
      width: 100%;
      height: 150px;
    }
    .image-upload {
      i {
        margin-top: 7px;
        color: #888888;
      }
      .icon {
        &:hover {
          cursor: pointer !important;
        }
        i {
          font-size: 14px;
          position: relative;
        }
      }
      text-align: center;
      input[type="file"] {
        display: none;
      }
    }
  }
  .increments {
    font-size: 16px !important;
    justify-content: center;
    display: flex;
    width: 37px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 5px;
    align-items: center;
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>