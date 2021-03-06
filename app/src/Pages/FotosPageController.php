<?php

namespace Jimev\Pages;

use \PageController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\PaginatedList;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Models\Gallery;
use Jimev\Models\GalleryImage;

class FotosPageController extends PageController
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = ['album'];

    protected function init()
    {
        parent::init();
    }

    public function album(HTTPRequest $request)
    {
        Injector::inst()->get(LoggerInterface::class)->debug('FotosPageController - gallery ID = ' . $request->param('ID') . '  image ID = ' . $request->postVar('ImageID'));
        // Fetch the ID from the URL
        $gallery = Gallery::get()->byID($request->param('ID'));
        if (!$gallery) {
            return $this->httpError(404, 'Das gewünschte Fotoalbum existiert nicht.');
        }
        // Get all images sorted
        $sortedImages = $gallery->getSortedGalleryImages();
        $total = $gallery->getSortedGalleryImages()->Count();
        $firstGalleryImage = $gallery->getGalleryImageJson($sortedImages->first());
        $galleryImageIDs = $gallery->getGalleryImageIdsJson();
        // Use the uppercase variable names on the left within the templates !
        $data = ['GalleryId' => $gallery->ID,
            'GalleryData' => $firstGalleryImage,
            'GalleryImageIDs' => $galleryImageIDs,
            'Total' => $total,
            'AlbumName' => $gallery->AlbumName,
            'AlbumDescription' => $gallery->AlbumDescription];

        foreach ($data as $key => $value) {
            Injector::inst()->get(LoggerInterface::class)
                ->debug('FotosPageController - key[ ' . $key . '] = ' . $value);
        }


        if ($request->isAjax()) {
            if ($request->isPost()) {
                $imageId = $request->postVar('ImageID');

                Injector::inst()
                ->get(LoggerInterface::class)
                ->debug('FotosPageController - imageID = ' . $imageId);

                $galleryImage = GalleryImage::get()->byID($imageId);
                $image = $gallery->getGalleryImageJson($galleryImage);
                // We render only one image as requested by Post parameter
                $data = ['Image' => $image];
                return $this->customise($data)->renderWith(['Jimev\Pages\Layout\FotosPage_Empty']);
            } else {
                // NOT used any more
                // Info FotosPage_Gallery was stored within templates (root) before using renderWith(FotosPage_Gallery)
                // return $this->customise($data)->renderWith(['Jimev\Pages\Layout\FotosPage_Gallery']);
            }
        } else {
            // Using Jimev\Pages\Layout\FotosPage_album
            return $data;
        }
    }
}
