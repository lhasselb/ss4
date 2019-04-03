<?php

namespace Jimev\Pages;

use \Page;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

use Jimev\Models\FacebookLink;

class KontaktPage extends Page
{
    private static $singular_name = 'Kontakt';
    private static $description = 'Seite für Kontakt';
    //private static $icon = 'mysite/images/treffen.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $db = [];

    private static $has_many = ['FacebookLinks' => FacebookLink::class . '.KontaktPage'];
    
    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'KontaktPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $facebookGruppen = GridField::create(
            'FacebookLinks',
            'Facebook Gruppen',
            $this->FacebookLinks(),
            GridFieldConfig_RecordEditor::create()
        );
        $fields->addFieldToTab('Root.Facebook', $facebookGruppen);
        return $fields;
    }
}
