import axios from "axios";
import store from "@/plugins/vuex";
import { merge } from "lodash";
axios.defaults.withCredentials = true;

export default {
    get(url, data = null, options = {}) {
        return this.request("GET", url, data, options);
    },
    post(url, data = null, options = {}) {
        return this.request("POST", url, data, options);
    },

    request(method, url, data = null, extraOptions = {}) {
        // url = "https://youtubegaming.live/api" + url;
        url = "/api" + url;
        let options = {
            method,
            url,
        };
        if (method == "GET") {
            options.params = data;
        } else {
            options.data = data;
        }
        let authParams = this.authParams();
        options = merge({}, options, authParams, extraOptions);
        return axios(options);
    },

    authParams() {
        let auth = store.state.auth;
        if (!auth || !auth.logged || !auth.token) {
            return {};
        }
        return {
            headers: {
                Authorization: `Bearer ${auth.token}`,
            },
        };
    },
};
