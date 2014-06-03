<?php

namespace CyoniteSystems\PaysonAPI;

class PaymentRequest {
    protected $constraints = [];
    protected $currency_code;
    protected $locale_code;
    protected $fees_payer;
    protected $receivers = [];
    protected $showReceiptPage = true;
    /**
     * @var PaymentSender
     */
    private $sender;
    private $return_url;
    private $cancel_url;
    private $ipn_notification_url;
    private $order = [];

    public function __construct(PaymentSender $sender, $return_url, $cancel_url, $ipn_notification_url) {
        $this->sender = $sender;
        $this->return_url = $return_url;
        $this->cancel_url = $cancel_url;
        $this->ipn_notification_url = $ipn_notification_url;
    }

    /**
     * @return PaymentSender
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl() {
        return $this->return_url;
    }

    /**
     * @return mixed
     */
    public function getCancelUrl() {
        return $this->cancel_url;
    }

    /**
     * @return mixed
     */
    public function getIpnNotificationUrl() {
        return $this->ipn_notification_url;
    }

    public function getPayload() {
        $output = array();

        $output["returnUrl"] = $this->return_url;
        $output["cancelUrl"] = $this->cancel_url;
        $output["ipnNotificationUrl"] = $this->ipn_notification_url;
//        $output["memo"] = $this->memo;

        if (isset($this->localeCode)) {
            $output["localeCode"] = $this->localeCode;
        }

        if (isset($this->currencyCode)) {
            $output["currencyCode"] = $this->currencyCode;
        }

        $output = array_merge($output, $this->sender->toArray());
        for($i=0;$i<sizeof($this->receivers); $i++) {
            $output = array_merge($output, $this->receivers[$i]->toArray($i));
        }

        $orders=[];
        for($i=0;$i<sizeof($this->order); $i++) {
            $orders=array_merge($orders, $this->order[$i]->toArray($i));
        }

        if (isset($this->fundingConstraints)) {
            $output[] = FundingConstraint::toString($this->fundingConstraints);
        }

        if (isset($this->custom)) {
            $output["custom"] = $this->custom;
        }

        if (isset($this->trackingId)) {
            $output["trackingId"] = $this->trackingId;
        }

        if (isset($this->feesPayer)) {
            $output["feesPayer"] = $this->feesPayer;
        }
/*
        if (isset($this->guaranteeOffered)) {
            $output["guaranteeOffered"] = GuaranteeOffered::ConstantToString($this->guaranteeOffered);
        }*/


        if (isset($this->showReceiptPage)) {
            $output["ShowReceiptPage"] = $this->showReceiptPage ? 'true' : 'false';
        }

        return $output;
    }


    /**
     * @param PaymentReceiver $receiver
     */
    public function addReceiver(PaymentReceiver $receiver) {
        array_push($this->receivers, $receiver);
    }

    /**
     * @return PaymentReceiver[]
     */
    public function getReceivers() {
        return $this->receivers;
    }

    /**
     * @param OrderItem $item
     */
    public function addOrderItem(OrderItem $item) {
        array_push($this->order, $item);
    }

    /**
     * @return OrderItem[]
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param int $constraint FundingConstraint
     */
    public function addFundingConstraint($constraint) {
        $this->constraints[] = $constraint;
    }

    /**
     * @return int[] FundingConstraint
     */
    public function getFundingConstraints() {
        return $this->constraints;
    }

    public function setCurrencyCode($currency_code) {
        $this->currency_code = $currency_code;
    }

    public function getCurrencyCode() {
        return $this->currency_code;
    }

    public function setLocaleCode($locale_code) {
        $this->locale_code = $locale_code;
    }

    public function getLocaleCode() {
        return $this->locale_code;
    }


    public function setFeesPayer($fees_payer) {
        $this->fees_payer = $fees_payer;
    }

    public function getFeesPayer() {
        return $this->fees_payer;
    }

    /**
     * @return boolean
     */
    public function showReceiptPage() {
        return $this->showReceiptPage;
    }

    /**
     * @param boolean $showReceiptPage
     */
    public function setShowReceiptPage($showReceiptPage)
    {
        $this->showReceiptPage = $showReceiptPage;
    }
}
