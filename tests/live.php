<?php
require '../vendor/autoload.php';
use CyoniteSystems\PaysonAPI\CurrencyCode;
use CyoniteSystems\PaysonAPI\FeesPayer;
use CyoniteSystems\PaysonAPI\FundingConstraint;
use CyoniteSystems\PaysonAPI\LocaleCode;
use CyoniteSystems\PaysonAPI\OrderItem;
use CyoniteSystems\PaysonAPI\PaymentReceiver;
use CyoniteSystems\PaysonAPI\PaymentRequest;
use CyoniteSystems\PaysonAPI\PaymentResponse;
use CyoniteSystems\PaysonAPI\PaymentSender;
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", dirname(__FILE__). '/error.log');
/**
 * Card number: 4581 1111 1111 1112
 * Expiration Date:Any
 * Cvc: Any
 */
$http = new \Httpful\Httpful();
$credentials = new \CyoniteSystems\PaysonAPI\PaysonCredentials(4, '2acab30d-fe50-426f-90d7-8c60a7eb31d4');
$api = new \CyoniteSystems\PaysonAPI\PaysonAPI($credentials, true);
$api->setTransport(new \CyoniteSystems\PaysonAPI\HttpfulTransport());

switch($_SERVER['QUERY_STRING']) {
    case 'page=return':
        echo 'payment has been done';
        break;
    case 'page=cancel':
        echo 'Payment was cancelled';
        break;
    case 'page=notify':
        $response = $api->validate($_REQUEST);
        $state = $response->isVerified() ? "VERIFIED\n" : "NOT VERIFIED\n";
        file_put_contents(dirname(__FILE__). '/payson.log', "**** IPN ****\n", FILE_APPEND);
        file_put_contents(dirname(__FILE__). '/payson.log', $state, FILE_APPEND);
        file_put_contents(dirname(__FILE__). '/payson.log', print_r($response, true), FILE_APPEND);
        file_put_contents(dirname(__FILE__). '/payson.log', print_r($_REQUEST, true), FILE_APPEND);
        break;
    default:
        $siteUrl = curPageURL();

        $receiver = new PaymentReceiver('testagent-1@payson.se', 125.00*1.25);
        $sender = new PaymentSender('test-shopper@payson.se', 'John', 'Doe');
        $paymentRequest = new PaymentRequest(
            $sender
            ,$siteUrl.'?page=return'
            ,$siteUrl.'?page=cancel'
            ,$siteUrl.'?page=notify');
        $paymentRequest->addReceiver($receiver);
        $paymentRequest->addOrderItem(new OrderItem("Test product", 125.00 , 1, 0.25, 'kalle'));
        $paymentRequest->setLocaleCode(LocaleCode::SWEDISH);
        $paymentRequest->setCurrencyCode(CurrencyCode::SEK);
        $paymentRequest->addFundingConstraint(FundingConstraint::CREDITCARD);
        $paymentRequest->setFeesPayer(FeesPayer::PRIMARYRECEIVER);
        /**
         * @var PaymentResponse $response
         */
        $response = $api->pay($paymentRequest);
        if ($response->wasSuccessfull()) {
            file_put_contents(dirname(__FILE__). '/payson.log', "**** Payment ****\n", FILE_APPEND);
            $forward = $api->makeForwardUrl($response);
            file_put_contents(dirname(__FILE__). '/payson.log', "$forward\n", FILE_APPEND);
            file_put_contents(dirname(__FILE__). '/payson.log', print_r($response, true), FILE_APPEND);
            header("Location: " . $forward);
        } else {
            echo '<pre>';
            print_r($response);
            echo '</pre>';
        }
        break;
}

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
