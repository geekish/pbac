<?php

namespace Pbac\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pbac\Events\Group\GroupCreated;
use Pbac\Events\Group\GroupDeleted;
use Pbac\Events\Group\GroupUpdated;
use Pbac\Models\PBACAccessGroup;

class PBACAccessGroupObserver
{
    public function created(PBACAccessGroup $group): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.group_created', true)) {
            return;
        }

        event(new GroupCreated($group, Auth::user()));
    }

    public function updated(PBACAccessGroup $group): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.group_updated', true)) {
            return;
        }

        event(
            new GroupUpdated(
                $group,
                $group->getOriginal(),
                Auth::user()
            )
        );
    }

    public function deleted(PBACAccessGroup $group): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.group_deleted', true)) {
            return;
        }

        event(new GroupDeleted($group, $group->getOriginal(), Auth::user()));
    }
}
