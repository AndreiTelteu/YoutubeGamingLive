import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home.vue";

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
            import(/* webpackChunkName: "browse" */ "@/pages/Browse.vue"),
    },
    {
        path: "/subscriptions",
        name: "Subscriptions",
        component: () =>
            import(
                /* webpackChunkName: "subscriptions" */ "@/pages/Subscriptions.vue"
            ),
    },
    {
        path: "/:pathMatch(.*)*",
        name: "Channel",
        component: () =>
            import(/* webpackChunkName: "Channel" */ "@/pages/Channel.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
