import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import { auth } from "@/store/auth";
import { settings } from "@/store/settings";
import { streamers } from "@/store/streamers";
import { subscriptions } from "@/store/subscriptions";

const vuex = createStore({
    modules: {
        auth,
        settings,
        streamers,
        subscriptions,
    },
    plugins: [createPersistedState()],
});

export default vuex;
