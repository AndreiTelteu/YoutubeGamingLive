import Pusher from "pusher-js";
// import store from "@/plugins/vuex";

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
        pusher = new Pusher(pusherOptions.key, pusherOptions);
        pusher.connection.bind("connected", () => {
            console.log(">>> pusher connected", pusher.connection.state);
        });
        pusher.connection.bind("error", (err) => {
            console.log(">>> detected limit error", err.error.data);
        });

        /*
        socket.on("channel", (data) => {
            data = socketResponse(data);
            // console.log(data);
            let items = [...store.state.subscriptions.items];
            items.map((item, index) => {
                if (item.id == this.id) {
                    items[index] = { ...item, ...data };
                }
            });
            store.commit("subscriptionsUpdate", {
                items: items,
                total: items.length,
            });
        });
        */
    },

    reset() {
        if (pusher) pusher.disconnect();
        pusher = null;
    },
};
