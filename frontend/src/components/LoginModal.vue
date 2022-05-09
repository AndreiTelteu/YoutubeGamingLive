<script>
import popupCenter from "@/utils/popupCenter";
import GoogleSignin from "@/components/GoogleSignin";
import emitter from "tiny-emitter/instance";

export default {
    name: "LoginModal",
    components: {
        GoogleSignin,
    },
    data: () => ({
        show: false,
        url: '',
    }),
    computed: {
        auth() {
            return this.$store.state.auth;
        },
    },

    mounted() {
        emitter.off("login-modal");
        emitter.on("login-modal", (params) => {
            if (params.show !== undefined) {
                this.show = params.show;
            }
        });
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
                this.$store.commit("authUpdate", auth);
            };
        },
        loginManual() {
        },
    },
};
</script>

<template>
    <v-dialog v-model="show" scrollable>
        <v-card class="login-modal-card">
            <v-card-title>Login</v-card-title>
            <v-divider />
            <div class="card-content">
                <div class="login-method">
                    <div class="login-method-title">Sign in with YouTube</div>
                    <v-divider />
                    <div class="login-method-wrap">
                        <GoogleSignin @click="loginYoutube()" />
                    </div>
                    <div class="login-method-info">
                        We will extract the list of subscribers from your account
                    </div>
                </div>
                
                <div class="login-method">
                    <div class="login-method-title">Sign in with your YouTube channel link</div>
                    <v-divider />
                    <div class="login-method-wrap">
                        <form @submit.prevent="loginManual">
                            <v-text-field
                                v-model="url"
                                label="YouTube Channel URL"
                                placeholder="https://www.youtube.com/channel/UCgb_00ab11cd22ef33gh44ij"
                                variant="underlined"
                            ></v-text-field>
                            <v-btn type="submit">Login</v-btn>
                        </form>
                    </div>
                </div>
                <!-- <v-switch
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
                ></v-switch> -->
            </div>
            <v-divider />
            <v-card-actions>
                <v-btn @click="show = false"> Close </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
.login-modal-card {
    min-width: 600px;
}
.card-content {
    padding: 1rem 1rem;
}
.login-method {
    margin-bottom: 20px;
}
.login-method-info {
    opacity: 0.6;
}
</style>
