<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeesPayerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CyoniteSystems\PaysonAPI\FeesPayer');
    }
}
