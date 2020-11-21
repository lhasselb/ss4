<?php

namespace Jimev\Models;

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\ReadOnlyField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Security\Permission;
use SilverStripe\TagField\TagField;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Pages\FotosPage;
use Jimev\Models\GalleryImage;
use Jimev\Models\GalleryTag;

// See https://github.com/UndefinedOffset/SortableGridField
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

/**
 * Gallery DataObject
 * to store a slider object for the homepage.
 * @package Jimev
 * @subpackage Model
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class Gallery extends DataObject
{
    private static $singular_name = 'Album';
    private static $plural_name = 'Alben';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'Gallery';

    private static $db = [
        'Title'=> 'Varchar(255)',
        'ImageFolder' => 'Varchar()',
        'AlbumName' => 'Varchar()',
        'AlbumDescription' => 'Varchar()',
        'AlbumDate' => 'Date'
    ];

    private static $has_one = [
        'AlbumImage' => Image::class
    ];

    private static $has_many = [
        'GalleryImages' => GalleryImage::class
    ];

    private static $many_many = [
        'GalleryTags' => GalleryTag::class
    ];

    private static $belongs_many_many = [
        'FotosPage' => FotosPage::class
    ];

    private static $default_sort = 'AlbumDate DESC';

    /**
     * @config
     * @var array List of has_many or many_many relationships owned by this object.
     */
    private static $owns = ['AlbumImage', 'GalleryImages'];

    /**
     * @config @var string upload folder name used to store/load images
     */
    private static $gallery_folder_name = "fotoalben";

    /**
     * Fields Searchable within top Filter
     * empty equals all
     *
     * @var array
     */
    private static $searchable_fields = ['AlbumName','AlbumDate'];

    private static $summary_fields = [
        'AlbumName' => 'AlbumName',
        'AlbumDescription' => 'AlbumDescription',
        'AlbumDate' => 'AlbumDate',
        'Tags' => 'Bereiche',
        'ImageNumber' => 'Anzahl der Bilder',
        'ImageFolder' => 'ImageFolder',
        'Thumb' => 'Album-Bild',
    ];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['AlbumName'] = 'Name';
        $labels['AlbumDescription'] = 'Beschreibung';
        $labels['AlbumDate'] = 'Datum';
        $labels['Tags'] = 'Tags';
        $labels['ImageNumber'] = 'Anzahl der Bilder';
        $labels['ImageFolder'] = 'Verzeichnis';
        $labels['Thumb'] = 'Album-Bild';
        return $labels;
    }

    /**
     * @config
     */
    private static $items_per_page = 30;

    /* Dynamic defaults for object instance
     * Sets the Date field to the current date.
     * @todo: Check Dates should be stored using ISO 8601 formatted date (y-MM-dd)
     */
    public function populateDefaults()
    {
        //$this->AlbumDate = date('Y-m-d');
        $this->AlbumDate = date('d.m.Y');
        parent::populateDefaults();
    }

    public function getThumb()
    {
        if ($this->AlbumImage()->exists()) {
            return $this->AlbumImage()->StripThumbnail();
        } else {
            return 'Kein Bild';
        }
    }

    public function getTags()
    {
        $tags = [];
        foreach ($this->GalleryTags() as $tag) {
            array_push($tags, $tag->Title);
        }
        return implode(',', $tags);
    }

    public function getAlbumDate()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('AlbumDate');
        // Use strftime to utilize locale
        return strftime('%d.%m.%Y', $dbDate->getTimestamp());
    }

    public function getImageNumber()
    {
        //return $this->GalleryImages()->count();
        return DBField::create_field('Int', $this->GalleryImages()->count());
    }

    public function getAlbumYear()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('AlbumDate');
        // Use strftime to utilize locale
        return strftime('%Y', $dbDate->getTimestamp());
    }

    public function getTitle()
    {
        return $this->AlbumName;
    }

    /**
     * Provide compatability with Menu loops in templates
     */
    public function getMenuTitle()
    {
        return $this->AlbumName;
    }

    public function getAlbumOrFirstImage()
    {
        if ($this->AlbumImageID) {
            return $this->AlbumImage;
        }
        // Not set yet, use the first one (should this be a default ?)
        if ($this->getFirstGalleryImage()) {
            return $this->getFirstGalleryImage()->Image();
        }
    }

    /*
     * Used to compare within array_unique() in FotosPage.php
     */
    public function __toString()
    {
        return $this->getAlbumYear();
    }

    public function getCMSFields()
    {

        $fields = parent::getCMSFields();

        //TODO: Add translation
        $fields->fieldByName('Root.Main')->setTitle('Album');
        // TODO: Verify HtmlEditorConfig::set_active_identifier('basic');
        // Remove Scafolded fields
        $fields->removeByName('GalleryImages');
        $fields->removeByName('GalleryTags');
        $fields->removeByName('FotosPage');
        $fields->removeByName('AlbumImage');

        // Admins only
        if ($this->ID && Permission::check('ADMIN')) {
            $fields->addFieldToTab('Root.Main', TextField::create('ImageFolder', 'Verzeichnis'));
        } else {
            $fields->addFieldToTab('Root.Main', ReadonlyField::create('ImageFolder', 'Verzeichnis'));
        }
        $year = DateField::create('AlbumDate', 'Datum');
        $fields->addFieldToTab('Root.Main', $year);
        $tag = TagField::create(
            'GalleryTags',
            'Bereich(e)',
            GalleryTag::get(),
            $this->GalleryTags()
        )
        ->setShouldLazyLoad(true) // tags should be lazy loaded
        ->setCanCreate(true);     // new tag DataObjects can be created
        $fields->addFieldToTab('Root.Main', $tag);
        $uploadfoldername = $this->ImageFolder;
        if (!empty($uploadfoldername)) {
            $albumImage = new UploadField('AlbumImage', 'Album-Bild');
            $albumImage->setFolderName($uploadfoldername);
            $albumImage->setFolderName($uploadfoldername);
            $fields->addFieldToTab('Root.Main', $albumImage);

            $gridFieldConfig = GridFieldConfig_RecordEditor::create();

            // NEW: GridFieldLazyLoader added with 4.3
            // Causes a "unwanted" change on page which teases the user on leaving
            //$gridFieldConfig->addComponent(new GridFieldLazyLoader());

            // Set number of items per page
            $paginator = $gridFieldConfig->getComponentByType('SilverStripe\Forms\GridField\GridFieldPaginator')
                ->setItemsPerPage($this->config()->get('items_per_page'));

            // Remove bulk delete action from non Administrators
            if (Permission::check('ADMIN') || $this->canDelete()) {
                // Add GridFieldBulkManager
                $gridFieldConfig->addComponent(new \Colymba\BulkManager\BulkManager());
                // Remove unwanted bulk actions
                $gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                    ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\UnlinkHandler');
                $gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                    ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\EditHandler');
                //$gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                //    ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\DeleteHandler');
            }

            // Add BulkUploader
            $gridFieldConfig->addComponent(new \Colymba\BulkUpload\BulkUploader());
            // Used to determine upload folder
            $gridFieldConfig->getComponentByType('Colymba\\BulkUpload\\BulkUploader')
                ->setUfSetup('setFolderName', $uploadfoldername);

            // Add this only if paging is not required
            if ($this->GalleryImages()->count() < $this->config()->get('items_per_page')) {
                $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
            }

            // We only use bulk upload button
            //$gridFieldConfig->removeComponentsByType(GridFieldAddNewButton::class);
            $gridFieldConfig->removeComponentsByType(GridFieldAddExistingAutocompleter::class);

            // Creates sortable grid field
            $gridfield = new GridField('GalleryImages', 'Fotos', $this->GalleryImages()
                ->sort('SortOrder'), $gridFieldConfig);
            $fields->addFieldToTab('Root.Fotos', $gridfield);
        }
        return $fields;
    }

    /**
     * Label displayed in "Insert link" menu
     * @return string
     */
    public static function LinkLabel()
    {
         return 'Foto-Album';
    }

    /**
     * Default sort for GalleryImage is used here
     * see GalleryImage::class @var private static $default_sort='SortOrder ASC, ImageID ASC';
     * ImageID ASC equals ID ASC for new uploads only
     * @return void
     */
    public function getSortedGalleryImages()
    {
        // TODO: Add a configuration enum to select a sort order if required
        // enum('Title','ImageID','SortOrder');
        // return $this->GalleryImages()->sort('SortOrder');
        return $this->GalleryImages()->sort('SortOrder');
    }

    public function getFirstGalleryImage()
    {
        return $this->getSortedGalleryImages()->first();
    }

    /**
     * Create Array of image date as a JSON encoded string
     * like  ["value","value",...]
     * Use the first image if parameter is empty
     * @param GalleryImage $galleryImage
     * @return String JSON encoded string ["value","value",...]
     */
    public function getGalleryImageJson($galleryImage = null)
    {
        if (!$galleryImage) {
            //return Convert::array2json($this->getSortedGalleryImages()->first()->getGalleryImage());
            return json_encode($this->getSortedGalleryImages()->first()->getGalleryImage());
        }
        return Convert::array2json($galleryImage->getGalleryImage());
    }

    public function getGalleryImageIdsJson()
    {
        $list = $this->getSortedGalleryImages()->column('ID');
        // Encode an array as a JSON encoded string, like ["value", "value"]
        // return Convert::array2json($list);
        return json_encode($list);
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // FotosPage is not visible in Editor, store a value automatically
        $fotosPage = FotosPage::get()->first();

        // Add an image folder for the created gallery
        if (empty($this->ImageFolder)) {
            $base = Config::inst()->get(__CLASS__, 'gallery_folder_name');
            if (empty($base)) {
                Injector::inst()->get(LoggerInterface::class)
                ->debug('Gallery - onBeforeWrite() base directory empty ! (' . $base . ')');
            }
            $albumName = preg_replace('/[^A-Za-z0-9]+/', '-', $this->AlbumName);
            $albumName = strtolower(preg_replace('/-+/', '-', $albumName));
            $this->ImageFolder = $base.'/'.$albumName;
        }
    }

    /**
     * Create a link for this DataObject
     *
     * @return string combined url
     */
    public function Link()
    {
        $fotoPage = DataObject::get_one(FotosPage::class);
        return Controller::join_links($fotoPage->Link(), 'album', $this->ID);
    }

    /**
     * All Permission use autogenerated Admin based permissions CMS_ACCESS_GalleryAdmin
     * Permission canView
     *
     * @param \SilverStripe\Security\Member|null $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CourseAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }
}
