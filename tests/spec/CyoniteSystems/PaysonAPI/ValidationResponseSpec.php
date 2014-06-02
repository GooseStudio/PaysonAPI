<?php

namespace spec\CyoniteSystems\PaysonAPI;

use CyoniteSystems\PaysonAPI\PaymentDetails;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidationResponseSpec extends ObjectBehavior
{
    function it_is_initializable() {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\ValidationResponse');
    }
    function let(PaymentDetails $paymentDetails) {
        $this->beConstructedWith($paymentDetails, []);
    }

    function it_should_be_verified() {
        $this->beConstructedWith(new PaymentDetails([]), 'VERIFIED');
        $this->isVerified()->shouldBe(true);
    }

    function it_should_not_be_verified() {
        $this->beConstructedWith(new PaymentDetails([]), 'INVALID');
        $this->isVerified()->shouldBe(false);
    }
}
