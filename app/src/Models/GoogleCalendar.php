<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;

use Jimev\Pages\CalendarPage;

class GoogleCalendar extends DataObject
{
    private static $singular_name = 'Kalendar';
    private static $plural_name = 'Kalendar';

    private static $db = [
        'Title' => 'Varchar(200)',
        'CalendarID' => 'Varchar(200)',
        'CalendarTextColor' => 'Varchar(200)',
        'CalendarColor' => 'Varchar(200)'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'Calendarpage' => CalendarPage::class
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
    private static $table_name = 'GoogleCalendar';

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'Title ASC';

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Title'];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('CalendarpageID');
        return $fields;
    }
}
