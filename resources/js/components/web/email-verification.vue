<template>
  <div class="email-verification">
    <div class="email-verification-box col-lg-7 col-md-9 m-auto">
      <div class="row">
        <!--Header-->
        <div class="header">
          <h6>{{ $t("EMAIL_VERIFICATION") }}</h6>
        </div>
        <!--Form-->
        <form @submit.prevent="verifyEmail">
          <div class="row">
            <div class="mb-3 col-12">
              <label class="form-label"
                >{{ $t("TOKEN") }} <span class="text-danger">*</span></label
              >
              <input
                v-model="v$.verification_code.$model"
                type="text"
                class="form-control"
                :class="{
                  'is-invalid':
                    v$.verification_code.$error || invalidVerificationCode,
                }"
              />
              <div class="invalid-feedback">
                <div v-for="error in v$.verification_code.$errors" :key="error">
                  {{ $t("TOKEN") + " " + $t(error.$validator) }}
                </div>
                <div
                  v-if="
                    !v$.verification_code.$invalid && invalidVerificationCode
                  "
                >
                  {{ $t("TOKEN") + " " + $t("INVALID") }}
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn confirm">
            {{ $t("SUBMIT") }}
          </button>
          <div class="links">
            <a href="" @click.prevent="resendToken">
              {{ $t("RESEND") + " " + $t("TOKEN") }}
            </a>
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
import global from "../../shared/global";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      verification_code: "",
      invalidVerificationCode: false,
    };
  },
  methods: {
    verifyEmail() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      this.invalidVerificationCode = false;
      authClient
        .verifyEmail(this.getForm())
        .then((response) => {
          this.$toast.success(this.$t("EMAIL_VERIFIED_SUCCESSFULLY"));
          this.$router.push(global.AUTH_REDIRECT);
          this.store.currentUser.email_verified_at = {};
        })
        .catch((error) => {
          this.invalidVerificationCode = true;
        });
    },
    resendToken() {
      authClient
        .resendToken()
        .then((response) => {
          this.$toast.success(this.$t("EMAIL_SENT_SUCCESSFULLY"));
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    //Commons
    getForm() {
      return {
        verification_code: this.verification_code,
      };
    },
  },
  inject: ["store"],
  validations() {
    return {
      verification_code: { required },
    };
  },
  created() {},
};
</script>

<style lang="scss" scoped>
.email-verification {
  padding: 100px 0;
  .links {
    margin-top: 10px;
    a {
      display: block;
      text-decoration: none;
    }
  }
  .email-verification-box {
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