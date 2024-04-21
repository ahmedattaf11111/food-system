<template>
  <div class="lang-container">
    <div class="dropdown">
      <a class="dropdown-btn" role="button" data-toggle="dropdown">
        <template v-if="$i18n.locale == 'en'">
          <img src="/images/en.png" /> {{ $t("ENGLISH_LANG") }}
        </template>
        <template v-else>
          <img style="margin-left:6px !important" src="/images/sa.png" />{{ $t("ARABIC_LANG") }}</template
        >
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        <a class="dropdown-item" href="" @click.prevent="changeLang('en')">
          <img src="/images/en.png" /> {{ $t("ENGLISH_LANG") }}
        </a>
        <a class="dropdown-item" href="" @click.prevent="changeLang('ar')">
          <img src="/images/sa.png" /> {{ $t("ARABIC_LANG") }}
        </a>
      </div>
    </div>
  </div>
</template>
<script>
import LangUtil from "../utils/lang-util";
export default {
  setup(props, context) {
    const lang = LangUtil.setup();
    function changeLang(locale) {
      lang.changeAdminLang(locale);
    }
    return { changeLang };
  },
  props: ["layout"],
};
</script>
<style lang="scss">
.lang-container {
  .dropdown-btn:hover{
    cursor: pointer;
  }
  body[dir="rtl"] & {
    .dropdown-menu {
      top: 32px !important;
      right: 0px !important;
    }
    .dropdown-menu-icon-list .dropdown-item {
      padding: 5px 14px !important;
      font-size: 12px;
    }
    .dropdown {
      img {
        top: 2px;
        height: 15px;
        width: 15px;
        margin-left:4px;
      }
    }
  }
  body[dir="ltr"] & {
    .dropdown-menu-icon-list .dropdown-item {
      padding: 5px 14px !important;
      font-size: 14px;
    }
    .dropdown-menu {
      top: 9px !important;
      left: 24px !important;
    }
    .dropdown {
      img {
        top: 4.2px;
        height: 16px;
        width: 16px;
        margin-right: 4px;
      }
    }
  }

  .dropdown {
    padding: 0;
    min-width: 0;
    img {
      position: relative;
      border-radius: 50%;
    }
  }
  .user-info-dropdown {
    padding: 0 !important;
  }
  .user-notification {
    margin-right: 0 !important ;
  }
  .dropdown-menu {
    min-width: 109px !important;
  }
}
</style>
