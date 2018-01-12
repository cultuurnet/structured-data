<?php

namespace publiq\structuredData;

use publiq\structuredData\models\Settings;
use publiq\structuredData\variables\StructuredDataVariable;
use publiq\structuredData\twigextensions\PubliqFormatterTwigExtension;

use Craft;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;

    public static $plugin;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new PubliqFormatterTwigExtension());

        $annotation = new AnnotationRegistry();
        $annotation->registerLoader('class_exists');

        $this->set('searchApi', '\publiq\structuredData\services\SearchApiService');

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('structuredData', StructuredDataVariable::class);
            }
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('structured-data/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}