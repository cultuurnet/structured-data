<?php

namespace publiq\structuredData\variables;

use Craft;
use publiq\structuredData\Plugin;
use yii\caching\TagDependency;

class StructuredDataVariable
{
    public function getOffers()
    {
        return Plugin::getInstance()->searchApi->getOffersFromApi();
    }

    public function getOfferData($cdbid = null, $includePastEvents = false)
    {
        if (!$cdbid) {
            $cdbid = substr($_GET['p'], -36);
        }

        if (isset($cdbid)) {
            if (getenv('ENVIRONMENT') == 'dev') {
                return Plugin::getInstance()->searchApi->getOfferFromApi($cdbid, $includePastEvents);
            } else {
                $tags = new TagDependency([
                    "tags" => [
                        Plugin::UDB_EVENTS_ALL,
                        Plugin::UDB_EVENTS_DETAIL,
                        "udb_events_$cdbid"
                    ]
                ]);
                return Craft::$app->getCache()->getOrSet("udb_events_$cdbid", function () use ($cdbid, $includePastEvents) {
                    return Plugin::getInstance()->searchApi->getOfferFromApi($cdbid, $includePastEvents);
                }, 86400, $tags);
            }
        }
    }
}