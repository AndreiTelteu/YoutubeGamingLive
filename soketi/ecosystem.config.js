module.exports = {
    apps: [
        {
            name: "soketi",
            cwd: "/www/wwwroot/youtubegaming.live/soketi/",
            script: "/home/ubuntu/.fnm/node-versions/v14.18.1/installation/lib/node_modules/@soketi/soketi/bin/server.js",
            args: [
                "start",
                "--config=/www/wwwroot/youtubegaming.live/soketi/config.json",
            ],
            watch: false,
            autorestart: true,
            instances: 4,
            exec_mode: "cluster",
        },
    ],
};
