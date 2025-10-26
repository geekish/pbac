<?php

namespace Pbac\Events;

class PolicyEvaluated
{

    public function __construct(
        public $user,
        public $resource,
        public string $action,
        public $policy,
        public bool $granted,
        public ?string $reason = null,
        public array $context = [],
        public ?float $duration = null
    ) {}

    public function getUserId(): ?int
    {
        return $this->user?->id ?? $this->user['id'] ?? null;
    }

    public function getResourceType(): ?string
    {
        if (is_object($this->resource)) {
            return get_class($this->resource);
        }

        return $this->resource['type'] ?? null;
    }

    public function getResourceId()
    {
        if (is_object($this->resource)) {
            return $this->resource->id ?? null;
        }
        return $this->resource['id'] ?? null;
    }
}
