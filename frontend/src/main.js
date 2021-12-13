import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router";
import vuex from "@/plugins/vuex";
import vuetify from "@/plugins/vuetify";
import { loadFonts } from "@/plugins/webfontloader";

loadFonts();

createApp(App).use(router).use(vuex).use(vuetify).mount("#app");
