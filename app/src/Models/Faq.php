<?php

namespace Jimev\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Security\Permission;

use Jimev\Pages\FaqPage;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use SilverStripe\TagField\TagField;

class Faq extends DataObject
{
    private static $singular_name = 'Frage und Antwort';
    private static $plural_name = 'Fragen und Antworten';

    private static $db = [
       'Question' => 'Varchar(255)',
       'Answer' => 'HTMLText'
    ];

    private static $has_one = [
        'FaqPage' => FaqPage::class
    ];

    private static $many_many = [
        'FAQTags' => FaqTag::class
    ];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'FAQ';

    private static $summary_fields = [
        'Question'=> 'Frage',
        'Tags' => 'Bereich(e)'
    ];

    /**
     * Hint: Use SortOrder with 3rd party module for DragAndDrop sorting
     * Defines a default sorting (e.g. within gridfield)
     * @var string
     */
    private static $default_sort = 'LastEdited DESC';

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = ['Title'];

    public function Tags()
    {
        $tags = [];
        foreach ($this->FAQTags() as $tag) {
            array_push($tags, $tag->Title);
        }
        return implode(',', $tags);
    }

    public function getTitle()
    {
        return $this->Question;
    }

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Question'] = 'Frage';
        $labels['Answer'] = 'Antwort';
        return $labels;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('FaqPageID');
        $fields->removeByName('FAQTags');
        $question = TextAreaField::create('Question', 'Frage')->setRows(1);
        $answer = HtmlEditorField::create('Answer', 'Antwort');

        // Injector::inst()->get(LoggerInterface::class)->debug('FAQ - getCMSFields() ' . $answer . ' ');

        $tag = TagField::create(
            'FAQTags',
            'FAQ Bereich(e)',
            FAQTag::get(),
            $this->FAQTags()
        )
            ->setShouldLazyLoad(true) // tags should be lazy loaded
            ->setCanCreate(true);     // new tag DataObjects can be created

        $fields->addFieldsToTab('Root.Main', [$question,$answer,$tag]);

        return $fields;
    }

    /**
     * Permission canView
     * The DataObject class displayed must define a
     * canView() method that returns a boolean on whether
     * the user can view this record.
     * @param [type] $member
     * @return boolean
     */
    public function canView($member = null)
    {
        if (Permission::checkMember($member, 'CMS_ACCESS')) {
            //user can access the CMS
            return true;
        } else {
            return false;
        }
    }
}
