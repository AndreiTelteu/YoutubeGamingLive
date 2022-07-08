import api from "@/services/api";
import socket from "@/services/socket";

export const subscriptions = {
    state: () => ({
        items: [],
        total: 0,
    }),

    mutations: {
        subscriptionsUpdate(state, value) {
            state.items = value.items;
            state.total = value.total;
        },
    },

    actions: {
        getSubscriptions(context) {
            api.get("/user/subscriptions").then(({ data }) => {
                let items = {};
                context.state.items.map((item) => {
                    items[item.id] = item;
                });
                data.map((item, index) => {
                    let currentData = items[item.id] || {};
                    data[index] = { ...currentData, ...item };
                });
                socket.subscribeChannels(data);
                context.commit("subscriptionsUpdate", {
                    items: data,
                    total: data.length,
                });
            });
        },
    },
};
