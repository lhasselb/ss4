<?php

namespace Jimev\Extensions;

use SilverStripe\Core\Extension;

class GalleryImageExtension extends Extension
{
    public function StageImage()
    {
        return $this->StageImageBy(null, null);
    }

    /**
     * Resize an image (if required)
     * to be displayed on gallery stage
     *
     * @return void
     */
    public function StageImageBy($width, $height)
    {
        if ($width == null|| $height == null) {
            $width = 1024;
            $height = 768;
        }
        $variant = $this->owner->variantName(__FUNCTION__, $width);
        return $this->owner->manipulateImage($variant, function (\SilverStripe\Assets\Image_Backend $backend) use ($width, $height) {
            $clone = clone $backend;
            $resource = clone $backend->getImageResource();

            if ($resource->getWidth() > $width &&
                $resource->getHeight() > $height
            ) {
                // FillMax() ? like Fill but prevents up-sampling
                $resource->Fill($width, $height);
            }
            $clone->setImageResource($resource);
            return $clone;
        });
    }
}
