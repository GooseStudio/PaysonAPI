<?php

namespace CyoniteSystems\PaysonAPI;

class PaymentDetails {

    protected $token;
    protected $status;
    protected $invoiceStatus;
    protected $guaranteeStatus;
    protected $guaranteeDeadlineTimestamp;
    protected $shippingAddressName;
    protected $shippingAddressStreetAddress;
    protected $shippingAddressPostalCode;
    protected $shippingAddressCity;
    protected $shippingAddressCountry;
    protected $receiverFee;
    protected $type;
    protected $currencyCode;
    protected $custom;
    protected $trackingId;
    protected $correlationId;
    protected $purchaseId;
    protected $senderEmail;

    public function __construct($paymentData = null) {
        if($paymentData)
            $this->parsePaymentData($paymentData);
    }

    public function parsePaymentData($paymentData) {
        if (isset($paymentData["token"])) {
            $this->token = $paymentData["token"];
        }

        if (isset($paymentData["status"])) {
            $this->status = $paymentData["status"];
        }
        if (isset($paymentData["invoiceStatus"])) {
            $this->invoiceStatus = $paymentData["invoiceStatus"];
        }

        if (isset($paymentData["guaranteeStatus"])) {
            $this->guaranteeStatus = $paymentData["guaranteeStatus"];
        }

        if (isset($paymentData["guaranteeDeadlineTimestamp"])) {
            $this->guaranteeDeadlineTimestamp = $paymentData["guaranteeDeadlineTimestamp"];
        }

        if (isset($paymentData["shippingAddress.name"])) {
            $this->shippingAddressName = $paymentData["shippingAddress.name"];
        }
        if (isset($paymentData["shippingAddress.streetAddress"])) {
            $this->shippingAddressStreetAddress = $paymentData["shippingAddress.streetAddress"];
        }
        if (isset($paymentData["shippingAddress.postalCode"])) {
            $this->shippingAddressPostalCode = $paymentData["shippingAddress.postalCode"];
        }
        if (isset($paymentData["shippingAddress.city"])) {
            $this->shippingAddressCity = $paymentData["shippingAddress.city"];
        }
        if (isset($paymentData["shippingAddress.country"])) {
            $this->shippingAddressCountry = $paymentData["shippingAddress.country"];
        }

        if (isset($paymentData["receiverFee"])) {
            $this->receiverFee = $paymentData["receiverFee"];
        }

        if (isset($paymentData["type"])) {
            $this->type = $paymentData["type"];
        }

        if (isset($paymentData["currencyCode"])) {
            $this->currencyCode = $paymentData["currencyCode"];
        }

        if (isset($paymentData["custom"])) {
            $this->custom = $paymentData["custom"];
        }
        if (isset($paymentData["trackingId"])) {
            $this->trackingId = $paymentData["trackingId"];
        }
        if (isset($paymentData["correlationId"])) {
            $this->correlationId = $paymentData["correlationId"];
        }
        if (isset($paymentData["purchaseId"])) {
            $this->purchaseId = $paymentData["purchaseId"];
        }

        if (isset($paymentData["senderEmail"])) {
            $this->senderEmail = $paymentData["senderEmail"];
        }        
    }

    /**
     * Returns the token for this payment
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Get status of the payment
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Get type of the payment
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get currency code of the payment
     *
     * @return string
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    /**
     * Get custom field of the payment
     *
     * @return string
     */
    public function getCustom() {
        return $this->custom;
    }

    /**
     * Get trackingId field of the payment
     *
     * @return string
     */
    public function getTrackingId() {
        return $this->trackingId;
    }

    /**
     * Get the correlation id for the payment
     *
     * @return string
     */
    public function getCorrelationId() {
        return $this->correlationId;
    }

    /**
     * Get purchase id for the payment
     *
     * @return string
     */
    public function getPurchaseId() {
        return $this->purchaseId;
    }

    /**
     * Get email address of the sender of the payment
     *
     * @return string
     */
    public function getSenderEmail() {
        return $this->senderEmail;
    }

    /**
     * Get the detailed status of an invoice payment
     *
     * @return string
     */
    public function getInvoiceStatus() {
        return $this->invoiceStatus;
    }

    /**
     * Get the detailed status of an guarantee payment
     *
     * @return string
     */
    public function getGuaranteeStatus() {
        return $this->guaranteeStatus;
    }

    /**
     * Get the next deadline of a guarantee payment
     *
     * @return string
     */
    public function getGuaranteeDeadlineTimestamp() {
        return $this->guaranteeDeadlineTimestamp;
    }

    /**
     * Get the name of an invoice payment
     *
     * @return string
     */
    public function getShippingAddressName() {
        return $this->shippingAddressName;
    }

    /**
     * Get the street address of an invoice payment
     *
     * @return string
     */
    public function getShippingAddressStreetAddress() {
        return $this->shippingAddressStreetAddress;
    }

    /**
     * Get the postal code of an invoice payment
     *
     * @return string
     */
    public function getShippingAddressPostalCode() {
        return $this->shippingAddressPostalCode;
    }

    /**
     * Get the city of an invoice payment
     *
     * @return string
     */
    public function getShippingAddressCity() {
        return $this->shippingAddressCity;
    }

    /**
     * Get the country of an invoice payment
     *
     * @return string
     */
    public function getShippingAddressCountry() {
        return $this->shippingAddressCountry;
    }

    /**
     * Returns the fee that the receiver of the payment are charged
     *
     * @return double
     */
    public function getReceiverFee() {
        return $this->receiverFee;
    }
}
