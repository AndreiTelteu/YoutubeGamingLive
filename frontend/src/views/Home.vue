<template>
    <v-container>
        <v-row class="text-center">
            <v-col class="mb-4">
                <h1 class="display-2 font-weight-bold mb-3">
                    <div>Welcome to the Vuetify 3</div>
                </h1>
            </v-col>

            <v-col class="mb-5" cols="12">
                <h2 class="headline font-weight-bold mb-5">Ecosystem</h2>

                <v-row justify="center">
                    <a
                        v-for="(eco, i) in ecosystem"
                        :key="i"
                        :href="eco.href"
                        class="subheading mx-3"
                        target="_blank"
                    >
                        {{ eco.text }}
                    </a>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "HelloWorld",

    data: () => ({
        ecosystem: [
            {
                text: "vuetify-loader",
                href: "https://github.com/vuetifyjs/vuetify-loader",
            },
            {
                text: "github",
                href: "https://github.com/vuetifyjs/vuetify",
            },
            {
                text: "awesome-vuetify",
                href: "https://github.com/vuetifyjs/awesome-vuetify",
            },
        ],
    }),
};
</script>
