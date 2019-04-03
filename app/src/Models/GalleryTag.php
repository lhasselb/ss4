<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;

use Jimev\Models\Gallery;

class GalleryTag extends DataObject
{
    private static $singular_name = 'Tag';
    private static $plural_name = 'Tags';

    private static $db = [
        'Title' => 'Varchar()'
    ];

    private static $belongs_many_many = [
        'Galleries' => Gallery::class
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
    private static $table_name = 'GalleryTag';

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = '';

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = [];

    private static $summary_fields = [];

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
     * Used to compare within array_unique() in FotosPage.php
     */
    public function __toString()
    {
        return $this->Title;
    }
}
