<?php

namespace Jimev\Pages;

use \Page;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\Tab;

use Jimev\Models\Vorstand;

/**
 * ContactAddressPage object
 *
 * @package app
 * @subpackage Pages
 *
 */
class ContactAddressPage extends Page
{
    private static $singular_name = 'Adresse und Vorstand';
    private static $description = 'Seite für Adresse und Vorstand';
    //private static $icon = '';
    private static $can_be_root = false;
    private static $allowed_children = 'none';

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'ContactAddressPage';

    private static $db = [
       'ManagementTitle' => 'Varchar(255)',
       'AddressTitle' => 'Varchar(255)',
    ];

    private static $has_many = [
        'Directors' => Vorstand::class
    ];

    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        $this->ManagementTitle = 'Der Vorstand des Vereins Jim e.V.';
        $this->AddressTitle = 'Anschrift';
        parent::populateDefaults();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.Main')->setTitle('Anschrift');

        $addressTitle = TextField::create('AddressTitle', 'Anschrift-Ü̱berschrift');
        $address = HtmlEditorField::create('Content', 'Anschrift');

        $fields->addFieldsToTab('Root.Main', [$addressTitle, $address], 'Metadata');

        $fields->insertBefore(new Tab('Vorstand', 'Vorstand'), 'Dependent');

        $managementTitle = TextField::create('ManagementTitle', 'Vorstand-Ü̱berschrift');
        $fields->addFieldToTab('Root.Vorstand', $managementTitle);

        // Add Vorstände
        $directors = GridField::create(
            'Directors',
            'Vorstand',
            $this->Directors(),
            GridFieldConfig_RecordEditor::create()
        );
        $fields->addFieldToTab('Root.Vorstand', $directors);

        return $fields;
    }
}
