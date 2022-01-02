require("dotenv").config({ path: process.cwd() + "/../.env" });
const knex = require("knex");

const db = knex({
    client: "mysql2",
    connection: {
        host: process.env.DB_HOST,
        port: parseInt(process.env.DB_PORT),
        user: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_DATABASE,
    },
});

module.exports = db;
