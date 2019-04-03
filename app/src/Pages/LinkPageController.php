<?php

namespace Jimev\Controllers;

use \PageController;

use SilverStripe\View\Requirements;

class LinkPageController extends PageController
{
    private static $allowed_actions = [];

    protected function init()
    {
        parent::init();
    }//init()
}
