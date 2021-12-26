import io from "socket.io-client";

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
            console.log("connected", this.connected());
        });
        socket.on("disconnect", (reason) => {
            console.error("disconnect", reason);
        });
        socket.on("connect_error", (reason) => {
            console.error("connect_error", reason);
        });
    },

    reset() {
        if (socket) socket.disconnect();
        socket = null;
    },
};
