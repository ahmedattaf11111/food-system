<template>
  <div class="dashboard-layout">
    <sidebar-menu
      theme="white-theme"
      :collapsed="collapsed"
      widthCollapsed="65px"
      width="250px"
      :rtl="dir == 'rtl' ? true : false"
      :menu="menu"
      :hideToggle="hideToggle"
      @update:collapsed="collapsed = !collapsed"
    />
    <div
      :class="{ expandedMargin: !collapsed, collapsedMargin: collapsed }"
      class="content"
    >
      <div class="simple-nav">
        <ul>
          <li><Lang /></li>
          <li><avatar class="avatar" /></li>
        </ul>
      </div>
      <router-view />
    </div>
  </div>
</template>
<script>
import Avatar from "../shared/components/avatar.vue";
import Lang from "../shared/components/lang.vue";
import { SidebarMenu } from "vue-sidebar-menu";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
export default {
  components: {
    Lang,
    Avatar,
    SidebarMenu,
  },
  data() {
    return {
      collapsed: false,
      hideToggle: false,
      menu: [],
    };
  },
  computed: {
    dir() {
      return this.store.dir;
    },
  },
  watch: {
    dir: {
      handler(value) {
        this.menu = [
          {
            header: this.$t("DASHBOARD"),
            hiddenOnCollapse: true,
          },
          {
            href: "/admin/many-image",
            title: this.$t("MANY_IMAGE"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/items",
            title: this.$t("ITEM"),
            icon: "fa fa-chart-area",
          },
          {
            title: "Charts",
            icon: "fa fa-chart-area",
            child: [
              {
            icon: "fa fa-chart-area",
                href: "/charts/sublink",
                title: "Sub Link",
              },
            ],
          },
        ];
      },
      immediate: true,
    },
  },
  created() {
    this.myEventHandler("init");
    window.addEventListener("resize", this.myEventHandler);
  },
  methods: {
    myEventHandler(e) {
      let element = e == "init" ? window : e.currentTarget;
      if (element.innerWidth <= 820) {
        this.collapsed = true;
        this.hideToggle = true;
      } else {
        this.collapsed = false;
        this.hideToggle = false;
      }
    },
  },
  inject: ["store"],
};
</script>
<style lang="scss">
.dashboard-layout {
  .v-sidebar-menu {
    i.vsm--icon {
      background: none !important;
    }
    .vsm--header {
      text-align: center;
    }
    *,
    .vsm--toggle-btn {
      color: #bdbdc7 !important;
    }
    &,
    .vsm--dropdown,
    .vsm--toggle-btn {
      background: #363a57 !important;
    }
    .vsm--link_open,
    .vsm--link:hover {
      background: #6d85fb !important;
      * {
        color: #fff !important;
      }
    }
    .vsm--link_active {
      * {
        color: #fff !important;
      }
    }
  }
  .avatar {
    margin-bottom: 5px;
  }
  body[dir="ltr"] & {
    .v-sidebar-menu {
      border-right: 1px solid #dee2e6;
    }
    .expandedMargin {
      margin-left: 250px;
    }
    .collapsedMargin {
      margin-left: 65px;
    }
  }
  body[dir="rtl"] & {
    .expandedMargin {
      margin-right: 250px;
    }
    .collapsedMargin {
      margin-right: 65px;
    }
    .v-sidebar-menu {
      border-left: 1px solid #dee2e6;
    }
  }
  .content {
    transition: 0.3s ease;
    .simple-nav {
      display: flex;
      justify-content: flex-end;
      background: #ffffff;
      z-index: 990;
      box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
      ul {
        list-style: none;
        margin: 0;
        padding: 15px;
        li {
          padding: 0 10px;
          display: inline-block;
        }
      }
    }
  }
}
</style>
