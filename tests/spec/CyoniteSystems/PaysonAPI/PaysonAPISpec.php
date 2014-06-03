<?php

namespace spec\CyoniteSystems\PaysonAPI;

use CyoniteSystems\PaysonAPI\CurrencyCode;
use CyoniteSystems\PaysonAPI\FeesPayer;
use CyoniteSystems\PaysonAPI\FundingConstraint;
use CyoniteSystems\PaysonAPI\LocaleCode;
use CyoniteSystems\PaysonAPI\OrderItem;
use CyoniteSystems\PaysonAPI\PaymentReceiver;
use CyoniteSystems\PaysonAPI\PaymentRequest;
use CyoniteSystems\PaysonAPI\PaymentResponse;
use CyoniteSystems\PaysonAPI\PaymentSender;
use CyoniteSystems\PaysonAPI\PaymentStatus;
use CyoniteSystems\PaysonAPI\PaysonAPI;
use CyoniteSystems\PaysonAPI\PaysonCredentials;
use CyoniteSystems\PaysonAPI\ValidationResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Mockery as m;


class PaysonAPISpec extends ObjectBehavior {
    private $credentials;
    private $headers;

    function it_is_initializable() {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\PaysonAPI');
    }

    function let() {
        $this->credentials = new PaysonCredentials('userid', 'userkey');
        $this->beConstructedWith($this->credentials, true);
        $this->headers =[
            'PAYSON-SECURITY-USERID' => 'userid',
            'PAYSON-SECURITY-PASSWORD' => 'userkey',
            'PAYSON-APPLICATION-ID' => ''
        ];

    }

    function it_should_pay_and_return_paymentresponse() {
        $receiver = new PaymentReceiver('testagent-1@payson.se', 10);
        $sender = new PaymentSender('test-shopper@payson.se', 'John', 'Doe');
        $paymentRequest = new PaymentRequest(
            $sender
            ,'https://localhost/return'
            ,'https://localhost/cancel'
            ,'https://localhost/notify');
        $paymentRequest->addReceiver($receiver);
        $token = uniqid();
        $timestamp = (new \DateTime())->format('Y-m-dd h:m:s');
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $http->shouldReceive('post')->once()->andReturn(['responseEnvelope.ack'=>'SUCCESS','responseEnvelope.timestamp'=>$timestamp,'TOKEN'=>$token]);
        $this->setTransport($http);
        $response=$this->pay($paymentRequest);
        $response->shouldReturnAnInstanceOf('CyoniteSystems\PaysonAPI\PaymentResponse');
        $response->wasSuccessfull()->shouldBe(true);
        $response->getToken()->shouldBeString();
    }

    function it_should_pay_and_make_request() {
        $receiver = new PaymentReceiver('testagent-1@payson.se', 10);
        $sender = new PaymentSender('test-shopper@payson.se', 'John', 'Doe');
        $paymentRequest = new PaymentRequest(
            $sender
            ,'https://localhost/return'
            ,'https://localhost/cancel'
            ,'https://localhost/notify');
        $paymentRequest->addReceiver($receiver);

        $token = uniqid();
        $timestamp = (new \DateTime())->format('Y-m-dd h:m:s');
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $http->shouldReceive('post')->withAnyArgs()->once()->andReturn(['responseEnvelope.ack'=>'SUCCESS','responseEnvelope.timestamp'=>$timestamp,'TOKEN'=>$token]);
        $this->setTransport($http);
        $payment_response = $this->pay($paymentRequest);
        $payment_response->wasSuccessfull()->shouldBe(true);
        $payment_response->getToken()->shouldEqual($token);
        $payment_response->getTimestamp()->shouldEqual($timestamp);
    }

    function it_should_return_forward_url() {
        $receiver = new PaymentReceiver('testagent-1@payson.se', 10);
        $sender = new PaymentSender('test-shopper@payson.se', 'John', 'Doe');
        $paymentRequest = new PaymentRequest(
            $sender
            ,'https://localhost/return'
            ,'https://localhost/cancel'
            ,'https://localhost/notify');
        $paymentRequest->addReceiver($receiver);

        $token = uniqid();
        $timestamp = (new \DateTime())->format('Y-m-dd h:m:s');
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $forwardUrl = sprintf('https://test-'.PaysonAPI::PAYSON_WWW_HOST.PaysonAPI::PAYSON_WWW_PAY_FORWARD_URL, $token);
        $http->shouldReceive('post')->withAnyArgs()->once()->andReturn(['responseEnvelope.ack'=>'SUCCESS','responseEnvelope.timestamp'=>$timestamp,'TOKEN'=>$token]);
        $this->setTransport($http);
        $payment_response = $this->pay($paymentRequest);
        $this->makeForwardUrl($payment_response)->shouldEqual($forwardUrl);
    }

