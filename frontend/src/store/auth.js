import socket from "@/services/socket";

export const auth = {
    state: () => ({
        logged: false,
    }),

    mutations: {
        authUpdate(state, value) {
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

    actions: {
        authLogin(context, data) {
            context.commit("authUpdate", data);
            socket.connect(data.token);
            context.dispatch('getSubscriptions');
        },
        authLogout(context, data) {
            context.commit("authUpdate", data);
            socket.reset();
        },
    },
};
