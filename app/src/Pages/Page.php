<?php

// Global (name)space!
namespace {
    use SilverStripe\CMS\Model\SiteTree;
    /* Logging */
    use SilverStripe\Core\Injector\Injector;
    use Psr\Log\LoggerInterface;

    use Jimev\Pages\HomePage;
    use Jimev\Pages\KontaktPage;

    /**
     * Default Page object
     *
     * @package Jimev
     * @subpackage Model
     * @author Lars Hasselbach <lars.hasselbach@gmail.com>
     * @since 15.03.2016
     * @copyright 2016 [sybeha]
     * @license see license file in modules root directory
     */
    class Page extends SiteTree
    {
        private static $singular_name = 'Seite';
        private static $description = 'Standard-Seite';

        /**
         * Make Homepage Alerts accessible from all pages
         * @return SilverStripe\ORM\ManyManyList list;
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
         * TODO: DataObject FacebookLinks will be removed after migration
         * @return SilverStripe\ORM\ManyManyList list;
         */
        public function getFacebookLinks()
        {
            if (KontaktPage::get()->first()) {
                //return KontaktPage::get()->First()->FacebookLinks();
                return KontaktPage::get()->First()->Links();
            }
        }

        /**
         * Generate the copyright string for the pages
         *
         * @param string $startYear
         * @param string $separator
         * @return string
         */
        public function Copyright($startYear = "2007", $separator = "-")
        {
            $currentYear = date('Y');
            if (!empty($startYear)) {
                $output = ($startYear>=$currentYear ? $currentYear : $startYear.$separator.$currentYear);
            } else {
                $output = $currentYear;
            }
            return $output;
        }
    }
}
