<?php

namespace CyoniteSystems\PaysonAPI;

/**
 * Class ValidationResponse
 * @package CyoniteSystems\PaysonAPI
 */
class ValidationResponse {
    /**
     * @var PaymentDetails
     */
    private $details;
    private $response;

    /**
     * Checks if response is verified
     * @return bool
     */
    public function isVerified() {
        return $this->response == 'VERIFIED';
    }

    /**
     * @return PaymentDetails
     */
    public function getPaymentDetails() {
        return $this->details;
    }

    /**
     * @param PaymentDetails $details
     * @param string $response
     */
    public function __construct(PaymentDetails $details, $response) {
        $this->details = $details;
        $this->response = $response;
    }
}
