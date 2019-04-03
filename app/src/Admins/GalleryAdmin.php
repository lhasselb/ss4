<?php

namespace Jimev\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;

//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;

/**
 * Permissions
 * Each new ModelAdmin subclass creates its' own permission code,
 * for the example above this would be CMS_ACCESS_GalleryAdmin.
 */
/* Permissions */
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;

use Jimev\Models\Gallery;
use Jimev\Models\GalleryTag;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Gallery administration system within the CMS
 *
 * @package app
 * @subpackage admins
 */
class GalleryAdmin extends ModelAdmin
{
    private static $url_segment = 'gallerymanager';
    //TODO:private static $menu_icon = 'app/images/gallery.png';
    private static $menu_icon_class = 'modeladmin-icon-galleriesmanager';
    private static $menu_title = 'Foto-Alben';

    private static $managed_models = [
        Gallery::class => ['title' => 'Foto-Alben'],
        GalleryTag::class => ['title' => 'Foto-Alben-Tags']
    ];

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
        /*
         *  $gridFieldName is generated from the ModelClass, eg if the Class 'Gallery'
         *  is managed by this ModelAdmin, the GridField for it will also be named 'Gallery'
         */
        $gridFieldName = $this->sanitiseClassName($this->modelClass);
        //$gridField = $form->Fields()->fieldByName($gridFieldName);
        $gridFieldConfig = $form->Fields()->fieldByName($gridFieldName)->getConfig();
        $gridFieldConfig->removeComponentsByType(GridFieldExportButton::class);
        $gridFieldConfig->removeComponentsByType(GridFieldPrintButton::class);
        //NEW: Added with 4.3
        $gridFieldConfig->addComponent(new GridFieldLazyLoader());
        $gridFieldConfig->getComponentByType('SilverStripe\Forms\GridField\GridFieldDeleteAction')
        ->setRemoveRelation(false);

        return $form;
    }

    public function init()
    {
        parent::init();
    }
}
