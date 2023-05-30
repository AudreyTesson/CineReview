<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiRomain 
{
    // Clé  dans .env
    private $apiKey;
    /**
     * Service client HTTP
     *
     * @var HttpClientInterface
     */
    private $client;


    public function __construct(HttpClientInterface $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }


    public function fetch(string $title): array
    {
        $response = $this->client->request(
            'GET',
            'http://www.omdbapi.com/', [
                'query' => [
                    't' => $title,
                    'apiKey' => $this->apiKey,
                ],
            ]
        );


        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        
        return $content;
    }
}