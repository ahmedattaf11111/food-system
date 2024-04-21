<template>
  <div class="profile-container">
    <div class="container-fluid rounded bg-white mb-5">
      <form enctype="multipart/form-data" @submit.prevent="updateProfile">
        <div class="row">
          <div class="col-md-4">
            <div
              class="d-flex flex-column align-items-center text-center p-3 py-5"
            >
              <img
                v-if="previewImage"
                class="rounded-circle border"
                :src="previewImage"
              />
              <img
                class="rounded-circle border"
                v-else
                src="../../../../public/assets/images/empty-image.png"
              />
              <div class="image-upload">
                <input
                  accept="image/png, image/gif, image/jpeg"
                  id="file"
                  type="file"
                  @change="uploadImage"
                />
                <label class="icon" for="file">
                  <i class="fas fa-camera text-secondary"></i>
                </label>
                <label
                  @click="deleteImage"
                  v-if="uploadedImage"
                  class="icon text-secondary px-2"
                >
                  <i class="fa fa-window-close" aria-hidden="true"></i>
                </label>
              </div>
              <span class="font-weight-bold">{{
                first_name + " " + last_name
              }}</span>
              <span class="text-black-50">{{ email }}</span>
            </div>
          </div>
          <div class="col-md-8">
            <div class="p-3 py-5">
              <div
                class="d-flex justify-content-between align-items-center mb-3"
              >
                <h4 class="text-right text-secondary">
                  {{ $t("PROFILE_SETTINGS") }}
                </h4>
              </div>
              <div class="row">
                <div class="col-md-6 mt-2">
                  <label class="labels">{{ $t("FIRST_NAME") }}</label
                  ><input
                    v-model="v$.first_name.$model"
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': v$.first_name.$error,
                    }"
                  />
                  <div class="invalid-feedback">
                    <div v-for="error in v$.first_name.$errors" :key="error">
                      {{ $t("FIRST_NAME") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-2">
                  <label class="labels">{{ $t("LAST_NAME") }}</label
                  ><input
                    v-model="v$.last_name.$model"
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': v$.last_name.$error,
                    }"
                  />
                  <div class="invalid-feedback">
                    <div v-for="error in v$.last_name.$errors" :key="error">
                      {{ $t("LAST_NAME") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mt-2">
                  <label class="labels">{{ $t("MOBILE_NUMBER") }}</label
                  ><input
                    v-model="v$.phone.$model"
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': v$.phone.$error,
                    }"
                  />
                  <div class="invalid-feedback">
                    <div v-for="error in v$.phone.$errors" :key="error">
                      {{ $t("MOBILE_NUMBER") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mt-2">
                  <label class="labels">{{ $t("ADDRESS") }}</label
                  ><input v-model="address" type="text" class="form-control" />
                </div>
                <div class="col-md-12 mt-2">
                  <label class="labels">{{ $t("CITY") }}</label
                  ><input v-model="city" type="text" class="form-control" />
                </div>
                <div class="col-md-12 mt-2">
                  <label class="labels">{{ $t("AGE") }}</label
                  ><input
                    :class="{
                      'is-invalid': v$.age.$error,
                    }"
                    v-model="v$.age.$model"
                    type="number"
                    class="form-control"
                  />
                  <div class="invalid-feedback">
                    <div v-for="error in v$.age.$errors" :key="error">
                      {{ $t(error.$validator) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 mt-2">
                <label class="labels">{{ $t("EDUCATION") }}</label
                ><input v-model="education" type="text" class="form-control" />
              </div>
              <div class="col-md-12 mt-2">
                <label class="labels">{{ $t("JOB") }}</label
                ><input v-model="job" type="text" class="form-control" />
              </div>
              <div class="col-md-12 mt-2">
                <label class="labels">{{ $t("WRITE_ABOUT_YOUR_SELF") }}</label
                ><textarea
                  v-model="about_me"
                  type="text"
                  class="form-control"
                  rows="4"
                >
                </textarea>
              </div>
              <div class="mt-3">
                <button class="btn profile-button" type="submit">
                  {{ $t("SUBMIT") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import phoneValidator from "../../shared/validators/phone-validator";
import useVuelidate from "@vuelidate/core";
import authClient from "../../http-clients/web/auth-client";
import { required, minValue } from "@vuelidate/validators";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      previewImage: "",
      first_name: "",
      last_name: "",
      phone: "",
      address: "",
      city: "",
      age: "",
      education: "",
      job: "",
      about_me: "",
      uploadedImage: null,
      email: "",
    };
  },
  methods: {
    uploadImage(e) {
      const image = e.target.files[0];
      this.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        this.previewImage = e.target.result;
      };
    },
    deleteImage() {
      this.uploadedImage = null;
      this.previewImage =
        this.store.currentUser && this.store.currentUser.image
          ? this.store.currentUser.image
          : "";
    },
    updateProfile() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      authClient
        .updateProfile(this.getForm())
        .then((response) => {
          this.store.currentUser = response.data;
          this.$toast.success(this.$t("UPDATED_SUCCESSFULLY"));
          this.uploadedImage = null;
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    //Commons
    getForm() {
      let formData = new FormData();
      this.appendField(formData, "first_name", this.first_name);
      this.appendField(formData, "last_name", this.last_name);
      this.appendField(formData, "phone", this.phone);
      this.appendField(formData, "address", this.address);
      this.appendField(formData, "city", this.city);
      this.appendField(formData, "age", this.age);
      this.appendField(formData, "education", this.education);
      this.appendField(formData, "job", this.job);
      this.appendField(formData, "about_me", this.about_me);
      if (this.uploadedImage) {
        formData.append("image", this.uploadedImage);
      }
      return formData;
    },
    appendField(formData, fieldName, fieldValue) {
      if (fieldValue) {
        formData.append(fieldName, fieldValue);
      }
    },
    setForm() {
      if (this.store.currentUser) {
        this.first_name = this.store.currentUser.first_name;
        this.last_name = this.store.currentUser.last_name;
        this.phone = this.store.currentUser.phone;
        this.address = this.store.currentUser.address;
        this.city = this.store.currentUser.city;
        this.age = this.store.currentUser.age;
        this.education = this.store.currentUser.education;
        this.job = this.store.currentUser.job;
        this.about_me = this.store.currentUser.about_me;
        this.email = this.store.currentUser.email;
        this.previewImage = this.store.currentUser.image;
      }
    },
  },
  validations() {
    return {
      first_name: { required },
      last_name: { required },
      phone: { phoneValidator },
      age: { minValue: minValue(0) },
    };
  },
  created() {
    this.setForm();
  },
  inject: ["store"],
};
</script>

<style scoped lang="scss">
.profile-container {
  img {
    width: 150px;
    height: 150px;
  }
  .profile-button {
    width: 150px;
    border-radius: 0;
    color: #fff;
    background-color: #2caae2 !important;
  }
  .image-upload {
    input[type="file"] {
      display: none;
    }
    .icon {
      &:hover {
        cursor: pointer;
      }
      i {
        font-size: 14px;
        position: relative;
        top: 1px;
      }
    }
  }
}
</style>