<template>
  <div class="login">
    <div class="login-box col-lg-7 col-md-9 m-auto">
      <div class="row">
        <div class="logo mb-3 text-center">
          <router-link to="/home">
            <img
              class="border"
              src="../../../../public/assets/images/logo.jpg"
            />
          </router-link>
        </div>
        <!--Header-->
        <div class="header text-center">
          <h6>{{ $t("LOGIN") }}</h6>
        </div>
        <!--Form-->
        <form @submit.prevent="login">
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
            <div class="mb-3 col-12">
              <label class="form-label"
                >{{ $t("PASSWORD") }} <span class="text-danger">*</span></label
              >
              <input
                v-model="v$.password.$model"
                :type="passwordHidden ? 'password' : 'text'"
                class="form-control"
                :class="{
                  'is-invalid': v$.password.$error,
                }"
              />
              <div class="invalid-feedback">
                <div v-for="error in v$.password.$errors" :key="error">
                  {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="form-check mt-2 mb-4">
                <input
                  @change="passwordHidden = !passwordHidden"
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
                <label class="form-check-label" for="flexCheckDefault">
                  {{ $t("SHOW_PASSWORD") }}
                </label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn confirm">
            {{ $t("LOGIN") }}
          </button>
          <div class="links">
            <router-link to="/register">
              {{ $t("REGISTER_INSTEAD") }}
            </router-link>
            <router-link to="/forget-password">
              {{ $t("FORGET_PASSWORD_?") }}
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
import TokenUtil from "../../shared/utils/token-util";
import global from "../../shared/global";
export default {
  setup() {
    return { v$: useVuelidate()};
  },
  data: function () {
    return {
      email: "",
      password: "",
      passwordHidden: true,
    };
  },
  methods: {
    login() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      authClient
        .login(this.getForm())
        .then((response) => {
          TokenUtil.set(response.data.access_token);
          this.$router.push(global.AUTH_REDIRECT);
          this.store.currentUser = TokenUtil.getUser();
        })
        .catch((error) => {
          this.$toast.error(this.$t("LOGIN_FAILED"));
        });
    },
    //Commons
    getForm() {
      return {
        email: this.email,
        password: this.password,
      };
    },
  },
  inject: ["store"],
  validations() {
    return {
      email: { required, email },
      password: { required },
    };
  },
  created() {},
};
</script>

<style lang="scss" scoped>
.login {
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
  .login-box {
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
    .password-hint {
      font-size: 14px;
      color: #5f6368;
    }
    .confirm {
      width: 150px;
      border-radius: 0;
      color: #fff;
      background-color: #2caae2 !important;
    }
    .form-check {
      label {
        position: relative;
        top: 2px;
      }
      input {
        border-radius: 0;
      }
    }
  }
}
</style>