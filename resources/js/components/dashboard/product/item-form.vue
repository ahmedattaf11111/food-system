<template>
  <div class="product-form">
    <div class="page-header border" style="margin-top: 10px">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            {{
              params.id == "create" ? $t("ADD_PRODUCT") : $t("UPDATE_PRODUCT")
            }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div
        class="col-lg-9 order-lg-1 order-2 basic-info-sec"
        :style="
          $i18n.locale == 'en' ? 'padding-right:10px' : 'padding-left:10px'
        "
      >
        <form>
          <div
            id="basic-information"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("BASIC_INFORMATION") }}</div>
              <div class="row">
                <div v-if="isArabic" class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("PRODUCT_NAME") }}
                      <a class="locale" href="" @click.prevent="toggleLocale">
                        {{ $t("ARABIC") }}
                      </a>
                    </label>
                    <input
                      :placeholder="$t('TYPE_PRODUCT_NAME')"
                      type="text"
                      class="form-control"
                      v-model="v$.name_ar.$model"
                      :class="{
                        'is-invalid': v$.name_ar.$error || titleArExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name_ar.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name_ar.$invalid && titleArExist">
                        {{ $t("NAME") + " " + $t("EXIST") }}
                      </div>
                    </div>
                    <div class="text-mute">
                      {{ $t("PRODUCT_NAME_REQUIRED_RECOMMENDED_UNIQUE") }}
                    </div>
                  </div>
                </div>
                <div v-else class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("PRODUCT_NAME") }}
                      <a class="locale" href="" @click.prevent="toggleLocale">
                        {{ $t("ENGLISH") }}
                      </a>
                    </label>
                    <input
                      :placeholder="$t('TYPE_PRODUCT_NAME')"
                      type="text"
                      class="form-control"
                      v-model="v$.name_en.$model"
                      :class="{
                        'is-invalid': v$.name_en.$error || titleEnExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name_en.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name_en.$invalid && titleEnExist">
                        {{ $t("NAME") + " " + $t("EXIST") }}
                      </div>
                    </div>
                    <div class="text-mute">
                      {{ $t("PRODUCT_NAME_REQUIRED_RECOMMENDED_UNIQUE") }}
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("SHORT_DESCRIPTION") }}
                    </label>
                    <textarea
                      rows="3"
                      :placeholder="$t('TYPE_PRODUCT_SHORT_DESCRIPTION')"
                      type="text"
                      class="form-control"
                      v-model="short_description"
                    >
                    </textarea>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group html-editor mb-30">
                    <label for="exampleInputEmail1">
                      {{ $t("DESCRIPTION") }}
                    </label>
                    <ckeditor :editor="editor" v-model="description"></ckeditor>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            id="image"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("IMAGES") }}</div>
              <div class="row">
                <div class="col-lg-12 mb-4">
                  <Image
                    :id="1"
                    title="Thumbnail_592_592"
                    subTitle="CHOOSE_CATEGORY_THUMBNAIL"
                    :selectedMedia="selectedMedia"
                    @selectedMedia="selectedMedia = $event"
                    :singleSelect="true"
                  />
                </div>
                <div class="col-lg-12 mb-2">
                  <Image
                    :id="2"
                    title="GALLERY"
                    subTitle="CHOOSE_GALLERY_IMAGES"
                    :selectedMedia="selectedGalleries"
                    @selectedMedia="selectedGalleries = $event"
                    :multiSelect="true"
                  />
                </div>
              </div>
            </div>
          </div>

          <div
            id="category"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("PRODUCT_CATEGORIES") }}</div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <Multiselect
                      :placeholder="$t('SELECT_CATEGORIES')"
                      mode="tags"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="categories_ids"
                      valueProp="id"
                      :options="categories"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            id="tag"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("PRODUCT_TAGS") }}</div>
              <div class="row">
                <div class="col-lg-12 mb-2">
                  <div class="form-group">
                    <Multiselect
                      :placeholder="$t('SELECT_TAGS')"
                      mode="tags"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="tags_ids"
                      valueProp="id"
                      :options="tags"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="brand_unit" class="row">
            <div class="col-lg-6">
              <div class="form-container bg-white border border-radius-4 mb-30">
                <div class="create-item">
                  <div class="title mb-4">{{ $t("PRODUCT_BRAND") }}</div>
                  <div class="form-group">
                    <Multiselect
                      :placeholder="$t('SELECT_BRAND')"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="brand_id"
                      valueProp="id"
                      :options="brands"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-container bg-white border border-radius-4 mb-30">
                <div class="create-item">
                  <div class="title mb-4">{{ $t("PRODUCT_UNIT") }}</div>
                  <div class="form-group">
                    <Multiselect
                      :placeholder="$t('SELECT_UNIT')"
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      :searchable="true"
                      v-model="unit_id"
                      valueProp="id"
                      :options="units"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            id="variation"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="d-flex justify-content-between align-items">
                <div class="title mb-4">
                  {{ $t("PRICE_SKU_STOCK") }}
                </div>
                <div class="variation-switch">
                  <label class="switch">
                    <input
                      v-model="has_variations"
                      :checked="has_variations"
                      type="checkbox"
                    />
                    <span class="slider round border"></span>
                  </label>
                  <label class="switch-label">{{ $t("HAS_VARIATIONS") }}</label>
                </div>
              </div>
              <div v-if="!has_variations" class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("PRICE") }}
                    </label>
                    <input
                      :placeholder="$t('PRODUCT_PRICE')"
                      type="number"
                      v-model="product_price[0].price"
                      class="form-control"
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("STOCK") }}
                      <span class="def-loc">{{ $t("DEFAULT_LOCATION") }}</span>
                    </label>
                    <input
                      type="number"
                      class="form-control"
                      :placeholder="$t('STOCK_QTY')"
                      v-model="product_price[0].stock"
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("SKU") }}
                    </label>

                    <input
                      type="text"
                      class="form-control"
                      :placeholder="$t('PRODUCT_SKU')"
                      v-model="product_price[0].sku"
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("CODE") }}
                    </label>

                    <input
                      type="text"
                      class="form-control"
                      v-model="product_price[0].code"
                      :placeholder="$t('PRODUCT_CODE')"
                    />
                  </div>
                </div>
              </div>
              <div v-else>
                <div
                  v-for="(variation, index) in variation_ids"
                  :key="index"
                  class="row"
                >
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">
                        {{ $t("SELECT_VARIATION") }}
                      </label>
                      <Multiselect
                        :disabled="variation.variation_values_ids.length"
                        :searchable="true"
                        v-model="variation.variation_id"
                        :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                        valueProp="id"
                        :options="variations"
                      />
                    </div>
                  </div>
                  <div
                    class="col-lg-6"
                    :class="index != 0 && variation_ids.length ? 'd-flex' : ''"
                    :style="
                      index != 0 && variation_ids.length ? 'gap:20px' : ''
                    "
                  >
                    <div
                      :style="
                        index != 0 && variation_ids.length ? 'width:90%' : ''
                      "
                      class="form-group"
                    >
                      <label for="exampleInputEmail1">
                        {{ $t("SELECT_VALUES") }}
                      </label>
                      <Multiselect
                        @change="onVariationValueChanged"
                        v-model="variation.variation_values_ids"
                        mode="tags"
                        :searchable="true"
                        :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                        valueProp="id"
                        :options="getVariationValues(variation.variation_id)"
                      />
                    </div>
                    <div
                      v-if="index != 0 && variation_ids.length"
                      style="width: 10%"
                      class="delete-variation"
                    >
                      <a
                        class="text-danger"
                        href=""
                        @click.prevent="removeVariation(index)"
                      >
                        <i class="fa fa-trash"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="add_variation">
                  <a href="" @click.prevent="addVariation"
                    ><i class="fa fa-plus"></i>
                    {{ $t("ADD_ANOTHER_VARIATION") }}</a
                  >
                </div>
                <div
                  v-if="all_product_variations.length"
                  class="variations-form"
                >
                  <div class="table-responsive">
                    <table class="table" id="printMe">
                      <thead>
                        <tr>
                          <th scope="col">{{ $t("VARIATION") }}</th>
                          <th scope="col">{{ $t("PRICE") }}</th>
                          <th scope="col">
                            {{ $t("STOCK") }}
                            <span class="def-loc">{{
                              $t("DEFAULT_LOCATION")
                            }}</span>
                          </th>
                          <th scope="col">{{ $t("SKU") }}</th>
                          <th scope="col">{{ $t("CODE") }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(variation, index) in all_product_variations"
                          :key="index"
                        >
                          <td>
                            <div class="form-group">
                              <input
                                :disabled="true"
                                :value="
                                  $i18n.locale == 'ar'
                                    ? variation.variation_name_ar
                                    : variation.variation_name_en
                                "
                                type="text"
                                class="form-control"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                v-model="variation.price"
                                class="form-control"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                class="form-control"
                                v-model="variation.stock"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="text"
                                class="form-control"
                                v-model="variation.sku"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="text"
                                class="form-control"
                                v-model="variation.code"
                              />
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            id="discount"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("PRODUCT_DISCOUNT") }}</div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("START_DATE") }}
                    </label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="from_date"
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("END_DATE") }}
                    </label>
                    <input type="date" class="form-control" v-model="to_date" />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("DISCOUNT_AMOUNT") }}
                    </label>

                    <input
                      type="number"
                      class="form-control"
                      v-model="discount"
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("DISCOUNT_OR_FIXED") }}
                    </label>

                    <Multiselect
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      v-model="percent"
                      valueProp="value"
                      :options="discountValues"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            id="tax"
            class="form-container bg-white border border-radius-4 mb-30"
          >
            <div class="create-item">
              <div class="title mb-4">{{ $t("PRODUCT_TAXES_DEFAULT_0") }}</div>
              <div v-for="tax in taxes" :key="tax.id" class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $i18n.locale == "ar" ? tax.name_ar : tax.name_en }}
                    </label>

                    <input
                      type="number"
                      class="form-control"
                      v-model="tax.value"
                    />
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">
                      {{ $t("DISCOUNT_OR_FIXED") }}
                    </label>

                    <Multiselect
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      v-model="tax.percent"
                      valueProp="value"
                      :options="discountValues"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="target_status" class="row">
            <div class="col-lg-6">
              <div class="form-container bg-white border border-radius-4 mb-30">
                <div class="create-item">
                  <div class="title mb-4">{{ $t("SELL_TARGET") }}</div>
                  <div class="form-group">
                    <input
                      :placeholder="$t('TYPE_YOUR_SELL_TARGET')"
                      type="number"
                      class="form-control"
                      v-model="sell_target"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-container bg-white border border-radius-4 mb-30">
                <div class="create-item">
                  <div class="title mb-4">{{ $t("PRODUCT_STATUS") }}</div>
                  <div class="form-group">
                    <Multiselect
                      :label="$i18n.locale == 'ar' ? 'name_ar' : 'name_en'"
                      v-model="published"
                      valueProp="value"
                      :options="statuses"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <button @click="save" class="btn save-submit">
          <i class="fa fa-save"></i>
          {{ $t("SAVE") }}
        </button>
      </div>
      <div class="col-lg-3 order-lg-2 order-1">
        <div
          class="bg-white information-container border border-radius-4 mb-30"
        >
          <div class="information">
            <div class="title">{{ $t("PRODUCT_INFORMATION") }}</div>
            <ul>
              <a
                id="a__basic-information"
                @click="currentSection = 'basic-information'"
                href="#basic-information"
              >
                <li :class="{ active: currentSection == 'basic-information' }">
                  {{ $t("BASIC_INFORMATION") }}
                </li>
              </a>
              <a id="a__image" @click="currentSection = 'image'" href="#image">
                <li :class="{ active: currentSection == 'image' }">
                  {{ $t("PRODUCT_IMAGES") }}
                </li>
              </a>

              <a
                id="a__category"
                @click="currentSection = 'category'"
                href="#category"
              >
                <li :class="{ active: currentSection == 'category' }">
                  {{ $t("CATEGORY") }}
                </li>
              </a>
              <a id="a__tag" @click="currentSection = 'tag'" href="#tag">
                <li :class="{ active: currentSection == 'tag' }">
                  {{ $t("PRODUCT_TAGS") }}
                </li>
              </a>
              <a
                id="a__brand_unit"
                @click="currentSection = 'brand_unit'"
                href="#brand_unit"
              >
                <li :class="{ active: currentSection == 'brand_unit' }">
                  {{ $t("PRODUCT_BRAND_UNIT") }}
                </li>
              </a>
              <a
                id="a__variation"
                @click="currentSection = 'variation'"
                href="#variation"
              >
                <li :class="{ active: currentSection == 'variation' }">
                  {{ $t("PRICE_SKU_STOCK_VARIATION") }}
                </li>
              </a>

              <a
                id="a__discount"
                @click="currentSection = 'discount'"
                href="#discount"
              >
                <li :class="{ active: currentSection == 'discount' }">
                  {{ $t("PRODUCT_DISCOUNT") }}
                </li>
              </a>
              <a id="a__tax" @click="currentSection = 'tax'" href="#tax">
                <li :class="{ active: currentSection == 'tax' }">
                  {{ $t("PRODUCT_TAXES") }}
                </li>
              </a>
              <a
                id="a__target_status"
                @click="currentSection = 'target_status'"
                href="#target_status"
              >
                <li :class="{ active: currentSection == 'target_status' }">
                  {{ $t("SELL_TARGET_STATUS") }}
                </li>
              </a>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
