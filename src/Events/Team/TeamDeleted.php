<?php

namespace Pbac\Events\Team;

use Pbac\Events\BasePBACLogEvent;

class TeamDeleted extends BasePBACLogEvent
{
    public function __construct(public $team, array $oldValues = [], public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $team;
        $this->oldValues = $oldValues;
        $this->tags = ['team', 'access-control'];
    }

    public function getTeamId(): ?int
    {
        return $this->getObjectId($this->team, 'id');
    }
}
