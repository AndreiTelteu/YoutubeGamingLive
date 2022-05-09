<script>
import emitter from "tiny-emitter/instance";

let loaderTimeout;
export default {
    name: "Navigation",
    data: () => ({
        loading: false,
        showAccountDropdown: false,
    }),
    computed: {
        auth() {
            return this.$store.state.auth;
        },
    },

    mounted() {
        emitter.off("loader");
        emitter.on("loader", (show, timeout = 0) => {
            this.loading = show;
            if (loaderTimeout) clearTimeout(loaderTimeout);
            if (show) {
                loaderTimeout = setTimeout(() => {
                    this.loading = false;
                }, timeout || 1000);
            }
        });
    },

    methods: {
        loginModal(show = true) {
            emitter.emit("login-modal", { show });
        },
        openSettings() {
            emitter.emit("settings", { show: true });
        },
        logout() {
            this.showAccountDropdown = false;
            this.$store.commit("authUpdate", { logged: false });
        },
    },
};
</script>

<template>
    <v-app-bar density="compact">
        <router-link to="/" class="logo">
            <v-app-bar-title> 🔴 Youtube Gaming Live </v-app-bar-title>
        </router-link>

        <v-divider inset vertical></v-divider>
        <v-btn to="/" class="ml-3">Home</v-btn>
        <v-btn to="/browse" class="ml-3">Browse</v-btn>
        <v-btn to="/subscriptions" class="ml-3" v-if="auth.logged == true">
            Subscriptions
        </v-btn>

        <v-spacer></v-spacer>
        <v-btn
            class="mr-4 ml-5"
            color="default"
            plain
            v-on:click="loginModal(true)"
            v-if="auth.logged == false"
        >
            <v-icon left icon="mdi-account-circle-outline"></v-icon>
            <span>Login with Youtube</span>
        </v-btn>
        <v-menu
            v-model="showAccountDropdown"
            offset-y
            absolute
            :close-on-click="true"
            :close-on-content-click="true"
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    class="mr-4 ml-5"
                    color="default"
                    plain
                    v-if="auth.logged == true"
                    v-bind="props"
                >
                    <v-img
                        width="30"
                        :aspect-ratio="1 / 1"
                        :src="auth.user.avatar"
                        class="rounded-circle mr-2"
                    />
                    <span>{{ auth.user.name }}</span>
                </v-btn>
            </template>
            <v-card>
                <v-list>
                    <v-list-item v-on:click="openSettings()">
                        <v-icon left icon="mdi-cog-outline"></v-icon>
                        <v-list-item-title>Settings</v-list-item-title>
                    </v-list-item>
                    <v-list-item v-on:click="logout()">
                        <v-icon left icon="mdi-logout"></v-icon>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-menu>

        <v-progress-linear
            :active="loading"
            :indeterminate="loading"
            color="red"
            class="global-loading-bar"
        />
    </v-app-bar>
</template>

<style>
.logo,
.logo:hover,
.logo:visited,
.logo:active {
    display: block;
    text-decoration: none;
    color: inherit;
}
.global-loading-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