    function it_should_validate_ipn() {
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_VALIDATE_ACTION . '/';
        $http->shouldReceive('post')->with($url,'', $this->headers, false)->once()->andReturn('VERIFIED');
        $this->setTransport($http);
        $response=$this->validate('',[]);
        $response->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\ValidationResponse');
        $response->isVerified()->shouldBe(true);
        $response->getPaymentDetails()->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\PaymentDetails');
    }

    function it_should_not_validate_ipn() {
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_VALIDATE_ACTION . '/';
        $http->shouldReceive('post')->with($url,'', $this->headers, false)->once()->andReturn('INVALID');
        $this->setTransport($http);
        $response=$this->validate('', []);
        $response->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\ValidationResponse');
        $response->isVerified()->shouldBe(false);
        $response->getPaymentDetails()->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\PaymentDetails');
    }

    function it_should_pay_qs_and_validate() {
        $paymentRequest = new PaymentRequest(
            new PaymentSender('test-shopper@payson.se', 'john', 'doe')
            ,'https://localhost/return'
            ,'https://localhost/cancel'
            ,'https://localhost/notify');
        $token = uniqid();
        $timestamp = (new \DateTime())->format('Y-m-dd h:m:s');
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_PAY_ACTION.'/';
        $response = 'currencyCode=SEK&senderEmail=test-shopper%40payson.se&custom=&fundingList.fundingConstraint(0).constraint=BANK&purchaseId=3692397&type=TRANSFER&status='. PaymentStatus::PROCESSING .'&token='.$token.'&receiverList.receiver(0).email=testagent-1%40payson.se&receiverList.receiver(0).amount=125.00&orderItemList.orderItem(0).description=Test+produkt&orderItemList.orderItem(0).unit=&orderItemList.orderItem(0).unitPrice=100.0000&orderItemList.orderItem(0).quantity=1.00&orderItemList.orderItem(0).taxPercentage=0.250000&orderItemList.orderItem(0).sku=kalle&receiverFee=6.7500&HASH=0bbbc23884ac7fe1ddd78bd94c8d8eb6';
        $data =  array('returnUrl'=>'https://localhost/return','cancelUrl'=>'https://localhost/cancel','ipnNotificationUrl'=>'https://localhost/notify','senderEmail'=>'test-shopper@payson.se','senderFirstName'=>'john','senderLastName'=>'doe','memo'=>'none','ShowReceiptPage'=>'true');
        $http->shouldReceive('post')->with($url,$data,$this->headers, true)->once()->andReturn(['responseEnvelope.ack'=>'SUCCESS','responseEnvelope.timestamp'=>$timestamp,'TOKEN'=>$token]);
        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_VALIDATE_ACTION.'/';
        $http->shouldReceive('post')->with($url,$response,$this->headers, false)->once()->andReturn('VERIFIED');

        $this->setTransport($http);
        $payment_response = $this->pay($paymentRequest);
        /**
         * @var ValidationResponse $validationResponse
         */
        $validationResponse = $this->validate($response, $this->toArray($response));
        $validationResponse->isVerified()->shouldBe(true);
        $validationResponse->getPaymentDetails()->getSenderEmail()->shouldEqual('test-shopper@payson.se');
        $validationResponse->getPaymentDetails()->getStatus()->shouldEqual(PaymentStatus::PROCESSING);

        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_VALIDATE_ACTION.'/';
        $response = 'currencyCode=SEK&senderEmail=test-shopper%40payson.se&custom=&fundingList.fundingConstraint(0).constraint=BANK&purchaseId=3692397&type=TRANSFER&status='. PaymentStatus::COMPLETED .'&token='.$token.'&receiverList.receiver(0).email=testagent-1%40payson.se&receiverList.receiver(0).amount=125.00&orderItemList.orderItem(0).description=Test+produkt&orderItemList.orderItem(0).unit=&orderItemList.orderItem(0).unitPrice=100.0000&orderItemList.orderItem(0).quantity=1.00&orderItemList.orderItem(0).taxPercentage=0.250000&orderItemList.orderItem(0).sku=kalle&receiverFee=6.7500&HASH=0bbbc23884ac7fe1ddd78bd94c8d8eb6';
        $http->shouldReceive('post')->with($url, $response, $this->headers, false)->once()->andReturn('VERIFIED');
        $validationResponse = $this->validate($response, $this->toArray($response));
        $validationResponse->isVerified()->shouldBe(true);
        $validationResponse->getPaymentDetails()->getSenderEmail()->shouldEqual('test-shopper@payson.se');
        $validationResponse->getPaymentDetails()->getStatus()->shouldEqual(PaymentStatus::COMPLETED);

    }

