<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;

use Jimev\Pages\ContactAddressPage;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class Vorstand extends DataObject
{
    private static $singular_name = 'Vorstand';
    private static $plural_name = 'Vorstände';
    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'Vorstand';

    private static $db = [
       'Name' => 'Varchar(255)',
       'Role' => 'Varchar(255)',
       'Mail' =>  'Varchar(255)'
    ];

    private static $has_one = [
       'Bild' => Image::class,
       'ContactAddressPage' => ContactAddressPage::class
    ];

    /**
    * @config
    * @var array List of relationships owned by this object.
    * "Owned images will be published automatically"
    */
    private static $owns = ['Bild'];

    private static $summary_fields = [
        'Name'=> 'Name',
        'Role' => 'Funktion',
        'Bild.StripThumbnail' => 'Vorschau'
    ];

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
        return $this->Name;
    }

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Name'] = 'Name';
        $labels['Role'] = 'Funktion';
        $labels['Mail'] = 'E-Mail';
        return $labels;
    }

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

        $fields->removeByName('ContactAddressPageID');

        $name = TextField::create('Name', 'Name');
        $role = TextField::create('Role', 'Funktion');
        $mail = TextField::create('Mail', 'E-Mail');

        $bildUploadField = new UploadField('Bild', 'Bild');
        $uploadfoldername = substr($this->Link(), 1, -1);
        //SS_Log::log($uploadfoldername ,SS_Log::WARN);
        $bildUploadField->getValidator()->allowedExtensions = ['jpg', 'gif', 'png'];
        $bildUploadField->setFolderName($uploadfoldername);

        $fields->addFieldsToTab('Root.Main', [$name,$role,$mail,$bildUploadField]);
        return $fields;
    }

    public function Link()
    {
        $page = DataObject::get_by_id(ContactAddressPage::class, $this->ContactAddressPageID);
        return Controller::join_links($page->Link());
        //return Controller::curr()->Link();
    }
}
