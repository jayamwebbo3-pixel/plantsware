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

        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            return;
        }

        try {
            $image = $manager->read($fullPath);
            $watermarkPath = public_path('assets/images/logo-1.png');

            if (!file_exists($watermarkPath)) {
                return;
            }

            $watermark = $manager->read($watermarkPath);

            // Reduce logo size: Scale logo to 30% of image width
            $targetWidth = $image->width() * 0.30;
            $watermark->scale(width: $targetWidth);

            // Remove tagline: Crop the bottom 15% of the logo height to keep only pot + name
            $watermark->crop(
                width: $watermark->width(),
                height: (int) ($watermark->height() * 0.85),
                position: 'top'
            );

            // Boost visibility slightly
            $watermark->brightness(10);
            $watermark->contrast(5);

            // Use a balanced padding (4% of width, minimum 8px)
            $padding = max(8, (int) ($image->width() * 0.04));

            // Place at bottom-right with 100% opacity
            $image->place($watermark, 'bottom-right', $padding, $padding, 100);

            $image->save($fullPath);
        } catch (\Exception $e) {
            // Log error or handle gracefully
            \Log::error('Watermarking failed: ' . $e->getMessage());
        }
    }
}
