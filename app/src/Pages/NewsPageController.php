<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\HTTPRequest;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\News;

class NewsPageController extends PageController
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
    private static $allowed_actions = ['date'];

    protected $newsList;
    protected $selectedYear;

    protected function init()
    {
        parent::init();
        // Only with section set
        $this->newsList = News::get()->filter(['HomepageSectionID:GreaterThan' => '0'])
            ->sort('NewsDate DESC');
        //Injector::inst()->get(LoggerInterface::class)->debug('NewsPageController - init() total = ' . $this->newsList->count());
    }

    /**
     *  Template getter for selectedYear
     *
     * @return string
     */
    public function SelectedYear()
    {
        return $this->selectedYear;
    }

    //TODO:Check return value // is this used ?
    public function date(HTTPRequest $httpRequest)
    {
        // Fetch the ID from the URL
        $year = $httpRequest->param('ID');
        $this->selectedYear = $year;
        if (!$year) {
            return $this->httpError(404, 'Das gewünschte Jahr existiert nicht.');
        } else {
            $this->newsList = $this->newsList->filter([
                'HomepageSectionID:GreaterThan' => '0',
                'NewsDate:PartialMatch' => $year
            ]);
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
