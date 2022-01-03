<script>
import Sidebar from "@/components/Sidebar";
import Navigation from "@/components/Navigation";
import Settings from "@/components/Settings";
import socket from "@/plugins/socket";

export default {
    name: "Youtube Gaming Live",
    components: {
        Navigation,
        Settings,
        Sidebar,
    },
    mounted() {
        this.startSocket();
    },
    computed: {
        auth() {
            return this.$store.state.auth;
        },
        settings() {
            return this.$store.state.settings;
        },
    },
    methods: {
        startSocket() {
            if (this.auth.logged && !socket.connected()) {
                socket.connect(this.auth.token);
            }
        },
    },
};
</script>

<template>
    <v-app :theme="settings.theme">
        <Navigation />
        <Settings />
        <v-main>
            <Sidebar />
            <router-view v-slot="{ Component, route }">
                <transition name="slide" :duration="100">
                    <component :is="Component" :key="route.path" />
                </transition>
            </router-view>
        </v-main>
    </v-app>
</template>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.1s linear;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
}
.slide-enter-from {
    transform: translateX(10%);
    opacity: 0;
}
.slide-enter-to {
    transform: translateX(0%);
    opacity: 1;
}
.slide-leave-from {
    transform: translateX(0%);
    opacity: 1;
}
.slide-leave-to {
    transform: translateX(-10%);
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.1s linear;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
}
.fade-enter-from {
    opacity: 0;
}
.fade-enter-to {
    opacity: 1;
}
.fade-leave-from {
    opacity: 1;
}
.fade-leave-to {
    opacity: 0;
}

.dark-scrollbar,
.dark-scrollbar > div {
    padding-right: 2px;
}
.dark-scrollbar > div {
    padding-left: 2px;
}
.dark-scrollbar::-webkit-scrollbar,
.dark-scrollbar > div::-webkit-scrollbar {
    width: 8px;
}
.dark-scrollbar::-webkit-scrollbar-track,
.dark-scrollbar > div::-webkit-scrollbar-track {
    background: transparent;
}
.dark-scrollbar::-webkit-scrollbar-thumb,
.dark-scrollbar > div::-webkit-scrollbar-thumb {
    background: #3e3e3e;
    border-radius: 7px;
}
.dark-scrollbar::-webkit-scrollbar-thumb:hover,
.dark-scrollbar > div::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
