<?php

if (!function_exists("socket_response")) {
    function socket_response($data)
    {
        return base64_encode(json_encode($data));
    }
}
