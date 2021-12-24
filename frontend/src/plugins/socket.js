import { io } from "socket.io-client";

export default {
    connected: false,
    conn: null,

    connect(token) {
        this.reset();
        this.conn = io("wss://youtubegaming.live", {
            transports: ["websocket"],
            query: {
                token: token,
            },
        });
    },

    reset() {
        this.connected = false;
        if (this.conn) this.conn.disconnect();
        this.conn = null;
    },
};
