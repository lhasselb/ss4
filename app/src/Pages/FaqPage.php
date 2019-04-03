<?php

namespace Jimev\Pages;

use \Page;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\View\Requirements;

use Jimev\Models\Faq;
use Jimev\Models\FaqTag;

class FaqPage extends Page
{
    private static $singular_name = 'FAQ';
    private static $description = 'Seite für FAQ';
    //private static $icon = 'mysite/images/treffen.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $has_many = ['FAQS' => Faq::class];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'FaqPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $config = GridFieldConfig_RecordEditor::create();
        $gridfield = GridField::create('FAQS', 'Fragen und Antworten', $this->FAQS(), $config);
        $fields->addFieldToTab('Root.Fragen und Antworten', $gridfield);

        $gridfield = GridField::create('Tags', 'Bereiche', FAQTag::get(), GridFieldConfig_RecordEditor::create());
        $fields->addFieldToTab('Root.Bereiche', $gridfield);


        return $fields;
    }

    public function Sections()
    {
        return FAQTag::get();
    }
}
