<template>
  <Loader color="#4eb529" />
  <PageLoader color="#4eb529" />

  <div :style="route.name == 'pos-system' ? 'width:100%' : ''" class="header">
    <div class="header-left">
      <div
        v-if="route.name !== 'pos-system'"
        class="menu-icon dw dw-menu"
      ></div>
      <div
        class="search-toggle-icon dw dw-search2"
        data-toggle="header_search"
      ></div>
      <div v-if="route.name == 'pos-system'" class="dashboard-link-container">
        <div class="first">
          <router-link to="/admin/products">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-pie-chart"
            >
              <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
            </svg>
          </router-link>
        </div>

        <div>
          <router-link to="/admin/products">
            <img
              src="https://grostore.themetags.com/public/uploads/media/yqqPV512Gk5DMpvCj2UllKrCl52bam3yD6QvfiPP.png"
            />
          </router-link>
        </div>
      </div>
      <div class="header-search">
        <form>
          <div class="form-group mb-0">
            <i class="dw dw-search2 search-icon"></i>
            <input
              type="text"
              class="form-control search-input"
              :placeholder="$t('SEARCH') + '...'"
            />
            <!-- <div class="dropdown">
              <a
                class="dropdown-toggle no-arrow"
                href="#"
                role="button"
                data-toggle="dropdown"
              >
                <i class="ion-arrow-down-c"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">From</label>
                  <div class="col-sm-12 col-md-10">
                    <input
                      class="form-control form-control-sm form-control-line"
                      type="text"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">To</label>
                  <div class="col-sm-12 col-md-10">
                    <input
                      class="form-control form-control-sm form-control-line"
                      type="text"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label"
                    >Subject</label
                  >
                  <div class="col-sm-12 col-md-10">
                    <input
                      class="form-control form-control-sm form-control-line"
                      type="text"
                    />
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-primary">Search</button>
                </div>
              </div>
            </div> -->
          </div>
        </form>
      </div>
    </div>

    <div class="header-right">
      <!-- <div class="dashboard-setting user-notification">
        <div class="dropdown">
          <a
            class="dropdown-toggle no-arrow"
            href="javascript:;"
            data-toggle="right-sidebar"
          >
            <i class="dw dw-settings2"></i>
          </a>
        </div>
      </div> -->
      <div class="item visit-store">
        <i class="icon-copy dw dw-worldwide"></i>
        <span>{{ $t("VISIT_STORE") }}</span>
      </div>
      <div class="item">
        <Lang />
      </div>
      <div class="item">
        <div class="user-notification">
          <div class="dropdown">
            <a
              class="dropdown-toggle no-arrow"
              href="#"
              role="button"
              data-toggle="dropdown"
            >
              <i class="icon-copy dw dw-notification"></i>
              <span class="badge notification-active"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="notification-list mx-h-350">
                <ul>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/img.jpg" alt="" />
                      <h3>John Doe</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/photo1.jpg" alt="" />
                      <h3>Lea R. Frith</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/photo2.jpg" alt="" />
                      <h3>Erik L. Richards</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/photo3.jpg" alt="" />
                      <h3>John Doe</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/photo4.jpg" alt="" />
                      <h3>Renee I. Hansen</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <img src="/vendors/images/img.jpg" alt="" />
                      <h3>Vicki M. Coleman</h3>
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed...
                      </p>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="item">
        <div class="dropdown">
          <a href="#" role="button" data-toggle="dropdown">
            <div class="nav-user-image">
              <img
                src="	https://grostore.themetags.com/public/uploads/media/dtkoInw3SD3IF3Q2I1jFtEDiE96mDD46RHB9RdxN.jpg"
              />
              <span class="online"></span>
            </div>
          </a>
          <div
            class="dropdown-menu user-dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
          >
            <a class="dropdown-item" href="profile.html"
              ><i class="dw dw-user1"></i> Profile</a
            >
            <a class="dropdown-item" href="profile.html"
              ><i class="dw dw-settings2"></i> Setting</a
            >
            <a class="dropdown-item" href="faq.html"
              ><i class="dw dw-help"></i> Help</a
            >
            <a @click.prevent="logout" class="dropdown-item" href=""
              ><i class="dw dw-logout"></i> {{ $t("LOGOUT") }}</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="right-sidebar">
    <div class="sidebar-title">
      <h3 class="weight-600 font-16 text-blue">
        Layout Settings
        <span class="btn-block font-weight-400 font-12"
          >User Interface Settings</span
        >
      </h3>
      <div class="close-sidebar" data-toggle="right-sidebar-close">
        <i class="icon-copy ion-close-round"></i>
      </div>
    </div>
    <div class="right-sidebar-body customscroll">
      <div class="right-sidebar-body-content">
        <h4 class="weight-600 font-18 pb-10">Header Background</h4>
        <div class="sidebar-btn-group pb-30 mb-10">
          <a
            href="javascript:void(0);"
            class="btn btn-outline-primary header-white active"
            >White</a
          >
          <a
            href="javascript:void(0);"
            class="btn btn-outline-primary header-dark"
            >Dark</a
          >
        </div>

        <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
        <div class="sidebar-btn-group pb-30 mb-10">
          <a
            href="javascript:void(0);"
            class="btn btn-outline-primary sidebar-light"
            >White</a
          >
          <a
            href="javascript:void(0);"
            class="btn btn-outline-primary sidebar-dark active"
            >Dark</a
          >
        </div>

        <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
        <div class="sidebar-radio-group pb-10 mb-10">
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebaricon-1"
              name="menu-dropdown-icon"
              class="custom-control-input"
              value="icon-style-1"
              checked=""
            />
            <label class="custom-control-label" for="sidebaricon-1"
              ><i class="fa fa-angle-down"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebaricon-2"
              name="menu-dropdown-icon"
              class="custom-control-input"
              value="icon-style-2"
            />
            <label class="custom-control-label" for="sidebaricon-2"
              ><i class="ion-plus-round"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebaricon-3"
              name="menu-dropdown-icon"
              class="custom-control-input"
              value="icon-style-3"
            />
            <label class="custom-control-label" for="sidebaricon-3"
              ><i class="fa fa-angle-double-right"></i
            ></label>
          </div>
        </div>

        <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
        <div class="sidebar-radio-group pb-30 mb-10">
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-1"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-1"
              checked=""
            />
            <label class="custom-control-label" for="sidebariconlist-1"
              ><i class="ion-minus-round"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-2"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-2"
            />
            <label class="custom-control-label" for="sidebariconlist-2"
              ><i class="fa fa-circle-o" aria-hidden="true"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-3"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-3"
            />
            <label class="custom-control-label" for="sidebariconlist-3"
              ><i class="dw dw-check"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-4"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-4"
              checked=""
            />
            <label class="custom-control-label" for="sidebariconlist-4"
              ><i class="icon-copy dw dw-next-2"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-5"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-5"
            />
            <label class="custom-control-label" for="sidebariconlist-5"
              ><i class="dw dw-fast-forward-1"></i
            ></label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              type="radio"
              id="sidebariconlist-6"
              name="menu-list-icon"
              class="custom-control-input"
              value="icon-list-style-6"
            />
            <label class="custom-control-label" for="sidebariconlist-6"
              ><i class="dw dw-next"></i
            ></label>
          </div>
        </div>

        <div class="reset-options pt-30 text-center">
          <button class="btn btn-danger" id="reset-settings">
            Reset Settings
          </button>
        </div>
      </div>
    </div>
  </div> -->

  <div
    :style="route.name !== 'pos-system' ? '' : 'display:none'"
    class="left-side-bar"
  >
    <div class="brand-logo">
      <a href="index.html">
        <img
          src="https://grostore.themetags.com/public/uploads/media/LOa3BqX3ydhVC0V1fwYEyvEpM5N9NaoA0E7u3EQs.png"
          alt=""
          class="dark-logo"
        />
        <img
          style="margin: 0 10px"
          src="https://grostore.themetags.com/public/uploads/media/yqqPV512Gk5DMpvCj2UllKrCl52bam3yD6QvfiPP.png"
          alt=""
          class="light-logo"
        />
        <img
          src="https://grostore.themetags.com/public/uploads/media/LOa3BqX3ydhVC0V1fwYEyvEpM5N9NaoA0E7u3EQs.png"
          alt=""
          class="light-logo"
        />
      </a>
      <div class="close-sidebar" data-toggle="left-sidebar-close">
        <i class="ion-close-round"></i>
      </div>
    </div>
    <div class="menu-block sidebarscroll">
      <div class="admin-sidebar">
        <div class="image">
          <img
            src="	https://grostore.themetags.com/public/uploads/media/dtkoInw3SD3IF3Q2I1jFtEDiE96mDD46RHB9RdxN.jpg"
          />
          <span class="online"></span>
        </div>
        <div class="info">
          <div class="name">Richard Toland</div>
          <div class="role">Super Admin</div>
        </div>
      </div>
      <div class="sidebar-menu">
        <ul id="accordion-menu">
          <li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle">
              <span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("PRODUCTS") }}</span>
            </a>
            <ul class="submenu">
              <li>
                <router-link to="/admin/products">{{
                  $t("ALL_PRODUCTS")
                }}</router-link>
              </li>

              <li>
                <router-link to="/admin/units">{{
                  $t("ALL_UNITS")
                }}</router-link>
              </li>

              <li>
                <router-link to="/admin/taxes">{{
                  $t("ALL_TAXES")
                }}</router-link>
              </li>
              <li>
                <router-link to="/admin/variations">{{
                  $t("ALL_VARIATIONS")
                }}</router-link>
              </li>
              <li>
                <router-link to="/admin/categories">{{
                  $t("ALL_CATEGORIES")
                }}</router-link>
              </li>

              <li>
                <router-link to="/admin/brands">{{
                  $t("ALL_BRANDS")
                }}</router-link>
              </li>
            </ul>
          </li>
          <li>
            <router-link class="dropdown-toggle no-arrow" to="/admin/pos"
              ><span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("POS_SYSTEM") }}</span></router-link
            >
          </li>
          <li>
            <router-link class="dropdown-toggle no-arrow" to="/admin/orders"
              ><span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("ORDERS") }}</span></router-link
            >
          </li>
          <li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle">
              <span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("STOCKS") }}</span>
            </a>
            <ul class="submenu">
              <li>
                <router-link to="/admin/locations">{{
                  $t("ALL_LOCATIONS")
                }}</router-link>
              </li>
              <li>
                <router-link to="/admin/add-stock">{{
                  $t("ADD_STOCK")
                }}</router-link>
              </li>
            </ul>
          </li>
          <!-- <li>
            <router-link
              class="dropdown-toggle no-arrow"
              to="/admin/media-manager"
              ><span class="micon dw dw-invoice"></span
              ><span class="mtext">المرتجعات</span></router-link
            >
          </li> -->
          <div class="sidebar-group">
            <span>{{ $t("CONTENTS") }}</span>
          </div>
          <li>
            <router-link class="dropdown-toggle no-arrow" to="/admin/tags"
              ><span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("TAGS") }}</span></router-link
            >
          </li>

          <li>
            <router-link
              class="dropdown-toggle no-arrow"
              to="/admin/media-manager"
              ><span class="micon dw dw-invoice"></span
              ><span class="mtext">{{ $t("MEDIA_MANAGER") }}</span></router-link
            >
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="mobile-menu-overlay"></div>

  <div
    class="main-container"
    :class="{ 'full-screen-padding': route.name == 'pos-system' }"
  >
    <div class="xs-pd-20-10">
      <div class="min-height-200px">
        <router-view />
      </div>
      <div class="footer-wrap pd-20 mb-20 border">
        DeskApp - Bootstrap 4 Admin Template By
        <a href="https://github.com/dropways" target="_blank"
          >Ankit Hingarajiya</a
        >
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../shared/components/avatar.vue";
import Lang from "../shared/components/lang.vue";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
import layout from "../shared/theme-js/layout.js";
import LangUtil from "../shared/utils/lang-util";
import { inject, onMounted, toRefs, watch } from "vue";
import Loader from "../shared/components/loader.vue";
import PageLoader from "../shared/components/pageLoader.vue";
import authAdminClient from "../http-clients/admin/admin-auth-client";
import global from "../shared/global";
import TokenUtil from "../shared/utils/token-util";
import { useRouter, useRoute } from "vue-router";

