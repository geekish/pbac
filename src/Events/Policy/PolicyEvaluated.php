<?php

namespace Pbac\Events\Policy;

use Pbac\Events\BasePBACLogEvent;

class PolicyEvaluated extends BasePBACLogEvent
{

    public function __construct(
        public ?\Illuminate\Foundation\Auth\User $user,
        public $resource,
        public string|array $action,
        public $policy,
        public bool $granted,
        public ?string $reason = null,
        public array $context = [],
        public ?float $duration = null
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
        return $this->getObjectId($this->resource, 'id');
    }

    public function getPolicyId(): ?int
    {
        return $this->getObjectId($this->policy, 'id');
    }
}
