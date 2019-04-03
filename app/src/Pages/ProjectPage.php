<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ArrayList;
use Jimev\Models\Project;

class ProjectPage extends Page
{
    private static $singular_name = 'Projekte';
    private static $description = 'Seite zum Darstellen von Projekten.';
    private static $icon = 'resources/app/client/dist/img/projects.png';
    private static $can_be_root = true;
    private static $allowed_children = ['none'];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'ProjectPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab(
            'Root.Main',
            new LiteralField(
                'Info',
                '<p><span style="color:red;">Achtung: </span>Projekte werden unter
                <a href="admin/projectmanager/">Projekte</a> (auf der linken Seite in der Navigation) verwaltet.</p>'
            ),
            'Content'
        );

        return $fields;
    }

    public function Projects()
    {
        return Project::get();
    }

    public function getProjectPageTags()
    {
        $usedtags = [];
        foreach ($this->Projects() as $project) {
            $currentTagList = $project->ProjectTags();
            foreach ($currentTagList as $tag) {
                // Add ProjectTag object to array
                array_push($usedtags, $tag);
            }
        }
        // Limit to used ones
        // this requires a __toString() method for the object compared
        // see GalleryTag __toString()
        return new ArrayList(array_unique($usedtags));
    }

    public function getProjectPageYears()
    {

        $usedYears = [];
        foreach ($this->Projects() as $project) {
            array_push($usedYears, $project);
        }

        // Limit to used ones
        // this requires a __toString() method for the object compared
        // see Project __toString()
        $list = new ArrayList(array_unique($usedYears));
        return $list->sort('ProjectDate', 'DESC');
    }
}
