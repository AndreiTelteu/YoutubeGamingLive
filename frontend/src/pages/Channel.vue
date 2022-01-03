<script>
import anchorme from "anchorme";
import emitter from "tiny-emitter/instance";
import socket from "@/plugins/socket";

export default {
    name: "Channel",
    data: () => ({
        slug: null,
        loading: true,
        channel: {},
        player: null,
    }),

    computed: {
        description() {
            let data = this.channel.data?.description || "";
            data = data.replace(/\n/g, "<br />");
            data = anchorme({
                input: data,
                options: {
                    attributes: () => ({
                        class: "channel-desc-link",
                        target: "_blank",
                    }),
                },
            });
            return data;
        },
    },

    mounted() {
        this.updateRoute(this.$route.params.pathMatch[0]);
    },
    beforeRouteUpdate(route) {
        this.updateRoute(route.params.pathMatch[0]);
    },

    methods: {
        updateRoute(slug) {
            this.slug = slug;
            this.fillData();
            this.fetchData();
        },
        fillData() {
            let channel = this.$store.state.subscriptions.items.find(
                (item) => item.slug == this.slug
            );
            if (!channel) return;
            this.channel = { ...this.channel, ...channel };
            this.openPlayer({});
        },
        fetchData() {
            emitter.emit("loader", true);
            let init = Date.now();
            socket
                .api("channel-details", { slug: this.slug })
                .then((response) => {
                    console.log(Date.now() - init + " ms", response.channel);
                    emitter.emit("loader", false);
                    if (response.success) {
                        this.channel = { ...this.channel, ...response.channel };
                        // this.channel.online = true;
                        let items = [...this.$store.state.subscriptions.items];
                        items.map((item, index) => {
                            if (item.slug == this.slug) {
                                items[index] = { ...item, ...response.channel };
                            }
                        });
                        this.$store.commit("subscriptionsUpdate", {
                            items: items,
                            total: items.length,
                        });
                    }
                });
        },
        openPlayer(props) {
            let videoId = props.videoId || "DWcJFNfaw9c";
            let autoplay = this.$store.state.settings.autoplay
                ? "&autoplay=1"
                : "";
            this.player = {
                videoId,
                playSrc: `https://www.youtube.com/embed/${videoId}?modestbranding=1&rel=0${autoplay}`,
                chatSrc: `https://www.youtube.com/live_chat?v=${videoId}&is_popout=1`,
            };
        },
    },
};
</script>

<template>
    <v-row class="channel-page text-center">
        <v-col
            cols="12"
            class="channel-player"
            v-if="channel.online && player !== null"
        >
            <v-row class="channel-row ma-0">
                <v-col cols="12" md="9" class="channel-video pa-0">
                    <iframe
                        :src="player.playSrc"
                        title="YouTube video player"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </v-col>
                <v-col cols="12" md="3" class="channel-chat pa-0">
                    <iframe
                        :src="player.chatSrc"
                        title="YouTube live chat"
                        frameborder="0"
                    ></iframe>
                </v-col>
            </v-row>
            <v-divider />
        </v-col>

        <v-col cols="12">
            <v-card class="mx-auto my-4" max-width="80%">
                <div
                    class="d-block d-md-flex flex-no-wrap justify-space-between align-center"
                >
                    <div style="width: 100%; height: 100%" class="flex-grow-1">
                        <v-img
                            :src="channel.banner_image"
                            cover
                            :aspect-ratio="512 / 288"
                        />
                    </div>
                    <div
                        style="width: 100%; height: 100%; min-height: 288px"
                        class="flex-grow-1 py-4 overflow-hidden"
                    >
                        <div class="d-flex align-center mx-4">
                            <div>
                                <v-img
                                    width="120"
                                    :aspect-ratio="1 / 1"
                                    :src="
                                        channel.avatar_medium || channel.avatar
                                    "
                                    class="rounded-circle"
                                />
                            </div>
                            <div>
                                <v-card-title>
                                    {{ channel.name }}
                                </v-card-title>
                                <v-card-subtitle
                                    :style="{
                                        opacity: channel.online ? 1 : 0.6,
                                    }"
                                >
                                    <div v-if="channel.online">
                                        <v-chip
                                            class="channel-online-badge is-outlined"
                                            size="small"
                                            color="red"
                                            variant="outlined"
                                            label
                                        >
                                            LIVE NOW
                                        </v-chip>
                                    </div>
                                    <div v-if="!channel.online">
                                        Channel is offline
                                    </div>
                                </v-card-subtitle>
                            </div>
                        </div>

                        <v-divider class="mx-4 my-4" />

                        <div class="text-left px-4">Latest Live streams:</div>
                    </div>
                </div>
                <div v-if="description">
                    <v-divider />
                    <div class="text-left px-4 py-4" v-html="description"></div>
                </div>
            </v-card>
        </v-col>
    </v-row>
</template>

<style>
.channel-row > div {
    height: calc(100vh - 48px);
    width: 100%;
    overflow: hidden;
}
.channel-row > div > iframe {
    width: 100%;
    height: 100%;
}
.channel-row .channel-chat > iframe {
    height: calc(100% + 1px);
}
.channel-online-badge {
    border-color: #ff4e45 !important;
}
.channel-online-badge.is-outlined {
    color: #ff4e45 !important;
}
.channel-desc-link {
    color: inherit;
}
</style>
