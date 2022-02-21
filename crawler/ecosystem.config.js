module.exports = {
    apps: [
        {
            name: "youtube-crawler",
            cwd: "/app/crawler",
            script: "./index.js",
            restart_delay: 10000,
            max_restarts: 10000000000000,
        },
    ],
};
