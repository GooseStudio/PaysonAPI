<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\PaymentResponse');
    }

    function let() {
        $this->beConstructedWith(["responseEnvelope.ack"=>'SUCCESS',
            "responseEnvelope.timestamp"=>time(),
            "TOKEN"=>'']);
    }
}
