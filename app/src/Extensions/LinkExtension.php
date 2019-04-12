<?php

namespace Jimev\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;

/**
 * Add an extension to the Link::class see https://github.com/gorriecoe/silverstripe-link
 * to store an description.
 *
 * @package Jimev
 * @subpackage Model
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 08.04.2019
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class LinkExtension extends DataExtension
{
    private static $db = [
        'Description' => 'Varchar(255)'
    ];

    /**
     * Use extension hook for Link:class->getCMSFields()
     *
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Add the field
        $fields->push(new TextField('Description', 'Beschreibung'));
    }
}
