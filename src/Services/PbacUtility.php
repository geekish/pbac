<?php

namespace Pbac\Services;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;

class PbacUtility
{

    /**
     * Get the User Model class name from configuration.
     *
     * @return class-string<\Illuminate\Foundation\Auth\User>
     */
    public function getUserModel(): string
    {
        $userModelClass = Config::get('pbac.user_model', \App\Models\User::class);
        return $userModelClass;
    }

    public function getAuthenticatableTableName(): string
    {
        // $userModelClass = $this->getUserModel();
        // /** @var Model&Authenticatable $userModelInstance */
        // $userModelInstance = new $userModelClass();
        // return $userModelInstance->getTable();
        return config('pbac.users.table') ?? (new ($this->getUserModel()))->getTable();
    }

    public function getAuthenticatableKeyName(): string
    {
        // $userModelClass = $this->getUserModel();
        // /** @var Model&Authenticatable $userModelInstance */
        // $userModelInstance = new $userModelClass();
        // return $userModelInstance->getKeyName();
        return config('pbac.users.key') ?? (new ($this->getUserModel()))->getKeyName();
    }

    public function getAuthenticatableKeyType(): string
    {
        // $userModelClass = $this->getUserModel();
        // /** @var Model&Authenticatable $userModelInstance */
        // $userModelInstance = new $userModelClass();
        // $tableName = $userModelInstance->getTable();
        // $keyName = $userModelInstance->getKeyName();
        // return \Illuminate\Support\Facades\Schema::getColumnType($tableName, $keyName);
        return config('pbac.users.key_type', 'unsignedBigInteger') ?? (new ($this->getUserModel()))->getKeyType();
    }

    public function getAuthenticatableKeyLength(): ?int
    {
        return config('pbac.users.key_length', null);
    }

    public function addUserForeignKey(Blueprint $table, string $column, bool $nullable = false, string $onDelete = 'cascade'): ColumnDefinition
    {
        $usersTable = $this->getAuthenticatableTableName();
        $usersKey   = $this->getAuthenticatableKeyName();

        switch ($this->getAuthenticatableKeyType()) {
            case 'uuid':
                $col = $table->uuid($column);
                break;
            case 'ulid':
                $col = $table->ulid($column);
                break;
            case 'string':
                $col = $table->string($column, $this->getAuthenticatableKeyLength());
                break;
            default: // bigint
                $col = $table->unsignedBigInteger($column);
                break;
        }

        if ($nullable) $col->nullable();

        $table->foreign($column)
              ->references($usersKey)
              ->on($usersTable)
              ->when($onDelete === 'cascade', fn ($c) => $c->cascadeOnDelete())
              ->when($onDelete === 'set null', fn ($c) => $c->nullOnDelete());
        return $col;
    }


}

