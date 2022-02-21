const db = require("./db");
const _ = require("lodash");
const ytubes = require("ytubes/dist/index.js");
const axios = require("axios").default;

let numOfChunks = 5;

const sleep = (s) => {
    return new Promise((resolve) => setTimeout(resolve, s * 1000));
};

db("channels")
    .select()
    .then(async (channels) => {
        let batches = _.chunk(channels, numOfChunks);
        for (let index = 0; index < batches.length; index++) {
            // process channels from batch
            let batchChannels = batches[index];
            for (let index2 = 0; index2 < batchChannels.length; index2++) {
                let channel = batchChannels[index2];
                await processChannel(channel);
                await sleep(0.5);
            }
            // get some rest
            await sleep(3);
        }
        // console.log("DONEEE !!!");
        process.exit(1);
    });

const processChannel = (channel) => {
    return new Promise((resolve, reject) => {
        // console.log("channel ", channel.id, "START");
        Promise.all([getStreams(channel, true), getStreams(channel, false)])
            .then((result) => {
                let online = result[0].length > 0;
                let online_streams = result[0].map(({ id, title, views }) => {
                    return { id, title, views, lastUpdate: Date.now() };
                });
                let last_stream_date = null; // TODO: update lib to extract date
                let last_streams = result[1].map(({ id, title, views }) => {
                    return { id, title, views };
                });
                db("channels")
                    .where("id", channel.id)
                    .update({
                        online: online ? 1 : 0,
                        online_streams: JSON.stringify(online_streams),
                        last_stream_date,
                        last_streams: JSON.stringify(last_streams),
                    })
                    .then((r) => {
                        // console.log("channel ", channel.id, "END");
                        notifyChannel(channel).then((result) => {
                            // console.log(result);
                            // console.log("channel ", channel.id, "NOTIF");
                            resolve();
                        });
                    })
                    .catch((e) => {
                        console.log("err", e);
                        resolve();
                    });
            })
            .catch((e) => {
                console.log("err", e);
                resolve();
            });
    });
};

const getStreams = (channel, live) => {
    return new Promise((resolve, reject) => {
        let promise = live
            ? ytubes.getChannelLive(channel.youtube_id)
            : ytubes.getChannelPastLive(channel.youtube_id);
        promise.then((streams) => resolve(streams)).catch((e) => resolve([]));
    });
};

const notifyChannel = (channel) => {
    return new Promise((resolve) => {
        let url = `${process.env.APP_URL}/api/channel/event-updated`;
        fetch({
            url,
            method: "POST",
            data: {
                sig: process.env.INTERNAL_API_SIG,
                channelId: channel.id,
            },
        })
            .then((response) => resolve(response.data))
            .catch((error) => resolve(error));
    });
};

const fetch = ({ url, method, data = {} }) => {
    return new Promise((resolve, reject) => {
        axios({ method, url, data, timeout: 5000 })
            .then((response) => resolve(response))
            .catch((error) => resolve(error));
    });
};
