<script>
import emitter from "tiny-emitter/instance";
import api from "@/services/api";

export default {
    name: "Browse",
    data: () => ({
        channels: null,
    }),

    mounted() {
        this.fetchData();
    },

    methods: {
        fetchData() {
            emitter.emit("loader", true);
            let init = Date.now();
            api.get(`/channels`).then((response) => {
                console.log(Date.now() - init + " ms", response.data.channels);
                emitter.emit("loader", false);
                if (response.data.success) {
                    // response.data.channels.map(channel => {
                    //     channel.title = '';
                    //     channel.video_id = '';
                    // })
                    this.channels = response.data.channels;
                }
            });
        },
    },
};
</script>

<template>
    <v-container>
        <v-row class="">
            <v-col cols="3" v-for="channel in channels" :key="channel.id">
                <v-card>
                    <router-link :to="'/' + channel.slug" class="channel-item-link">
                        <v-img
                            :aspect-ratio="16 / 9"
                            :src="`https://i3.ytimg.com/vi/${channel.online_streams[0].id}/hqdefault.jpg`"
                            cover
                        ></v-img>
                        <v-card-text>
                            <div class="channel-item-title">
                                <div class="text-h6">{{ channel.online_streams[0].title }}</div>
                                <small class="subheading">{{ channel.online_streams[0].views }} viewers</small>
                            </div>
                        </v-card-text>
                    </router-link>
                    <router-link :to="'/' + channel.slug" class="channel-item-link">
                        <v-card-text class="pt-0">
                            <div class="channel-item-user">
                                <v-avatar
                                    size="30"
                                    class="mr-2"
                                    color="rounded-circle"
                                    :image="channel.avatar_medium || channel.avatar"
                                ></v-avatar>
                                <span>{{ channel.name }}</span>
                            </div>
                        </v-card-text>
                    </router-link>
                    <!-- <v-img
                        class="rounded-circle"
                        :aspect-ratio="1 / 1"
                        :src="channel.avatar_medium || channel.avatar"
                        cover
                    ></v-img> -->
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style>
.channel-item-link {
    color: initial !important;
    text-decoration: none;
}
</style>
