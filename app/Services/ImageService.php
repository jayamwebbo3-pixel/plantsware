<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Apply a logo watermark to an image.
     *
     * @param string $imagePath Path relative to storage/app/public/
     * @return void
     */
    public function applyWatermark($imagePath)
    {
        $manager = new ImageManager(new Driver());

        // Absolute paths
        $fullPath = storage_path('app/public/' . $imagePath);
        $watermarkPath = public_path('assets/images/logo-1.png');

        // Validate paths
        if (!file_exists($fullPath)) {
            throw new \Exception('Main image not found: ' . $fullPath);
        }

        if (!file_exists($watermarkPath)) {
            throw new \Exception('Watermark image not found: ' . $watermarkPath);
        }

        try {
            // Load images
            $image = $manager->read($fullPath);
            $watermark = $manager->read($watermarkPath);

            /*
            -----------------------------------------
            STEP 1 — SCALE WATERMARK
            -----------------------------------------
            30% of main image width
            */
            $targetWidth = (int) ($image->width() * 0.30);
            $watermark->scale(width: $targetWidth);

            /*
            -----------------------------------------
            STEP 2 — CROP WATERMARK (REMOVE TAGLINE)
            -----------------------------------------
            Keep top 85% only
            */
            $watermark->crop(
                width: $watermark->width(),
                height: (int) ($watermark->height() * 0.85),
                position: 'top'
            );

            /*
            -----------------------------------------
            STEP 3 — ENHANCE VISIBILITY
            -----------------------------------------
            */
            $watermark->brightness(10);
            $watermark->contrast(5);

            /*
            -----------------------------------------
            STEP 4 — POSITION WATERMARK (BOTTOM-RIGHT)
            -----------------------------------------
            We manually compute the top-left insertion point so
            the ENTIRE watermark sits inside the image with a margin.
            margin = 3% of image dimension, at least 8px
            */
            $marginX = max(8, (int) ($image->width()  * 0.03));
            $marginY = max(8, (int) ($image->height() * 0.03));

            // Top-left X and Y where watermark will be pasted
            $posX = $image->width()  - $watermark->width()  - $marginX;
            $posY = $image->height() - $watermark->height() - $marginY;

            // Safety clamp — never go negative
            $posX = max(0, $posX);
            $posY = max(0, $posY);

            $image->place($watermark, 'top-left', $posX, $posY, 100);

            /*
            -----------------------------------------
            STEP 5 — SAVE FINAL IMAGE
            -----------------------------------------
            */
            $image->save($fullPath);

        } catch (\Exception $e) {
            // Never fail silently
            \Log::error('Watermark failed: ' . $e->getMessage());
            throw $e;
        }
    }
}