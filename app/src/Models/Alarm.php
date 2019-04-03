<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\ORM\ValidationResult;

use Jimev\Pages\HomePage;

//Add global namespace
use \DateTime;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Alarm DataObject to store alarm messages.
 *
 * @package Jimev
 * @subpackage Model
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2019 [sybeha]
 * @license see license file in modules root directory
 */
class Alarm extends DataObject
{
    private static $singular_name = 'Alarm';
    private static $plural_name = 'Alarme';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'Alarm';

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'StartDate' => 'DBDatetime',
        'EndDate' => 'DBDatetime',
        'Title' => 'Varchar',
        'Meldung' => 'HTMLText()'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'Homepage' => HomePage::class
    ];

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $start= new DateTime();
        $this->StartDate = $start->format('Y-m-d H:i:s');//date('Y-m-d');
        $this->EndDate = $start->modify('+2 week')->format('Y-m-d H:i:s');
        parent::populateDefaults();
    }

    private static $casting = [
        'StartYear' => 'Int',
        'StartMonth' => 'Int',
        'StartDay' => 'Int',
        'StartHour' => 'Int',
        'StartMinute' => 'Int',
        'EndYear' => 'Int',
        'EndMonth' => 'Int',
        'EndDay' => 'Int',
        'EndHour' => 'Int',
        'EndMinute' => 'Int',
    ];

    /**
     * Get the Year
     * Important the frontend requires digits like 2019
     *
     * @return String
     */
    public function getStartYear()
    {
        return $this->obj('StartDate')->Year();
    }

    /**
     * Get the Month
     * Important the frontend requires digits like 03 for march
     *
     * @return String
     */
    public function getStartMonth()
    {

        /* $smonth = date('m', $this->obj('StartDate')->getTimestamp());
        Injector::inst()
        ->get(LoggerInterface::class)
        ->debug('Alarm - getStartMonth() start month = ' . $smonth . ' ' . $this->obj('StartDate')->Nice()
        . ' ' . $this->obj('StartDate')->getTimestamp());
        */
        return date('m', $this->obj('StartDate')->getTimestamp());
    }

    /**
     * Get the Day
     * Important the frontend requires digits like 01 for the first of a month
     *
     * @return String
     */
    public function getStartDay()
    {
        return date('d', $this->obj('StartDate')->getTimestamp());
    }

    /**
     * Get the Hour
     * Important the frontend requires digits like 01 for the first hour of a day
     *
     * @return String
     */
    public function getStartHour()
    {
        return date('H', $this->obj('StartDate')->getTimestamp());
    }

    /**
     * Get the Minute
     * Important the frontend requires digits like 01 for the first minute of an hour
     *
     * @return String
     */
    public function getStartMinute()
    {
        return date('i', $this->obj('StartDate')->getTimestamp());
    }

    /**
     * Get the Year
     * Important the frontend requires digits like 2019
     *
     * @return String
     */
    public function getEndYear()
    {
        return $this->obj('EndDate')->Year();
    }

    /**
     * Get the Month
     * Important the frontend requires digits like 03 for march
     *
     * @return String
     */
    public function getEndMonth()
    {
        return date('m', $this->obj('EndDate')->getTimestamp());
    }

    /**
     * Get the Day
     * Important the frontend requires digits like 01 for the first of a month
     *
     * @return String
     */
    public function getEndDay()
    {
        return date('d', $this->obj('EndDate')->getTimestamp());
    }

    /**
     * Get the Hour
     * Important the frontend requires digits like 01 for the first hour of a day
     *
     * @return String
     */
    public function getEndHour()
    {
        return date('H', $this->obj('EndDate')->getTimestamp());
    }

    /**
     * Get the Minute
     * Important the frontend requires digits like 01 for the first minute of an hour
     *
     * @return String
     */
    public function getEndMinute()
    {
        return date('i', $this->obj('EndDate')->getTimestamp());
    }

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Titel',
        'StartDate.FormatFromSettings' => 'Anzeigen ab',
        'EndDate.FormatFromSettings' => 'Anzeigen bis'
    ];

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Title'];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = 'Titel';
        $labels['Meldung'] = 'Meldungen';
        $labels['StartDate'] = 'Anzeigen ab';
        $labels['EndDate'] = 'Anzeigen bis';
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

        $fields->removeByName('HomepageID');

        //HtmlEditorConfig::set_active_identifier('basic');

        $alerts = HtmlEditorField::create('Meldung', 'Meldungen');
        $fields->addFieldToTab('Root.Main', $alerts);

        //$datetime = new DateTime('now', new DateTimeZone('Europe/Berlin'));
        //$now = $datetime->format('d.m.Y H:i');
        //Injector::inst()
        //->get(LoggerInterface::class)
        //->debug('Alarm - getCMSFields() now = ' . $now . ' startdate=' . $this->StartDate);

        $startDate = DatetimeField::create('StartDate', 'Start');
        $fields->addFieldToTab('Root.Main', $startDate);

        $endDate = DatetimeField::create('EndDate', 'Ende');
        $fields->addFieldToTab('Root.Main', $endDate);


        return $fields;
    }

    /**
     * Validation needs to be disabled for migration
     *
     * @return void
     */
    public function validate()
    {
        $result = parent::validate();

        if (empty($this->Title)) {
            //$result->addError('Bitte einen Titel angeben!', ValidationResult::TYPE_ERROR);
            $result->addFieldMessage(
                'Title',
                'Bitte einen Titel angeben!',
                ValidationResult::TYPE_ERROR
            );
            return false;
        }
        if (empty($this->Meldung)) {
            //$result->addError('Bitte eine Meldung angeben!', ValidationResult::TYPE_ERROR);
            $result->addFieldMessage(
                'Meldung',
                'Bitte eine Meldung angeben!',
                ValidationResult::TYPE_ERROR
            );
        }
        return $result;
    }
}
