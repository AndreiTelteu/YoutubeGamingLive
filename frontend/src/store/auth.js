export const auth = {
    state: () => ({
        logged: false,
    }),

    mutations: {
        update(state, value) {
            state.logged = value.logged;
            if (value.logged) {
                state.user = value.user;
                state.token = value.token;
            } else {
                delete state.user;
                delete state.token;
            }
        },
    },

    getters: {},
};
