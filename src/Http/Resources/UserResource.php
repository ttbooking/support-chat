<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Contracts\Personifiable;

/**
 * @mixin User|Personifiable
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $person = $this->getPersonInfo();
        $lastChanged = $person->lastChanged ? Carbon::createFromInterface($person->lastChanged) : Carbon::now();

        return [
            '_id' => $this->getKey(),
            'username' => $person->name,
            'avatar' => $person->avatar,
            'status' => [
                'state' => $person->online ? 'online' : 'offline',
                'lastChanged' => $lastChanged->translatedFormat('d F, H:i'),
            ],
        ];
    }
}
