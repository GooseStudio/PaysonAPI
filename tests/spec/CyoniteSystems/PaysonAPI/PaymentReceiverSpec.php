<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentReceiverSpec extends ObjectBehavior {
    function it_should_take_params() {
        $this->beConstructedWith('a@a.com', 10, 'john', 'doe', true);
        $this->getEmail()->shouldReturn('a@a.com');
        $this->getAmount()->shouldReturn(10);
        $this->getFirstName()->shouldReturn('john');
        $this->getLastName()->shouldReturn('doe');
        $this->isPrimary()->shouldReturn(true);
    }
}