export default {
  components: {
    Lang,
    Loader,
    PageLoader,
    Avatar,
  },
  setup() {
    const lang = LangUtil.setup();
    const store = inject("store");
    const router = useRouter();
    const route = useRoute();
    onMounted(() => {
      layout();
      lang.changeAdminLang(LangUtil.get());
    });
    //Methods
    function logout() {
      authAdminClient
        .logout()
        .then((response) => {
          TokenUtil.remove();
          router.push(global.ADMIN_GUEST_REDIRECT);
        })
        .catch((error) => {});
    }
    watch(
      () => {
        store.dir;
      },
      (value) => {},
      { immediate: true, deep: true }
    );
    return { logout, route, ...toRefs(store) };
  },
};
</script>
<style lang="scss">
.dashboard-link-container {
  .first {
    position: relative;
    top: 2px;
    a {
      color: #20b250;
    }
  }
  gap: 20px;
  align-items: center;
  display: flex;
  img {
    position: relative;
    top: 3px;
    width: 35px;
    height: 24px;
  }
}
body[dir="rtl"] {
  .full-screen-padding {
    padding-right: 0 !important;
  }
  .header-left .header-search {
    padding-right: 4px !important;
  }
  .dashboard-link-container {
    padding-right: 35px;
  }
  .header-right {
    element.style {
      position: absolute;
      will-change: transform;
      top: 0px;
      left: 0px;
      transform: translate3d(-30px, 30px, 0px);
    }
    .user-dropdown-menu {
      top: 40px !important;
    }
    .item {
      font-size: 12.5px;
      padding: 24px 10px 10px 10px;
    }
  }
  .visit-store {
    position: relative;
    bottom: 1px;
    i {
      position: relative;
      bottom: -2px;
      margin: 0 6px;
    }
  }
}
body[dir="ltr"] {
  .full-screen-padding {
    padding-left: 0 !important;
  }
  .header-left .header-search {
    padding-left: 6px !important;
  }
  .dashboard-link-container {
    padding-left: 35px;
  }
  .dropdown-menu {
    .dropdown-item {
      font-size: 13px;
    }
  }
  .user-notification .dropdown-toggle .badge {
    right: 0px;
    top: 0px;
  }

  .header-right {
    .item {
      font-size: 14px;
      padding: 24px 10px 10px 10px;
    }
  }
  .visit-store {
    i {
      position: relative;
      bottom: -1px;
      margin: 0 6px;
    }
  }
}

