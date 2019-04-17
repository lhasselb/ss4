<?php

namespace Jimev\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\Gallery;
use Jimev\Models\GalleryTag;

/**
 * Gallery administration system within the CMS
 * @package Jimev
 * @subpackage Admins
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class GalleryAdmin extends ModelAdmin
{
    private static $url_segment = 'gallerymanager';
    //TODO:private static $menu_icon = 'app/images/gallery.png';
    private static $menu_icon_class = 'modeladmin-icon-galleriesmanager';
    private static $menu_title = 'Foto-Alben';

    private static $managed_models = [
        Gallery::class => ['title' => 'Foto-Alben'],
        GalleryTag::class => ['title' => 'Bereiche']
    ];

    /**
     * Change this variable if you don't want the Import from CSV form to appear.
     * This variable can be a boolean or an array.
     * If array, you can list className you want the form to appear on. i.e. array('myClassOne','myClassTwo')
     */
    public $showImportForm = false;

    /**
     * Change this variable if you don't want the gridfield search to appear.
     * This variable can be a boolean or an array.
     * If array, you can list className you want the form to appear on. i.e. array('myClassOne','myClassTwo')
     */
    //public $showSearchForm = false;
    /**
     * @config
     */
    private static $items_per_page = 30;

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
        // Get gridfield name, should be Jimev-Models-Gallery
        $gridFieldName = $this->sanitiseClassName($this->modelClass);

        // Get gridfield
        $gridField = $form->Fields()->fieldByName($gridFieldName);

        // Get gridfield config
        $gridFieldConfig = $gridField->getConfig();

        // Set number of items per page
        $paginator = $gridFieldConfig->getComponentByType('SilverStripe\Forms\GridField\GridFieldPaginator')
            ->setItemsPerPage($this->config()->get('items_per_page'));

        // NEW: GridFieldLazyLoader added with 4.3
        $gridFieldConfig->addComponent(new GridFieldLazyLoader());

        // Remove Export and Print-Button
        $gridFieldConfig
            ->removeComponentsByType(GridFieldExportButton::class)
            ->removeComponentsByType(GridFieldPrintButton::class);

        // Remove "remove"
        $gridFieldConfig->getComponentByType('SilverStripe\Forms\GridField\GridFieldDeleteAction')
            ->setRemoveRelation(false);

        return $form;
    }
}
