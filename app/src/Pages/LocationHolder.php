<?php

namespace Jimev\Pages;

use \Page;

class LocationHolder extends Page
{
    private static $singular_name = 'Jongliertreffen';
    private static $description = 'Seite zum Gruppieren von Treffpunkten.';
    private static $icon = 'resources/app/client/dist/img/treffen.png';
    private static $can_be_root = true;
    private static $allowed_children = [LocationPage::class];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'LocationHolder';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
