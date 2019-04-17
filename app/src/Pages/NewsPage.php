<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\News;

class NewsPage extends Page
{
    private static $singular_name = 'News';
    private static $description = 'Seite News';
    //private static $icon = 'mysite/images/treffen.png';
    private static $can_be_root = true;
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
    private static $table_name = 'NewsPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        // Hide the Content field
        $fields->removeByName('Content');
        return $fields;
    }

    /**
     * Create a list for news archive navigation (by year)
     *
     * @return SilverStripe\ORM\ArrayList list;
     */
    public function ArchiveDates()
    {
        // Store the selected data
        $list = ArrayList::create();
        // Get all news DataObjects with a valid section
        $allNews = News::get()->filter(['HomepageSectionID:GreaterThan' => '0']);
        foreach ($allNews as $news) {
            // Get the year from news DataObject
            $year = $news->getYear();
            // Add year "unique"
            if (!$list->find('Year', $year)) {
                $list->push(ArrayData::create([
                    'Year' => $year,
                    'Link' => $this->Link('date/'.$year),
                    'NewsCount' => News::get()->filter([
                        'HomepageSectionID:GreaterThan' => '0',
                        'NewsDate:PartialMatch' => $year
                    ])->count()
                ]));
            }
        }

        return $list;
    }
}
