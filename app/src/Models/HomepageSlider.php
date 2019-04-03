<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\CMS\Model\SiteTree;

use Jimev\Pages\HomePage;
use \Page;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/* See https://github.com/gorriecoe/silverstripe-link */
use gorriecoe\Link\Models\Link;
/* See https://github.com/gorriecoe/silverstripe-linkfield */
use gorriecoe\LinkField\LinkField;

class HomepageSlider extends DataObject
{

    private static $singular_name = 'Slider-Bild';
    private static $plural_name = 'Slider-Bilder';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'HomepageSlider';

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'Headline' => 'Varchar(255)',
        'LinkText' => 'Varchar(50)', //Not used anymore moved to Link
        'ExternalURL' => 'Text', //Not used anymore moved to Link
        'HeadlineColor' => "Enum('Weiss,Schwarz,Grau,Blau','Weiss')",
        'SortOrder'=>'Int'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'Parent' => HomePage::class,  // Relation for homepage
        'InternalURL' => Page::class, //Not used any more moved to Link
        'SliderImage' => Image::class,
        'Link' => Link::class
    ];

    /**
     * @config
     * @var array List of has_many or many_many relationships owned by this object.
     */
    private static $owns = ['SliderImage'];

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Headline' => 'Schlagzeile',
        'Link' => 'Link',
        //'LinkText' => 'Text',
        'SliderImage.StripThumbnail' => 'Vorschau'
    ];

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Headline'];

    /* SortOrder used by sortable gridfield */
    private static $default_sort='SortOrder';

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

        // Include link switcher JavaScript
        //Requirements::javascript('mysite/javascript/JimEvCms.js');

        // First remove the old fields
        $fields->removeByName('Headline');
        $fields->removeByName('LinkText');
        $fields->removeByName('ExternalURL');
        $fields->removeByName('HeadlineColor');
        $fields->removeByName('SortOrder');
        //Has_one
        $fields->removeByName('ParentID'); // trailing 'ID' as this is a has_one
        $fields->removeByName('InternalURLID'); // trailing 'ID' as this is a has_one
        //$fields->removeByName('SliderImageID');  // trailing 'ID' as this is a has_one
        $fields->removeByName('SliderImage');  // has_one without trailing 'ID' !!? (TODO:Clarify)
        $fields->removeByName('LinkID');  // trailing 'ID' as this is a has_one

        // Slider Headline
        $sliderHeadline = TextareaField::create('Headline', _t('Homepage.HEADLINE', 'Slider Headline'));
        // Headline Color used on the SliderImage
        $sliderColor = DropdownField::create(
            'HeadlineColor',
            'Schlagzeilen-Farbe:',
            singleton(HomepageSlider::class)->dbObject('HeadlineColor')->enumValues()
        );

        // Settings for UploadField - SliderImage
        $sliderUploadField = new UploadField('SliderImage', 'Bild');
        $sliderUploadField->getValidator()->allowedExtensions = ['jpg', 'gif', 'png'];
        $sliderUploadField->setFolderName('homepage');

        // Link Text / URL
        $sliderLinkField = LinkField::create('Link', 'Schlagzeilen-Link', $this)->setSortColumn('SortOrder');

        $fields->addFieldsToTab(
            'Root.Main',
            [$sliderHeadline, $sliderColor, $sliderUploadField, $sliderLinkField]
        );

        return $fields;
    }

    public function getTitle()
    {
        if ($this->SliderImage()->exists()) {
            return 'Bild: '.$this->SliderImage()->Title;
        }

        return '(kein Bild)';
    }

    /**
     * @return void
     */
    public function onBeforeWrite()
    {
        // If we've set an external link unset any existing internal link
        /*
        if ($this->ExternalURL && $this->isChanged('ExternalURL')) {
            $this->InternalURLID = false;
        // Otherwise, if we've set an internal link unset any existing external link
        } elseif ($this->InternalURLID) {
            $this->ExternalURL = false;
        }*/
        parent::onBeforeWrite();
    }

    /**
     * Fetch the current link, use with $Link in templates
     * @return string|false
     */
    /*public function getLink()
    {
        if ($this->ExternalURL) {
            return $this->ExternalURL;
        } elseif ($this->InternalURL() && $this->InternalURL()->exists()) {
            return $this->InternalURL()->Link();
        }
        return false;
    }*/

    /**
     * Get text color used for frontend slider
     *
     * @return void
     */
    public function getColor()
    {
        //Grau #8797ae; Weiss #ffffff, Schwarz #000000, Blau #57bfe1;
        $color = $this->getField('HeadlineColor');
        //SS_Log::log($color, SS_Log::WARN);
        switch ($color) {
            case "Weiss":
                return 'color: #ffffff !important;';
                break;
            case "Schwarz":
                return 'color: #000000 !important;';
                break;
            case "Grau":
                return 'color: #8797ae !important;';
                break;
            case "Blau":
                return 'color: #57bfe1 !important;';
                break;
        }
    }
}
