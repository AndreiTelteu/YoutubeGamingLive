export const streamers = {
    state: () => ({
        items: [],
    }),

    mutations: {
        update(state, value) {
            state.items = value.items;
        },
    },
};
