<script>
import popupCenter from "@/utils/popupCenter";

export default {
    name: "Navigation",
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
    },
};
</script>

<template>
    <v-app-bar density="compact">
        <v-app-bar-title> 🔴 Youtube Gaming Live </v-app-bar-title>

        <v-divider inset vertical></v-divider>
        <v-btn to="/" class="ml-3">Home</v-btn>
        <v-btn to="/browse" class="ml-3">Browse</v-btn>

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
        <v-btn
            class="mr-4 ml-5"
            color="default"
            plain
            v-if="auth.logged == true"
        >
            <v-img
                width="30"
                :aspect-ratio="1 / 1"
                :src="auth.user.avatar"
                class="rounded-circle mr-2"
            />
            <span>{{ auth.user.name }}</span>
        </v-btn>
    </v-app-bar>
</template>
