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
     * @param bool $parseData
     * @return string|array
     */
    function post($uri, $data, $headers, $parseData = true);
}
