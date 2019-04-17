<?php

namespace Jimev\Pages;

use \PageController;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use Jimev\Models\Project;

class ProjectPageController extends PageController
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
    private static $allowed_actions = ['projekt'];

    protected function init()
    {
        parent::init();
        //Requirements moved to app/client/src/js/Jimev.Pages.ProjectPageController.js using webpack
    }

    public function projekt(HTTPRequest $request)
    {

        $project = Project::get_by_url_segment(Convert::raw2sql($request->param('ID')));
        if (!$project) {
            return $this->httpError(404, 'Das gewünschte Projekt existiert nicht.');
        }
        return ['Project' => $project];
    }
}
