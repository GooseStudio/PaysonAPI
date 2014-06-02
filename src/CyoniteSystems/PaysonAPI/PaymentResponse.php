<?php

namespace CyoniteSystems\PaysonAPI;

/**
 * Class PaymentResponse
 * @package CyoniteSystems\PaysonAPI
 */
class PaymentResponse {
    private $AckCode;
    private $timestamp;
    private $TOKEN;

    /**
     * @return bool
     */
    public function wasSuccessfull() {
        return $this->AckCode=='SUCCESS';
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->TOKEN;
    }


    /**
     * @return string
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * Make a payment response.
     * @param array $response key value array
     * @internal
     */
    public function __construct($response) {
        $this->AckCode = $response['responseEnvelope.ack'];
        $this->timestamp = $response['responseEnvelope.timestamp'];
        $this->TOKEN = $response['TOKEN'];
    }
}
