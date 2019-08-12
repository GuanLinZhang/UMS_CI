<?php


class JSON_helper {
    public function sendJSONResponse($data) {
        return json_encode($data);
    }
}