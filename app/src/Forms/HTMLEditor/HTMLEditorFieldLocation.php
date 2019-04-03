<?php

namespace Jimev\Forms\HTMLEditor;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class HTMLEditorFieldLocation extends HTMLEditorField
{
    private static $config_class = 'Jimev\Forms\HTMLEditor\HTMLEditorFieldLocation_Config';
    private static $number_of_rows = 5;
    private static $default_config_name = 'cms';
    public static function create(...$args)
    {
        $args = func_get_args();
        // Class to create should be the calling class if not Object,
        // otherwise the first parameter
        $class = 'SilverStripe\Forms\HTMLEditor\HTMLEditorField';
        $obj = Injector::inst()->createWithArgs($class, $args);
        $configClass = Config::inst()->get(HTMLEditorFieldLocation::class, 'config_class');
        $configClassObject = Injector::inst()->get($configClass);
        $configName = Config::inst()->get(HTMLEditorFieldLocation::class, 'default_config_name');
        $configClassObject->setConfig($configName);
        $rows = $configClassObject->getNumberOfRows();
        if (! $rows) {
            $rows = Config::inst()->get(HTMLEditorFieldLocation::class, 'number_of_rows');
        }
        $obj->setRows($rows);
        return $obj;
    }
}
