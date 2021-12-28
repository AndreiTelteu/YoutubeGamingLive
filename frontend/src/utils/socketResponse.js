import { Base64 } from "js-base64";
export default function (data) {
    return JSON.parse(Base64.decode(data));
}
