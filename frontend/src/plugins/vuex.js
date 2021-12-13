import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import { auth } from "@/store/auth";
import { streamers } from "@/store/streamers";
import { subscriptions } from "@/store/subscriptions";

const vuex = createStore({
    modules: {
        auth,
        streamers,
        subscriptions,
    },
    plugins: [createPersistedState()],
});

export default vuex;
