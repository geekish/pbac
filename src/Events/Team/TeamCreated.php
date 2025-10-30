<?php

namespace Pbac\Events\Team;

use Pbac\Events\BasePBACLogEvent;

class TeamCreated extends BasePBACLogEvent
{
    public function __construct(public $team, public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $team;
        $this->tags = ['team', 'access-control'];
    }

    public function getTeamId(): ?int
    {
        return $this->getObjectId($this->team, 'id');
    }
}
