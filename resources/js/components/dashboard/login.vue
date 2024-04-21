<template>
  <Loader color="#4eb529" />
  <div class="login-header box-shadow">
    <div
      class="container-fluid d-flex justify-content-between align-items-center"
    >
      <div class="brand-logo">
        <a href="login.html">
          <img style="margin: 0 8px" src="/images/admin-logo.png" alt="" />
          {{ $t("DASHBOARD") }}
        </a>
      </div>
      <div class="login-menu"></div>
    </div>
  </div>
  <div
    class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
  >
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 col-lg-7">
          <img src="/vendors/images/login-page-img.png" alt="" />
        </div>
        <div class="col-md-6 col-lg-5">
          <div class="login-box bg-white box-shadow border-radius-10">
            <div class="login-title">
              <h2 class="text-center text-primary">
                {{ $t("LOGIN_TO_DESKTOP") }}
              </h2>
            </div>

            <form>
              <div class="input-group custom">
                <input
                  :class="{
                    'is-invalid': v$.email.$error,
                  }"
                  v-model="v$.email.$model"
                  type="text"
                  class="form-control form-control-lg"
                  :placeholder="$t('ENTER_EMAIL')"
                />
                <div class="input-group-append custom">
                  <span class="input-group-text"
                    ><i class="icon-copy dw dw-user1"></i
                  ></span>
                </div>
                <div class="invalid-feedback">
                  <div v-for="error in v$.email.$errors" :key="error">
                    {{ $t("EMAIL") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
              <div class="input-group custom">
                <input
                  v-model="v$.password.$model"
                  :type="passwordHidden ? 'password' : 'text'"
                  :class="{
                    'is-invalid': v$.password.$error,
                  }"
                  class="form-control form-control-lg"
                  placeholder="**********"
                />
                <div class="input-group-append custom">
                  <span class="input-group-text"
                    ><i class="dw dw-padlock1"></i
                  ></span>
                </div>
                <div class="invalid-feedback">
                  <div v-for="error in v$.password.$errors" :key="error">
                    {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
              <div class="row pb-30">
                <div class="col-6">
                  <div class="custom-control custom-checkbox">
                    <input
                      @change="passwordHidden = !passwordHidden"
                      type="checkbox"
                      class="custom-control-input"
                      id="customCheck1"
                    />
                    <label class="custom-control-label" for="customCheck1">{{
                      $t("SHOW_PASSWORD")
                    }}</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="input-group mb-0">
                    <!--
											use code for form submit
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
										-->
                    <a
                      @click.prevent="login"
                      class="signin btn btn-primary btn-lg btn-block"
                      href=""
                      >{{ $t("SIGN_IN") }}</a
                    >
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="login">
    <Loader color="#1b00ff" />
    <div class="login-box col-lg-4 col-md-6">
      <div class="row">
        <form @submit.prevent="login">
          <div class="row">
            <div class="col-12">
              <label class="form-label">{{ $t("EMAIL") }} <span class="text-danger">*</span></label>
              <input v-model="v$.email.$model" type="text" class="form-control" :class="{
                'is-invalid': v$.email.$error,
              }" />
              <div class="invalid-feedback">
                <div v-for="error in v$.email.$errors" :key="error">
                  {{ $t("EMAIL") + " " + $t(error.$validator) }}
                </div>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">{{ $t("PASSWORD") }} <span class="text-danger">*</span></label>
              
            </div>
            <div class="col-12 mt-3 mb-2">
              <div class="form-check">
                <input @change="passwordHidden = !passwordHidden" class="form-check-input" type="checkbox" value=""
                  id="flexCheckDefault" />
                <label class="form-check-label" for="flexCheckDefault">
                  {{ $t("SHOW_PASSWORD") }}
                </label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-danger">
            {{ $t("LOGIN") }}
          </button>
        </form>
      </div>
    </div>
  </div> -->
</template>
<script>
import { required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import adminClient from "../../http-clients/admin/admin-auth-client";
import TokenUtil from "../../shared/utils/token-util";
import global from "../../shared/global";
import Loader from "../../shared/components/loader.vue";
import LangUtil from "../../shared/utils/lang-util";
import { inject, onMounted } from "vue";
export default {
  setup() {
    const lang = LangUtil.setup();
    onMounted(() => {
      lang.changeAdminLang(LangUtil.get());
    });
    return { v$: useVuelidate() };
  },
  components: {
    Loader,
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
      this.store.showPageLoader = true;
      adminClient
        .login(this.getForm())
        .then((response) => {
          this.store.showPageLoader = false;
          TokenUtil.set(response.data.access_token);
          this.$router.push(global.ADMIN_AUTH_REDIRECT);
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
.signin:hover {
  color: #fff !important;
}
.brand-logo a {
  font-size: 16px;
  color: black;
}
.login {
  padding: 0 0 100px 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  .logo {
    img {
      width: 135px;
      height: 124px;
      border-radius: 50%;
    }
  }

  .login-box {
    background: #fff;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px,
      rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;

    @media (max-width: 767px) {
      //For small devices
      & {
        box-shadow: none !important;
      }
    }

    .submit {
      width: 120px;
    }

    .form-control {
      background: none;
      padding: 12px;
    }

    input {
      border-radius: 0;
    }

    padding: 36px;

    .header {
      margin-bottom: 22px;
    }

    .password-hint {
      font-size: 14px;
      color: #5f6368;
    }

    .form-check {
      label {
        position: relative;
        bottom: 3px;
      }
    }
  }
}
</style>
