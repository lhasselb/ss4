<?php

namespace Jimev\Pages;

use \PageController;

use SilverStripe\View\Requirements;

class KontaktPageController extends PageController
{
    private static $allowed_actions = [];

    protected function init()
    {
        parent::init();
        $theme = $this->themeDir();
    }//init()
}
