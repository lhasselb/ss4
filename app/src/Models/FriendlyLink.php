<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;

//TODO: Check migration
//use Sheadawson\Linkable\Models\Link;
//use Sheadawson\Linkable\Forms\LinkField;

/* See https://github.com/gorriecoe/silverstripe-link */
use gorriecoe\Link\Models\Link;
/* See https://github.com/gorriecoe/silverstripe-linkfield */
use gorriecoe\LinkField\LinkField;

//TODO: DELETE this class and the table in the database after migtation
class FriendlyLink extends DataObject
{
    private static $singular_name = 'Link';
    private static $plural_name = 'Links';

    private static $db = [
        'Description' => 'Varchar(255)'
    ];

    private static $belongs_many_many = [
        'LinkSet' => LinkSet::class
    ];

    private static $has_one = [
        'FriendlyLink' => Link::class // moved from FriendlyLink to Link
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
    private static $table_name = 'FriendlyLink';

    private static $summary_fields = [
        'Description'=>'Beschreibung',
        'FriendlyLink'=>'Link',
    ];

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'LastEdited DESC'; // e.g. 'Description DESC' or 'Created ASC' or 'LastEdited DESC'

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Description'];

    public function getTitle()
    {
        return $this->Description;
    }

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Description'] = 'Beschreibung';
        return $labels;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['FriendlyLinkID']);
        $fields->addFieldToTab('Root.Main', LinkField::create('FriendlyLink', 'Link', $this));

        return $fields;
    }
}
