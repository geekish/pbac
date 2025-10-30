<?php

namespace Pbac\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pbac\Events\Target\TargetCreated;
use Pbac\Events\Target\TargetDeleted;
use Pbac\Events\Target\TargetUpdated;
use Pbac\Models\PBACAccessTarget;

class PBACAccessTargetObserver
{
    public function created(PBACAccessTarget $target): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.target_created', true)) {
            return;
        }

        event(new TargetCreated($target, Auth::user()));
    }

    public function updated(PBACAccessTarget $target): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.target_updated', true)) {
            return;
        }

        event(
            new TargetUpdated(
                $target,
                $target->getOriginal(),
                Auth::user()
            )
        );
    }

    public function deleted(PBACAccessTarget $target): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.target_deleted', true)) {
            return;
        }

        event(new TargetDeleted($target, $target->getOriginal(), Auth::user()));
    }
}
