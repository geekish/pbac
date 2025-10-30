<?php

namespace Pbac\Events\Resource;

use Pbac\Events\BasePBACLogEvent;

class ResourceCreated extends BasePBACLogEvent
{
    public function __construct(public $resource, public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $resource;
        $this->tags = ['resource', 'access-control'];
    }

    public function getResourceId(): ?int
    {
        return $this->getObjectId($this->resource, 'id');
    }
}
