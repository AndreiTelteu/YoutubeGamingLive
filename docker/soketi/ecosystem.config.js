module.exports = {
    apps: [
        {
            name: "soketi",
            cwd: "/app/",
            script: "/app/bin/pm2.js",
            env: {
                DEBUG: "1",
                PORT: "8084",
                ADAPTER_DRIVER: "cluster",
                METRICS_ENABLED: "1",
                METRICS_SERVER_PORT: "9601",
                DEFAULT_APP_ID: "ytlive0268",
                DEFAULT_APP_KEY: "Hf9ELp7X6AHaPJ",
                DEFAULT_APP_SECRET: "YIf912xR62gCZ6SngM5u1Vc12EP2",
            },
            watch: false,
            autorestart: true,
            instances: 4,
            exec_mode: "cluster",
        },
    ],
};
