import Pusher from "pusher-js";
import store from "@/plugins/vuex";

let pusher = null;

export default {
    connected: () => pusher?.connection?.state == "connected",

    connect() {
        this.reset();
        let pusherOptions = {
            key: "Hf9ELp7X6AHaPJ",
            wsHost: location.host,
            wsPath: "/pusher",
            enabledTransports: ["wss", "ws"],
            disableStats: true,
        };
        if (location.host.indexOf('localhost') !== -1) {
            pusherOptions.wsHost = location.host.split(':')[0];
            pusherOptions.wsPort = location.host.split(':')[1];
            pusherOptions.enabledTransports = ["ws"];
            pusherOptions.forceTLS = false;
            pusherOptions.encrypted = false;
        }
        pusher = new Pusher(pusherOptions.key, pusherOptions);
        pusher.connection.bind("connected", () => {
            console.log(">>> pusher connected", pusher.connection.state);
        });
        pusher.connection.bind("error", (err) => {
            console.log(">>> pusher error", err.error);
        });

        pusher.bind("channel", ({ channel }) => {
            let items = [...store.state.subscriptions.items];
            items.map((item, index) => {
                if (item.id == channel.id) {
                    items[index] = { ...item, ...channel };
                }
            });
            store.commit("subscriptionsUpdate", {
                items: items,
                total: items.length,
            });
        });
    },

    subscribeChannels(channels) {
        channels.forEach((i) => {
            pusher.subscribe(`channel.${i.id}`);
        });
    },

    reset() {
        if (pusher) pusher.disconnect();
        pusher = null;
    },
};
