<?php

namespace Jimev\Tasks;

//use SilverStripe\AssetAdmin\Helper\ImageThumbnailHelper;
use SilverStripe\ORM\DB;
use SilverStripe\Assets\FileMigrationHelper;
use SilverStripe\Dev\BuildTask;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Migrates all 3.x file dataobjects to use the new DBFile field.
 */
class MigrateContentImageTask extends BuildTask
{

    private static $segment = 'MigrateContentImageTask';

    protected $title = 'Migrate Sitetree Content with images from 3.x (JIM)';

    protected $description = 'Run through the sitetree to replace all images referenced within the Content field';

    public function run($request)
    {
        if (!class_exists(ImageContentHelper::class)) {
            DB::alteration_message("No content image migration helper detected", "notice");
            return;
        }

        $migrated = ImageContentHelper::singleton()->run();
        if ($migrated) {
            DB::alteration_message("{$migrated} Content with image upgraded", "changed");
        } else {
            DB::alteration_message("No image need upgrading", "notice");
        }
    }
}
