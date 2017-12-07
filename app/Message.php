<?php

namespace App;

use Cmgmyr\Messenger\Models\Message as BaseMessage;
use Cmgmyr\Messenger\Models\Models;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\Media;

class Message extends BaseMessage implements HasMedia
{
    use HasMediaTrait;

    protected $appends = ['files'];

    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('messages');

        parent::__construct($attributes);
    }

    public function getFilesAttribute()
    {
        $urls = $this->getMedia('files')->map(function (Media $media) {
            return [
                'name' => $media->name,
                'url'  => $media->getUrl(),
            ];
        });

        return $urls;
    }
}
