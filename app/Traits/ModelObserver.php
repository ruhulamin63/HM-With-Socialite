<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ModelObserver
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = (request()->user()) ? request()->user()->id : null;
            }
            self::clearCache();
        });

        static::updating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = (request()->user()) ? request()->user()->id : null;
            }
            self::clearCache($model->id);
        });

        static::deleting(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'deleted_by')) {
                $model->deleted_by = (request()->user()) ? request()->user()->id : null;
            }
            self::clearCache($model->id);
        });

        // static::restoring(function ($model) {
        //     if (Schema::hasColumn($model->getTable(), 'updated_by')) {
        //         $model->updated_by = (request()->user()) ? request()->user()->id : null;
        //     }
        //     self::clearCache($model->id);
        // });

        static::saved(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = (request()->user()) ? request()->user()->id : null;
            }
            self::clearCache();
        });

        static::deleted(function ($model) {
            self::clearCache($model->id);
        });

        // static::restored(function ($model) {
        //     if (Schema::hasColumn($model->getTable(), 'updated_by')) {
        //         $model->updated_by = (request()->user()) ? request()->user()->id : null;
        //     }
        //     self::clearCache($model->id);
        // });

        // static::forceDeleted(function ($model) {
        //     self::clearCache($model->id);
        // });
    }
}