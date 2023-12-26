<?php

declare(strict_types=1);

namespace App\Http\Requests\OAuth;

use App\Models\OAuth\OAuthTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OauthRedirectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => Rule::in(OAuthTypes::cases()),
        ];
    }

    public function all($keys = null): array
    {
        return ['type' => $this->route('type')];
    }
}
