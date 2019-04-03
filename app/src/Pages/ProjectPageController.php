<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\View\Requirements;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use Jimev\Models\Project;

class ProjectPageController extends PageController
{
    private static $allowed_actions = ['projekt'];

    protected function init()
    {
        parent::init();
        $theme = $this->themeDir();
        //Requirements::javascript('mysite/javascript/ProjectPage.js');
    }//init()

    public function projekt(HTTPRequest $request)
    {
        //SS_Log::log('ID='.$request->param('ID'),SS_Log::WARN);
        //$project = Project::get_by_id('Project',$request->param('ID'));
        //SS_Log::log('ID='.Convert::raw2sql($request->param('ID')),SS_Log::WARN);
        $project = Project::get_by_url_segment(Convert::raw2sql($request->param('ID')));
        //SS_Log::log($project->ProjectTitle,SS_Log::WARN);
        if (!$project) {
            return $this->httpError(404, 'Das gewünschte Projekt existiert nicht.');
        }
        return ['Project' => $project];
    }
}
