<?php

namespace App\Generators;

use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class ModeratedUrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return '/file/' . $this->media->id;
    }

    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getPath(): string
    {
        return storage_path('app/media/' . $this->media->id . '/' . $this->media->file_name);
    }
}
