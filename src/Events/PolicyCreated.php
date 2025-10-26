<?php

namespace Pbac\Events;

class PolicyCreated
{

    public function __construct(public $policy, public $user = null)
    {}

    public function getPolicyId(): ?int
    {
        if (is_object($this->policy)) {
            return $this->policy->id ?? null;
        }
        return $this->policy['id'] ?? null;
    }
}
