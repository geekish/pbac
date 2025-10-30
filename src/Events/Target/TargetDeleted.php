<?php

namespace Pbac\Events\Target;

use Pbac\Events\BasePBACLogEvent;

class TargetDeleted extends BasePBACLogEvent
{
    public function __construct(public $target, array $oldValues = [], public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $target;
        $this->oldValues = $oldValues;
        $this->tags = ['target', 'access-control'];
    }

    public function getTargetId(): ?int
    {
        return $this->getObjectId($this->target, 'id');
    }
}
