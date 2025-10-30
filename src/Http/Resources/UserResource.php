<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function toArray(?Request $request = null): array
    {
        if (! is_subclass_of($this->resource, Model::class, false)) {
            throw new Exception(
                sprintf('User should be an Eloquent model instance, %s given.', get_debug_type($this->resource))
            );
        }

        if (! isset($this->name, $this->email)) {
            throw new Exception('User model should provide at least "name" and "email" attributes.');
        }

        return [
            '_id' => (string) $this->getKey(),
            'username' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ?? null,
            'status' => $this->whenHas('participant', static fn ($participant) => [
                'state' => $participant->online ? 'online' : 'offline',
                'lastChanged' => $participant->created_at->translatedFormat('d F, H:i'),
            ]),
        ];
    }
}
