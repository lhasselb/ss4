<?php

namespace Jimev\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\Project;
use Jimev\Models\ProjectTag;

/**
 * Project administration system within the CMS
 * @package Jimev
 * @subpackage Admins
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class ProjectAdmin extends ModelAdmin
{

    private static $url_segment = 'projectmanager';
    //TODO:private static $menu_icon = 'app/images/projects.png';
    private static $menu_icon_class = 'modeladmin-icon-projectsmanager';
    private static $menu_title = 'Projekte';

    private static $managed_models = [
        Project::class,
        ProjectTag::class
    ];

    public $showImportForm = false;

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
         *  $gridFieldName is generated from the ModelClass, eg if the Class 'Project'
         *  is managed by this ModelAdmin, the GridField for it will also be named 'Project'
         */
        // Get gridfield name, should be Jimev-Models-Project
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
