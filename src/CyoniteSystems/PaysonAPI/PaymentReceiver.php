<?php

namespace CyoniteSystems\PaysonAPI;

/**
 * Class PaymentReciever
 * @package CyoniteSystems\PaysonAPI
 */
class PaymentReceiver {

    private $email;
    private $amount;
    private $first_name;
    private $last_name;
    private $primary;

    /**
     * @param string $email
     * @param double $amount
     * @param string $first_name
     * @param string $last_name
     * @param bool $primary
     */
    public function __construct($email, $amount, $first_name = '', $last_name = '', $primary = true) {
        $this->email = $email;
        $this->amount = $amount;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->primary = $primary;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return double
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @return bool
     */
    public function isPrimary() {
        return $this->primary;
    }

    public function toArray($n=0) {
        $output["receiverList.receiver($n).email"]=$this->email;
        $output["receiverList.receiver($n).amount"]=$this->amount;
        $output["receiverList.receiver($n).primary"]=$this->primary;
        return $output;
    }
}
