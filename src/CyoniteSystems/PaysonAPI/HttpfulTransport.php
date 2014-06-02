<?php

namespace CyoniteSystems\PaysonAPI;

use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;

class HttpfulTransport implements IHttp {
    /**
     * @param $uri
     * @param $data
     * @param $headers
     * @throws PaysonHttpException
     * @return array
     */
    function post($uri, $data, $headers) {
        try {
            $response = Request::post($uri, $data)->addHeaders($headers)->send();
        } catch(ConnectionErrorException $ex) {
            throw new PaysonHttpException(sprintf('Post request to %s failed',$uri),$ex->getCode(), $ex);
        }
        return $response;
    }

    /**
     * @param $uri
     * @param $data
     * @param $headers
     * @throws PaysonHttpException
     * @return array
     */
    function get($uri, $data, $headers) {
        try {
            $response = Request::get($uri, $data)->addHeaders($headers)->send();
        } catch(ConnectionErrorException $ex) {
            throw new PaysonHttpException(sprintf('Post request to %s failed',$uri),$ex->getCode(), $ex);
        }
        return $response;
    }
}