    private function toArray($string) {$entries = explode('&', $string);$data = [];
        foreach($entries as $entry) {
            $tuple = explode('=', $entry);
            $data[array_shift($tuple)] = ($value = array_shift($tuple))?urldecode($value):null;
        }
        return $data;
    }

    function it_should_order_pay_and_validate() {
        $receiver = new PaymentReceiver('testagent-1@payson.se', 10);
        $sender = new PaymentSender('test-shopper@payson.se', 'John', 'Doe');
        $paymentRequest = new PaymentRequest(
            $sender
            ,'https://localhost/return'
            ,'https://localhost/cancel'
            ,'https://localhost/notify');
        $paymentRequest->addReceiver($receiver);
        $paymentRequest->addOrderItem(new OrderItem("Test product", 125.00 , 1, 0.25, 'kalle'));
        $paymentRequest->setLocaleCode(LocaleCode::SWEDISH);
        $paymentRequest->setCurrencyCode(CurrencyCode::SEK);
        $paymentRequest->addFundingConstraint(FundingConstraint::BANK);
        $paymentRequest->setFeesPayer(FeesPayer::PRIMARYRECEIVER);

        $data = array('returnUrl'=>'https://localhost/return','cancelUrl'=>'https://localhost/cancel','ipnNotificationUrl'=>'https://localhost/notify','senderEmail'=>'test-shopper@payson.se','senderFirstName'=>'John','senderLastName'=>'Doe','receiverList.receiver(0).email'=>'testagent-1@payson.se','receiverList.receiver(0).amount'=>10,'receiverList.receiver(0).primary'=>true,'orderItemList.orderItem(0).description'=>'Test product','orderItemList.orderItem(0).sku'=>'kalle','orderItemList.orderItem(0).quantity'=>1,'orderItemList.orderItem(0).unitPrice'=>125.00,'orderItemList.orderItem(0).taxPercentage'=>0.25,'memo'=>'none','ShowReceiptPage'=>'true');
        $token = uniqid();
        $timestamp = (new \DateTime())->format('Y-m-dd h:m:s');
        $http = m::mock('CyoniteSystems\PaysonAPI\IHttp');
        $url = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_PAY_ACTION.'/';
        $http->shouldReceive('post')->with($url, $data, $this->headers, true)->once()->andReturn(['responseEnvelope.ack'=>'SUCCESS','responseEnvelope.timestamp'=>$timestamp,'TOKEN'=>$token]);
        $validateUrl = 'https://test-'.PaysonAPI::PAYSON_API_ENDPOINT.'/'.PaysonAPI::PAYSON_API_VERSION.'/'.PaysonAPI::PAYSON_API_VALIDATE_ACTION.'/';
        $response = 'purchaseId=3692397&type=TRANSFER&status='. PaymentStatus::PROCESSING .'&token='.$token.'&'.$this->toString($data);
        $http->shouldReceive('post')->with($validateUrl, $response, $this->headers, false)->once()->andReturn('VERIFIED');

        $this->setTransport($http);

        /**
         * @var PaymentResponse $response
         */
        $presponse = $this->pay($paymentRequest);
        $presponse->wasSuccessfull()->shouldBe(true);

                /**
                 * @var ValidationResponse $validationResponse
                 */
        $validationResponse = $this->validate($response, $this->toArray($response));
        $validationResponse->isVerified()->shouldBe(true);
        $validationResponse->getPaymentDetails()->getSenderEmail()->shouldEqual('test-shopper@payson.se');
        $validationResponse->getPaymentDetails()->getStatus()->shouldEqual(PaymentStatus::PROCESSING);



    }

    private function toString($array) {
        $entries = array();

        foreach ($array as $key => $value) {
                $entries[$key] = sprintf("%s=%s", $key, urlencode($value));
        }

        return join("&", $entries);
    }
}
