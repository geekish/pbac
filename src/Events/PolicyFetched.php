<?php

namespace Pbac\Events;

class PolicyFetched
{
    public int $count;

    public function __construct(
        public $user,
        public array $policies,
        public ?string $resourceType = null,
        public ?string $action = null,
        public array $context = []
    ) {
        $this->count = count($policies);
    }

    public function getUserId(): ?int
    {
        return $this->user?->id ?? $this->user['id'] ?? null;
    }
}
