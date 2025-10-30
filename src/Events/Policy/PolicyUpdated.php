<?php

namespace Pbac\Events\Policy;

use Pbac\Events\BasePBACLogEvent;

class PolicyUpdated extends BasePBACLogEvent
{

    public function __construct(public $policy, array $oldValues = [], public ?\Illuminate\Foundation\Auth\User $user = null)
    {
        parent::__construct($user);
        $this->model = $policy;
        $this->oldValues = $oldValues;
        $this->tags = ['policy', 'access-control'];
    }

    public function getPolicyId(): ?int
    {
        return $this->getObjectId($this->policy, 'id');
    }
}
