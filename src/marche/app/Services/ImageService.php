<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService {
    public static function upload($imageFile, $folderName): bool {
        $fileName = uniqid(rand() . '');
        $extension = $imageFile->extension();
        $fileNameToStore = $fileName . '.' . $extension;
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        $result = Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);

        return $result;
    }
}
