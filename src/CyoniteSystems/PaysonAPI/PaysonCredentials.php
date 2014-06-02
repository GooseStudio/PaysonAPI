<?php

namespace CyoniteSystems\PaysonAPI;


class PaysonCredentials {

    private $user_id;
    private $user_key;

    /**
     * Retrieve user id
     * @return string
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * Retrieve user key
     * @return string
     */
    public function getUserKey() {
        return $this->user_key;
    }

    /**
     * Construct Payson credentials with your Payson user id and user key
     * @param string $user_id
     * @param string $user_key
     */
    public function __construct($user_id, $user_key) {
        $this->user_id = $user_id;
        $this->user_key = $user_key;
    }

    public function getHeaders() {
        return [
            'PAYSON-SECURITY-USERID'=>$this->getUserId(),
            'PAYSON-SECURITY-PASSWORD'=>$this->getUserKey(),
        ];
    }
}
