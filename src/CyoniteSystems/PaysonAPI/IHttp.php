<?php
namespace CyoniteSystems\PaysonAPI;

/**
 * Interface IHttp
 * @package CyoniteSystems\PaysonAPI
 */
interface IHttp {
    /**
     * @param string $uri
     * @param string $data
     * @param array $headers
     * @return string|array
     */
    function post($uri, $data, $headers, $parseData = true);
    /**
     * @param string $uri
     * @param string $data
     * @param array $headers
     * @return string|array
     */
    function get($uri, $data, $headers);
}
