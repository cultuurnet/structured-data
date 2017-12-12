<?php

namespace publiq\structuredData\services;

use craft\base\Component;
use publiq\structuredData\Plugin;
use GuzzleHttp\Client;

use Cultuurnet\Searchv3\SearchQuery;
use Cultuurnet\SearchV3\Parameter\Query;
use CultuurNet\SearchV3\SearchClient;
use CultuurNet\SearchV3\Serializer\Serializer;

class SearchApiService extends Component
{

    public function getOfferFromApi()
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
            'timeout' => 1
        ]);

        if ($settings->apiKey === '') {
            return [
                'success' => false,
                'message' => 'API Key not supplied. Check your settings.'
            ];
        }

        $eventId = $_GET["cdbid"];

        if ($eventId === null || $eventId === '') {
            return;
        }

        // create a new search query
        $query = new SearchQuery(true);
        $query->addParameter(new Query($eventId));

        // Fire the searchQuery
        $searchClient = new SearchClient($client, new Serializer());

        try {
            $return = $searchClient->searchEvents($query);
            $collection = $return->getMember()->getItems();

            return [
                'success' => true,
                'event' => $collection
            ];

        } catch (\Exception $e) {
            $msg = json_decode($e->getMessage());
            return [
                'success' => false,
                'message' => $msg->detail
            ];
        }

    }
}