<template>
    <v-app-bar density="compact">
        <v-app-bar-title> 🔴 Youtube Gaming Live </v-app-bar-title>

        <v-divider inset vertical></v-divider>
        <v-btn to="/" class="ml-3">Home</v-btn>
        <v-btn to="/browse" class="ml-3">Browse</v-btn>

        <v-spacer></v-spacer>
        <v-btn class="mr-4 ml-5" color="default" plain href="/youtube/login">
            <v-icon left icon="mdi-account-circle-outline"></v-icon>
            <span>Login with Youtube</span>
        </v-btn>
    </v-app-bar>
</template>

<script>
export default {
    name: "Navigation",
};
</script>
