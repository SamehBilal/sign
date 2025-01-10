<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class AdobeSignProvider extends AbstractProvider
{
    protected $scopes = ['user_read:account account_read:account account_write:account user_write:account user_login:account agreement_read:account agreement_write:account agreement_send:account widget_read:account widget_write:account library_read:account library_write:account workflow_read:account workflow_write:account webhook_read:account webhook_write:account webhook_retention:account application_read:account application_write:account'];

    /**
     * Get the authorization URL.
     *
     * @param string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://secure.na4.adobesign.com/public/oauth/v2', $state);
    }

    /**
     * Get the token URL.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://secure.na4.adobesign.com/oauth/v2/token';
    }

    /**
     * Get the user details by token.
     *
     * @param string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = (new Client())->get('https://api.na4.adobesign.com/api/rest/v6/users/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user data to a Socialite User instance.
     *
     * @param array $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['userId'] ?? null,
            'name' => $user['fullName'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }
}
