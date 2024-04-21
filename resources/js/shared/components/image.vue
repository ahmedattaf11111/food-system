<template>
  <div :id="id">
    <div class="media-manager-background"></div>
    <div class="media-manager">
      <div class="image-media-title border-bottom">
        <div class="head-title">{{ $t("MEDIA_FILES") }}</div>
        <div>
          <button
            type="button"
            @click="closeMediaManagerModal"
            class="close-media"
          >
            <i class="fa fa-close"></i>
          </button>
        </div>
      </div>
      <div class="media-body">
        <MediaManager
          :id="id"
          :selectedMedia="selectedMedia.map((media) => media.id)"
          @selectedMedia="triggerEmit($event)"
          :singleSelect="singleSelect"
          :multiSelect="multiSelect"
          :usedAsComponent="true"
        />
      </div>
    </div>
    <div class="image-container">
      <div class="image-title">{{ $t(title) }}</div>
      <div class="image-body">
        <div class="image-sub-title">{{ $t(subTitle) }}</div>
        <div class="image-list">
          <div v-for="item in selectedMedia" :key="item.id" class="image">
            <button
              type="button"
              @click="deleteMedia(item.id)"
              class="delete-image"
            >
              <i class="fa fa-trash"></i>
            </button>
            <img :src="`/uploads/${item.hash_name}`" />
          </div>
          <button
            @click="showMediaManagerModal"
            type="button"
            class="add-image"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
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
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, toRefs } from "vue";
import MediaManager from "../../components/dashboard/media-manager/item-list.vue";
export default {
  components: {
    MediaManager,
  },

  setup(props, context) {
    const data = reactive({});
    onMounted(() => {
      $(`#${props.id} .media-manager-background`).on("click", function () {
        closeMediaManagerModal();
      });
      $(document).on("keydown", function (event) {
        if (event.key == "Escape") {
          closeMediaManagerModal();
        }
      });
    });

    //methods
    function triggerEmit($event) {
      context.emit("selectedMedia", (data.selectedMedia = $event));
    }
    function deleteMedia(id) {
      let index = props.selectedMedia.findIndex((media) => media.id == id);
      if (index >= 0) {
        props.selectedMedia.splice(index, 1);
      }
      context.emit("selectedMedia", props.selectedMedia);
    }
    function showMediaManagerModal() {
      $(`#${props.id} .media-manager`).css("bottom", "0");
      $(`#${props.id} .media-manager-background`)
        .css("z-index", "9996")
        .css("opacity", ".7");
      $("body").css("overflow", "hidden");
    }
    function closeMediaManagerModal() {
      $(`#${props.id} .media-manager`).css("bottom", "-85vh");
      $(`#${props.id} .media-manager-background`)
        .css("z-index", "-1")
        .css("opacity", "0");
      $("body").css("overflow", "auto");
    }
    return {
      showMediaManagerModal,
      closeMediaManagerModal,
      deleteMedia,
      triggerEmit,
      ...toRefs(data),
      ...toRefs(props),
    };
  },
  props: [
    "id",
    "singleSelect",
    "multiSelect",
    "title",
    "subTitle",
    "selectedMedia",
  ],
};
</script>

<style lang="scss">
body[dir="ltr"] {
  .head-title {
    font-size: 17px !important;
    color: #212b36;
    font-weight: 550;
  }
  .image-sub-title {
    font-size: 14px !important;
    color: #212b36;
    font-weight: 550;
  }
}
body[dir="rtl"] {
  .head-title {
    font-size: 16px !important;
    color: #212b36;
    font-weight: 550;
  }
  .image-sub-title {
    font-size: 14px !important;
    color: #212b36;
    font-weight: 550;
  }
}
.btn-primary {
  background: #4eb529 !important;
  border-color: #4eb529 !important;
}
.close-image-window {
  position: fixed;
  bottom: 1px;
}
.media-manager-background {
  transition: all 0.3s;
  opacity: 0;
  z-index: -1;
  background: black;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
}
.media-manager {
  transition: all 0.3s;

  .media-body {
    height: 75vh;
    padding: 22px;
    overflow: auto;
  }
  .close-media {
    border: none;
    background: none;
    color: #919eab !important;
    font-weight: lighter;
    i {
      font-size: 20px !important;
    }
  }
  z-index: 9997;
  position: fixed;
  bottom: -85vh;
  left: 0;
  width: 100%;
  background: #f9fafb;
  .image-media-title {
    align-items: center;
    height: 10vh;
    position: relative;
    top: 0;
    font-weight: bold;
    font-size: 20px;
    background: #fff;
    padding: 35px 24px;
    display: flex;
    justify-content: space-between;
  }
}
.image-title {
  margin-bottom: 10px;
}
.image:hover .delete-image {
  background: rgba(224, 58, 29, 0.7);
}
.image:hover .delete-image i {
  opacity: 1;
}
.delete-image {
  transition: all 0.5s;
  background: rgba(224, 58, 29, 0);
  border-radius: 50%;
  color: #fff;
  width: 50px;
  height: 50px;
  border: none;
  position: absolute;
  i {
    opacity: 0;
    transition: all 0.5s;
  }
}
.add-image {
  background: #dfe3e8;
  height: 50px;
  width: 50px;
  border: none;
  color: #fff;
  border-radius: 50%;
}
.image-body {
  height: 170px;
  border-radius: 5px;
  padding: 33px 0;
  border: 2px dashed #dfe3e8;
  gap: 15px;
  flex-direction: column;
  align-items: center;
  display: flex;
}
.image {
  img {
    object-fit: contain;
    border-radius: 50%;
    height: 50px;
    width: 50px;
  }
}
.image-list {
  padding: 0 15px;
  flex-wrap: wrap;
  display: flex;
  gap: 10px;
}
</style>