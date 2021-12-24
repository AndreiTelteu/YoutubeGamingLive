<script>
import Sidebar from "@/components/Sidebar";
import Navigation from "@/components/Navigation";
import socket from "@/plugins/socket";

export default {
    name: "Youtube Gaming Live",
    components: {
        Sidebar,
        Navigation,
    },
    mounted() {
        this.startSocket();
    },
    computed: {
        auth() {
            return this.$store.state.auth;
        },
    },
    methods: {
        startSocket() {
            if (this.auth.logged) {
                socket.connect(this.auth.token);
            }
        },
    },
};
</script>

<template>
    <v-app>
        <Navigation />
        <v-main>
            <Sidebar />
            <router-view />
        </v-main>
    </v-app>
</template>
