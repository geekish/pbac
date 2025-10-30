<?php

namespace Pbac\Events\Impersonation;

use Pbac\Events\BasePBACLogEvent;

class ImpersonationStopped extends BasePBACLogEvent
{
    public function __construct(
        public $impersonator,
        public $target,
        public ?array $sessionData = [],
        public ?\Illuminate\Foundation\Auth\User $user = null
    ) {
        parent::__construct($user ?? $impersonator);
        $this->model = $target;
        $this->oldValues = $sessionData;
        $this->tags = ['impersonation', 'security', 'stopped'];
        $this->eventType = 'impersonation_stopped';
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

        return "User #{$impersonatorId} stopped impersonating User #{$targetId}";
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
            'stopped_at' => now()->toDateTimeString(),
        ];
    }
}
