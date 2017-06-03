<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 6/3/17
 * Time: 11:34 AM
 */

namespace App\Console\Commands\Contest\Fetch;
use GuzzleHttp\Client;


trait DoRequest
{
    protected function doRequest($url)
    {
        $client = new Client();
        $response = $client->get($url);
        $body = $response->getBody();
        return $body->getContents();
    }
}