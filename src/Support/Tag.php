<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Stringable;

final readonly class Tag implements Arrayable, Stringable
{
    public function __construct(public string $name, public string $type = '') {}

    public static function from(string|Model|self $tag): self
    {
        return match (true) {
            is_string($tag) => self::fromString($tag),
            $tag instanceof Model => self::fromModel($tag),
            default => $tag,
        };
    }

    protected static function fromString(string $tag): self
    {
        return new self(...(str_contains($tag, ':') ? explode(':', $tag, 2) : ['', $tag]));
    }

    protected static function fromModel(Model $model): self
    {
        if ($model::class === $alias = $model->getMorphClass()) {
            throw new ClassMorphViolationException($model);
        }

        return new self((string) $model->getKey(), $alias);
    }

    public function subject(): ?Model
    {
        if (! $this->type || ! $model = Relation::getMorphedModel($this->type)) {
            return null;
        }

        return $model::query()->find($this->name);
    }

    /**
     * @return array{name: string, type: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
        ];
    }

    public function __toString(): string
    {
        return ltrim($this->type.':'.$this->name, ':');
    }
}
