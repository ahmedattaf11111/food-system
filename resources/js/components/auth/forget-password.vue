<template>
  <div class="forget-password">
    <div class="forget-password-box col-lg-7 col-md-9 m-auto">
      <div class="row">
        <!--Header-->
        <div class="logo mb-3 text-center">
          <router-link to="/home">
            <img
              class="border"
              src="../../../../public/assets/images/logo.jpg"
            />
          </router-link>
        </div>
        <div class="header text-center">
          <h6>{{ $t("RESET_PASSWORD") }}</h6>
        </div>
        <!--Form-->
        <form @submit.prevent="forgetPassword">
          <div class="row">
            <div class="mb-3 col-12">
              <label class="form-label"
                >{{ $t("EMAIL") }} <span class="text-danger">*</span></label
              >
              <input
                v-model="v$.email.$model"
                type="text"
                class="form-control"
                :class="{
                  'is-invalid': v$.email.$error,
                }"
              />
              <div class="invalid-feedback">
                <div v-for="error in v$.email.$errors" :key="error">
                  {{ $t("EMAIL") + " " + $t(error.$validator) }}
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn confirm">
            {{ $t("SEND") }}
          </button>
          <div class="links">
            <router-link to="/login">
              {{ $t("LOGIN_INSTEAD") }}
            </router-link>
            <router-link to="/register">
              {{ $t("REGISTER_INSTEAD") }}
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import authClient from "../../http-clients/web/auth-client";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      email: "",
    };
  },
  methods: {
    forgetPassword() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      authClient
        .forgetPassword(this.email)
        .then((response) => {
          this.$toast.success(this.$t("EMAIL_SENT_SUCCESSFULLY"));
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
  },
  inject: ["store"],
  validations() {
    return {
      email: { required, email },
    };
  },
  created() {},
};
</script>

<style lang="scss" scoped>
.forget-password {
  padding: 0 0 100px 0;
  .links {
    margin-top: 10px;
    a {
      display: block;
      text-decoration: none;
    }
  }
  .logo {
    img {
      width: 135px;
      height: 124px;
      border-radius: 50%;
    }
  }
  .forget-password-box {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px,
      rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
    @media (max-width: 767px) {
      //For small devices
      & {
        box-shadow: none !important;
      }
    }
    input {
      border-radius: 0;
      padding: 8px;
    }
    padding: 36px;
    .header {
      margin-bottom: 22px;
    }
    .confirm {
      width: 150px;
      border-radius: 0;
      color: #fff;
      background-color: #2caae2 !important;
    }
  }
}
</style>