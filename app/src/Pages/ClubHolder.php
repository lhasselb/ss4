<?php

namespace Jimev\Pages;

use \Page;

/**
 * ClubHolder Page object
 *
 * @package mysite
 * @subpackage pages
 *
 */
class ClubHolder extends Page
{
    private static $singular_name = 'Verein';
    private static $description = 'Seite zum Gruppieren von Vereinsseiten.';
    private static $icon = 'resources/app/client/dist/img/club.png';
    private static $can_be_root = true;
    private static $allowed_children =
    [
        '*Page',
        'SYBEHA\Clubmaster\Pages\EnrollPage',
        'Jimev\Pages\ContactAddressPage'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
