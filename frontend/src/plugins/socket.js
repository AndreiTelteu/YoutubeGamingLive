import io from "socket.io-client";
import store from "@/plugins/vuex";
import socketResponse from "@/utils/socketResponse";
import { v4 as uuidv4 } from "uuid";

let socket = null;
let apiCallbacks = {};

export default {
    connected: () => (socket ? socket?.connected : false),
    conn: null,

    connect(token) {
        this.reset();
        socket = io("wss://youtubegaming.live", {
            transports: ["websocket"],
            query: {
                token: token,
            },
        });

        socket.on("connect", () => {
            console.info("connected", this.connected());
            socket.emit("join-subscribers-rooms");
            this.api("subscribers").then((data) => {
                let items = {};
                store.state.subscriptions.items.map((item) => {
                    items[item.id] = item;
                });
                data.map((item, index) => {
                    let currentData = items[item.id] || {};
                    data[index] = { ...currentData, ...item };
                });
                store.commit("subscriptionsUpdate", {
                    items: data,
                    total: data.length,
                });
            });
        });

        socket.on("disconnect", (reason) => {
            console.error("disconnect", reason);
        });
        socket.on("connect_error", (reason) => {
            console.error("connect_error", reason);
        });

        socket.on("api", (data) => {
            data = data.split("---|---");
            if (apiCallbacks[data[0]]) {
                let result = socketResponse(data[1]);
                apiCallbacks[data[0]](result);
                delete apiCallbacks[data[0]];
            }
        });

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
    },

    api(name, data = null) {
        let uuid = Date.now() + "-" + uuidv4();
        return new Promise((resolve) => {
            apiCallbacks[uuid] = (data) => {
                resolve(data);
            };
            socket.emit("api", { uuid, name, data });
        });
    },

    reset() {
        if (socket) socket.disconnect();
        socket = null;
        apiCallbacks = {};
    },
};
