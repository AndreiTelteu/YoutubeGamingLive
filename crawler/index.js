const db = require("./db");

db("channels")
    .select()
    .then((channels) => {
        console.log("channels", channels);
    });

const ytubes = require("./ytubes/dist/index.js");
// ytubes
//     .getChannelLive("UCSJ4gkVC6NrvII8umztf0Ow")
//     .then((r) => {
//         console.log("then", r);
//     })
//     .catch((e) => {
//         console.log("err", e);
//     });
