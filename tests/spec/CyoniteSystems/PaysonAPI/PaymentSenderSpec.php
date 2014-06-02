<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSenderSpec extends ObjectBehavior {
    function it_should_take_params() {
        $this->beConstructedWith('a@a.com', 'john', 'doe');
        $this->getEmail()->shouldReturn('a@a.com');
        $this->getFirstName()->shouldReturn('john');
        $this->getLastName()->shouldReturn('doe');
    }
}
