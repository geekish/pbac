<?php

namespace Pbac\Events\Resource;

use Pbac\Events\BasePBACLogEvent;

class ResourceDeleted extends BasePBACLogEvent
{
    public function __construct(public $resource, array $oldValues = [], public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $resource;
        $this->oldValues = $oldValues;
        $this->tags = ['resource', 'access-control'];
    }

    public function getResourceId(): ?int
    {
        return $this->getObjectId($this->resource, 'id');
    }
}