.header-search {
  .search-input {
    border: none !important;
    font-size: 13px;
  }
  i {
    margin-top: 3px;
    font-size: 14px;
  }
}

.header-right {
  padding: 0 25px;
}
.nav-user-image {
  margin-top: 0 !important;
}
.user-notification {
  .dropdown-toggle {
    padding: 0 !important;
  }
  position: relative;
  bottom: 1px;
  padding: 0 !important;
  margin-right: 0 !important;
  margin-left: 0 !important;
}
.nav-user-image {
  position: relative;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  margin-top: 12px;
  bottom: 4px;
  img {
    border-radius: 50%;

    height: 100%;
    width: 100%;
  }
  .online {
    border: 2px solid white;
    border-radius: 50%;
    height: 12px;
    width: 12px;
    background: #20b250;
    bottom: -1px;
    right: 0px;
    position: absolute;
  }
}
.sidebar-menu {
  .dropdown.show .dropdown-toggle {
    background-color: #dcf0d4 !important;
    margin: 8px 8px 16px 8px;
    margin: 8px 8px 2px 8px;
    border-radius: 5px;
    span,
    &::after {
      font-weight: 500;
      color: #4eb529 !important;
      border-radius: 0.25rem !important;
    }
  }
  * {
    font-size: 14px !important;
  }
  .sidebar-group {
    font-weight: 500;
    color: #919eab;
    margin: 13px 0 7px 0;
  }
  .dropdown-toggle:after {
    bottom: 6px !important;
  }
  body[dir="ltr"] & {
    .item {
      font-size: 14px;
      padding: 24px 9px 10px 8px;
    }
    .submenu li a {
      font-weight: 400 !important;
      padding: 6px 15px 6px 53px !important;
      font-size: 13px !important;
    }
    .sidebar-group {
      span {
        font-size: 11px !important;
        font-weight: 600;
      }
    }
    .sidebar-group::before {
      display: inline-block;
      margin-right: 11px;
      content: "";
      font-size: 15px !important;
      background: #cbc7c7;
      width: 17px;
      height: 2px;
    }
    .submenu li > a:before {
      background: #c4cdd5;
      width: 6px !important;
      height: 2px;
      content: "" !important;
      left: 34px;
    }
    .dropdown-toggle {
      padding: 10px 15px 10px 53px !important;
    }
  }
  body[dir="rtl"] & {
    .submenu li a {
      font-weight: 400 !important;
      padding: 6px 53px 6px 15px !important;
      font-size: 12px !important;
    }
    .sidebar-group {
      span {
        font-size: 11px !important;
        font-weight: 500;
      }
    }
    .dropdown-toggle span {
      font-size: 13px !important;
    }
    .sidebar-group::before {
      display: inline-block;
      margin-left: 11px;
      content: "";
      font-size: 15px !important;
      background: #cbc7c7;
      width: 14px;
      height: 2px;
    }
    .submenu li > a:before {
      background: #c4cdd5;
      width: 6px !important;
      height: 2px;
      content: "" !important;
      right: 34px;
    }
    .dropdown-toggle {
      padding: 10px 53px 10px 15px !important;
    }
  }
  .dropdown-toggle:after {
    font-size: 18px !important;
    bottom: 6px !important;
  }
}
.dropdown-toggle:hover {
  background: none !important;
}
.submenu li a:hover {
  background: none !important;
}
.sidebar-menu .dropdown-toggle:hover,
.sidebar-menu .show > .dropdown-toggle {
  background: none !important;
}
.left-side-bar {
  background: #fff;
}
.dropdown .dropdown-toggle,
.dropdown-toggle {
  &,
  span {
    color: #637381 !important;
    font-weight: 400;
  }
}
.dropdown .submenu a {
  color: #637381 !important;
  font-size: 14px !important;
}

.menu-block {
  overflow: auto;
  .admin-sidebar {
    border-radius: 10px;
    padding: 8px;
    background: #f9fafb;
    margin: 22px 8px 29px 8px;
    gap: 11px;
    align-items: center;
    display: flex;
    .info {
      .name {
        font-size: 14px;
        font-weight: bold;
        color: black !important;
      }
      .role {
        font-size: 13px;
        color: #919eab !important;
      }
    }
    .image {
      position: relative;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      img {
        height: 100%;
        width: 100%;
      }
      .online {
        border: 2px solid white;
        border-radius: 50%;
        height: 12px;
        width: 12px;
        background: #20b250;
        bottom: -1px;
        right: 0px;
        position: absolute;
      }
    }
  }
}

.notification-list {
  overflow: auto;
}
body[dir="rtl"] {
  .dropdown-menu {
    .dropdown-item {
      font-size: 12px;
    }
  }
  .user-notification .dropdown-toggle .badge {
    left: 0px;
    top: 0px;
  }
  .dropdown-menu {
    position: absolute !important;
    transform: translate3d(0, -7px, 0px) !important;
    top: 51px !important;
    left: 0px !important;
  }
}
.submenu a.router-link-active {
  color: #4eb529 !important;
}
</style>
