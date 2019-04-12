<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use Jimev\Models\Faq;

/**
 * FaqTag DataObject to store Tags for FAQs.
 *
 * @package Jimev
 * @subpackage Model
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class FaqTag extends DataObject
{
    private static $singular_name = 'Bereich';
    private static $plural_name = 'Bereiche';

    private static $db = [
        'Title' => 'Varchar()',
    ];

    private static $belongs_many_many = [
        'FAQS' => Faq::class
    ];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = 'Name';
        return $labels;
    }

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'FAQTag';

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'Title ASC';

    /**
     * Defines a default list of filters for the search context
     * empty equals all
     * @var array
     */
    private static $searchable_fields = ['Title'];

    private static $summary_fields = ['Title'];

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
