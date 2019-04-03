<?php

namespace Jimev\Pages;

use \Page;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
// 3rdparty
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

use Jimev\Models\HomepageSlider;
use Jimev\Models\Alarm;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * HomePage
 *
 * @package mysite
 * @subpackage pages
 *
 */
class HomePage extends Page
{
    private static $singular_name = 'Startseite';
    private static $description = 'Startseite für JIMEV';
    private static $icon = 'resources/app/client/dist/img/home.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $has_many = [
        'Sliders' => HomepageSlider::class . '.Parent',
        'Alarm' => Alarm::class . '.Homepage',
        'News' => News::class
    ];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'HomePage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $content = $fields->fieldByName('Root.Main.Content');
        //Injector::inst()->get(LoggerInterface::class)
        //->debug('HomePage - getCMSFields() content = ' . $content->debug());

        // SLIDER
        $sliderConfig = GridFieldConfig_RecordEditor::create();
        $sliderConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $sliderGridField = new GridField('SLider', 'Bild(er) auf der Startseite', $this->Sliders());
        $sliderGridField->setConfig($sliderConfig);
        $fields->addFieldToTab('Root.Slider-Bilder', $sliderGridField);

        // ALARM
        $alarmConfig = GridFieldConfig_RecordEditor::create();
        if ($this->Alarm()->count() > 0) {
            // remove the buttons if we don't want to allow more records to be added/created
            $alarmConfig->removeComponentsByType('GridFieldAddNewButton');
            $alarmConfig->removeComponentsByType('GridFieldAddExistingAutocompleter');
        }
        $alarmGridField = new GridField('Alarm', 'Alarm auf der Startseite', $this->Alarm());
        $alarmGridField->setConfig($alarmConfig);
        $fields->addFieldToTab('Root.Alarm', $alarmGridField);

        return $fields;
    }
}
