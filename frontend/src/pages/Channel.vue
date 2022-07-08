<script>
import anchorme from "anchorme";
import emitter from "tiny-emitter/instance";
import { Splitpanes, Pane } from "splitpanes";
import api from "@/services/api";

export default {
    name: "Channel",
    components: { Splitpanes, Pane },
    data: () => ({
        slug: null,
        loading: true,
        channel: {},
        player: null,
    }),

    computed: {
        settings() {
            return this.$store.state.settings;
        },
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
        },
        fetchData() {
            emitter.emit("loader", true);
            let init = Date.now();
            api.get(`/channel/${this.slug}`).then((response) => {
                console.log(Date.now() - init + " ms", response.data.channel);
                emitter.emit("loader", false);
                if (response.data.success) {
                    this.channel = {
                        ...this.channel,
                        ...response.data.channel,
                    };
                    // this.channel.online = true;
                    let items = [...this.$store.state.subscriptions.items];
                    items.map((item, index) => {
                        if (item.slug == this.slug) {
                            items[index] = {
                                ...item,
                                ...response.data.channel,
                            };
                        }
                    });
                    if (this.channel.online) {
                        let videoId =
                            this.channel.online_streams[0]?.id || null;
                        this.openPlayer({ videoId });
                    }
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
        resized(panels) {
            if (typeof panels?.[0]?.size != 'undefined') {
                let panelPlayerWidth = panels[0].size;
                if (panelPlayerWidth >= 100) panelPlayerWidth = 99;
                this.$store.commit("settingsUpdate", { panelPlayerWidth });
            }
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
            <splitpanes class="channel-row ma-0" @resized="resized">
                <pane class="channel-col channel-video pa-0" :size="settings.panelPlayerWidth">
                    <iframe
                        :src="player.playSrc"
                        title="YouTube video player"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </pane>
                <pane class="channel-col channel-chat pa-0">
                    <iframe
                        :src="player.chatSrc"
                        title="YouTube live chat"
                        frameborder="0"
                    ></iframe>
                </pane>
            </splitpanes>
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
.channel-row {
    height: calc(100vh - 48px);
    width: 100%;
}
.channel-col iframe {
    width: 100%;
    height: 100%;
}
.channel-row .channel-chat iframe {
    height: calc(100% + 1px);
}
.channel-row .splitpanes__splitter {
    position: relative;
}
.channel-row .splitpanes__splitter::before {
    content: '';
    position: absolute;
    left: -10px;
    right: -10px;
    width: 20px;
    top: 0;
    bottom: 0;
    height: 100%;
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
