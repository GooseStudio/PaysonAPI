<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentDetailsSpec extends ObjectBehavior
{
    function it_is_initializable() {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\PaymentDetails');
    }

    function let() {
        $this->beConstructedWith([]);
    }
}
