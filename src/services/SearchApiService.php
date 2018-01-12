<?php

namespace publiq\structuredData\services;

use craft\base\Component;
use publiq\structuredData\Plugin;
use GuzzleHttp\Client;

use CultuurNet\SearchV3\SearchQuery;
use CultuurNet\SearchV3\Parameter\Id;
use CultuurNet\SearchV3\SearchClient;
use CultuurNet\SearchV3\Serializer\Serializer;

class SearchApiService extends Component
{
    protected $client;

    protected function getClient()
    {
        $settings = Plugin::getInstance()->getSettings();

        if ($settings->apiLocation === '') {
            return [
                'succes' => false,
                'message' => 'API location not supplied. Check your settings.'
            ];
        }


        if ($settings->apiKey === '') {
            return [
                'success' => false,
                'message' => 'API Key not supplied. Check your settings.'
            ];
        }

        $this->client = new Client([
            //'base_uri' => 'https://search-acc.uitdatabank.be/',
            'base_uri' => $settings->apiLocation,
            'timeout' => 5,
            'headers' => [
                'X-Api-Key' => $settings->apiKey
            ]
        ]);
    }

    protected function executeQuery($query)
    {
        // Fire the searchQuery
        $searchClient = new SearchClient($this->client, new Serializer());

        try {
            $result = $searchClient->searchOffers($query);
            $items = $result->getMember()->getItems();

            return [
                'success' => true,
                'items' => $items
            ];

        } catch (\Exception $e) {
            $msg = $e->getMessage();

            return [
                'success' => false,
                'message' => $msg
            ];
        }
    }

    public function getOffersFromApi()
    {
        $this->getClient();
        $query = new SearchQuery(true);

        return $this->executeQuery($query);
    }

    public function getOfferFromApi($eventId)
    {
        $this->getClient();

        // create a new search query
        $query = new SearchQuery(true);
        $query->addParameter(new Id($eventId));

        return $this->executeQuery($query);
    }
}