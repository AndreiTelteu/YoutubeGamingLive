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
            socket.emit("subscribers");
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

        socket.on("subscribers", (data) => {
            data = socketResponse(data);
            store.commit("subscriptionsUpdate", {
                items: data,
                total: data.length,
            });
        });
    },

    api(name, data) {
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
