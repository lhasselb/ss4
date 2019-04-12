<?php

namespace Jimev\Tasks;

use Dynamic\ClassNameUpdate\MappingObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

//use SilverStripe\Assets\Image;
//use SilverStripe\Assets\File;
//use SilverStripe\Assets\Folder;

use Jimev\Models\Course;
use Jimev\Models\News;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Class DatabaseClassNameUpdateTask
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class MigrateNewsAndCourse extends BuildTask
{
    /**
     * @var string
     */
    private static $segment = 'migrate-news-and-course';

    /**
     * @var string
     */
    protected $title = 'Migrate News and Course (JIM)';

    /**
     * @var string
     */
    protected $description = "Add relations between News and Course objects";

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request)
    {
        $this->updateNews();
        echo "News have been updated\n";

        $this->updateCourses();
        echo "Course(s) have been updated\n";
    }

    /**
     * Same ass running
     * UPDATE `News` SET `ClassName` = 'Jimev\\Models\\News' WHERE `ClassName` = 'Course';
     * at Database level
     */
    protected function updateNews()
    {
        $allNews = News::get();
        $news = new News();
        foreach ($allNews as $newsItem) {
            $newsItem->ClassName = $news->ClassName;
            $newsItem->write();
        }
    }

    /**
     * Same ass running
     * UPDATE `Course` SET `NewsID`=`ID`;
     * at Database level
     */
    protected function updateCourses()
    {
        $allCourses = Course::get();
        foreach ($allCourses as $course) {
            $course->NewsID = $course->ID;
            $course->write();
        }
    }
}
