<?php

namespace publiq\structuredData\services;

use craft\base\Component;
use publiq\structuredData\Plugin;
use GuzzleHttp\Client;

use CultuurNet\SearchV3\SearchQuery;
use CultuurNet\SearchV3\Parameter\Query;
use CultuurNet\SearchV3\Parameter\ApiKey;
use CultuurNet\SearchV3\SearchClient;
use CultuurNet\SearchV3\Serializer\Serializer;

class SearchApiService extends Component
{

    public function getOfferFromApi($eventId)
    {
        $settings = Plugin::getInstance()->getSettings();

        if ($settings->apiLocation === '') {
            return [
                'succes' => false,
                'message' => 'API location not supplied. Check your settings.'
            ];
        }

        $client = new Client([
            'base_uri' => $settings->apiLocation,
            'timeout' => 3
        ]);

        if ($settings->apiKey === '') {
            return [
                'success' => false,
                'message' => 'API Key not supplied. Check your settings.'
            ];
        }

        // create a new search query
        $query = new SearchQuery(true);
        $query->addParameter(new ApiKey($settings->apiKey));
        $query->addParameter(new Query($eventId));

        // Fire the searchQuery
        $searchClient = new SearchClient($client, new Serializer());

        try {
            $result = $searchClient->searchOffers($query);
            $items = $result->getMember()->getItems();

            return [
                'success' => true,
                'event' => $items
            ];

        } catch (\Exception $e) {
            $msg = $e->getMessage();

            return [
                'success' => false,
                'message' => $msg
            ];
        }

    }
}