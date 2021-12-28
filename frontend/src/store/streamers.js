export const streamers = {
    state: () => ({
        items: [],
    }),

    mutations: {
        streamersUpdate(state, value) {
            state.items = value.items;
        },
    },
};
