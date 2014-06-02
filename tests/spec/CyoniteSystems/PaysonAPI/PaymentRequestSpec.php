<?php

namespace spec\CyoniteSystems\PaysonAPI;

use CyoniteSystems\PaysonAPI\FeesPayer;
use CyoniteSystems\PaysonAPI\LocaleCode;
use CyoniteSystems\PaysonAPI\CurrencyCode;
use CyoniteSystems\PaysonAPI\FundingConstraint;
use CyoniteSystems\PaysonAPI\OrderItem;
use CyoniteSystems\PaysonAPI\PaymentReceiver;
use CyoniteSystems\PaysonAPI\PaymentSender;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentRequestSpec extends ObjectBehavior
{
    function it_is_initializable() {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\PaymentRequest');
    }
    function let(PaymentSender $sender) {
        $this->beConstructedWith($sender, 'returnurl', 'cancelurl', 'notificationurl');
    }
    function it_should_have_return_url() {
        $this->getReturnUrl()->shouldBeString();
    }

    function it_should_have_cancel_url() {
        $this->getCancelUrl()->shouldBeString();
    }

    function it_should_have_ipn_notification_url() {
        $this->getIpnNotificationUrl()->shouldBeString();
    }

    function it_should_take_a_receiver() {
        $this->addReceiver(new PaymentReceiver('a@a.com',0));
        $receivers = $this->getReceivers();
        $receivers->shouldBeArray();
        $receivers[0]->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\PaymentReceiver');
    }

    function it_should_take_an_order() {
        $this->addOrderItem(new OrderItem("A product", 100, 1, 0.25, 'product1'));
        $orders = $this->getOrder();
        $orders->shouldBeArray();
        $orders[0]->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\OrderItem');
    }

    function it_should_take_a_funding_constraint() {
        $this->addFundingConstraint(FundingConstraint::CREDITCARD);
        $this->getFundingConstraints()->shouldBeArray();
    }

    function it_should_take_a_currency_code() {
        $this->setCurrencyCode(CurrencyCode::SEK);
        $this->getCurrencyCode()->shouldEqual(CurrencyCode::SEK);
    }

    function it_should_take_a_locale() {
        $this->setLocaleCode(LocaleCode::SWEDISH);
        $this->getLocaleCode()->shouldEqual(LocaleCode::SWEDISH);
    }

    function it_should_take_a_feespayer() {
        $this->setFeesPayer(FeesPayer::PRIMARYRECEIVER);
        $this->getFeesPayer()->shouldEqual(FeesPayer::PRIMARYRECEIVER);
    }

}
