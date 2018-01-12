<?php

namespace publiq\structuredData\variables;

use publiq\structuredData\Plugin;

class StructuredDataVariable
{
    public function getOffers()
    {
        return Plugin::getInstance()->searchApi->getOffersFromApi();
    }

    public function getOfferData()
    {
        $cdbid = substr($_GET['p'], -36);

        if (isset($cdbid)) {
            return Plugin::getInstance()->searchApi->getOfferFromApi($cdbid);
        }
    }
}