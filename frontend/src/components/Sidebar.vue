<script>
export default {
    name: "Sidebar",
    data: () => ({
        expandSubscriptions: false,
    }),
    computed: {
        auth() {
            return this.$store.state.auth;
        },
        subscriptions() {
            let subscriptions = { ...this.$store.state.subscriptions };
            subscriptions.items.sort((a, b) => {
                let viewsa =
                    a.online && a.online_streams
                        ? a.online_streams[0]?.views
                        : 0;
                let viewsb =
                    b.online && b.online_streams
                        ? b.online_streams[0]?.views
                        : 0;
                return a.online ? (viewsa > viewsb ? -1 : 0) : 1;
            });
            if (!this.expandSubscriptions) {
                subscriptions.items = subscriptions.items.slice(0, 16);
            }
            return subscriptions;
        },
    },
    methods: {
        calcViews: (channel) => {
            let views =
                channel.online && channel.online_streams
                    ? channel.online_streams[0]?.views
                    : 0;
            if (views > 999 && views < 1000000) {
                views = (views / 1000).toFixed(1) + "K";
            } else if (views > 1000000) {
                views = (views / 1000000).toFixed(1) + "M";
            } else {
                views = String(views);
            }
            return views === "0" ? "" : views;
        },
    },
};
</script>

<template>
    <v-card elevation="4" width="256">
        <v-navigation-drawer class="dark-scrollbar">
            <v-list dense rounded>
                <div v-if="this.auth.logged">
                    <v-list-item
                        v-for="(channel, index) in this.subscriptions.items"
                        link
                        :to="'/' + channel.slug"
                        :key="index"
                        :class="[
                            'channel-item',
                            channel.online ? 'is-online' : 'is-offline',
                        ]"
                    >
                        <v-list-item-icon class="channel-icon">
                            <v-img
                                width="30"
                                :aspect-ratio="1 / 1"
                                :src="channel.avatar"
                                class="rounded-circle mr-2"
                            />
                        </v-list-item-icon>
                        <v-list-item-content
                            class="overflow-hidden channel-text"
                        >
                            <v-list-item-title class="flex-grow">
                                {{ channel.name }}
                            </v-list-item-title>
                        </v-list-item-content>
                        <div v-if="channel.online" class="channel-badge">
                            <v-chip
                                class="channel-online-badge is-full"
                                size="x-small"
                                color="red"
                                label
                            >
                                {{ calcViews(channel) }}
                            </v-chip>
                        </div>
                    </v-list-item>
                    <v-list-item
                        link
                        v-if="this.subscriptions.total > 16"
                        @click="
                            this.expandSubscriptions = !this.expandSubscriptions
                        "
                    >
                        <v-list-item-content>
                            {{
                                this.expandSubscriptions
                                    ? "Show less"
                                    : "Show more"
                            }}
                        </v-list-item-content>
                    </v-list-item>
                </div>
            </v-list>
        </v-navigation-drawer>
    </v-card>
</template>

<style>
.channel-item.is-offline img {
    filter: grayscale(1);
}
.channel-item.is-offline {
    opacity: 0.8;
}
.channel-item {
    display: flex;
    align-items: center;
    align-content: center;
    justify-content: center;
    padding-top: 5px !important;
    padding-bottom: 5px !important;
    min-height: 0px !important;
}
.channel-item .channel-icon {
    flex-shrink: 0;
}
.channel-item .channel-text {
    flex-grow: 1;
}
.channel-item .channel-badge {
    flex-shrink: 0;
    pointer-events: none;
    line-height: 0;
}
</style>
