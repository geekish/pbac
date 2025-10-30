<?php

namespace Pbac\Events\Group;

use Pbac\Events\BasePBACLogEvent;

class GroupUpdated extends BasePBACLogEvent
{
    public function __construct(public $group, array $oldValues = [], public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $group;
        $this->oldValues = $oldValues;
        $this->tags = ['group', 'access-control'];
    }

    public function getGroupId(): ?int
    {
        return $this->getObjectId($this->group, 'id');
    }
}
