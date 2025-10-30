<?php

namespace Pbac\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pbac\Events\Team\TeamCreated;
use Pbac\Events\Team\TeamDeleted;
use Pbac\Events\Team\TeamUpdated;
use Pbac\Models\PBACAccessTeam;

class PBACAccessTeamObserver
{
    public function created(PBACAccessTeam $team): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.team_created', true)) {
            return;
        }

        event(new TeamCreated($team, Auth::user()));
    }

    public function updated(PBACAccessTeam $team): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.team_updated', true)) {
            return;
        }

        event(
            new TeamUpdated(
                $team,
                $team->getOriginal(),
                Auth::user()
            )
        );
    }

    public function deleted(PBACAccessTeam $team): void
    {
        if (!Config::get('pbac.events.enabled', true)) {
            return;
        }

        if (!Config::get('pbac.events.team_deleted', true)) {
            return;
        }

        event(new TeamDeleted($team, $team->getOriginal(), Auth::user()));
    }
}
