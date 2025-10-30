<?php

namespace Pbac\Events\Policy;

use Pbac\Events\BasePBACLogEvent;

class PolicyFetched extends BasePBACLogEvent
{
    public int $count;

    public function __construct(
        public ?\Illuminate\Foundation\Auth\User $user,
        public array $policies,
        public ?string $resourceType = null,
        public string|array|null $action = null,
        public array $context = []
    ) {
        parent::__construct($user);
        $this->count = count($policies);
    }

}
