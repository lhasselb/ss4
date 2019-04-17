<?php

namespace Jimev\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
//NEW: Added with 4.3
use SilverStripe\Forms\GridField\GridFieldLazyLoader;
use SilverStripe\View\Requirements;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
// See https://github.com/jinjie/duplicate-dataobject
use SwiftDevLabs\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

use Jimev\Models\Course;

/**
 * Course administration system within the CMS
 * @package Jimev
 * @subpackage Admins
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class CourseAdmin extends ModelAdmin
{
    private static $url_segment = 'coursemanager';
    //TODO:private static $menu_icon = 'app/images/workshops.png';
    private static $menu_icon_class = 'modeladmin-icon-coursesmanager';
    private static $menu_title = 'Workshops und Kurse';

    private static $managed_models = [
        Course::class => ['title' => 'Workshops und Kurse']
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
         * $gridFieldName is generated from the ModelClass, e.g. if the Class 'Course'
         * is managed by this ModelAdmin, the GridField for it will also be named 'Course'
         */
        // Get gridfield name, should be Jimev-Models-Course
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

        // Add duplicate action
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());

        // Remove "remove"
        $gridFieldConfig->getComponentByType('SilverStripe\Forms\GridField\GridFieldDeleteAction')
            ->setRemoveRelation(false);

        return $form;
    }

    public function init()
    {
        parent::init();
        // Required to copy title for hidden hasOne
        Requirements::javascript('app/client/dist/js/courseadmin.js');
    }
}
