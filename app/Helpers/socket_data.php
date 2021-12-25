<?php
if (!function_exists("socket_data")) {
    function socket_data($attr)
    {
        if (gettype($attr) == "array") {
            $data = $attr;
            cache(["ws:" . $attr["socketId"] => $data]);
        } else {
            $socketId = $attr;
            $data = cache("ws:$socketId");
            if (!$data) {
                $data = [
                    "socketId" => $socketId,
                ];
                cache(["ws:$socketId" => $data]);
            }
            return $data;
        }
    }
}
