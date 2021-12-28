<script>
export default {
    name: "Channel",
    data: () => ({
        slug: null,
        loading: true,
    }),

    mounted() {
        this.updateRoute(this.$route.params.pathMatch[0]);
    },
    beforeRouteUpdate(route) {
        this.updateRoute(route.params.pathMatch[0]);
    },

    methods: {
        updateRoute(slug) {
            this.slug = slug;
            this.fetchData();
        },
        fetchData() {
            console.log(this.slug);
        },
    },
};
</script>

<template>
    <v-row class="text-center bg-red">
        <v-col cols="12">
            <div class="about">
                <h1>
                    {{ this.slug }}
                </h1>
            </div>
        </v-col>
    </v-row>
</template>