import productClient from "../../../http-clients/admin/product-client";
import { computed, inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import Image from "../../../shared/components/image.vue";
import Multiselect from "@vueform/multiselect";
export default {
  components: {
    Image,
    Multiselect,
  },
  setup(props, context) {
    const route = useRoute();
    const { t, locale } = useI18n({ useScope: "global" });
    const item_store = inject("store");
    const data = reactive({
      isArabic: false,
      product_price: [{ price: 0, stock: 0, sku: "", code: "" }],
      variations: [],
      variation_ids: [{ variation_id: null, variation_values_ids: [] }],
      variationValues: [],
      selectedGalleries: [],
      uploadedImage: null,
      selectedMedia: [],
      previewImage: "",
      discountValues: [
        { value: 1, name_ar: "نسبة %", name_en: "Percent %" },
        { value: 0, name_ar: "ثابت", name_en: "Fixed" },
      ],
      statuses: [
        { value: 1, name_ar: "منشور", name_en: "Published" },
        { value: 0, name_ar: "غير منشور", name_en: "Unpublished" },
      ],

      titleArExist: false,
      titleEnExist: false,
      categories: [],
      tags: [],
      units: [],
      taxes: [],
      brands: [],
      all_product_variations: [],
      currentSection: "basic-information",
      editor: ClassicEditor,
      editorConfig: {
        language: "",
      },
    });
    const form = reactive({
      name_ar: "",
      name_en: "",
      unit_id: "",
      brand_id: "",
      to_date: "",
      from_date: "",
      discount: 0,
      percent: 1,
      tags_ids: [],
      has_variations: false,
      description: "...",
      short_description: "",
      categories_ids: [],
      published: 1,
      sell_target: "",
    });
    const rules = {
      name_ar: { required },
      name_en: { required },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getCategories();
      getTags();
      getBrands();
      getUnits();
      getTaxes();
      getVariations();
      setForm();
      setScrollTemp();
    });
    //Methods
    function addVariation() {
      data.variation_ids.push({ variation_id: null, variation_values_ids: [] });
    }
    function removeVariation(index) {
      data.variation_ids.splice(index, 1);
    }
    function onVariationValueChanged() {
      setTimeout(() => {
        let productVariations = data.variation_ids
          .filter((variation) => {
            return variation.variation_id;
          })
          .map((variation) => {
            return {
              values: variation.variation_values_ids.map((valueId) => {
                let fullValueInfo = getValue(variation.variation_id, valueId);
                return fullValueInfo;
              }),
            };
          });
        function cartesianProductOf() {
          return _.reduce(
            arguments,
            function (a, b) {
              return _.flatten(
                _.map(a, function (x) {
                  return _.map(b, function (y) {
                    return x.concat([y]);
                  });
                }),
                true
              );
            },
            [[]]
          );
        }
        const productValues = productVariations.map(({ values }) => values);

        data.all_product_variations = cartesianProductOf(...productValues).map(
          (e) => ({
            variation_name_en: e.map(({ name_en }) => name_en).join(" | "),
            variation_name_ar: e.map(({ name_ar }) => name_ar).join(" | "),
            stock: 0,
            sku: "",
            code: "",
            price: 0,
          })
        );
      }, 500);
    }
    function getValue(variation_id, valueId) {
      let variation = data.variations.filter((variation) => {
        return variation.id == variation_id;
      });
      if (variation.length) {
        let value = variation[0].variation_values.filter((value) => {
          return value.id == valueId;
        });
        return value.length ? value[0] : null;
      }
      return null;
    }
    function onDeselect() {
      setTimeout(() => {
        form.base_category_id = -1;
      }, 10);
    }
    function setForm() {
      if (route.params.id != "create") {
        productClient.getItem(route.params.id).then((res) => {
          form.name_ar = res.data.name_ar;
          form.name_en = res.data.name_en;
          form.to_date = res.data.to_date;
          form.brand_id = res.data.brand_id;
          form.unit_id = res.data.unit_id;
          form.from_date = res.data.from_date;
          form.discount = res.data.discount;
          form.percent = res.data.percent;
          form.sell_target = res.data.sell_target;
          form.published = res.data.published;
          form.description = res.data.description ?? "...";
          form.short_description = res.data.short_description;
          form.categories_ids = res.data.categories.map(
            (category) => category.id
          );
          form.tags_ids = res.data.tags.map((tag) => tag.id);
          data.selectedMedia = res.data.media_manager
            ? [res.data.media_manager]
            : [];
          data.selectedGalleries = res.data.gallaries;
          form.has_variations = res.data.has_variations == 1 ? true : false;
          if (!form.has_variations && res.data.variations.length) {
            data.product_price = res.data.variations;
          } else if (form.has_variations && res.data.variations.length) {
            data.all_product_variations = res.data.variations;
            data.variation_ids = res.data.variation_ids;
          }
          data.taxes = data.taxes.map((tax) => {
            let currentTax = getTax(res.data.taxes, tax.id);
            return {
              ...tax,
              percent: currentTax ? currentTax.pivot.percent : 1,
              value: currentTax ? currentTax.pivot.value : 0,
            };
          });
        });
      }
    }
    function getTax(taxes, taxId) {
      let result = taxes.filter((tax) => tax.pivot.tax_id == taxId);
      return result.length ? result[0] : null;
    }
    function setScrollTemp() {
      let linkClicked = false;
      let timeout = null;

      $(document).ready(function () {
        // Add smooth scrolling to all links
        $("a").on("click", function (event) {
          linkClicked = true;
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            linkClicked = false;
          }, 1000);
          // Make sure this.hash has a value before overriding default behavior
          if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $("html, body").animate(
              {
                scrollTop: $(hash).offset().top - 100,
              },
              800,
              function () {
                // Add hash (#) to URL when done scrolling (default click behavior)
                // window.location.hash = hash;
              }
            );
          } // End if
        });
      });
      window.onscroll = function () {
        let tablehashElement = $("#a__basic-information").attr("href");
        let addhashElement = $("#a__category").attr("href");
        let imageHashElement = $("#a__image").attr("href");
        let tagHashElement = $("#a__tag").attr("href");
        let brandUnitHashElement = $("#a__brand_unit").attr("href");
        let variationHashElement = $("#a__variation").attr("href");
        let discountHashElement = $("#a__discount").attr("href");
        let taxHashElement = $("#a__tax").attr("href");
        let targetStatusHashElement = $("#a__target_status").attr("href");
        if (linkClicked) return;
        if (
          document.documentElement.scrollTop >=
          $(targetStatusHashElement).offset().top - 140
        ) {
          data.currentSection = "target_status";
        } else if (
          document.documentElement.scrollTop >=
          $(taxHashElement).offset().top - 140
        ) {
          data.currentSection = "tax";
        } else if (
          document.documentElement.scrollTop >=
          $(discountHashElement).offset().top - 140
        ) {
          data.currentSection = "discount";
        } else if (
          document.documentElement.scrollTop >=
          $(variationHashElement).offset().top - 140
        ) {
          data.currentSection = "variation";
        } else if (
          document.documentElement.scrollTop >=
          $(brandUnitHashElement).offset().top - 140
        ) {
          data.currentSection = "brand_unit";
        } else if (
          document.documentElement.scrollTop >=
          $(tagHashElement).offset().top - 140
        ) {
          data.currentSection = "tag";
        } else if (
          document.documentElement.scrollTop >=
          $(addhashElement).offset().top - 140
        ) {
          data.currentSection = "category";
        } else if (
          document.documentElement.scrollTop >=
          $(imageHashElement).offset().top - 140
        ) {
          data.currentSection = "image";
        } else {
          data.currentSection = "basic-information";
        }

        // if (
        //   document.documentElement.scrollTop == $("#add").offset().top - 100 ||
        //   document.documentElement.scrollTop == $("#table").offset().top - 100
        // ) {
        //   linkClicked = false;
        // }
      };
    }
    function getCategories() {
      productClient.getCategories().then((res) => {
        data.categories = res.data;
      });
    }
    function getTags() {
      productClient.getTags().then((res) => {
        data.tags = res.data;
      });
    }
    function getBrands() {
      productClient.getBrands().then((res) => {
        data.brands = res.data;
      });
    }
    function getVariationValues(variation_id) {
      let result = data.variations.filter(
        (variation) => variation.id == variation_id
      );
      return result.length ? result[0].variation_values : null;
    }

    function getUnits() {
      productClient.getUnits().then((res) => {
        data.units = res.data;
      });
    }
    function getVariations() {
      productClient.getVariations().then((res) => {
        data.variations = res.data;
      });
    }

    function getTaxes() {
      productClient.getTaxes().then((res) => {
        data.taxes = res.data.map((tax) => {
          return {
            ...tax,
            percent: 1,
            value: 0,
          };
        });
      });
    }
    function toggleLocale() {
      data.isArabic = !data.isArabic;
    }
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
        return;
      }
      if (route.params.id == "create") {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      data.titleArExist = false;
      data.titleEnExist = false;
      productClient
        .store(getForm())
        .then((category) => {
          v$.value.$reset();
          form.name_ar = "";
          form.name_en = "";
          form.to_date = "";
          form.from_date = "";
          form.discount = 0;
          form.percent = true;
          form.description = "";
          form.brand_id = "";
          form.unit_id = "";
          form.short_description = "";
          form.categories_ids = [];
          form.sell_target = "";
          form.published = 1;
          data.selectedMedia = [];
          data.selectedGalleries = [];
          data.isArabic = false;
          data.product_price = [{ price: 0, stock: 0, sku: "", code: "" }];
          data.variation_ids = [
            { variation_id: null, variation_values_ids: [] },
          ];
          form.has_variations = false;
          data.all_product_variations = [];
          data.taxes = data.taxes.map((tax) => {
            return {
              ...tax,
              value: 0,
              percent: 1,
            };
          });
          toast.success(t("CREATED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.name_ar ? true : false;
          data.titleEnExist = error.response.data.errors.name_en ? true : false;
        });
    }
    function update() {
      data.titleArExist = false;
      data.titleEnExist = false;
      productClient
        .update(getForm())
        .then((category) => {
          data.isArabic = false;
          toast.success(t("UPDATED_SUCCESSFULLY"), {
            autoClose: 2000,
            position: "top-center",
          });
        })
        .catch((error) => {
          data.titleArExist = error.response.data.errors.name_ar ? true : false;
          data.titleEnExist = error.response.data.errors.name_en ? true : false;
        });
    }
    function touchlist() {
      form.list.forEach((item) => {
        item.name_ar_dirty = true;
        item.name_en_dirty = true;
      });
    }
    function getElement() {
      return { name_ar: "", name_en: "" };
    }
    function getForm() {
      return {
        id: route.params.id == "create" ? null : route.params.id,
        media_manager_id: data.selectedMedia.length
          ? data.selectedMedia[0].id
          : null,
        media_manager_ids: data.selectedGalleries.map((gallery) => gallery.id),
        name_ar: form.name_ar,
        name_en: form.name_en,
        description: form.description,
        short_description: form.short_description,
        categories_ids: form.categories_ids,
        tags_ids: form.tags_ids,
        brand_id: form.brand_id,
        unit_id: form.unit_id,
        to_date: form.to_date,
        from_date: form.from_date,
        discount: form.discount,
        percent: form.percent,
        sell_target: form.sell_target,
        published: form.published,
        has_variations: form.has_variations,
        variations: form.has_variations
          ? data.all_product_variations
          : data.product_price,
        variation_ids: data.variation_ids,

        taxes: data.taxes.map((tax) => {
          return {
            value: tax.value,
            tax_id: tax.id,
            percent: tax.percent,
          };
        }),
      };
    }
    function setlistToFormData(formData) {
      form.list.forEach((item, index) => {
        formData.append(`list[${index}][name_ar]`, item.name_ar);
        formData.append(`list[${index}][name_en]`, item.name_en);
      });
    }
    watch(
      () => {
        item_store.dir;
      },
      (value) => {
        const lang = locale.value;
        setTimeout(() => {
          data.editorConfig = {
            language: lang,
          };
        }, 500);
      },
      { deep: true, immediate: true }
    );
    return {
      toggleLocale,
      getCategories,
      onDeselect,
      getVariationValues,
      removeVariation,
      onVariationValueChanged,
      ...toRefs(data),
      ...toRefs(form),
      ...toRefs(route),
      addVariation,
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
<style src="@vueform/multiselect/themes/default.css"></style>

<style lang="scss">
.product-form {
  .variation-switch {
    display: flex;
    gap: 10px;
    .switch-label {
      font-weight: 500;
      color: #4eb529;
      position: relative;
    }
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 36px;
    height: 18px;
    margin-bottom: 0 !important;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: none;
    -webkit-transition: 0.4s;
    transition: 0.4s;
  }

  .slider::before {
    position: absolute;
    content: "";
    height: 10px;
    width: 10px;
    left: 4px;
    bottom: 3px;
    background-color: #ccc;
    -webkit-transition: 0.4s;
    transition: 0.4s;
  }

  input:checked + .slider {
    background-color: #4eb529;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #4eb529;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(15px);
    -ms-transform: translateX(15px);
    transform: translateX(15px);
    background: white;
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  } // centering

  .ck-editor__editable {
    min-height: 140px;
  }
  iframe {
    height: 150px !important;
  }
  textarea.form-control {
    padding: 10px !important;
    height: 120px !important;
  }
  .media-manager .head-title {
    font-size: 15px !important;
  }
  .media-manager .close-media i {
    font-size: 16px !important;
  }
  .basic-info-sec {
    padding-bottom: 400px;
  }
  .information-container {
    position: sticky;
    top: 90px;
  }
  .multiselect {
    padding: 1px 0 !important;
  }

  .multiselect-tag,
  .is-selected,
  .is-pointed {
    color: #454f5b !important;
    background: rgb(244, 246, 248) !important;
  }
  body[dir="rtl"] & {
    * {
      font-size: 12.5px !important;
    }
    .variation-switch {
      .switch-label {
        bottom: 1px;
        font-size: 14px !important;
      }
    }

    .text-mute {
      margin-top: 5px;
      color: #919eab !important;
      font-size: 11px !important;
    }
    .form-group {
      label {
        font-size: 12.8px !important;
      }
    }
    .multiselect-placeholder {
      font-size: 12.5px !important;
    }
    .form-control {
      font-size: 12px !important;
    }
    .form-control::placeholder {
      font-size: 11.5px;
    }
    input.form-control::placeholder {
      padding: 10px 0;
    }

    .multiselect-tag {
      font-weight: 500;
    }
    .locale {
      border-radius: 5px;
      padding: 3px 7px;
      position: relative;
      color: #fcac00;
      font-size: 9px !important;
      bottom: 1px;
      &:hover {
        color: #fcac00 !important;
        background: rgba(252, 172, 0, 0.15);
      }
    }
    .form-group {
      label {
        font-weight: 400 !important;
      }
    }
    table {
      th{
        font-weight: 450;
      }
      th,td{
        font-size: 12px !important;
      }
    }
    
    .def-loc {
      border-radius: 5px;
      padding: 3px 1px;
      color: #fcac00;
      font-size: 10px !important;
    }
    .title {
      font-size: 15px !important;
      color: #212b36;
      font-weight: 550;
    }
    .search {
      i {
        right: 17px !important;
      }
    }

    .actions-cell .dropdown-menu {
      position: absolute !important;
      transform: translate3d(103px, -43px, 0px) !important;
      top: 0px !important;
      left: 0px !important;
      width: 100px;
      will-change: transform !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
      font-family: "FontAwesome";
      content: "\f104" !important;
    }

    .information {
      a * {
        font-size: 13px !important;
      }
      ul {
        padding: 0 30px 0 0px;
        position: relative;
        margin-top: 25px;
      }
      li::before {
        height: 12px;
        position: absolute;
        width: 12px;
        content: "";
        background: #dfe3e8;
        border-radius: 50%;
        top: 4px;
        right: -23px;
      }
      ul::before {
        position: absolute;
        display: block;
        content: "";
        top: 14px;
        height: 100%;
        width: 2px;
        right: 12px;
        background: #dfe3e8;
      }

      li.active::before {
        width: 10px;
        height: 10px;
        background: #4eb529;
        right: -25px;
        box-sizing: content-box;
        outline: 1px solid #4eb529;
        border: 3px solid #fff;
      }
      li.active {
        color: #4eb529;
      }

      li {
        position: relative;
        margin-bottom: 15px;
      }
      padding: 15px 15px 20px 15px;
    }
  }
  body[dir="ltr"] & {
    * {
      font-size: 13px !important;
    }
    .variation-switch {
      .switch-label {
        bottom: 3px;
        font-size: 14px !important;
      }
    }
    .def-loc {
      border-radius: 5px;
      padding: 3px 1px;
      color: #fcac00;
      font-size: 11px !important;
    }
    .text-mute {
      margin-top: 5px;
      color: #919eab !important;
      font-size: 11.5px !important;
    }
    .form-control {
      font-size: 13px !important;
      font-weight: 500;
    }
    .form-control::placeholder {
      font-size: 12.5px;
    }
    .locale {
      border-radius: 5px;
      padding: 3px 7px;
      position: relative;
      color: #fcac00;
      font-size: 10px !important;
      bottom: 1px;
      &:hover {
        color: #fcac00 !important;
        background: rgba(252, 172, 0, 0.15);
      }
    }

    .title {
      font-size: 17px !important;
      color: #212b36;
      font-weight: 600;
    }
    .search {
      i {
        left: 17px !important;
      }
    }
    .information {
      ul {
        padding: 0 30px;
        position: relative;
        margin-top: 25px;
      }
      li::before {
        height: 11px;
        position: absolute;
        width: 12px;
        content: "";
        background: #dfe3e8;
        border-radius: 50%;
        top: 3px;
        left: -23px;
      }
      ul::before {
        position: absolute;
        display: block;
        content: "";
        top: 14px;
        height: 100%;
        width: 2px;
        left: 12px;
        background: #dfe3e8;
      }

      li.active::before {
        width: 10px;
        height: 10px;
        background: #4eb529;
        left: -25px;
        box-sizing: content-box;
        outline: 1px solid #4eb529;
        border: 3px solid #fff;
      }
      li.active {
        color: #4eb529;
      }

      li {
        position: relative;
        margin-bottom: 15px;
      }
      padding: 15px 15px 20px 15px;
    }
  }
  .delete-variation {
    top: 38px;
    position: relative;
    a {
      i {
        font-size: 20px !important;
      }
    }
  }
  .page-header {
    box-shadow: none;
    margin-bottom: 19px !important;
  }
  .save-submit {
    background: #4eb529;
    color: #fff;
    margin-bottom: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 110px;
    gap: 12px;
    justify-content: center;
    padding: 10px 0;
    border-radius: 5px;
  }
  .form-container {
    padding: 20px 20px 30px 20px;
    border-radius: 5px;
  }
  #brand_unit .form-container {
    padding: 20px 20px 0 20px !important;
  }
  .form-control {
    padding: 21px 10px !important;
  }
  .information {
    a * {
      font-size: 14px !important;
    }
    ul {
      padding: 0 30px;
      position: relative;
      margin-top: 25px;
    }

    li {
      position: relative;
      margin-bottom: 15px;
    }
    padding: 15px 15px 20px 15px;
  }
  .add_variation {
    margin-bottom: 30px;
    a {
      font-size: 14px !important;
      color: #4eb529;
    }
  }
}
</style>