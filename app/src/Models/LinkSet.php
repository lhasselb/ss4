<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Security\Permission;

/* See https://github.com/gorriecoe/silverstripe-link */
use gorriecoe\Link\Models\Link;
/* See https://github.com/gorriecoe/silverstripe-linkfield */
use gorriecoe\LinkField\LinkField;

use Jimev\Pages\LinkPage;

class LinkSet extends DataObject
{

    private static $singular_name = 'Sammlung';
    private static $plural_name = 'Sammlungen';

    private static $db = ['Title' => 'Varchar(255)'];

    private static $has_one = ['LinkPage' => LinkPage::class];

    /**
     * Migrated
     * FriendlyLink has been replaced by Link
     * The table FriendlyLink is obsolete
     * private static $many_many = ['Links' => FriendlyLink::class];
     */
    private static $many_many = ['Links' => Link::class];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'LinkSet';

    private static $summary_fields = ['Title' => 'Sammlung'];

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'LastEdited DESC';

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Title'];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = 'Sammlungs-Titel';
        $labels['Links'] = 'Sammlung';
        return $labels;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('LinkPageID');
        $fields->removeByName('Links');
        $config = GridFieldConfig_RecordEditor::create();
        $gridfield = GridField::create('Links', 'Links', $this->Links(), $config);
        $fields->addFieldToTab('Root.Main', $gridfield);
        return $fields;
    }

    /**
     * Permission canView
     * For Gridfield the DataObject class displayed must define a
     * canView() method that returns a boolean on whether the user can view this record.
     * @param \SilverStripe\Security\Member|null $member
     * @return boolean
     */
    public function canView($member = null)
    {
        if (Permission::checkMember($member, 'CMS_ACCESS')) {
            //user can access the CMS
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \SilverStripe\Security\Member|null $member
     * @param array $context
     * @return bool
     */
    public function canEdit($member = null)
    {
        if (Permission::checkMember($member, 'CMS_ACCESS')) {
            //user can access the CMS
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \SilverStripe\Security\Member|null $member
     * @param array $context
     * @return bool
     */
    public function canCreate($member = null, $context = [])
    {
        if (Permission::checkMember($member, 'CMS_ACCESS')) {
            //user can access the CMS
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \SilverStripe\Security\Member|null $member
     * @param array $context
     * @return bool
     */
    public function canDelete($member = null, $context = [])
    {
        if (Permission::checkMember($member, 'CMS_ACCESS')) {
            //user can access the CMS
            return true;
        } else {
            return false;
        }
    }
}
