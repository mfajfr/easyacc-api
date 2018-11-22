<?php
/**
 * @author Bc. Marek Fajfr <mfajfr90(at)gmail.com>
 * Created at: 9:27 21.11.2018
 */

namespace AccountancyAPI;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Connection
{
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $auth;

    /**
     * WarehouseV1API constructor.
     * @param string $url
     * @param string $auth
     */
    public function __construct($url, $auth)
    {
        $this->url = $url . '/api/v1/';
        $this->auth = $auth;
    }

    protected function client()
    {
        return new Client([
            'base_uri' => $this->url,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->auth,
                'Accept' => 'application/json'
            ]
        ]);
    }

    protected function uri($uri)
    {
        return $this->url . $uri;
    }

    public function get($uri)
    {
        return $this->client()->get($this->uri($uri));
    }

    public function post($uri, $data)
    {
        return $this->client()->post($this->uri($uri), [
            'json' => $data
        ]);
    }

    public function data($uri, $data)
    {
        return $this->client()
            ->post($this->uri($uri), [
                'multipart' => [
                    [
                        'name'     => 'invoice',
                        'filename' => 'invoice.pdf',
                        'contents' => $data,
                        'headers'  => [
                            'Content-Type'              => 'application/pdf',
                            'Content-Transfer-Encoding' => 'binary',
                        ]
                    ],
                ],
            ]);
    }

    public function patch($uri, $data)
    {
        return $this->client()->patch($this->uri($uri), [
            'json' => $data
        ]);
    }

    public function put($uri, $data)
    {
        return $this->client()->post($this->uri($uri), [
            'json' => $data
        ]);
    }

    public function delete($uri)
    {
        return $this->client()->delete($this->uri($uri));
    }

    public function response(ResponseInterface $response, $onlyData = true)
    {
        if ($onlyData) {
            return json_decode($response->getBody()->getContents());
        } else {
            return $response;
        }
    }
}