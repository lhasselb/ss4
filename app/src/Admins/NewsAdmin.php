<?php

namespace Jimev\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;

use Jimev\Models\News;

// See https://github.com/jinjie/duplicate-dataobject
use SwiftDevLabs\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

/**
 * Permissions
 * Each new ModelAdmin subclass creates its' own permission code,
 * for the example above this would be CMS_ACCESS_NewsAdmin.
 */
use SilverStripe\Security\Permission;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * New administration system within the CMS
 *
 * News are sorted by date
 * @package app
 * @subpackage admins
 */
class NewsAdmin extends ModelAdmin
{
    private static $url_segment = 'newsmanager';
    // TODO:private static $menu_icon = 'app/images/news.png';
    private static $menu_icon_class = 'modeladmin-icon-newsmanager';
    private static $menu_title = 'News';

    private static $managed_models = [News::class];

    public $showImportForm = false;

    /**
     * @config
     */
    private static $items_per_page = '20';

    /**
     *  Prepare search
     */
    public function getSearchContext()
    {
        $context = parent::getSearchContext();
        return $context;
    }

    /**
     * Get a result list
     * The results list are retrieved from SearchContext::getResults(), based on the parameters passed through
     * the search form. If no search parameters are given, the results will show every record.
     * Results are a DataList instance, so can
     * be customized by additional SQL filters, joins.
     */
    public function getList()
    {
        // Get all including inactive
        $list = parent::getList();
        return $list;
    }

    /**
     * Alter look & feel for EditForm
     * To alter how the results are displayed (via GridField),
     * you can also overload the getEditForm() method.
     * For example, to add or remove a new component.
     */
    public function getEditForm($id = null, $fields = null)
    {

        $form = parent::getEditForm($id, $fields);

        // Only required to distinguish between classes
        /*
        foreach ($form->Fields() as $field) {
            Injector::inst()->get(LoggerInterface::class)
                ->debug('NewsAdmin - getEditForm() field = ' . get_class($field) );
            if ($field instanceof SilverStripe\Forms\GridField\GridField ) {
                foreach ($field->getComponents() as $c_name) {
                    Injector::inst()->get(LoggerInterface::class)
                        ->debug(get_class(&this) . ' - getEditForm() component = ' . get_class ($c_name) );
                }
            }
        };
        */

        /**
         * $gridFieldName is generated from the ModelClass, e.g. if the Class 'News'
         * is managed by this ModelAdmin, the GridField for it will also be named 'News'
         */
        $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass))->getConfig()
            ->removeComponentsByType(GridFieldExportButton::class)
            ->removeComponentsByType(GridFieldPrintButton::class)
            ->addComponent(new GridFieldDuplicateAction())
            // NEW: GridFieldLazyLoader added with 4.3
            ->addComponent(new GridFieldLazyLoader())
            ->getComponentByType('SilverStripe\Forms\GridField\GridFieldDeleteAction')->setRemoveRelation(false);
            //->removeComponentsByType('GridFieldDeleteAction');
            //->addComponent(new GridFieldDeleteAction());
            //Injector::inst()->get(LoggerInterface::class)
            //->debug('NewsAdmin - getEditForm()' . ' gridField=' . $gridField);
            //Injector::inst()->get(LoggerInterface::class)
            //->debug(get_class(&this) . ' - getEditForm()' . ' CMS_ACCESS_NewsAdmin ? '
            //. Permission::check('CMS_ACCESS_NewsAdmin'));

        return $form;
    }

    public function init()
    {
        parent::init();
    }
}
