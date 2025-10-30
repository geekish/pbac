<?php

namespace Pbac\Events\Access;

use Pbac\Events\BasePBACLogEvent;

class AccessDenied extends BasePBACLogEvent
{

    public function __construct(
        public ?\Illuminate\Foundation\Auth\User $user,
        public $resource,
        public string $action,
        public ?string $reason = null,
        public array $context = []
    ) {
        parent::__construct($user);
    }

    public function getResourceType(): ?string
    {
        if (is_object($this->resource)) {
            return get_class($this->resource);
        }
        return $this->getObjectId($this->resource, 'type', true) ?? null;
    }

    public function getResourceId()
    {
        $this->getObjectId($this->resource, 'id');
    }
}
