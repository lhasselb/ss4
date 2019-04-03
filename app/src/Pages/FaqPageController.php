<?php

namespace Jimev\Pages;

use \PageController;

use SilverStripe\View\Requirements;

class FaqPageController extends PageController
{
    private static $allowed_actions = [];

    protected function init()
    {
        parent::init();
        // Moved to requirement.yml
    }
}
