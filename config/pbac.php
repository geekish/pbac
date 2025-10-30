<?php


return [

    'user_model' => \App\Models\User::class,
    'users' => [
        'table'    => 'users',   // override if different
        'key'      => 'id',      // primary key column name, could be 'id' or 'uuid' etc.
        'key_type' => 'bigint',  // one of: unsignedBigInteger|bigint|uuid|ulid|string
        // when key_type=string, also set:
        'key_length' => 36,   // e.g. 36 for UUID, 26 for ULID, or custom, mainly used for migrations
    ],

    /*
    |--------------------------------------------------------------------------
    | Super Admin Attribute
    |--------------------------------------------------------------------------
    |
    | This is the name of the boolean attribute on your User model that, if true,
    | will grant the user full bypass access via a Gate::before check.
    | Set to null to disable this bypass via attribute.
    |
    */
    'super_admin_attribute' => 'is_super_admin', // Example: Add a boolean column 'is_super_admin' to your users table

    /*≤≤
    |--------------------------------------------------------------------------
    | Strict Resource Registration
    |--------------------------------------------------------------------------
    |
    | If true, access will be denied immediately if a resource type passed to
    | the PolicyEvaluator is not registered in the 'pbac_access_resources' table
    | or is marked as inactive.
    |
    | If false, if a resource type is not found or is inactive, the evaluation
    | will proceed as if no specific resource was provided (i.e., only rules
    | applying to *any* resource type will be considered for that resource).
    |
    */
    'strict_resource_registration' => false,

    /*
    | --------------------------------------------------------------------------
    | Strict Target Registration
    | --------------------------------------------------------------------------
    |
    | If true, access will be denied immediately if a target type passed to
    | the PolicyEvaluator is not registered in the 'pbac_access_targets' table
    | or is marked as inactive.
    |
    | If false, if a target type is not found or is inactive, the evaluation
    | will proceed as if no specific target was provided (i.e., only rules
    | applying to *any* target type will be considered for that target).
    |
    */
    'strict_target_registration' => false,

    'traits' => [
        'groups' => \Pbac\Traits\HasPbacGroups::class,
        'teams' => \Pbac\Traits\HasPbacTeams::class,
        'access_control' => \Pbac\Traits\HasPbacAccessControl::class
    ],


    'condition_handlers' => [
        'min_level' => \Pbac\Support\ConditionerHandlers\MinLevelHandler::class,
        'allowed_ips' => \Pbac\Support\ConditionerHandlers\AllowedIpsHandler::class,
        'requires_attribute_value' => \Pbac\Support\ConditionerHandlers\RequiresAttributeValueHandler::class,
        // Add your custom condition handlers here, e.g.:
        // 'is_business_hours' => \App\Pbac\Conditions\BusinessHoursHandler::class,
    ],

    'models' => [
        'access_control' => \Pbac\Models\PBACAccessControl::class,
        'access_resource' => \Pbac\Models\PBACAccessResource::class,
        'access_target' => \Pbac\Models\PBACAccessTarget::class,
        'access_group' => \Pbac\Models\PBACAccessGroup::class,
        'access_team' => \Pbac\Models\PBACAccessTeam::class,
    ],

    'supported_actions' => [
        'view',
        'viewAny',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
        'publish',
        'archive',
        // Add more actions as needed for your application...
    ],

    'cache' => [
        'enabled' => env('PBAC_CACHE_ENABLED', true),
        'ttl' => env('PBAC_CACHE_TTL', 60 * 24), // Cache duration in seconds (e.g., 24 hours)
        'key_prefix' => 'pbac:', // Prefix for cache keys
    ],

    'logging' => [
        'enabled' => env('PBAC_LOGGING_ENABLED', true),
        'channel' => env('PBAC_LOGGING_CHANNEL', 'stderr'), // Log channel to use (null for default)
        'level'   => env('LOG_LEVEL', 'warning'), // Add this line
    ],

    /*
    |--------------------------------------------------------------------------
    | Events Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which PBAC events should be fired. These events allow you to
    | track policy evaluations, access decisions, and policy CRUD operations.
    | You can listen to these events to create audit logs, analytics,
    | visualizations, or security monitoring.
    |
    */
    'events' => [
        'enabled' => env('PBAC_EVENTS_ENABLED', true), // Master switch for all PBAC events

        // Policy evaluation events
        'policy_evaluated' => env('PBAC_EVENT_POLICY_EVALUATED', true), // Fires on every policy evaluation with timing and context
        'access_granted' => env('PBAC_EVENT_ACCESS_GRANTED', true),     // Fires when access is granted
        'access_denied' => env('PBAC_EVENT_ACCESS_DENIED', true),       // Fires when access is denied
        'policy_fetched' => env('PBAC_EVENT_POLICY_FETCHED', true),     // Fires when policies are fetched for a user

        // Policy CRUD events
        'policy_created' => env('PBAC_EVENT_POLICY_CREATED', true),     // Fires when a policy is created
        'policy_updated' => env('PBAC_EVENT_POLICY_UPDATED', true),     // Fires when a policy is updated
        'policy_deleted' => env('PBAC_EVENT_POLICY_DELETED', true),     // Fires when a policy is deleted

        // Team CRUD events
        'team_created' => env('PBAC_EVENT_TEAM_CREATED', true),         // Fires when a team is created
        'team_updated' => env('PBAC_EVENT_TEAM_UPDATED', true),         // Fires when a team is updated
        'team_deleted' => env('PBAC_EVENT_TEAM_DELETED', true),         // Fires when a team is deleted

        // Group CRUD events
        'group_created' => env('PBAC_EVENT_GROUP_CREATED', true),       // Fires when a group is created
        'group_updated' => env('PBAC_EVENT_GROUP_UPDATED', true),       // Fires when a group is updated
        'group_deleted' => env('PBAC_EVENT_GROUP_DELETED', true),       // Fires when a group is deleted

        // Resource CRUD events
        'resource_created' => env('PBAC_EVENT_RESOURCE_CREATED', true), // Fires when a resource is created
        'resource_updated' => env('PBAC_EVENT_RESOURCE_UPDATED', true), // Fires when a resource is updated
        'resource_deleted' => env('PBAC_EVENT_RESOURCE_DELETED', true), // Fires when a resource is deleted

        // Target CRUD events
        'target_created' => env('PBAC_EVENT_TARGET_CREATED', true),     // Fires when a target is created
        'target_updated' => env('PBAC_EVENT_TARGET_UPDATED', true),     // Fires when a target is updated
        'target_deleted' => env('PBAC_EVENT_TARGET_DELETED', true),     // Fires when a target is deleted
    ],

];
