<?php

namespace Pbac\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Pbac\Traits\HasUUIDColumn;

class PBACAccessGroup extends Model
{
    use HasFactory, HasUUIDColumn;
    protected $table = 'pbac_access_groups';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'identifier',
    ];

    public function users(): BelongsToMany
    {
        $userClass = config('pbac.user_model');
        return $this->belongsToMany($userClass, 'pbac_group_user', 'pbac_access_group_id', 'user_id');
    }

}
