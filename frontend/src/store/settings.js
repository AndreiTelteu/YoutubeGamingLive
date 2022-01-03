export const settings = {
    state: () => ({
        theme: "dark",
        autoplay: true,
    }),

    mutations: {
        settingsUpdate(state, value) {
            Object.keys(value).map((key) => {
                state[key] = value[key];
            });
        },
    },
};
