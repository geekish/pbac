<?php

namespace Pbac\Events\Group;

use Pbac\Events\BasePBACLogEvent;

class GroupCreated extends BasePBACLogEvent
{
    public function __construct(public $group, public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $group;
        $this->tags = ['group', 'access-control'];
    }

    public function getGroupId(): ?int
    {
        return $this->getObjectId($this->group, 'id');
    }
}
