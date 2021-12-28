import io from "socket.io-client";
import store from "@/plugins/vuex";
import socketResponse from "@/utils/socketResponse";

let socket = null;

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

        socket.on("subscribers", (data) => {
            data = socketResponse(data);
            store.commit("subscriptionsUpdate", {
                items: data,
                total: data.length,
            });
        });
    },

    reset() {
        if (socket) socket.disconnect();
        socket = null;
    },
};
