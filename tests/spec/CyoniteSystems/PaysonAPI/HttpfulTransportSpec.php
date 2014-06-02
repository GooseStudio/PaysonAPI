<?php

namespace spec\CyoniteSystems\PaysonAPI;

use CyoniteSystems\PaysonAPI\HttpfulTransport;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpfulTransportSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\HttpfulTransport');
        $this->shouldBeAnInstanceOf('CyoniteSystems\PaysonAPI\IHttp');
    }
}
