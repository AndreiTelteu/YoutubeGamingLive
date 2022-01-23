module.exports = {
    apps: [
        {
            name: "soketi",
            cwd: "/www/wwwroot/youtubegaming.live/soketi/",
            script: "soketi",
            args: [
                "start",
                "--config=/www/wwwroot/youtubegaming.live/soketi/config.json",
            ],
            watch: false,
            interpreter: "/home/ubuntu/.fnm/fnm",
            interpreter_args: "exec --using=14 node",
            autorestart: true,
            // instances: 4,
            // exec_mode: "cluster",
        },
    ],
};
