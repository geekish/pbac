<?php

namespace Pbac\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pbac\Events\Resource\ResourceCreated;
use Pbac\Events\Resource\ResourceDeleted;
use Pbac\Events\Resource\ResourceUpdated;
use Pbac\Models\PBACAccessResource;

class PBACAccessResourceObserver
{
    public function created(PBACAccessResource $resource): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.resource_created', true)) {
            return;
        }

        event(new ResourceCreated($resource, Auth::user()));
    }

    public function updated(PBACAccessResource $resource): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.resource_updated', true)) {
            return;
        }

        event(
            new ResourceUpdated(
                $resource,
                $resource->getOriginal(),
                Auth::user()
            )
        );
    }

    public function deleted(PBACAccessResource $resource): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.resource_deleted', true)) {
            return;
        }

        event(new ResourceDeleted($resource, $resource->getOriginal(), Auth::user()));
    }
}
