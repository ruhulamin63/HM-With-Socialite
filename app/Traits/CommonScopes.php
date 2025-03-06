<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait CommonScopes
{
    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeNotActive($query)
    {
        $query->where('is_active', false);
    }
}