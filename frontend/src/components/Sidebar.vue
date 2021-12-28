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
            if (!this.expandSubscriptions) {
                subscriptions.items = subscriptions.items.slice(0, 16);
            }
            return subscriptions;
        },
    },
};
</script>

<template>
    <v-card elevation="4" width="256">
        <v-navigation-drawer>
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
                        <v-list-item-icon>
                            <v-img
                                width="30"
                                :aspect-ratio="1 / 1"
                                :src="channel.avatar"
                                class="rounded-circle mr-2"
                            />
                        </v-list-item-icon>
                        <v-list-item-content class="overflow-hidden">
                            <v-list-item-title>
                                {{ channel.name }}
                            </v-list-item-title>
                        </v-list-item-content>
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
</style>
