<?php

namespace Pbac\Events;

use Illuminate\Database\Eloquent\Model;
use Pbac\Contracts\AuditableEvent;
use Pbac\Services\PbacUtility;

abstract class BasePBACLogEvent implements AuditableEvent
{
    protected PbacUtility $pbacUtility;
    protected mixed $model;
    protected array $oldValues = [];
    protected array $tags = [];
    protected string $eventType;

    public function __construct(
        public ?\Illuminate\Foundation\Auth\User $user = null
    ) {
        $this->pbacUtility = app('pbac-utility');
    }

    /**
     * Get comprehensive audit information.
     */
    public function auditableInfo(): array
    {
        return [
            'event' => $this->getEventName(),
            'event_type' => $this->getEventType(),
            'notes' => $this->getNotes(),
            'auditable_type' => $this->getAuditableType(),
            'auditable_id' => $this->getAuditableId(),
            'user_id' => $this->getUserId(),
            'tags' => $this->getTags(),
            'old_values' => $this->getOldValues(),
            'new_values' => $this->getNewValues(),
            'diff' => $this->getDiff(),
        ];
    }

    protected function getEventName(): string
    {
        return class_basename($this);
    }

    /**
     * Get event type (created, updated, deleted).
     */
    public function getEventType(): string
    {
        return $this->eventType ?? $this->inferEventType();
    }

    /**
     * Infer event type from class name.
     */
    protected function inferEventType(): string
    {
        $className = class_basename($this);

        if (str_contains($className, 'Created')) {
            return 'created';
        }
        if (str_contains($className, 'Updated')) {
            return 'updated';
        }
        if (str_contains($className, 'Deleted')) {
            return 'deleted';
        }

        return 'unknown';
    }

    /**
     * Get human-readable notes. Override in child classes for custom messages.
     */
    public function getNotes(): string
    {
        $type = $this->getResourceTypeName();
        $eventType = $this->getEventType();
        $id = $this->getAuditableId();

        return match ($eventType) {
            'created' => "{$type} #{$id} was created",
            'updated' => "{$type} #{$id} was updated",
            'deleted' => "{$type} #{$id} was deleted",
            default => "{$type} #{$id} event occurred",
        };
    }

    /**
     * Get the resource type name (Policy, Team, Group, etc.).
     */
    protected function getResourceTypeName(): string
    {
        $className = class_basename($this);
        return preg_replace('/(Created|Updated|Deleted)$/', '', $className);
    }

    /**
     * Get tags for this event.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Get the model class that was affected.
     */
    public function getAuditableType(): ?string
    {
        if ($this->model && is_object($this->model)) {
            return get_class($this->model);
        }

        if (is_array($this->model) && isset($this->model['type'])) {
            return $this->model['type'];
        }

        return null;
    }

    /**
     * Get the ID of the model instance.
     */
    public function getAuditableId(): string|int|null
    {
        return $this->getObjectId($this->model, 'id');
    }

    /**
     * Get user ID who triggered the event.
     */
    public function getUserId(): string|int|null
    {
        $userKey = $this->pbacUtility->getAuthenticatableKeyName();
        if ($this->user && is_object($this->user)) {
            return $this->user->{$userKey} ?? null;
        }
        return $this->user[$userKey] ?? null;
    }

    /**
     * Get old values (before change).
     */
    public function getOldValues(): ?array
    {
        return !empty($this->oldValues) ? $this->oldValues : null;
    }

    /**
     * Get new values (after change).
     */
    public function getNewValues(): ?array
    {
        if ($this->model && is_object($this->model) && method_exists($this->model, 'toArray')) {
            return $this->model->toArray();
        }

        if (is_array($this->model)) {
            return $this->model;
        }

        return null;
    }

    /**
     * Get diff of what changed.
     */
    public function getDiff(): ?array
    {
        $old = $this->getOldValues();
        $new = $this->getNewValues();

        if (!$old || !$new) {
            return null;
        }

        $diff = [];
        foreach ($new as $key => $value) {
            if (!array_key_exists($key, $old) || $old[$key] !== $value) {
                $diff[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        return !empty($diff) ? $diff : null;
    }

    /**
     * Helper to get ID from an object or array.
     */
    protected function getObjectId(mixed $obj, string $objProp, bool $strictObjProp = false): string|int|null
    {
        if (!$obj) {
            return null;
        }

        if ($obj && is_object($obj)) {
            if ($strictObjProp) {
                return $obj->{$objProp} ?? null;
            }
            $keyName = method_exists($obj, 'getKeyName') ? $obj->getKeyName() : 'id';
            return $obj->{$objProp} ?? $obj->{$keyName} ?? null;
        }
        if (isset($obj[$objProp])) {
            return $obj[$objProp];
        }
        return null;
    }
}
