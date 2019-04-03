<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

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

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        //$labels['Linkset'] = 'Sammlung';
        return $labels;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Content');
        return $fields;
    }

    public function ArchiveDates()
    {
        $list = ArrayList::create();
        $newsList = News::get();
        if ($newsList) {
            foreach ($newsList as $news) {
                $year = $news->getYear();
                if (!$list->find('Year', $year)) {
                    $list->push(ArrayData::create([
                        'Year' => $year,
                        'Link' => $this->Link('date/'.$year),
                        'NewsCount' => News::get()->filterAny('NewsDate:PartialMatch', $year)->count()
                    ]));
                }
            }
        }
        return $list;
    }
}
