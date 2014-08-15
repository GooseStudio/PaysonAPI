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
    private $errors;

    /**
     * @return bool
     */
    public function wasSuccessfull() {
        return $this->AckCode=='SUCCESS';
    }


    /**
     * @return bool
     */
    public function hasErrors() {
        return sizeof($this->errors);
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
        $this->AckCode = isset($response['responseEnvelope.ack']) ? $response['responseEnvelope.ack'] : '';
        $this->timestamp = isset($response['responseEnvelope.timestamp'])?$response['responseEnvelope.timestamp'] : '';
        $this->TOKEN = isset($response['TOKEN']) ? $response['TOKEN'] : '';
        $this->errors = $this->parseErrors($response);
    }

    private function parseErrors($output) {
        $errors = array();
        for($i = 0; isset($output[sprintf("errorList.error(%d).message", $i)]); $i++) {
            $errors[$i] = new PaysonApiError(
                $output[sprintf("errorList.error(%d).errorId", $i)], $output[sprintf("errorList.error(%d).message", $i)], isset($output[sprintf("errorList.error(%d).parameter", $i)]) ?
                    $output[sprintf("errorList.error(%d).parameter", $i)] : null
            );
        }
        return $errors;
    }
}
