<?php

namespace Jimev\Dev;

use SilverStripe\Assets\Image;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLUpdate;

use SilverStripe\Assets\ImageBackendFactory;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\InjectionCreator;

use Jimev\Models\GalleryImage;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class AddThumbnailTask extends BuildTask
{
    private static $segment = 'AddThumbnailTask';
    protected $title = 'Add Thumbnail to GalleryImage.';
    protected $description = 'Batch task to add an additional image (a thumbnail)';

    use Injectable;

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request)
    {
        $creator = new InjectionCreator();
        Injector::inst()->registerService(
            $creator,
            ImageBackendFactory::class
        );

        if ($request) {
            $this->addThumbnail();
        }
        echo "Thumbnails have been added\n";
    }
    /**
     * @param $mapping
     */
    protected function addThumbnail()
    {
        $queryClass = GalleryImage::class;
        set_time_limit(0);
        $i = 0;
        foreach ($this->yieldRecords($queryClass) as $record) {
            Injector::inst()
            ->get(LoggerInterface::class)
            ->debug('before ' . round(memory_get_usage() / 1024 / 1024, 2) . ' MB');

            Injector::inst()->get(LoggerInterface::class)->debug('AddThumbnailTask  '.$i);
            $i++;
            $this->updateRecord($record);

            Injector::inst()
            ->get(LoggerInterface::class)
            ->debug('after ' . round(memory_get_usage() / 1024 / 1024, 2) . ' MB');
        }
    }

    /**
     * @param $record
     */
    protected function updateRecord($record)
    {
        if ($record instanceof SiteTree || $record->hasExtension(Versioned::class)) {
            $published = $record->isPublished();
        }

        // Fetch the GalleryImage has_one Image
        $image = $record->Image();

        Injector::inst()
        ->get(LoggerInterface::class)
        ->debug('AddThumbnailTask - updateRecord() for ' . $image->FileName);

        // StageImage width
        $sWidth = 1024;

        // StageImage height
        $sHeight = 768;

        // Set the StageImage to Image
        $record->StageImage = $image;

        // Only if required
        if ($image->getWidth() > $sWidth &&
            $image->getHeight() > $sHeight
        ) {
            $record->StageImage = $image->Fill($sWidth, $sHeight);
        }
        $record->write();
        $image->destroy();

        if (isset($published) && $published) {
            $record->publishSingle();
        }
    }

    /**
     * @param $singleton
     * @param $legacyName
     * @return \Generator
     */
    public function yieldRecords($class)
    {
        foreach ($class::get() as $object) {
            yield $object;
        }
    }
}
