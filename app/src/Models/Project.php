<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Security\Permission;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\DateField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\View\Parsers\URLSegmentFilter;

use SilverStripe\TagField\TagField;

use Jimev\Pages\ProjectPage;
use Jimev\Models\ProjectTag;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

//TODO: implements Linkable
class Project extends DataObject
{
    private static $singular_name = 'Projekt';
    private static $plural_name = 'Projekte';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'Project';

    private static $db = [
        'ProjectTitle' => 'Varchar(255)',
        'Title'=> 'Varchar(255)',
        'URLSegment' => 'Varchar(255)',  //Not used any more ?
        'ProjectDescription' => 'Varchar(255)',
        'ProjectDate' => 'Date',
        'ProjectContent' => 'HTMLText'
    ];

    private static $has_one = [
        'ProjectPage' => ProjectPage::class,
        'ProjectImage' => Image::class
    ];

    private static $many_many = [
        'ProjectTags' => ProjectTag::class
    ];

    private static $summary_fields = [
        'ProjectTitle' => 'ProjectTitle',
        'ProjectDescription' => 'ProjectDescription',
        'ProjectDate' => 'ProjectDate',
        'Tags' => 'Tags',
        'Thumb' => 'Bild'
    ];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['ProjectTitle'] = 'Name';
        $labels['ProjectDescription'] = 'Beschreibung';
        $labels['ProjectDate'] = 'Datum';
        $labels['Tags'] = 'Bereiche';
        $labels['Thumb'] = 'Bild';
        return $labels;
    }

    /**
    * @config
    * @var array List of relationships owned by this object.
    * "Owned images will be published automatically"
    */
    private static $owns = ['ProjectImage'];

    /**
     * Fields Searchable within top Filter
     * empty equals all
     *
     * @var array
     */
    private static $searchable_fields = ['ProjectTitle'];
    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'ProjectDate DESC';

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $this->ProjectDate = date('Y-m-d');
        $this->Title = $this->dbObject('ProjectTitle');
        parent::populateDefaults();
    }

    public function getThumb()
    {
        if ($this->ProjectImage()->exists()) {
            return $this->ProjectImage()->StripThumbnail();
        } else {
            return 'Kein Bild';
        }
    }

    /**
     * Used for $summary_fields
     *
     * @return string (formatted)
     */
    public function getProjectDate()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('ProjectDate');
        // Use strftime to utilize locale
        return strftime('%d.%m.%Y', $dbDate->getTimestamp());
    }

    /**
     * Used for $summary_fields
     *
     * @return string (formatted)
     */
    public function getTags()
    {
        $tags = [];
        foreach ($this->ProjectTags() as $tag) {
            array_push($tags, $tag->Title);
        }
        return implode(',', $tags);
    }

    public function getProjectYear()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('ProjectDate');
        // Use strftime to utilize locale
        return strftime('%Y', $dbDate->getTimestamp());
    }

    public function getTitle()
    {
        return $this->ProjectTitle;
    }

    /*
     * Used to compare within array_unique() in ProjectPage.php
     */
    public function __toString()
    {
        return $this->getProjectYear();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        //TODO: Add translation
        $fields->fieldByName('Root.Main')->setTitle('Projekt');#
        // TODO: Verify HtmlEditorConfig::set_active_identifier('basic');
        $fields->removeByName('ProjectPageID');
        $fields->removeByName('ProjectTags');
        $fields->removeByName('URLSegment');
        $fields->addFieldToTab('Root.Projekt-Inhalt', HtmlEditorField::create('ProjectContent', 'Inhalt'));
        $date = DateField::create('ProjectDate', 'Datum');
        $fields->addFieldToTab('Root.Main', $date);
        $tags = TagField::create('ProjectTags', 'Projekt-Bereich(e)', ProjectTag::get(), $this->ProjectTags())
        ->setShouldLazyLoad(true) // tags should be lazy loaded
        ->setCanCreate(true);     // new tag DataObjects can be created
        $fields->addFieldToTab('Root.Main', $tags);
        $projectImage = new UploadField('ProjectImage', 'Projekt-Bild');
        $projectImage->setFolderName('projekte');
        $fields->addFieldToTab('Root.Main', $projectImage);
        return $fields;
    }

    public function Link()
    {
        $projectPage = DataObject::get_one(ProjectPage::class);
        return Controller::join_links($projectPage->Link(), 'projekt', $this->URLSegment);
    }

    /**
     * Label displayed in "Insert link" menu
     * @return string
     */
    public static function LinkLabel()
    {
         return 'Projekt';
    }

    /**
     * Check if there is already a DOAP with this URLSegment
     */
    public function LookForExistingURLSegment($URLSegment, $ID)
    {
        return Course::get()->filter(
            'URLSegment',
            $URLSegment
        )->exclude('ID', $ID)->exists();
    }

    /**
     * Generate a URL segment based on the title provided.
     *
     * If {@link Extension}s wish to alter URL segment generation, they can do so by defining
     * updateURLSegment(&$url, $title).  $url will be passed by reference and should be modified.
     * $title will contain the title that was originally used as the source of this generated URL.
     * This lets extensions either start from scratch, or incrementally modify the generated URL.
     *
     * @param string $title Page title.
     * @return string Generated url segment
     */
    public function generateURLSegment($title)
    {
        $filter = URLSegmentFilter::create();
        $t = $filter->filter($title);

        // Fallback to generic page name if path is empty (= no valid, convertable characters)
        if (!$t || $t == '-' || $t == '-1') {
            $t = "page-$this->ID";
        }

        // Hook for extensions
        $this->extend('updateURLSegment', $t, $title);

        return $t;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // If there is no URLSegment set, generate one from Title
        if (!$this->URLSegment) {
            $this->URLSegment = $this->generateURLSegment($this->ProjectTitle.$this->getProjectYear());
        } elseif ($this->isChanged('URLSegment')) {
            // Make sure the URLSegment is valid for use in a URL
            $segment = preg_replace('/[^A-Za-z0-9]+/', '-', $this->URLSegment);
            $segment = preg_replace('/-+/', '-', $segment);
            // If after sanitising there is no URLSegment, give it a reasonable default
            if (!$segment) {
                $segment = "item-$this->ID";
            }
            $this->URLSegment = $segment;
        }
        // Ensure that this object has a non-conflicting URLSegment value.
        $count = 2;
        $URLSegment = $this->URLSegment;
        $ID = $this->ID;
        while ($this->LookForExistingURLSegment($URLSegment, $ID)) {
            $URLSegment = preg_replace('/-[0-9]+$/', null, $URLSegment) . '-' . $count;
            $count++;
        }
        $this->URLSegment = $URLSegment;
    }

    /**
     * @var array
     */
    protected static $cached_get_by_url = [];

    /**
     * @param string $str
     * @return Course|Boolean
     */
    public static function get_by_url_segment($str, $excludeID = null)
    {
        if (!isset(static::$cached_get_by_url[$str])) {
            $list = static::get()->filter('URLSegment', $str);
            if ($excludeID) {
                $list = $list->exclude('ID', $excludeID);
            }
            $obj = $list->First();
            static::$cached_get_by_url[$str] = ($obj && $obj->exists()) ? $obj : false;
        }
        return static::$cached_get_by_url[$str];
    }

    /**
     * All Permission use autogenerated Admin based permissions CMS_ACCESS_ProjectAdmin
     * Permission canView
     *
     * @param [type] $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_ProjectAdmin', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_ProjectAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_ProjectAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_ProjectAdmin', 'any', $member);
    }
}
