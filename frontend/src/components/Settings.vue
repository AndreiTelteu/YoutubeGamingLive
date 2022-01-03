<script>
import emitter from "tiny-emitter/instance";

export default {
    name: "Settings",
    data: () => ({
        show: false,
    }),
    computed: {
        settings() {
            return this.$store.state.settings;
        },
    },

    watch: {
        settings: {
            deep: true,
            handler(settings) {
                this.$store.commit("settingsUpdate", settings);
            },
        },
    },

    mounted() {
        emitter.off("settings");
        emitter.on("settings", (params) => {
            if (params.show !== undefined) {
                this.show = params.show;
            }
        });
    },
};
</script>

<template>
    <v-dialog v-model="show" scrollable>
        <v-card>
            <v-card-title>Settings</v-card-title>
            <v-divider />
            <v-card-text>
                <v-switch
                    v-model="settings.autoplay"
                    color="info"
                    label="Autoplay live stream when you enter the page"
                    hide-details
                ></v-switch>
                <v-switch
                    v-model="settings.theme"
                    true-value="dark"
                    false-value="light"
                    color="info"
                    label="Dark theme"
                    hide-details
                ></v-switch>
            </v-card-text>
            <v-divider />
            <v-card-actions>
                <v-btn @click="show = false"> Close </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
