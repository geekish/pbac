<?php

namespace Pbac\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

trait HasUUIDColumn {

    protected string $uuidColumn = 'identifier';

    public static function newUUID7ForColumn()
    {
        return (string) Str::uuid7();
    }


    public static function bootHasUUIDColumn(): void
    {
        static::creating(function ($model) {
            $column = property_exists($model, 'uuidColumn') ? $model->uuidColumn : 'identifier';
            if (empty($model->{$column})) {
                $model->{$column} = self::newUUID7ForColumn(); // or Str::uuid7() if you're on Laravel 11.36+
            }
        });
    }
}