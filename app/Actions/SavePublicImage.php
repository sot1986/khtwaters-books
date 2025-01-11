<?php

declare(strict_types=1);

namespace App\Actions;

use \Illuminate\Http\UploadedFile;

class SavePublicImage
{
    public function handle(
        UploadedFile $file,
    ): string {
        $path = $file->store('images', 'public');

        if ($path === false) {
            throw new \Exception('File was not saved');
        }

        return $path;
    }
}
