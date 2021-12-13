<script>
import popupCenter from "@/utils/popupCenter";

export default {
    name: "Navigation",
    data: () => ({
        showAccountDropdown: false,
    }),
    computed: {
        auth() {
            return this.$store.state.auth;
        },
    },
    methods: {
        loginYoutube() {
            popupCenter({
                url: "/youtube/login",
                title: "Login with Youtube",
                w: 900,
                h: 500,
            });
            window.YoutubeLoginCallback = (auth) => {
                this.$store.commit("update", auth);
            };
        },
        logout() {
            this.showAccountDropdown = false;
            this.$store.commit("update", { logged: false });
        },
    },
};
</script>

<template>
    <v-app-bar density="compact">
        <v-app-bar-title> 🔴 Youtube Gaming Live </v-app-bar-title>

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
            v-on:click="loginYoutube"
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
                    <v-list-item v-on:click="logout()">
                        <v-icon left icon="mdi-logout"></v-icon>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-menu>
    </v-app-bar>
</template>
