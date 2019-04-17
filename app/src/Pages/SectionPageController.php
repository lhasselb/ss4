<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Control\HTTPRequest;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\Course;

class SectionPageController extends PageController
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
    private static $allowed_actions = ['kurs'];

    protected function init()
    {
        parent::init();
    }

    public function kurs(HTTPRequest $request)
    {
        $course = Course::get_by_url_segment(Convert::raw2sql($request->param('ID')));
        if (!$course) {
            return $this->httpError(404, 'Der gewünschte Kurs existiert nicht.');
        }
        return ['Course' => $course];
    }

    /**
     * Create a course items list
     * @return PaginatedList list containing course items
     */
    public function PaginatedCourses($num = 5)
    {
        return $courses = PaginatedList::create($this->Courses(), $this->getRequest())->setPageLength($num);
    }

    // TODO: Move to extension
    public function Breadcrumbs($maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false)
    {
        $pages = $this->getBreadcrumbItems($maxDepth, $stopAtPageType, $showHidden);
        $template = new SSViewer('BreadcrumbsTemplate');
        return $template->process($this->customise(new ArrayData([
            "Pages" => $pages,
            "Unlinked" => $unlinked
        ])));
    }

    // TODO: Move to extension
    public function getBreadcrumbItems($maxDepth = 20, $stopAtPageType = false, $showHidden = false)
    {
        $page = $this;
        $pages = [];
        if ($course = $this->getCurrentCourse()) {
            array_push($pages, $this->getCurrentCourse());
        }
        while ($page
            && (!$maxDepth || count($pages) < $maxDepth)
            && (!$stopAtPageType || $page->ClassName != $stopAtPageType)
        ) {
            if ($showHidden || $page->ShowInMenus || ($page->ID == $this->ID)) {
                $pages[] = $page;
            }
            $page = $page->Parent;
        }
        return new ArrayList(array_reverse($pages));
    }

    // TODO: Move to extension
    public function getCurrentCourse()
    {
        $Params = $this->getURLParams();
        $URLSegment = Convert::raw2sql($Params['ID']);

        if ($URLSegment && $course = DataObject::get_one(Course::class, "URLSegment = '" . $URLSegment . "'")) {
            return $course;
        }
    }
}
