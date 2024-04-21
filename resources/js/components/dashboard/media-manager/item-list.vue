<template>
  <div class="media-manager-container">
    <DeleteConfirmation @confirm="deleteMedia" />
    <div
      v-if="!usedAsComponent"
      class="page-header border"
      style="margin-top: 10px"
    >
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="head-title">
            {{ $t("MEDIA_MANAGER") }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="media-container col-lg-6">
        <div class="col-12" style="padding: 0">
          <div class="head-title" style="margin-bottom: 8px">
            {{ $t("RECENTLY_UPLOADED_FILES") }}
          </div>
        </div>

        <div class="row">
          <div
            v-for="item in recentUploadedFiles"
            :key="item.id"
            class="mb-4"
            :class="{
              'col-lg-6': !usedAsComponent,
              'col-lg-4': usedAsComponent,
            }"
          >
            <div
              @click="markMedia(item)"
              class="c-media"
              :class="{
                hovered: usedAsComponent,
                'selected-border':
                  selectedMedia &&
                  selectedMedia.includes(item.id) &&
                  (singleSelect || multiSelect),
              }"
            >
              <div
                v-if="
                  selectedMedia &&
                  selectedMedia.includes(item.id) &&
                  (singleSelect || multiSelect)
                "
                class="selected-media"
              >
                <i class="fa fa-check"></i>
              </div>
              <div class="delete-media" v-if="!usedAsComponent">
                <button
                  data-toggle="modal"
                  data-target="#deleteConfirmationModal"
                  @click="onDeleteClicked(item.id)"
                  class="btn btn-danger"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </div>
              <img
                :style="`height:${usedAsComponent ? '115px' : '130px'}`"
                :src="`/uploads/${item.hash_name}`"
                class="img-fluid"
              />
              <div class="info">
                <div class="media-title">{{ item.name }}</div>
                <div class="extension text-mute">{{ item.extension }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="file-upload-container col-lg-6">
        <div class="col-12 p-0">
          <div class="head-title" style="margin-bottom: 8px">
            {{ $t("ADD_FILES_HERE") }}
          </div>
          <div v-if="uploadedImage.length" class="control">
            <div>
              <a
                style="font-size: 12px !important"
                v-if="!filesUploadedCompleted"
                href=""
                @click.prevent="clearAllMedia"
                >{{ $t("CANCEL") }}</a
              >
            </div>
            <div
              style="font-size: 12px !important; font-weight: 500"
              v-if="!filesUploadedCompleted"
            >
              {{ $t("FILES_SELECTED", { n: uploadedImage.length }) }}
            </div>
            <div style="font-size: 12px !important; font-weight: 500" v-else>
              {{ $t("Upload complete") }}
            </div>

            <div class="add-more-files">
              <a href="" @click.prevent="openBrowserFile">+</a>
            </div>
          </div>
        </div>
        <div class="col-12 p-0">
          <div class="mb-10">
            <form
              :style="`min-height:${usedAsComponent ? '194px' : '213px'}`"
              class="dropzone"
              :class="
                !uploadingFiles && !filesUploadedCompleted
                  ? 'dz-border dz-clickable ' + id
                  : 'dz-clickable ' + id
              "
              action="#"
              id="my-awesome-dropzone"
            >
              <div class="fallback">
                <input type="file" name="file" />
              </div>
              <div class="dz-message border-bottom" data-dz-message>
                <span class="icon">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                </span>
                <span class="text">{{ $t("DROP_FILES_HERE") }}</span>
              </div>
            </form>
            <div style="background-color: #fff" class="progress">
              <div
                id="progress-bar"
                class="progress-bar"
                role="progressbar"
                style="
                  width: 0%;
                  background-color: #4eb529;
                  transition: width 0.5s;
                "
                aria-valuenow="25"
                aria-valuemin="0"
                aria-valuemax="100"
              ></div>
            </div>
            <div
              :style="`display:${
                uploadingFiles || filesUploadedCompleted ? 'flex' : 'none'
              }`"
              class="upload-info"
            >
              <div class="first">
                <template v-if="filesUploadedCompleted">
                  <div>
                    <i
                      style="color: #4eb529; position: relative; bottom: 2px"
                      class="fa fa-check"
                    ></i>
                  </div>
                  <div style="font-size: 12px !important; font-weight: 500">
                    {{ $t("COMPLETED") }}
                  </div>
                </template>
                <template v-else>
                  <div>
                    <div
                      class="spinner-border spinner-border-sm text-secondary"
                      role="status"
                    >
                      <span class="sr-only">Loading...</span>
                    </div>
                  </div>
                  <div style="font-size: 12px !important">
                    {{ $t("UPLOADING") }}
                  </div>
                  <div>{{ `${progress}%` }}</div>
                </template>
              </div>
              <div>
                <button v-if="uploadingFiles" class="btn cancel-loading border">
                  <i class="fa fa-close"></i>
                </button>
                <button
                  @click="clearAllMedia"
                  v-if="filesUploadedCompleted"
                  class="btn done-completed"
                >
                  {{ $t("DONE") }}
                </button>
              </div>
            </div>
          </div>
        </div>
        <div
          v-if="uploadedImage.length && !filesUploadedCompleted"
          class="d-flex justify-content-center mb-30"
        >
          <div class="col-11 p-0">
            <button
              type="button"
              @click="uploadAllMedias"
              style="width: 100%; padding: 10px"
              class="btn upload-files-btn"
            >
              {{ $t("UPLOAD_FILES", { n: uploadedImage.length }) }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div style="margin: 0" class="row prev-files-container border">
      <div class="col-12" style="padding: 0">
        <div
          style="
            background: rgba(69, 79, 91, 0.03) !important;
            padding: 20px;
            margin-bottom: 25px;
          "
          class="d-flex justify-content-between mb-10 align-items-center"
        >
          <div class="head-title">
            {{ $t("PREVIOUSLY_UPLOADED_FILES") }}
          </div>
          <div class="d-flex" style="gap: 20px">
            <input
              v-model="text"
              style="width: 250px"
              class="form-control"
              :placeholder="$t('SEARCH_BY_NAME')"
            />
            <button
              type="button"
              @click="search"
              class="btn border"
              style="color: #454f5b !important; width: 100px"
            >
              <i class="fa fa-search"></i>
              {{ $t("SEARCH") }}
            </button>
          </div>
        </div>
      </div>
      <div class="prev-medias">
        <div class="row">
          <div
            v-for="item in previousUploadedFiles"
            :key="item.id"
            class="mb-4"
            :class="{
              'col-lg-3': !usedAsComponent,
              ' col-lg-2': usedAsComponent,
            }"
          >
            <div
              @click="markMedia(item)"
              class="c-media"
              :class="{
                hovered: usedAsComponent,
                'selected-border':
                  selectedMedia &&
                  selectedMedia.includes(item.id) &&
                  (singleSelect || multiSelect),
              }"
            >
              <div
                v-if="
                  selectedMedia &&
                  selectedMedia.includes(item.id) &&
                  (singleSelect || multiSelect)
                "
                class="selected-media"
              >
                <i class="fa fa-check"></i>
              </div>
              <div class="delete-media" v-if="!usedAsComponent">
                <button
                  data-toggle="modal"
                  data-target="#deleteConfirmationModal"
                  @click="onDeleteClicked(item.id)"
                  class="btn btn-danger"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </div>
              <img
                :style="`height:${usedAsComponent ? '120px' : '130px'}`"
                :src="`/uploads/${item.hash_name}`"
                class="img-fluid"
              />
              <div class="info">
                <div class="media-title">{{ item.name }}</div>
                <div class="extension text-mute">{{ item.extension }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <button
        type="button"
        @click="loadMoreMedia"
        style="padding: 10px; margin-top: 25px; font-size: 12px !important"
        class="btn btn-primary mb-30"
      >
        <i class="fa fa-refresh" aria-hidden="true"></i>
        {{ $t("LOAD_MORE") }}
      </button>
    </div>
  </div>
</template>

<script>
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import mediaManagerClient from "../../../http-clients/admin/media-manager-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
export default {
  components: {
    DeleteConfirmation,
  },
  setup(props, context) {
    const route = useRoute();
    const { t, locale } = useI18n({ useScope: "global" });
    const item_store = inject("item_store");
    const _store = inject("store");
    const data = reactive({
      uploadedImage: [],
      text: "",
      selectedMediaId: null,
      uploadingFiles: false,
      filesUploadedCompleted: false,
      progress: 0,
      previewImage: "",
      recentUploadedFiles: [],
      previousUploadedFiles: [],
      titleArExist: false,
      titleEnExist: false,
      prevLimit: 12,
      recentLimit: 3,
      selectedMediaInfo: [],
    });

    onMounted(() => {
      Dropzone.autoDiscover = false;
      $("."+props.id).dropzone({
        addRemoveLinks: true,
        acceptedFiles: "image/*",
        removedfile: function (file) {
          var name = file.name;
          let index = data.uploadedImage.findIndex((e) => e == file);
          data.uploadedImage.splice(index, 1);
          var _ref;
          if (data.uploadedImage.length == 0) {
            $(".dz-message").css("display", "flex");
            $("#progress-bar").css("width", 0);
            data.uploadingFiles = false;
            data.filesUploadedCompleted = false;
          }
          return (_ref = file.previewElement) != null
            ? _ref.parentNode.removeChild(file.previewElement)
            : void 0;
        },
        init: function () {
          this.on("addedfiles", (files) => {
            for (const key in files) {
              if (typeof files[key] == "object") {
                data.uploadedImage.push(files[key]);
              }
            }
            $(".dz-message").css("display", "none");
          });
        },
      });
      getMedia();
    });
    //Methods
    function markMedia(media) {
      let index = props.selectedMedia.findIndex(
        (mediaId) => mediaId == media.id
      );
      if (index >= 0) {
        props.selectedMedia.splice(index, 1);
        data.selectedMediaInfo.splice(index, 1);
      } else {
        if (props.singleSelect) {
          props.selectedMedia = [media.id];
          data.selectedMediaInfo = [media];
        } else if (props.multiSelect) {
          props.selectedMedia.push(media.id);
          data.selectedMediaInfo.push(media);
        }
      }
      context.emit("selectedMedia", data.selectedMediaInfo);
    }
    function search() {
      data.page = 1;
      getMedia();
    }
    function openBrowserFile() {
      data.uploadingFiles = false;
      data.filesUploadedCompleted = false;
      $("#progress-bar").css("width", 0);
      $("."+props.id).trigger("click");
    }
    function clearAllMedia() {
      data.uploadedImage.forEach((file) => {
        var _ref = file.previewElement;
        _ref.parentNode.removeChild(file.previewElement);
      });
      data.uploadedImage = [];
      $(".dz-message").css("display", "flex");
      $("#progress-bar").css("width", 0);
      data.uploadingFiles = false;
      data.filesUploadedCompleted = false;
    }
    function loadMoreMedia() {
      data.prevLimit += data.prevLimit;
      getMedia();
    }
    function getMedia() {
      mediaManagerClient
        .getMedia(data.prevLimit, data.recentLimit, data.text)
        .then((res) => {
          data.recentUploadedFiles = res.data.recent_uploaded_files;
          data.previousUploadedFiles = res.data.previously_uploaded_files;
        });
    }
    function onDeleteClicked(id) {
      data.selectedMediaId = id;
    }
    function deleteMedia() {
      mediaManagerClient.deleteMedia(data.selectedMediaId).then((res) => {
        toast.success(t("DELETED_SUCCESSFULLY"), {
          autoClose: 2000,
          position: "top-center",
        });

        getMedia();
      });
    }
    function uploadAllMedias() {
      data.uploadingFiles = true;
      _store.pendLoader = true;
      let bar = document.getElementById("progress-bar");
      const config = {
        onUploadProgress: function (progressEvent) {
          const percentCompleted = Math.round(
            (progressEvent.loaded / progressEvent.total) * 100
          );
          data.progress = percentCompleted;
          bar.style.width = `${percentCompleted}%`;
        },
      };
      let formData = new FormData();
      data.uploadedImage.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
      });
      formData.append("images", data.formData);
      mediaManagerClient
        .store(formData, config)
        .then((response) => {
          setTimeout(() => {
            _store.pendLoader = false;
          }, 1000);
          data.uploadingFiles = false;
          data.filesUploadedCompleted = true;
          data.prevLimit = 12;
          getMedia();
        })
        .catch((error) => {});
    }
    //Commons
    return {
      loadMoreMedia,
      clearAllMedia,
      openBrowserFile,
      search,
      markMedia,
      deleteMedia,
      ...toRefs(data),
      onDeleteClicked,
      uploadAllMedias,
    };
  },
  props: [
    "id",
    "selectedItem",
    "usedAsComponent",
    "singleSelect",
    "multiSelect",
    "selectedMedia",
  ],
};
</script>

<style lang="scss">
.media-manager-container {
  .page-header {
    box-shadow: unset !important;
    padding: 22px 20px;
  }

  .upload-files-btn {
    background: #4eb529 !important;
    color: #fff !important;
  }
  body[dir="ltr"] & {
    .head-title {
      font-size: 15px !important;
      color: #212b36;
      font-weight: 550;
    }
  }
  body[dir="rtl"] & {
    .head-title {
      font-size: 14px !important;
      color: #212b36;
      font-weight: 550;
    }
  }
  .hovered {
    cursor: pointer;
  }
  * {
    font-size: 13px !important;
  }
  .done-completed {
    font-size: 10px !important;
    color: #2275d7;
    background: #2275d729;
  }
  .cancel-loading {
    justify-content: center;
    display: flex;
    border-radius: 50%;
    width: 22px;
    padding: 0;
    height: 21px;
    color: #6c757d;
    font-size: 13px;
    i {
      position: relative;
      top: 3px;
    }
  }
  .upload-info {
    * {
      font-size: 13px;
    }
    background: #fff;
    padding: 10px 13px;
    display: flex;
    justify-content: space-between;
    .first {
      align-items: center;
      gap: 10px;
      display: flex;
    }
  }
  .progress,
  .progress-bar {
    height: 2px;
  }
  .control {
    align-items: center;
    .add-more-files {
      * {
        font-size: 23px !important;
      }
      position: relative;
      bottom: 3px;
      color: #2275d7;
    }
    font-size: 14px;
    padding: 5px 10px;
    justify-content: space-between;
    display: flex;
    a {
      color: #2275d7;
    }
  }
  .delete-media {
    border-radius: 5px;

    display: flex;
    background: rgba(78, 181, 41, 0);
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 100%;
    align-items: center;
    transition: all 0.5s;
    button {
      transition: all 0.5s;
      align-items: center;
      height: 40px;
      width: 40px;
      display: flex;
      justify-content: center;
      opacity: 0;
    }
    &:hover {
      background: rgba(78, 181, 41, 0.3);
    }
    &:hover button {
      opacity: 1;
    }
  }
  .dz-clickable {
    padding: 0 !important;
  }
  .selected-border {
    border: 2px solid #4eb529;
  }
  .selected-media {
    color: #fff;
    align-items: center;
    justify-content: center;
    display: flex;
    background: #4eb529;
    height: 44px;
    width: 64px;
    position: absolute;
    top: -15px;
    right: -26px;
    transform: rotate(45deg);
    i {
      transform: rotate(-45deg);
      position: relative;
      top: 10px;
    }
  }
  .c-media {
    overflow: hidden;
    position: relative;
    .extension {
      font-size: 13px;
      color: #919eab !important;
    }
    img {
      border-radius: 8px;
      width: 100%;
      object-fit: cover;
    }
    .media-title {
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      padding: 8px 0px;
    }
    border-radius: 5px;
    padding: 12px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(33, 43, 54, 0.15);
  }
  .prev-medias {
    padding: 0 15px;
  }

  .dz-message {
    .text {
      margin-bottom: 12px;
    }
    .icon {
      i {
        font-size: 14px;
        left: 1px;
        position: relative;
        bottom: 1px;
      }
      margin-bottom: 11px;
      color: #fff;
      align-items: center;
      background: #2275d7;
      height: 30px;
      width: 30px;
      display: flex;
      border-radius: 50%;
      justify-content: center;
    }
    padding: 0 15px;
    gap: 13px;
    align-items: center;
    display: flex;
    margin: 4em 0;
  }
  .dropzone {
    box-shadow: 0 1px 2px rgba(33, 43, 54, 0.15);
    transition: all 0.5s;
    border: none;
    &.dz-border {
      border: 2px dashed #4eb529 !important;
    }
  }

  .prev-files-container {
    border-radius: 6px;
  }
}
</style>