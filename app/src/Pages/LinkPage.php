<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

use Jimev\Models\LinkSet;

class LinkPage extends Page
{
    private static $singular_name = 'Linksammlung';
    private static $description = 'Seite für Links';
    //private static $icon = 'mysite/images/treffen.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $db = [];

    private static $has_many = ['Linkset' => LinkSet::class];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'LinkPage';

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Linkset'] = 'Sammlung';
        return $labels;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        /*$fields->addFieldToTab('Root.Main',
            HtmlEditorField::create('Content', $this->fieldLabel('Content'))
            ->setRows(3)
        );*/
        $fields->removeByName('Content');
        $config = GridFieldConfig_RecordEditor::create();
        //$config->removeComponentsByType($config->getComponentByType('GridFieldAddNewButton'));
        $gridfield = GridField::create("Linkset", "Link-Sammlungen", $this->Linkset(), $config);
        //$fields->addFieldToTab('Root.Link-Sammlungen', $gridfield);
        $fields->addFieldToTab('Root.Main', $gridfield, 'Metadata');

        return $fields;
    }
}
