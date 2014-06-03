<?php

namespace CyoniteSystems\PaysonAPI;

class PaysonAPI {
    /**
     * @var PaysonCredentials
     */
    private $credentials;
    /**
     * @var IHttp
     */
    private $transport;

    const PAYSON_WWW_HOST = "www.payson.se";
    const PAYSON_WWW_PAY_FORWARD_URL = "/paysecure/?token=%s";
    const PAYSON_API_ENDPOINT = "api.payson.se";
    const PAYSON_API_VERSION = "1.0";
    const PAYSON_API_PAY_ACTION = "Pay";
    const PAYSON_API_PAYMENT_DETAILS_ACTION = "PaymentDetails";
    const PAYSON_API_PAYMENT_UPDATE_ACTION = "PaymentUpdate";
    const PAYSON_API_VALIDATE_ACTION = "Validate";
    /**
     * @var bool
     */
    private $test;

    /**
     * @param PaysonCredentials $credentials
     * @param bool $test
     */
    public function __construct(PaysonCredentials $credentials, $test = false) {
        $this->credentials = $credentials;
        $this->test = $test;
    }

    /**
     * Makes a payment to Payson
     * @param PaymentRequest $payment_request
     * @return PaymentResponse
     */
    public function pay(PaymentRequest $payment_request) {
        $payload = $payment_request->getPayload();
        $response = $this->makeRequest(self::PAYSON_API_PAY_ACTION, $payload);
        $payment_response = new PaymentResponse($response);
        return $payment_response;
    }

    /**
     * @param string $action
     * @param $payload
     * @return mixed
     */
    private function makeRequest($action, $payload) {
        $paymentUrl = $this->makeUrl($action);
        $headers = $this->credentials->getHeaders();
        return $this->transport->post($paymentUrl, $payload, $headers, $action !== self::PAYSON_API_VALIDATE_ACTION);
    }

    public function setTransport(IHttp $transport) {
        $this->transport = $transport;
    }

    function makeUrl($action) {
        return 'https://'.($this->isTesting()?'test-':'').PaysonAPI::PAYSON_API_ENDPOINT . "/".PaysonAPI::PAYSON_API_VERSION. "/$action/";
    }

    /**
     * @return PaysonCredentials
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * Url to redirect buyers to after payment request is sent.
     * @param PaymentResponse $payment_response
     * @return string
     */
    public function makeForwardUrl(PaymentResponse $payment_response) {
        return sprintf('https://'.($this->isTesting()?'test-':''). PaysonAPI::PAYSON_WWW_HOST . PaysonAPI::PAYSON_WWW_PAY_FORWARD_URL, $payment_response->getToken());
    }

    /**
     * @param array $request
     * @return ValidationResponse
     */
    public function validate($raw_request,$request) {
        $response = $this->makeRequest(self::PAYSON_API_VALIDATE_ACTION, $raw_request);
        return new ValidationResponse(new PaymentDetails($request), $response);
    }

    /**
     * @return boolean
     */
    public function isTesting() {
        return $this->test;
    }
}
