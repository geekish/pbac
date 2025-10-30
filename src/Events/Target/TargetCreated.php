<?php

namespace Pbac\Events\Target;

use Pbac\Events\BasePBACLogEvent;

class TargetCreated extends BasePBACLogEvent
{
    public function __construct(public $target, public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $target;
        $this->tags = ['target', 'access-control'];
    }

    public function getTargetId(): ?int
    {
        return $this->getObjectId($this->target, 'id');
    }
}
