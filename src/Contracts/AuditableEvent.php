<?php

namespace Pbac\Contracts;

/**
 * Contract for events that can be audited/logged.
 *
 * Events implementing this interface can provide structured audit information
 * for logging purposes, including what changed, who made the change, and context.
 */
interface AuditableEvent
{
    /**
     * Get comprehensive audit information for logging.
     *
     * @return array{
     *     event: string,
     *     event_type: string,
     *     notes: string,
     *     auditable_type: ?string,
     *     auditable_id: string|int|null,
     *     user_id: string|int|null,
     *     tags: array<string>,
     *     old_values: ?array,
     *     new_values: ?array,
     *     diff: ?array
     * }
     */
    public function auditableInfo(): array;

    /**
     * Get the event type (created, updated, deleted, etc.).
     */
    public function getEventType(): string;

    /**
     * Get a human-readable description of the event.
     */
    public function getNotes(): string;

    /**
     * Get tags for categorizing/filtering this event.
     *
     * @return array<string>
     */
    public function getTags(): array;

    /**
     * Get the model class name that was affected.
     */
    public function getAuditableType(): ?string;

    /**
     * Get the ID of the model instance that was affected.
     */
    public function getAuditableId(): string|int|null;

    /**
     * Get the user ID who triggered this event.
     */
    public function getUserId(): string|int|null;

    /**
     * Get old values before change (for updated/deleted events).
     */
    public function getOldValues(): ?array;

    /**
     * Get new values after change (for created/updated events).
     */
    public function getNewValues(): ?array;

    /**
     * Get diff of what changed (for updated events).
     */
    public function getDiff(): ?array;
}
