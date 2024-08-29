<?php

namespace FrentePatriaGrande\AHAuth\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class ArgentinaHumanaSocialiteProvider extends AbstractProvider
{
    protected $scopeSeparator = ' ';

    private function getArgentinaHumanaUrl(): string
    {
        return config('services.argentina-humana.base_url');
    }

    private function getArgentinaHumanaOauthUrl(): string
    {
        return $this->getArgentinaHumanaUrl().'/oauth';
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getArgentinaHumanaOauthUrl().'/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->getArgentinaHumanaOauthUrl().'/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getArgentinaHumanaUrl().'/api/user', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'email' => $user['email'],
            'first_name' => Arr::get($user, 'contact.first_name'),
            'last_name' => Arr::get($user, 'contact.last_name'),
        ]);
    }

    protected function userInstance(array $response, array $user)
    {
        $user = parent::userInstance($response, $user);

        $scope = Arr::get($response, 'scope');
        $user->setApprovedScopes($scope ? explode($this->scopeSeparator, $scope) : []);

        return $user;
    }
}
