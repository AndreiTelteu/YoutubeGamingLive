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
};
