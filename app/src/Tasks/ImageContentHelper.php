<?php

namespace Jimev\Tasks;

use Dynamic\ClassNameUpdate\MappingObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;
use SilverStripe\AssetAdmin\Controller\AssetAdmin;
use SilverStripe\Assets\File;
use SilverStripe\Assets\ImageBackendFactory;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\InjectionCreator;
use SilverStripe\Core\Injector\Injector;

/* Logging */
use Psr\Log\LoggerInterface;

class ImageContentHelper
{
    use Injectable;

    public function run()
    {
        //$assetAdmin = AssetAdmin::singleton();
        //$creator = new InjectionCreator();
        //Injector::inst()->registerService($creator, ImageBackendFactory::class);
        //$files = File::get();
        $sitetree = SiteTree::get();

        set_time_limit(0);
        // Loop over all sitetree entries
        $count = 0;
        $originalState = null;
        if (class_exists(Versioned::class)) {
            $originalState = Versioned::get_reading_mode();
            Versioned::set_stage(Versioned::DRAFT);
        }

        foreach ($sitetree as $page) {
            $success = $this->migrateContent($page);
            if ($success) {
                $count++;
            }
        }

        if (class_exists(Versioned::class)) {
            Versioned::set_reading_mode($originalState);
        }
        return $count;
    }

    protected function migrateContent($page)
    {
        if ($page->Content) {
            $title = $page->Title;
            $content = $page->Content;

            // Find images in content <img class="left" title="" src="assets/verein/logo-jimev.jpg"
            // src="(*)"
            preg_match_all('/src="([^"]*)"/i', $content, $matches);
            if (array_pop($matches)) {
                foreach ($matches as $match) {
                    Injector::inst()->get(LoggerInterface::class)->debug('ImageContentHelper - migrateContent()  page = ' . $title);
                    foreach ($match as $imageUrl) {
                        // Get the file name
                        $imagename = strrchr($imageUrl, '/');
                        $filename = substr($imagename, 1, strlen($imagename)-2);
                        //Injector::inst()->get(LoggerInterface::class)->debug('ImageContentHelper - migrateContent() old = ' . $oldImageUrl);
                        $file = File::get()->filter('Name', $filename)->first();
                        if ($file) {
                            //Injector::inst()->get(LoggerInterface::class)->debug('ImageContentHelper - migrateContent() new = ' . $newImageUrl);
                            if ($this->replacePageImageWithFile($page, $imageUrl, $file)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }

    protected function replacePageImageWithFile(Dataobject $page, string $oldImageUrl, File $file)
    {
        // Add attribute to image url
        $newImageUrl = 'src="'. substr($file->getURL(), 1) . '"';
        // Only if different
        if ($oldImageUrl != $newImageUrl) {
            Injector::inst()->get(LoggerInterface::class)->debug('ImageContentHelper - replaceImagePath()  OLD = ' . $page->Content);
            $page->Content = str_replace($oldImageUrl, $newImageUrl, $page->Content);
            Injector::inst()->get(LoggerInterface::class)->debug('ImageContentHelper - replaceImagePath()  NEW = ' . $page->Content);
            return $page->write();
        }
    }
}
