module.exports = {
    apps: [
        {
            name: "soketi",
            cwd: "/app/",
            script: "/app/bin/pm2.js",
            env: {
                DEBUG: "1",
                PORT: process.env.PUSHER_PORT,
                ADAPTER_DRIVER: "redis",
                RATE_LIMITER_DRIVER: "redis",
                ADAPTER_REDIS_PREFIX: "yt-soketi",
                DB_REDIS_HOST: "redis",
                DB_REDIS_DB: "2",
                METRICS_ENABLED: "1",
                METRICS_SERVER_PORT: "9601",
                DEFAULT_APP_ID: process.env.PUSHER_APP_ID,
                DEFAULT_APP_KEY: process.env.PUSHER_APP_KEY,
                DEFAULT_APP_SECRET: process.env.PUSHER_APP_SECRET,
            },
            watch: false,
            autorestart: true,
            instances: 4,
            exec_mode: "cluster",
        },
    ],
};
