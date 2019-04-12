<?php

namespace Jimev\Tasks;

use Dynamic\ClassNameUpdate\MappingObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\ORM\Queries\SQLUpdate;
use SilverStripe\ORM\Queries\SQLInsert;

/* See https://github.com/gorriecoe/silverstripe-link */
use gorriecoe\Link\Models\Link;
use Jimev\Models\HomepageSlider;
use Jimev\Models\FriendlyLink;
use Jimev\Models\LinkSet;
use Jimev\Pages\KontaktPage;

/* Logging */
use SilverStripe\Dev\Debug;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

/**
 * Class DatabaseClassNameUpdateTask
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class MigrateLinks extends BuildTask
{
    /**
     * @var string
     */
    private static $segment = 'migrate-links';

    /**
     * @var string
     */
    protected $title = 'Migrate Homepage and Linkset-Links (JIM)';

    /**
     * @var string
     */
    protected $description = "Move Links to changed implamentation";

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request)
    {
        $this->updateLinksWithFriendlyLinks();
        echo "Link's have been updated with FriendlyLink's \n";

        $this->updateLinkSetsFriendlyLinks();
        echo "Linkset's have been updated with FriendlyLinks's (delete table FriendlyLinks) \n";

        $this->updateHomepageSliderLinks();
        echo "HomepageSlider-Link's have been updated\n";

        $this->updateFacebookLinks();
        echo "HomepageSlider-Link's have been updated\n";

    }

    /**
     * Same as running
     * UPDATE `Links` SET `ClassName` = 'gorriecoe\\Link\\Models\\Link' WHERE `ClassName` = 'Link';
     * at Database level
     */
    protected function updateLinksWithFriendlyLinks()
    {
        $allLinks = Link::get();
        $link = new Link();
        foreach ($allLinks as $linkItem) {
            $linkItem->ClassName = $link->ClassName;
            $friendlyLinkAll = FriendlyLink::get();
            foreach ($friendlyLinkAll as $friendly) {
                if ($friendly->FriendlyLinkID == $linkItem->ID) {
                    $linkItem->Description  = $friendly->Description;
                }
            }
            $linkItem->write();
        }
    }

    /**
     * Update Linkset table FriendlyLinkID -> LinkID
     * UPDATE "LinkSet_Links" set LinkID = WHERE FriendlyLinkID matches table "FiendlyLink" column FriendlyLinkID);
     */
    protected function updateLinkSetsFriendlyLinks()
    {
        // Fetch all ids from table FriendlyLink
        $sqlQuery = new SQLSelect();
        $sqlQuery->setFrom('FriendlyLink');
        $friendlyLinks = $sqlQuery->execute();
        // $friendlyLinkIDs = $friendlyLinks->column('FriendlyLinkID');
        // Injector::inst()->get(LoggerInterface::class)->debug('MigrateLinks - updateLinkSets() count ids = ' . count($friendlyLinks));

        // Update table LinkSet_Links with fetched ids
        foreach ($friendlyLinks as $row) {
            // Injector::inst()->get(LoggerInterface::class)->debug('MigrateLinks - updateLinkSets() ' . $row['ID'] .' '.$row['FriendlyLinkID']);
            $friendlyID = $row['ID'];
            $linkID = $row['FriendlyLinkID'];
            $update = SQLUpdate::create('"LinkSet_Links"');
            $update->addWhere(['FriendlyLinkID' => $friendlyID]);
            $update->assign('"LinkID"', $linkID);
            $update->execute();
        }
    }

    /**
     * Table FriendlyLink will be obsolete afterwards
     */
    protected function updateHomepageSliderLinks()
    {
        $sliders = HomepageSlider::get();
        foreach ($sliders as $slider) {
            if ($slider->LinkID == 0 || empty($slider->LinkID)) {
                // Create a new link object
                $link = new Link();
                // Copy the link text (title)
                $link->Title = $slider->LinkText;
                // internal or external
                if ($slider->InternalURLID > 0) {
                    $link->SiteTreeID = $slider->InternalURLID;
                    $link->Type = 'SiteTree';
                } elseif ($slider->ExternalURL != null) {
                    $link->URL = $slider->ExternalURL;
                    $link->Type = 'URL';
                }
                // Store the new link
                $id = $link->write();
                // Store the change for HomepageID = ParentID
                $slider->LinkID = $id;
                //Debug::dump($slider);
                //Injector::inst()->get(LoggerInterface::class)->debug('MigrateLinks - updateHomepageSliderLinks() id = ' . $id);
                // ParentID is already obsolete and cannot be used here
                $slider->HomepageID = 1;
                $slider->write();
            }
        }
    }
    /**
     * Table FacebookLink will be obsolete afterwards
     */
    protected function updateFacebookLinks()
    {
        $kontaktPage = KontaktPage::get()->first();
        $facebookLinks = $kontaktPage->Links();
        if ($facebookLinks->Count() == 0) {
            Injector::inst()->get(LoggerInterface::class)->debug('MigrateLinks - updateFacebookLinks() NO links found');
            $insert = SQLInsert::create('"KontaktPage_Links"');
            // Add multiple rows in a single call. Note that column names do not need to be symmetric
            $insert->addRows([
                ['"KontaktPageID"' => $kontaktPage->ID, '"LinkID"' => '1'],
                ['"KontaktPageID"' => $kontaktPage->ID, '"LinkID"' => '2'],
                ['"KontaktPageID"' => $kontaktPage->ID, '"LinkID"' => '3'],
                ['"KontaktPageID"' => $kontaktPage->ID, '"LinkID"' => '4']
            ]);
            $insert->execute();
        } else {
            Injector::inst()->get(LoggerInterface::class)->debug('MigrateLinks - updateFacebookLinks() number of links  = ' . $facebookLinks->Count());
        }

        foreach ($facebookLinks as $link) {
            // Create a new link object
            //$link = new Link();
        }
    }
}
