<?php

namespace publiq\structuredData\models;

use craft\base\Model;

class Settings extends Model
{
    public $apiLocation = '';
    public $apiKey = '';

    public function rules()
    {
        return [
            [['apiLocation', 'apiKey'], 'required'],
        ];
    }
}