<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\ORM\ArrayList;

use Jimev\Models\Gallery;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class FotosPage extends Page
{
    private static $singular_name = 'Fotos';
    private static $description = 'Seite für Fotos';
    private static $icon = 'resources/app/client/dist/img/fotos.png';
    private static $can_be_root = true;
    private static $allowed_children = 'none';

    private static $many_many = [
        'Galleries' => Gallery::class
    ];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'FotosPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $galleries = CheckboxSetField::create('Galleries', 'Zeige Alben', Gallery::get()->map());
        $fields->addFieldToTab('Root.Main', $galleries, 'Content');
        return $fields;
    }

    /**
     * Get the used Tags from Gallery->GalleryTags() (remove duplicates)
     * Comparing objects requires a __toString() method
     * Only used ones $this->Galleries() not all Gallery::get()
     * @see Gallery::__toString()
     *
     * @return ArrayList
     */
    public function getFotosPageTags()
    {
        $usedtags = [];
        foreach ($this->Galleries() as $gallery) {
            //$currentTagList = $gallery->GalleryTags();
            foreach ($gallery->GalleryTags() as $tag) {
                // Add GalleryTag object to array
                array_push($usedtags, $tag);
            }
        }
        return new ArrayList(array_unique($usedtags));
    }

    /**
     * Get the used Years from Gallery->AlbumDate (remove duplicates)
     * Comparing objects requires a __toString() method
     * Only used ones $this->Galleries() not all Gallery::get()
     * @see Gallery::__toString()
     *
     * @return ArrayList sorted by Date
     */
    public function getFotosPageYears()
    {
        $usedYears = [];
        foreach ($this->Galleries() as $gallery) {
            array_push($usedYears, $gallery);
        }

        // Limit to used ones
        // Careful Gallery::class->_toString() returns years only!
        $list = new ArrayList(array_unique($usedYears));
        return $list;
    }
}
