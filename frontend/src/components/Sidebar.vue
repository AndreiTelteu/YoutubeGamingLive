<template>
    <v-card elevation="4" width="256">
        <v-navigation-drawer>
            <v-list dense rounded>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon icon="mdi-account-circle-outline"></v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>login</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon icon="mdi-account-circle-outline"></v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>login</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
    </v-card>
</template>

<script>
export default {
    name: "Sidebar",
};
</script>
