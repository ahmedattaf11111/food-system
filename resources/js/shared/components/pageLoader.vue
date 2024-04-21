<template>
  <Loading
    :opacity="1"
    :color="color"
    :width="width"
    :height="height"
    :active="showPageLoader"
    :can-cancel="canCancel"
    :is-full-page="fullPage"
  ></Loading>
</template>
<script>
import { inject, toRefs } from "vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  components: {
    Loading,
  },
  setup() {
    return toRefs(inject("store"));
  },
  props:["color"],
  data() {
    return {
      width: 80,
      height: 80,
      canCancel: true,
      fullPage: true,
    };
  },
};
</script>

<style lang="scss" scoped>
</style>