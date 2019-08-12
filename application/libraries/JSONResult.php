<?php

/***
 * 封装JSON格式返回对象
 * Class JSONResult
 */
class JSONResult {
    private $state;
    private $message;
    private $data;

    /**
     * JSONResult constructor.
     * @param $state
     * @param $message
     * @param $data
     */
    public function __construct($data = null,int $state = 1, string $message = "success") {
        $this->state = $state;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state) {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /***
     * 重写tostring(),让其返回json对象
     * @return false|string 返回json格式的对象
     */
    public function __toString() {
        return json_encode(get_object_vars($this));
    }

}