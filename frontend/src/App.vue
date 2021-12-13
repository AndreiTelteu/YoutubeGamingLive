<script>
import Sidebar from "@/components/Sidebar";
import Navigation from "@/components/Navigation";
export default {
    name: "Youtube Gaming Live",
    components: {
        Sidebar,
        Navigation,
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
