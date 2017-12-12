<?php

namespace publiq\structuredData\variables;

use publiq\structuredData\Plugin;

class StructuredDataVariable
{
    public function getOfferData()
    {
        return Plugin::getInstance()->searchApi->getOfferFromApi();
    }
}