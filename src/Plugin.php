<?php

namespace publiq\structuredData;

use Craft;
use craft\events\RegisterCacheOptionsEvent;
use craft\utilities\ClearCaches;
use craft\web\twig\variables\CraftVariable;
use Doctrine\Common\Annotations\AnnotationRegistry;
use publiq\structuredData\models\Settings;
use publiq\structuredData\twigextensions\PubliqFormatterTwigExtension;
use publiq\structuredData\variables\StructuredDataVariable;
use yii\base\Event;
use yii\caching\TagDependency;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;

    public static $plugin;

    const UDB_EVENTS_DETAIL = 'udb_events_detail';
    const UDB_EVENTS_ALL = 'udb_events_all';

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

        Event::on(
            ClearCaches::class,
            ClearCaches::EVENT_REGISTER_CACHE_OPTIONS,
            function (RegisterCacheOptionsEvent $event) {
                $event->options = array_merge(
                    $event->options, [
                    [
                        "key" => 'udb_events_detail',
                        "label" => "UDB event detail caches",
                        "action" => [Plugin::getInstance(), 'clearDetailCaches']
                    ]
                ]);
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

    public function clearDetailCaches($tags = [self::UDB_EVENTS_DETAIL])
    {
        TagDependency::invalidate(
            Craft::$app->getCache(),
            $tags
        );
    }
}