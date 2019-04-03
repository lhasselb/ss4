<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;

use Jimev\Pages\KontaktPage;

//TODO: Check migration
//use Sheadawson\Linkable\Models\Link;
//use Sheadawson\Linkable\Forms\LinkField;

/* See https://github.com/gorriecoe/silverstripe-link */
use gorriecoe\Link\Models\Link;
/* See https://github.com/gorriecoe/silverstripe-linkfield */
use gorriecoe\LinkField\LinkField;

class FacebookLink extends DataObject
{
    private static $singular_name = 'Facebookgruppen-Link';
    private static $plural_name = 'Facebookgruppen-Links';

    private static $db = [];

    private static $has_one = [
        'KontaktPage' => KontaktPage::class,
        'FacebookLink' => Link::class
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
    private static $table_name = 'FacebookLink';

    private static $summary_fields = ['FacebookLink'=>'Link'];

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

    public function getTitle()
    {
        return $this->FacebookLink()->Title;
    }

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['FacebookLink'] = 'Link';
        //$labels['Description'] = 'Beschreibung';
        return $labels;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        /**
         * Temporarily hide all link and file tracking tabs/fields in the CMS UI
         * added in SS 4.2 until 4.3 is available
         *
         * Related GitHub issues and PRs:
         *   - https://github.com/silverstripe/silverstripe-cms/issues/2227
         *   - https://github.com/silverstripe/silverstripe-cms/issues/2251
         *   - https://github.com/silverstripe/silverstripe-assets/pull/163
         * */
        $fields->removeByName(['FileTracking', 'LinkTracking']);

        $fields->removeByName('KontaktPageID');
        //$fields->addFieldToTab('Root.Main', LinkField::create('FacebookLinkID', 'Facebook-Gruppe'));
        // NEW
        $fields->removeByName('FacebookLinkID');
        $fields->addFieldToTab('Root.Main', LinkField::create('FacebookLink', 'Facebook-Gruppe', $this));
        return $fields;
    }
}
