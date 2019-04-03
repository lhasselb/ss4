<?php

namespace Jimev\Models;

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
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
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Security\Permission;
//use SilverStripe\Control\Director;
use SilverStripe\TagField\TagField;

use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

use Jimev\Pages\FotosPage;
use Jimev\Models\GalleryImage;
use Jimev\Models\GalleryTag;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Gallery DataObject
 * See https://github.com/arambalakjian/DataObject-as-Page/blob/master/code/DataObjects/DataObjectAsPage.php
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
    private static $searchable_fields = ['AlbumName'];

    private static $summary_fields = [
        'AlbumName' => 'Name',
        'AlbumDescription' => 'Beschreibung',
        'getNiceAlbumDate' => 'Datum',
        'getTags' => 'Tags',
        'getImageNumber' => 'Anzahl der Bilder',
        'ImageFolder' => 'Verzeichnis',
        'AlbumImage.StripThumbnail' => 'Album-Bild',
    ];

    private static $default_sort = 'AlbumDate DESC';

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $this->AlbumDate = date('Y-m-d');
        parent::populateDefaults();
    }

    public function getTags()
    {
        $tags = [];
        foreach ($this->GalleryTags() as $tag) {
            array_push($tags, $tag->Title);
        }
        return implode(',', $tags);
    }

    public function getNiceAlbumDate()
    {
        /*$date = new DateTime();
        $date->setValue($this->AlbumDate);
        return $date->Format('d.m.Y');*/

        // Create a DBDate object
        $dbDate = $this->dbObject('AlbumDate');
        // Use strftime to utilize locale
        return strftime('%d.%m.%Y', $dbDate->getTimestamp());
    }

    public function getImageNumber()
    {
        return $this->GalleryImages()->count();
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
            'Album-Tag(s)',
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
            // Add GridFieldBulkManager
            $gridFieldConfig->addComponent(new \Colymba\BulkManager\BulkManager());
            // Remove bulk actions
            $gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\UnlinkHandler');
            $gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\EditHandler');
            // Remove bulk delete action from non Administrators
            if (!$this->canDelete()) {
                $gridFieldConfig->getComponentByType('Colymba\\BulkManager\\BulkManager')
                    ->removeBulkAction('Colymba\\BulkManager\\BulkAction\\DeleteHandler');
            }
            // Add BulkUploader
            $gridFieldConfig->addComponent(new \Colymba\BulkUpload\BulkUploader());
            // Used to determine upload folder
            $gridFieldConfig->getComponentByType('Colymba\\BulkUpload\\BulkUploader')
                ->setUfSetup('setFolderName', $uploadfoldername);
            // Customise gridfield
            $gridFieldConfig->removeComponentsByType('GridFieldPaginator'); // Remove default paginator
            $gridFieldConfig->addComponent(new GridFieldPaginator(20)); // Add custom paginator
            $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
            $gridFieldConfig->removeComponentsByType('GridFieldAddNewButton'); // We only use bulk upload button

            // Creates sortable grid field
            $gridfield = new GridField('GalleryImages', 'Fotos', $this->GalleryImages()
                ->sort('SortOrder'), $gridFieldConfig);
            $fields->addFieldToTab('Root.Fotos', $gridfield);
        }
        return $fields;
    }

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['AlbumName'] = 'Name';
        $labels['AlbumDescription'] = 'Beschreibung';
        $labels['ImageFolder'] = 'Verzeichnisname';
        $labels['AlbumImage'] = 'Album-Bild';
        return $labels;
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
        return $this->GalleryImages();
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
            return Convert::array2json($this->getSortedGalleryImages()->first()->getGalleryImage());
        }
        return Convert::array2json($galleryImage->getGalleryImage());
    }

    public function getGalleryImageIdsJson()
    {
        $list = $this->getSortedGalleryImages()->column('ID');
        // Encode an array as a JSON encoded string, like ["value", "value"]
        return Convert::array2json($list);
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

        /**
         * MOVED TO TASK
         * Added for migration frm 3. to 4. , required after cleanup of data (images)
         * We need to add some self healing for the Gallery has_many GalleryImage relation
         */
        //Injector::inst()->get(LoggerInterface::class)->debug('Gallery - ImageFolder  = ' . $this->ImageFolder);
        // The stored folder contains both the base and folder name like fotoalben/artcon
        /*
        $albumPathName = $this->ImageFolder;
        if (strstr($albumPathName, '/')) {
            $folderName = substr($albumPathName, strrpos($albumPathName, '/') + 1);
        }
        $folder = Folder::get()->filter('Name', $folderName)->first();
        $total = $this->GalleryImages()->count();
        //Injector::inst()->get(LoggerInterface::class)->debug('Gallery - Single Folder  = ' . $folder->Name);
        // Do we have children
        if ($folder->hasChildren() && $total == 0) {
            // Yes - Iterate over all children
            foreach ($folder->myChildren() as $image) {
                // Is this an image
                if ($image->getIsImage()) {
                    // Add ID to GalleryImages
                    //Injector::inst()->get(LoggerInterface::class)->debug('Gallery - image ID  = ' . $image->ID);
                    $gImage = GalleryImage::create();
                    $gImage->Image = $image;
                    $this->GalleryImages()->add($gImage->write());
                }
            }
        }*/
    }

    /**
     * Create a link for this DataObject
     *
     * @return string combined url
     */
    public function Link()
    {
        $fotoPage = DataObject::get_one(FotosPage::class);
        //Injector::inst()
        //    ->get(LoggerInterface::class)
        //    ->debug('Gallery - Link() page  = ' . $fotoPage . ' ' .
        //    Controller::join_links($fotoPage->Link(), 'album', $this->ID));

        return Controller::join_links($fotoPage->Link(), 'album', $this->ID);
    }

    // All Permission use autogenerated Admin based permissions "CMS_ACCESS_GalleryAdmin"

    /**
     * Permission canView
     *
     * @param [type] $member
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

    // Admins only
    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_LeftAndMain', 'any', $member); //CMS_ACCESS_CourseAdmin
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }
}
