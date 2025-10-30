<?php

namespace Pbac\Events\Impersonation;

use Pbac\Events\BasePBACLogEvent;

class ImpersonationStarted extends BasePBACLogEvent
{
    public function __construct(
        public $impersonator,
        public $target,
        public ?\Illuminate\Foundation\Auth\User $user = null
    ) {
        parent::__construct($user ?? $impersonator);
        $this->model = $target;
        $this->tags = ['impersonation', 'security', 'started'];
        $this->eventType = 'impersonation_started';
    }

    public function getImpersonatorId(): string|int|null
    {
        return $this->getObjectId($this->impersonator, 'id');
    }

    public function getTargetId(): string|int|null
    {
        return $this->getObjectId($this->target, 'id');
    }

    public function getNotes(): string
    {
        $impersonatorId = $this->getImpersonatorId();
        $targetId = $this->getTargetId();

        return "User #{$impersonatorId} started impersonating User #{$targetId}";
    }

    public function getAuditableType(): ?string
    {
        if ($this->target && is_object($this->target)) {
            return get_class($this->target);
        }

        if (is_array($this->target) && isset($this->target['type'])) {
            return $this->target['type'];
        }

        return config('pbac.user_model', 'App\Models\User');
    }

    public function getNewValues(): ?array
    {
        return [
            'impersonator_id' => $this->getImpersonatorId(),
            'target_id' => $this->getTargetId(),
            'started_at' => now()->toDateTimeString(),
        ];
    }
}
