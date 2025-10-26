<?php

namespace Pbac\Events;

class PolicyUpdated
{

    public function __construct(public $policy, public array $oldValues = [], public $user = null)
    {}

    public function getPolicyId(): ?int
    {
        if (is_object($this->policy)) {
            return $this->policy->id ?? null;
        }

        return $this->policy['id'] ?? null;
    }
}
