module.exports = {
    apps: [
        {
            name: "frontend",
            cwd: "/app/frontend",
            script: "./node_modules/.bin/vue-cli-service",
            args: [
                "serve"
            ],
            watch: false,
        },
    ],
};
