<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;

class ProjectTag extends DataObject
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
    private static $table_name = 'ProjectTag';

    private static $singular_name = 'Bereich';
    private static $plural_name = 'Bereiche';

    private static $db = [
        'Title' => 'Varchar()'
    ];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = 'Name';
        return $labels;
    }

    private static $belongs_many_many = [
        'Projects' => Project::class
    ];

    /**
     * Fields Searchable within top Filter
     * empty equals all
     *
     * @var array
     */
    private static $searchable_fields = [];

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = '';

    public function getTagTitle()
    {
        $tagTitle = preg_replace('/[^A-Za-z0-9]+/', '-', $this->Title);
        $tagTitle = strtolower(preg_replace('/-+/', '-', $tagTitle));
        return $tagTitle;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
    /*
     * Used to compare within array_unique() in ProjectPage.php
     */
    public function __toString()
    {
        return $this->Title;
    }
}
