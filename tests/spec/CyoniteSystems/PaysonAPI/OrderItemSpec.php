<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderItemSpec extends ObjectBehavior {
    function it_should_take_params() {
        $this->beConstructedWith('desc', 10, 1, 0.25, '');
        $this->getDescription()->shouldReturn('desc');
        $this->getUnitPrice()->shouldReturn(10);
        $this->getQuantity()->shouldReturn(1);
        $this->getTaxPercentage()->shouldReturn(0.25);
        $this->getSku()->shouldReturn('');
    }
}
