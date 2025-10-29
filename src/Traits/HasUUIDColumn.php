<?php

namespace Pbac\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

trait HasUUIDColumn {

    use HasUuids;
    public $uuid = true;
    public $fieldName = 'uuid';

    public function getIncrementing(): bool
    {
        return false;
    }

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            // $model->uuid = self::newUUID7();
            $model->{$model->fieldName} = self::newUUID7ForColumn();
        });
    }

    public static function newUUID7ForColumn()
    {
        return (string) Str::uuid7();
    }
}