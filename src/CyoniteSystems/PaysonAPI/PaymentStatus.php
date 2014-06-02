<?php

namespace CyoniteSystems\PaysonAPI;

/**
 * Class PaymentStatus
 * Contains constants related to payment status
 * @package CyoniteSystems\PaysonAPI
 */
class PaymentStatus {
    /**
     * The payment request was received and has been created in Payson's system. Funds will be transferred once approval is received.
     */
    const CREATED = "CREATED";
    /**
     *  The sender has a pending transaction. A guarantee payment in progress has status pending. Please check guaranteeStatus for further details.
     */
    const PENDING = "PENDING";
    /**
     *  - The payment is in progress, check again later.
     */
    const PROCESSING = "PROCESSING";
    /**
     * The sender's transaction has completed.
     */
    const COMPLETED = "COMPLETED";
    /**
     * The sender's transaction has been credited.
     */
    const CREDITED = "CREDITED";
    /**
     * Obsolete, this status is not used anymore. Some transfers succeeded and some failed for a parallel payment.
     */
    const INCOMPLETE = "INCOMPLETE";
    /**
     * - The payment failed and all attempted transfers failed or all completed transfers were successfully reversed.
     */
    const ERROR = "ERROR";
    /**
     * One or more transfers failed when attempting to reverse a payment.
     */
    const REVERSALERROR = "REVERSALERROR";
    /**
     * The payment was aborted before any money were transferred.
     */
    const ABORTED = "ABORTED";
} 