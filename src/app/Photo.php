<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $keyType = 'string';

    private const ID_LENGTH = 12;

    protected $appends = [
        'url',
    ];

    protected $visible = [
        'id',
        'owner',
        'url',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!array_get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    private function setId(): void
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    private function getRandomId(): string
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = '';

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->attributes['filename']);
    }
}
