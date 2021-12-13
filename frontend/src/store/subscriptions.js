export const subscriptions = {
    state: () => ({
        items: [],
    }),

    mutations: {
        update(state, value) {
            state.items = value.items;
        },
    },
};
