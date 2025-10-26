<?php

namespace Pbac\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pbac\Events\PolicyCreated;
use Pbac\Events\PolicyDeleted;
use Pbac\Events\PolicyUpdated;
use Pbac\Models\PBACAccessControl;

class PBACAccessControlObserver
{

    public function created(PBACAccessControl $policy): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.policy_created', true)) {
            return;
        }

        event(new PolicyCreated($policy, Auth::user()));
    }

    public function updated(PBACAccessControl $policy): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.policy_updated', true)) {
            return;
        }

        event(
            new PolicyUpdated(
                $policy,
                $policy->getOriginal(),
                Auth::user()
            )
        );
    }

    public function deleted(PBACAccessControl $policy): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.policy_deleted', true)) {
            return;
        }

        event(new PolicyDeleted($policy, Auth::user()));
    }
}
