<?php

namespace spec\CyoniteSystems\PaysonAPI;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CyoniteSystems\PaysonAPI\PaysonCredentials;
class PaysonCredentialsSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->shouldHaveType('\CyoniteSystems\PaysonAPI\PaysonCredentials');
    }

    function let() {
        $this->beConstructedWith('userid', 'userkey');
    }

    function it_should_have_user_id() {
        $this->getUserId()->shouldReturn('userid');
    }

    function it_should_have_user_key() {
        $this->getUserKey()->shouldReturn('userkey');
    }

    function it_should_return_headers() {
        $this->getHeaders()->shouldEqual([
            'PAYSON-SECURITY-USERID'=>'userid',
            'PAYSON-SECURITY-PASSWORD'=>'userkey',
        ]);
    }
}
