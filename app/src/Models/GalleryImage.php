<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\AssetAdmin\Forms\UploadField;


use Jimev\Models\Gallery;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class GalleryImage extends DataObject
{
    private static $singular_name = 'Foto';
    private static $plural_name = 'Fotos';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will Titlealso change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'GalleryImage';

    private static $db = [
      'SortOrder' => 'Int',
      'Title' => 'Varchar',
      'Description' => 'Varchar'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'Image' => Image::class,      // Uploaded image max 1600 x 1200 (1 MB)
        'Gallery' => Gallery::class
    ];

    /**
     * @config
     * @var array List of has_many or many_many relationships owned by this object.
     */
    private static $owns = ['Image'];
    private static $defaults = [];

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $this->Title = $this->Image()->Title;
        parent::populateDefaults();
    }

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = [];

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort='SortOrder ASC, ImageID ASC'; //'SortOrder DESC, Title ASC, Created ASC'

    // Tell the datagrid what fields to show in the table
    private static $summary_fields = [
        'Title' => 'Titel',
        'Image.StripThumbnail' => 'Vorschau'
    ];

    // Add fields to dataobject
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab("Root.Main", "GalleryID");
        $fields->removeFieldFromTab("Root.Main", "SortOrder");

        $fields = new FieldList(
            new TextField('Title', 'Foto-Titel'),
            new TextAreaField('Description', 'Foto Beschreibung (Max 280 Zeichen)'),
            new UploadField('Image', 'Foto')
        );
        return $fields;
    }

    /**
     * Validate the length of the description text
     *
     * @see {@link ValidationResult}
     * @return ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();
        $charcount = strlen($this->Description);
        $description = 'Bitte weniger als 280 Zeichen in der Beschreibung. Es sind ';
        if ($charcount > 280) {
            $result->addFieldMessage(
                'Description',
                $description,
                ValidationResult::TYPE_WARNING
            );
        }
        return $result;
    }

    /**
     * Create all required sizes
     *
     * @return array data
     */
    public function getGalleryImage()
    {
        $description = $this->Description ? $this->Description : '';
        $width = 1024;
        $height = 768;
        $resizedImage = $this->Image();
        if ($this->Image->getWidth() > $width &&
            $this->Image->getHeight()> $height) {
                $resizedImage = $this->Image->Fill($width, $height);
        }
        $data = ['id' => $this->ID,
            'thumb' => $this->Image->ThumbnailURL(80, 60),
            'image' => $resizedImage->URL,
            'big' => $this->Image->URL,
            'title' => $this->Title,
            'description' => $description];
        if (!array_filter($data)) {
            Injector::inst()->get(LoggerInterface::class)
            ->debug('GalleryImage - getGalleryImage()  problem ? ' . $data);
        }

        return $data;
    }

    public function getTitle()
    {
        $title = $this->getField('Title');
        if (!$title) {
            $title = $this->Image()->Title;
        }
        return $title;
    }

    //Permissions

    /**
     * Permission canView
     *
     * @param [type] $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return true;//Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }

    // Admins only
    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_LeftAndMain', 'any', $member);
    }


    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_GalleryAdmin', 'any', $member);
    }

    public function canCreate($Member = null, $context = [])
    {
        if (permission::check('EDIT_GALLERY')) {
            return true;
        } else {
            return false;
        }
    }
}
