<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use Jimev\Models\Faq;

class FaqTag extends DataObject
{
    private static $singular_name = 'Bereich';
    private static $plural_name = 'Bereiche';

    private static $db = [
        'Title' => 'Varchar(200)',
    ];

    private static $belongs_many_many = [
        'FAQS' => Faq::class
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
    private static $table_name = 'FAQTag';

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

        return $fields;
    }
}
