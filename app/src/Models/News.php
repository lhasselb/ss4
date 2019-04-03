<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Assets\Image;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\ReadonlyField;

// See https://github.com/stevie-mayhew/hasoneedit/blob/master/src/HasOneEdit.php
use SGN\HasOneEdit\HasOneEdit;

use Jimev\Pages\SectionPage;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * News DataObject to store news.
 *
 * @package Jimev
 * @subpackage Model
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2019 [sybeha]
 * @license see license file in modules root directory
 */
class News extends DataObject
{
    private static $singular_name = 'News';
    private static $plural_name = 'News';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'News';

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'NewsTitle' => 'Varchar(255)',
        'NewsDate' => 'Date',
        'ExpireDate' => 'Date',
        'NewsContent' => 'HTMLText',
        'Section' => 'Varchar(255)',  // Not used but kept for compatibility / Use HomepageSection instead
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'NewsImage' => Image::class,
        'HomepageSection' => SectionPage::class
    ];

    /**
     * Belongs_to relationship
     * @var array
     */
    private static $belongs_to = [
        'Course' => Course::class . '.News'
    ];
    /**
     * @config
     * @var array List of has_many or many_many relationships owned by this object.
     */
    private static $owns = ['NewsImage'];

    private static $casting =
    [
        'TitleWithDate' => 'Varchar',
        'NewsSection' =>  'Varchar',
        'NiceNewsDate' => 'Varchar',
        'NiceExpireDate' => 'Varchar',
    ];

    /**
     * TODO: Add translation ?
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'NewsTitle' => 'Schlagzeile',
        'NewsSection' => 'Bereich',
        'NiceNewsDate' => 'Anzeige-Datum',
        'NiceExpireDate' => 'Ablauf-Datum',
        'OnHomepage' => 'Wird auf der Startseite angezeigt?',
        'NewsImage.StripThumbnail' => 'Miniaturbild',
    ];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = 'Schlagzeile';
        $labels['NewsTitle'] = 'Schlagzeile';
        $labels['NewsSection'] = 'Bereich';
        $labels['HomepageSection.Title'] = 'Bereich';
        $labels['NewsContent'] = 'News-Inhalt';
        $labels['NewsDate'] = 'Anzeige-Datum';
        $labels['ExpireDate'] = 'Ablauf-Datum';
        $labels['NewsImage'] = 'News-Bild';
        return $labels;
    }

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort='ExpireDate DESC, NewsDate DESC'; //NewsDate

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['NewsTitle', 'HomepageSection.Title', 'NewsDate', 'ExpireDate'];

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $this->NewsDate = date('Y-m-d');
        parent::populateDefaults();
    }

    /**
     * See https://github.com/stevie-mayhew/hasoneedit
     *
     * @param string $relationName
     * @return FieldList|FormField[]
     */
    public function provideHasOneInlineFields($relationName)
    {
        //all the field names should be in the form $relationName . HasOneEdit::FIELD_SEPARATOR . $dataObjectFieldName
        $sectionDropdown = DropdownField::create(
            'News' . HasOneEdit::FIELD_SEPARATOR . 'HomepageSectionID',
            'Bereich',
            SectionPage::get()->map('ID', 'Title')
        )
            ->setEmptyString('(Bitte auswählen)')
            ->setDescription('Bereich ist optional. Ohne Eingabe wird "News" als Bereich angezeigt.');

        $newsImage = UploadField::create(
            'News' . HasOneEdit::FIELD_SEPARATOR . 'NewsImage',
            $this->fieldLabel('NewsImage')
        )
            ->setIsMultiUpload(false)
            ->setFolderName('news');
        $newsImage->getValidator()->allowedExtensions = ['jpeg','jpg', 'gif', 'png'];

        $fieldlist = new FieldList(
            new TextField('News'. HasOneEdit::FIELD_SEPARATOR . 'NewsTitle', $this->fieldLabel('Title')),
            $sectionDropdown,
            new DateField('News'. HasOneEdit::FIELD_SEPARATOR . 'NewsDate', $this->fieldLabel('NewsDate')),
            new DateField('News'. HasOneEdit::FIELD_SEPARATOR . 'ExpireDate', $this->fieldLabel('ExpireDate')),
            $newsImage,
            new HtmlEditorField('News'. HasOneEdit::FIELD_SEPARATOR . 'NewsContent', $this->fieldLabel('NewsContent'))
        );
        return $fieldlist;
    }

    /**
     * Added for create a guessing hint for Course/News relation
     *
     * @return string (formatted)
     */
    public function getTitleWithDate()
    {
        return $this->Title . ' (' . $this->getNiceExpireDate() . ')';
    }

    /**
     * Used for $summary_fields
     *
     * @return string (formatted)
     */
    public function getNiceNewsDate()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('NewsDate');
        // Use strftime to utilize locale
        return strftime('%d.%m.%Y', $dbDate->getTimestamp());
    }

    /**
     * Used for $summary_fields
     *
     * @return string (formatted)
     */
    public function getNiceExpireDate()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('ExpireDate');
        // Use strftime to utilize locale
        return strftime('%d.%m.%Y', $dbDate->getTimestamp());
    }

    /**
     * / Used for $summary_fields
     *
     * @return string
     */
    public function getOnHomepage()
    {
        $today = date("Y-m-d");
        $state = 'Nein';
        if (!$this->ExpireDate) {
            $state .= '-Ablaufdatum fehlt';
        } elseif ($this->ExpireDate < $today) {
            $state .= '-Abgelaufen';
        } else {
            $state = "Ja";
        }
        return $state;
    }

    /**
     * Get Year for Frontend
     *
     * @return string
     */
    public function getYear()
    {
        // Create a DBDate object
        $dbDate = $this->dbObject('NewsDate');
        // Use strftime to utilize locale
        return strftime('%Y', $dbDate->getTimestamp());
    }

    /**
     * Get the section for Frontend
     *
     * @return string
     */
    public function getNewsSection()
    {

        // Change to HomepageSection HomepageSection.Title'
        //return empty($this->Section) ? 'News' : $this->Section;
        return ($this->HomepageSectionID > 0) ? SectionPage::get()->byID($this->HomepageSectionID)->Title : 'News';
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        //TODO: Add translation
        $fields->fieldByName('Root.Main')->setTitle('Newsdetails');

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

        // News might have a section or "News" will be added see @method mixed getNewsSection()
        // Hide this here and move it down to another position
        $fields->removeByName('HomepageSectionID');
        $fields->removeByName('Section');

        if (Permission::check('ADMIN')) {
            //Injector::inst()->get(LoggerInterface::class)->debug('News  - getCMSFields() related course = ' . $this->Course()->ID);
            if ($this->Course()->ID) {
                $courseInfo = $this->Course()->Title .' (ID='. $this->Course()->ID . ')';
            } else {
                $courseInfo = 'Keine Verknüpfung zu einem Kurs';
            }
            $course = ReadonlyField::create('Kurs', 'Verknüpfter Kurs', $courseInfo)
                ->setDescription('Dieses Feld ist nur für Administratoren sichtbar!');
            $fields->addFieldToTab('Root.Main', $course);
        }

        $title = TextField::create('NewsTitle', $this->fieldLabel('Title'))->setDescription('Der Titel der News.');
        if (strpos($this->Title, 'Kopie von') !== false) {
            $title->setDescription('<strong>Bitte den Titel ändern und "Kopie von" entfernen!</strong>');
        }
        $fields->addFieldToTab('Root.Main', $title);

        //$fields->addFieldToTab('Root.Main', TextField::create('Section', 'Bereich')
        $sectionDropdown = DropdownField::create('HomepageSectionID', 'Bereich', SectionPage::get()->map('ID', 'Title'))
            ->setEmptyString('(Bitte auswählen)')
            ->setDescription('Bereich ist optional. Ohne Eingabe wird "News" als Bereich angezeigt.');
        $fields->addFieldToTab('Root.Main', $sectionDropdown);

        $newsDate = DateField::create('NewsDate', $this->fieldLabel('NewsDate'));
        $fields->addFieldToTab('Root.Main', $newsDate);

        $expireDate = DateField::create('ExpireDate', $this->fieldLabel('ExpireDate'));
        if (empty($this->getNiceExpireDate())) {
            $title->setDescription('<strong>Bitte Datum setzen.</strong>');
        }
        $fields->addFieldToTab('Root.Main', $expireDate);

        $newsImage = new UploadField('NewsImage', $this->fieldLabel('NewsImage'));
        $newsImage->setIsMultiUpload(false);
        $newsImage->getValidator()->allowedExtensions = ['jpeg','jpg', 'gif', 'png'];
        $newsImage->setFolderName('news');
        $fields->addFieldToTab('Root.Main', $newsImage);

        $fields->addFieldToTab(
            'Root.Main',
            HtmlEditorField::create('NewsContent', $this->fieldLabel('NewsContent'))
            ->setRows(12)
        );

        return $fields;
    }

    /**
     * Overwrite default for title
     *
     * @return void
     */
    public function getTitle()
    {
        return $this->NewsTitle;
    }

    /**
     * News will always link to a Course / Event / Workshop
     * @return string
     * TODO: Change implementation to create a link for relation to course
     * and null for non (to avoid a link for the Title in the page template)
     */
    public function Link()
    {
        $belongsToCourseID = $this->Course()->ID;
        $link = null;
        if ($belongsToCourseID) {
            Injector::inst()->get(LoggerInterface::class)->debug('News - Link() called. Related to course with ID ' . $belongsToCourseID);
        }

        if ($belongsToCourseID > 0) {
            $course = Course::get_by_id($belongsToCourseID);
            $link = $course->Link();
            //njector::inst()->get(LoggerInterface::class)->debug('News - Link() for Course = ' . $link);
        }
        return $link;
    }

    public function onBeforeWrite()
    {
        // CLONE: Check on first write action, aka "database row creation" (ID-property is not set)
        if (!$this->isInDb()) {
            if (!empty($this->NewsTitle)) {
                // Check for title duplicate // Cloning
                $existingNewsTitleList = News::get()->filter(['NewsTitle' => $this->NewsTitle]);
                if ($existingNewsTitleList->count() > 0) {
                    Injector::inst()->get(LoggerInterface::class)->debug('News - onBeforeWrite() found copy');
                    $this->NewsTitle = 'Kopie von ' . $this->NewsTitle;
                    // Set default date to today
                    $this->NewsDate = date('Y-m-d');
                    $this->ExpireDate = null;
                }
            }
        }

        // CAUTION: You are required to call the parent-function, otherwise SilverStripe will not execute the request.
        parent::onBeforeWrite();
    }

    public function validate()
    {
        $result = parent::validate();

        if (empty($this->NewsTitle)) {
            /* Attention: Initial creation of News in Course ->  HasOneEdit
             * The HasOneEdit needs to save an DataObject no matter what.
             * Validation might confuse the user here
             */
            // $result->addError('Bitte eine Schlagzeile (Titel der News) in Verknüpfte News angeben!');
        }
        /* if (empty($this->ExpireDate)) {
            $result->addFieldMessage(
                'ExpireDate',
                'Bitte eine Ablauf-Datum für die verknüpfte News angeben!',
                ValidationResult::TYPE_WARNING
            );
        } */

        return $result;
    }

    // All Permission use autogenerated Admin based permissions "CMS_ACCESS_NewsAdmin"

    /**
     * Permission canView
     *
     * @param [type] $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    // Admins only
    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_LeftAndMain', 'any', $member); //CMS_ACCESS_NewsAdmin
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }
}
