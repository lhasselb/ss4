<?php

namespace Jimev\Pages;

use \PageController;

use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;
use Jimev\Models\News;

use Site\Templates\DeferedRequirements;

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
        //Requireents moved to app/client/src/js/Jimev.Pages.HomePageController.js using webpack
    }

    /**
     * Create a news items list
     * @return PaginatedList list containing news items
     */
    public function PaginatedLatestNews($num = 10)
    {
        $today = date('Y-m-d');
        $start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
        $list = News::get()
        ->filterAny([
            //'ClassName' => 'News',
            'HomepageSectionID:GreaterThan' => '0'
        ])
        ->filter('ExpireDate:GreaterThan', $today);
        //->exclude('ExpireDate:LessThan',$today);
        //->sort('NewsDate','DESC');
        foreach ($list as $news) {
            //SS_Log::log('ExpireDate'.$news->ExpireDate,SS_Log::WARN);
        }

        return new PaginatedList($list, $this->getRequest());
    }
}
