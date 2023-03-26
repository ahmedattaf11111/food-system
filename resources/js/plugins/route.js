import { createWebHistory, createRouter } from "vue-router";
import AdminGuestGuard from "../shared/guards/guest-admin-guard";
import AdminLogin from "../components/dashboard/login.vue";
import DashboardLayout from '../layouts/dashboard-layout';
import AuthLayout from '../layouts/auth-layout';
import WebLayout from '../layouts/web-layout';
import Register from '../components/auth/register';
import Login from '../components/auth/login';
import ForgetPassword from '../components/auth/forget-password';
import ResetPassword from '../components/auth/reset-password';
import EmailVerification from '../components/dashboard/email-verification';
import Profile from '../components/dashboard/profile';
import Home from '../components/web/home';
import Chat from '../components/web/chat';
import ItemTable from '../components/dashboard/item/item-table';
import ManyImageTable from '../components/dashboard/many-image/item-table';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import PageNotFound from "../shared/components/page-not-found";
import AuthenticatedAdminGuard from "../shared/guards/authenticated-admin-guard";

const routes = [
  {
    path: "",
    redirect: "/home"
  },
  {
    path: "/admin",
    component: DashboardLayout,
    beforeEnter: [
      AuthenticatedAdminGuard
    ],
    children: [
      { path: "items", component: ItemTable },
      { path: "many-image", component: ManyImageTable },
    ]
  },
  {
    path: "/admin",
    beforeEnter: [AdminGuestGuard],
    children: [
      { path: "login", component: AdminLogin },
    ]
  },
  {
    path: "",
    component: AuthLayout,
    beforeEnter: [
      GuestGuard
    ],
    children: [
      { path: "register", component: Register },
      { path: "login", component: Login },
      { path: "forget-password", component: ForgetPassword },
      { path: "reset-password/:token", component: ResetPassword },
    ]
  },
  {
    path: "",
    component: WebLayout,
    children: [
      { path: "home", component: Home },
    ]
  },
  {
    path: "",
    component: WebLayout,
    beforeEnter: [
      AuthenticatedGuard
    ],
    children: [
      { path: "chat", component: Chat },
      { path: "verify-email", component: EmailVerification },
      { path: "profile", component: Profile },
    ]
  },

  {
    path: '/:pathMatch(.*)*',
    component: PageNotFound
  }
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});
export default router;