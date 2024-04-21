<template>
  <div>
    <router-view />
  </div>
</template>
<script>
import { inject, onMounted } from "vue";
import authClient from "../http-clients/web/auth-client";
import TokenUtil from "../shared/utils/token-util";

export default {
  components: {},
  setup() {
    const store = inject("store");
    onMounted(() => {
      // if (TokenUtil.get()) {
      //   authClient
      //     .getCurrentUser()
      //     .then((response) => {
      //       store.currentUser = response.data;
      //     })
      //     .catch((error) => {
      //       console.log("err", error.response);
      //     });
      // }
    });
  },
};
</script>
<style scoped>
</style>