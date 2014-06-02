<?php

namespace CyoniteSystems\PaysonAPI;

class PaymentSender
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $first_name;
    /**
     * @var string
     */
    private $last_name;

    /**
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     */
    public function __construct($email, $first_name, $last_name) {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
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
    public function toArray() {
        $output["senderEmail"] = $this->getEmail();
        $output["senderFirstName"] = $this->getFirstName();
        $output["senderLastName"] = $this->getLastName();
        return $output;
    }

}
