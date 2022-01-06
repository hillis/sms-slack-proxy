<?php
// src/Service/SlackApiClient.php

namespace App\Service;

use GuzzleHttp\Client;

class SlackApiClient
{
    /** @var Client $client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getConversationReplies(string $threadParentId)
    {
        $response = $this->client->get('conversations.replies', [
            'query' => array_merge(
                $this->client->getConfig()['query'],
                ['ts' => $threadParentId]
            )
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body['messages'];
    }
}
