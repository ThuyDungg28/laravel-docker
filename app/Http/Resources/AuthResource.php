<?php

namespace App\Http\Resources;

use App\Http\Resources\ApiResource;

class AuthResource extends ApiResource
{
    protected $method;

    public function __construct($resource, $method = 'login', $message = null)
    {
        parent::__construct($resource, $message);
        $this->method = $method;
    }

    public function toArray($request)
    {
        return $this->{$this->method}();
    }

    protected function login()
    {
        return [
            'token_type' => $this->resource['token_type'],
            'access_token' => $this->resource['access_token'],
            'refresh_token' => $this->resource['refresh_token'],
            'expires_at' => now()->addMinutes(config('common.api.token_expire_in'))->toIso8601String(),
        ];
    }

    protected function logout()
    {
        return [];
    }

    protected function refreshToken()
    {
        return $this->login();
    }

    protected function loginFailed()
    {
        return [];
    }
}