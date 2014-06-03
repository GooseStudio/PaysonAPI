<?php

namespace CyoniteSystems\PaysonAPI;

use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use Httpful\Mime;
class HttpfulTransport implements IHttp {
    /**
     * @param $uri
     * @param $data
     * @param $headers
     * @throws PaysonHttpException
     * @return array
     */
    function post($uri, $data, $headers, $parseData = true) {
        try {
            $response = Request::post($uri, $data, MIME::FORM)
                        ->addHeaders($headers)
                        ->serializePayload($parseData?Request::SERIALIZE_PAYLOAD_SMART : Request::SERIALIZE_PAYLOAD_NEVER)
                        ->withoutStrictSsl()
                        ->parseWith(function ($string) {$entries = explode('&', $string);$data = [];
        foreach($entries as $entry) {
            $tuple = explode('=', $entry);
            $data[array_shift($tuple)] = ($value = array_shift($tuple))?urldecode($value):null;
        }
            return $data;})->send();
        } catch(ConnectionErrorException $ex) {
            throw new PaysonHttpException(sprintf('Post request to %s failed',$uri),$ex->getCode(), $ex);
        }
        return $parseData?$response->body:$response->raw_body;
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
