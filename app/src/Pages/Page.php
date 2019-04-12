<?php

// Global (name)space!
namespace {
    use SilverStripe\ORM\DataObject;

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use SilverStripe\View\ArrayData;
    use SilverStripe\Control\HTTPRequest;

    use Jimev\Pages\HomePage;
    use Jimev\Pages\KontaktPage;

    /* Logging */
    use SilverStripe\Core\Injector\Injector;
    use Psr\Log\LoggerInterface;

    /**
     * Default Page object
     *
     * @package app
     * @subpackage Pages
     *
     */
    class Page extends SiteTree
    {
        private static $singular_name = 'Seite';
        private static $description = 'Standard-Seite';
        private static $db = [];
        private static $has_one = [];

        /**
         * Make Homepage Alerts accessible from all pages
         */
        public function getAlert()
        {
            $alerts = HomePage::get()->First();
            if ($alerts) {
                return $alerts->Alarm();
            } else {
                return null;
            }
        }

        /**
         * Make Facebook Links accessible from all pages
         */
        public function getFacebookLinks()
        {
            if (KontaktPage::get()->first()) {
                //return KontaktPage::get()->First()->FacebookLinks();
                return KontaktPage::get()->First()->Links();
            }
        }
    }
}
