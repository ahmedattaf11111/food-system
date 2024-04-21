import { createWebHistory, createRouter } from "vue-router";
import PageNotFound from "../shared/components/page-not-found";
import admin from "./admin.js";
import web from "./web.js";
const routes = [
  ...admin,
  ...web,
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