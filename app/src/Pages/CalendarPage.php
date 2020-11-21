<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\View\Requirements;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\GoogleCalendar;

/**
 * CalendarPage
 *
 * @package Jimev
 * @subpackage Pages
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class CalendarPage extends Page
{
    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'CalendarPage';

    private static $singular_name = 'Termine';
    private static $description = 'Terminkalender mit Daten vom Google Kalender';
    private static $icon = 'resources/app/client/dist/img/calendar.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $db = [
        'GoogleCalendarApiKey' => 'Varchar(40)', //googleCalendarApiKey
     ];

    private static $has_many = [
        'Calendar' => GoogleCalendar::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        // Customise TinyMCE
        TinyMCEConfig::get('cms')->setContentCSS([ '/resources/app/client/dist/css/CalendarPageTinyMce.css' ]);
        TinyMCEConfig::get('cms')->setButtonsForLine(1, 'bullist', 'sslink', 'unlink', 'code');
        TinyMCEConfig::get('cms')->addButtonsToLine(1, 'styleselect')
        ->setOptions([
                'importcss_append' => true,
                // Added to remove default styles
                'style_formats' => [], //$style_formats,
        ]);
        TinyMCEConfig::get('cms')->setButtonsForLine(2, ''); // no button
        TinyMCEConfig::get('cms')->setButtonsForLine(3, ''); // no button
        TinyMCEConfig::get('cms')->disablePlugins('charcount');


        $fields->fieldByName('Root.Main.Content')->setTitle('Legende');

        /**
         * Not yet used googleCalendarApiKey !
         * The API key is required to fetch some additional information from Google
         * e.g. the used colors of the different calendars
         */
        // GOOGLE CALENDAR
        /*
        $googleApiKey = new TextField('GoogleCalendarApiKey', 'Google Kalender API Key');
        $fields->addFieldToTab('Root.Google Kalender', $googleApiKey);
        $calendarConfig = GridFieldConfig_RecordEditor::create();
        $calendarGridField = new GridField('Calendar', 'Google Kalendar', $this->Calendar());
        $calendarGridField->setConfig($calendarConfig);
        $fields->addFieldToTab('Root.Google Kalender', $calendarGridField);
        */
        return $fields;
    }
}
