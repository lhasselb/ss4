<?PHP
// Global (name)space!
namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\View\SSViewer;
    use SilverStripe\Forms\Form;
    use SilverStripe\Forms\EMailField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TextAreaField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\RequiredFields;
    use SilverStripe\View\Requirements;
    use SilverStripe\Control\Email\Email;
    use SilverStripe\Control\Director;
    /* Logging */
    use SilverStripe\Core\Injector\Injector;
    use Psr\Log\LoggerInterface;

    class PageController extends ContentController
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
            // You can include any CSS or JS required by your project here.
            // See: http://doc.silverstripe.org/framework/en/reference/requirements
        }

        /**
         * Information about dev environment type
         * @return boolean true if environment type equals dev
         */
        public function isDev()
        {
            return Director::isDev();
        }

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
