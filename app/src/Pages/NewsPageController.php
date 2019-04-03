<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\HTTPRequest;

use Jimev\Models\News;

class NewsPageController extends PageController
{
    private static $allowed_actions = ['date'];
    protected $newsList;
    protected $selectedYear;

    protected function init()
    {
        parent::init();
        $this->newsList = News::get()->filterAny([
            'ClassName' => 'News',
            'HomepageSectionID:GreaterThan' => '0'
        ])->sort('NewsDate DESC');
    }//init()

    public function SelectedYear()
    {
        return $this->selectedYear;
    }

    //TODO:Check return value // is this used ?
    public function date(HTTPRequest $r)
    {
        //log !!
        $year = $r->param('ID');
        $this->selectedYear = $year;
        if (!$year) {
            return $this->httpError(404);
        }
        if ($year == 'all') {
            $this->newsList = News::get()->filterAny([
                'ClassName' => 'News',
                'HomepageSectionID:GreaterThan' => '0'
            ])->sort('NewsDate DESC');
        } else {
            $this->newsList = $this->newsList->filterAny('NewsDate:PartialMatch', $year);
        }
        return [];
    }

    /**
     * Create a news items list
     * @return PaginatedList list containing news items
     */
    public function PaginatedLatestNews($num = 5)
    {
        return PaginatedList::create($this->newsList, $this->getRequest())->setPageLength($num);
    }
}
