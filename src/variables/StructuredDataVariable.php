<?php

namespace publiq\structuredData\variables;

use publiq\structuredData\Plugin;

class StructuredDataVariable
{
    public function getOfferData()
    {
        if (isset($_GET["cdbid"])) {
            return Plugin::getInstance()->searchApi->getOfferFromApi($_GET["cdbid"]);
        }
    }
}