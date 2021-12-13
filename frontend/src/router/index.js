import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/Home.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/browse",
        name: "Browse",
        component: () =>
            import(/* webpackChunkName: "browse" */ "@/views/Browse.vue"),
    },
    {
        path: "/subscriptions",
        name: "Subscriptions",
        component: () =>
            import(
                /* webpackChunkName: "subscriptions" */ "@/views/Subscriptions.vue"
            ),
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
