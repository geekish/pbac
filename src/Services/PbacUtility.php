<?php

namespace Pbac\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;

class PbacUtility
{
    /**
     * @return class-string<\Illuminate\Foundation\Auth\User>
     */
    public function getUserModel(): string
    {
        return Config::get('pbac.user_model', \App\Models\User::class);
    }

    public function getAuthenticatableTableName(): string
    {
        return Config::get('pbac.users.table') ?? (new ($this->getUserModel()))->getTable();
    }

    public function getAuthenticatableKeyName(): string
    {
        return Config::get('pbac.users.key') ?? (new ($this->getUserModel()))->getKeyName();
    }

    public function getAuthenticatableKeyType(): string
    {
        return strtolower(Config::get('pbac.users.key_type', 'unsignedbiginteger'));
    }

    public function getAuthenticatableKeyLength(): ?int
    {
        return Config::get('pbac.users.key_length');
    }

    public function addUserForeignKey(
        Blueprint $table,
        string $column,
        bool $nullable = false,
        string $onDelete = 'cascade'
    ): ColumnDefinition {
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
                $length = $this->getAuthenticatableKeyLength() ?? 191;
                $col = $table->string($column, $length);
                break;
            case 'biginteger':
            case 'unsignedbiginteger':
            default:
                $col = $table->unsignedBigInteger($column);
                break;
        }

        if ($nullable) {
            $col->nullable();
        }

        $table->foreign($column)
              ->references($usersKey)
              ->on($usersTable)
              ->when($onDelete === 'cascade', fn ($c) => $c->cascadeOnDelete())
              ->when($onDelete === 'set null', fn ($c) => $c->nullOnDelete());

        // $table->index($column);

        return $col;
    }
}
