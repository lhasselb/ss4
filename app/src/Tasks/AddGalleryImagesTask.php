<?php

namespace Jimev\Tasks;

use Dynamic\ClassNameUpdate\MappingObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;

use Jimev\Models\Gallery;
use Jimev\Models\GalleryImage;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Class DatabaseClassNameUpdateTask
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class AddGalleryImagesTask extends BuildTask
{
    /**
     * @var string
     */
    private static $segment = 'add-gallery-images-task';

    /**
     * @var string
     */
    protected $title = 'Add Gallery Images Task (JIM)';

    /**
     * @var string
     */
    protected $description = "Add images from folder to Galleries using that folder as GalleryImage";

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request)
    {
        $this->updateGalleries();

        echo "Galleries have been updated\n";
    }

    /**
     * @param $mapping
     */
    protected function updateGalleries()
    {
        $galleries = Gallery::get();
        foreach ($galleries as $gallery) {
            $albumPathName = $gallery->ImageFolder;
            if (strstr($albumPathName, '/')) {
                $folderName = substr($albumPathName, strrpos($albumPathName, '/') + 1);
            }
            $folder = Folder::get()->filter('Name', $folderName)->first();
            $total = $gallery->GalleryImages()->count();
            if (!$folder) {
                Injector::inst()->get(LoggerInterface::class)
                ->debug('AddGalleryImagesTask - updateGalleries no folder found for ' . $folderName);
            }

            // Do we have children
            if ($folder && $folder->hasChildren() && $total == 0) {
                // Yes - Iterate over all children
                foreach ($this->yieldImage($folder) as $image) {
                    // Is this an image
                    if ($image->getIsImage()) {
                        $this->updateGalleryImage($gallery, $image);
                    }
                }
            }
        }
    }

    /**
     * @param $record
     * @param $updatedClassName
     */
    protected function updateGalleryImage($currentGallery, $currentImage)
    {
        set_time_limit(0);
        // Add image ID to GalleryImages
        //Injector::inst()->get(LoggerInterface::class)->debug('Gallery - image ID  = ' . $image->ID);
        $gImage = GalleryImage::create();
        $gImage->Image = $currentImage;
        $gImage->Gallery = $currentGallery;
        $gImage->Title = $currentImage->Title;
        $currentGallery->GalleryImages()->add($gImage->write());
    }

    /**
     * @param $singleton
     * @param $legacyName
     * @return \Generator
     */
    public function yieldImage($folder)
    {
        foreach ($folder->myChildren() as $image) {
            yield $image;
        }
    }
}
