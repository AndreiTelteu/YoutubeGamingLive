<script>
import anchorme from "anchorme";
import emitter from "tiny-emitter/instance";

export default {
    name: "Channel",
    data: () => ({
        slug: null,
        loading: true,
        channel: {},
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
        },
        fetchData() {
            console.log(this.slug);
            emitter.emit("loader", true);
        },
    },
};
</script>

<template>
    <v-row class="channel-page text-center py-4">
        <v-col cols="12">
            <v-card class="mx-auto" max-width="80%">
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
                                    width="80"
                                    :aspect-ratio="1 / 1"
                                    :src="channel.avatar"
                                    class="rounded-circle"
                                />
                            </div>
                            <div>
                                <v-card-title>
                                    {{ channel.name }}
                                </v-card-title>
                                <v-card-subtitle>
                                    Channel is offline
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
.channel-desc-link {
    color: inherit;
}
</style>
