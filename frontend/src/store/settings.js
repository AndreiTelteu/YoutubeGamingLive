export const settings = {
    state: () => ({
        theme: "dark",
        autoplay: true,
        panelPlayerWidth: 80,
    }),

    mutations: {
        settingsUpdate(state, value) {
            Object.keys(value).map((key) => {
                state[key] = value[key];
            });
        },
    },
};
