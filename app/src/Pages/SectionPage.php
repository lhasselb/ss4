<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\Forms\LiteralField;

use Jimev\Models\Course;

class SectionPage extends Page
{
    private static $singular_name = 'Bereich in WS und Kurse';
    private static $description = 'Enhält Workshops und Kurse für einen Bereich.';
    //private static $icon = 'mysite/images/workshops.png';
    private static $can_be_root = false;
    private static $allowed_children = 'none';

    private static $belongs_many_many = ['Courses' => Course::class];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SectionPage';

    private static $summary_fields = [
        'Title' => 'Bereich',
    ];

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = [];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'Content');
        $fields->addFieldToTab('Root.Main', new LiteralField(
            'Info',
            '<p><span style="color:red;">Achtung: </span>Zum Bearbeiten der Kurse bitte
            <a href="admin/coursemanager/">Workshops und Kurse</a> auf der linken Seite in der Navigation wählen
            und dort editieren.</p>'
        ), 'Metadata');
        return $fields;
    }

    public function OtherCourses($id)
    {
        return $this->Courses()->exclude('ID', $id);
    }
}
