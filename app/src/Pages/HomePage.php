<?php

namespace Jimev\Pages;

use \Page;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
/* 3rdparty */
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

use Jimev\Models\HomepageSlider;
use Jimev\Models\Alarm;

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
        'Sliders' => HomepageSlider::class . '.Homepage', //.Parent
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
        // The DataObject class displayed must define a
        // canView() method that returns a boolean on whether the user can view this record.
        $sliderGridField = new GridField('SLider', 'Bild(er) auf der Startseite', $this->Sliders());
        $sliderConfig = GridFieldConfig_RecordEditor::create();
        $sliderConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $sliderGridField->setConfig($sliderConfig);
        $fields->addFieldToTab('Root.Slider-Bilder', $sliderGridField);

        // ALARM
        $alarmGridField = new GridField('Alarm', 'Alarm auf der Startseite', $this->Alarm());
        $alarmConfig = GridFieldConfig_RecordEditor::create();
        $alarmGridField->setConfig($alarmConfig);
        if ($this->Alarm()->count() > 0) {
            // remove the buttons. We don't want to allow more records to be added/created
            $alarmConfig->removeComponentsByType(GridFieldAddNewButton::class);
            // $alarmConfig->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
        }
        $fields->addFieldToTab('Root.Alarm', $alarmGridField);

        return $fields;
    }
}
