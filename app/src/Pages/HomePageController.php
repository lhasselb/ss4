<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\News;

class HomePageController extends PageController
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = [];

    protected function init()
    {
        parent::init();
        //Requirements moved to app/client/src/js/Jimev.Pages.HomePageController.js using webpack
    }

    /**
     * Create a paginated news items list
     * @return PaginatedList list containing news items
     */
    public function PaginatedLatestNews($num = 10)
    {
        $today = date('Y-m-d');

        $list = News::get()
            // Get all with a valid HomepageSectionID
            ->filterAny(['HomepageSectionID:GreaterThan' => '0'])
            // Exclude the expited ones
            ->filter('ExpireDate:GreaterThan', $today);

        /*
        foreach ($list as $news) {
            Injector::inst()
            ->get(LoggerInterface::class)
            ->debug('HomePageController - PaginatedLatestNews() news = ' . $news->Title . ' link=' . $news->Link());
        }
        */

        return PaginatedList::create($list, $this->getRequest())->setPageLength($num);
    }

    /**
     * Create a news items list
     * @return DataList list containing news items
     */
    public function LatestNews()
    {
        $today = date('Y-m-d');

        $list = News::get()
            // Get all with a valid HomepageSectionID
            ->filterAny(['HomepageSectionID:GreaterThan' => '0'])
            // Exclude the expited ones
            ->filter('ExpireDate:GreaterThan', $today);

        /*
        foreach ($list as $news) {
            Injector::inst()
            ->get(LoggerInterface::class)
            ->debug('HomePageController - PaginatedLatestNews() news = ' . $news->Title . ' link=' . $news->Link());
        }
        */

        return $list;
    }
}
