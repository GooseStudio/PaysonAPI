<?php

namespace CyoniteSystems\PaysonAPI;

class OrderItem {

    private $description;
    private $unitPrice;
    private $quantity;
    private $taxPercentage;
    private $sku;

    /**
     * Constructs an OrderItem object
     *
     * If any other value than description is provided all of them has to be entered
     *
     * @param string $description Description of order item. Max 128 characters
     * @param double $unitPrice Unit price not incl. VAT
     * @param int $quantity  Quantity of this item. Can have at most 2 decimal places
     * @param double $taxPercentage Tax value. Not actual percentage. For example, 25% has to be entered as 0.25
     * @param string $sku Sku of item
     */
    public function __construct($description, $unitPrice = null, $quantity = null, $taxPercentage = null, $sku = null) {
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->taxPercentage = $taxPercentage;
        $this->sku = $sku;
    }

    /**
     * Returns the description of the order item
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Price of item. <strong>Note: </strong>Not including vat
     *
     * @return double
     */
    public function getUnitPrice() {
        return $this->unitPrice;
    }

    /**
     * Quantity of this item
     *
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Returns the tax as a decimal value. Multiply this with 100
     * to get actual tax value.
     *
     * @return double Tax value
     */
    public function getTaxPercentage() {
        return $this->taxPercentage;
    }

    /**
     * Returns the Sku of the order item
     *
     * @return string
     */
    public function getSku() {
        return $this->sku;
    }

    public function toArray($n) {
        $order["orderItemList.orderItem($n).description"]=$this->description;
        if ($this->sku)
            $order["orderItemList.orderItem($n).sku"] = $this->sku;
        if ($this->quantity)
            $order["orderItemList.orderItem($n).quantity"]=$this->quantity;
        if ($this->unitPrice)
            $order["orderItemList.orderItem($n).unitPrice"]=$this->unitPrice;
        if ($this->taxPercentage)
            $order["orderItemList.orderItem($n).taxPercentage"]=$this->taxPercentage;
        return $order;
    }
}
