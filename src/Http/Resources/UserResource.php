<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Contracts\Personifiable;

/**
 * @mixin Model&Personifiable
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        $person = $this->getPersonInfo();
        $lastChanged = $person->lastChanged ? Carbon::createFromInterface($person->lastChanged) : Carbon::now();

        return [
            '_id' => (string) $this->getKey(),
            'username' => $person->name,
            'email' => $person->email,
            'avatar' => $person->avatar,
            'status' => [
                'state' => $person->online ? 'online' : 'offline',
                'lastChanged' => $lastChanged->translatedFormat('d F, H:i'),
            ],
        ];
    }
}
