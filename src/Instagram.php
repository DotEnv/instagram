<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram;

use Dotenv\Instagram\User;
use Dotenv\Instagram\Provider;
use Dotenv\Instagram\Traits\TagsWS;
use Dotenv\Instagram\Traits\UsersWS;
use Dotenv\Instagram\Traits\MediaWS;
use Dotenv\Instagram\Traits\LikesWS;
use Dotenv\Instagram\Traits\CommentsWS;
use Dotenv\Instagram\Traits\LocationsWS;
use Dotenv\Instagram\Traits\RelationshipsWS;

use Dotenv\Instagram\Contracts\Instagram as InstagramContract;

class Instagram extends Provider implements InstagramContract
{
	use TagsWS, UsersWS, MediaWS, LikesWS, CommentsWS, LocationsWS, RelationshipsWS;

    /**
     * The base Instagram URL.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.instagram.com';

    /**
     * The Instagram API version for the request.
     *
     * @var string
     */
    protected $version = 'v1';

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';    

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
    	$url = $this->baseUrl . '/oauth/authorize/?' .
    		   'client_id='     . $this->clientId . 
    		   '&redirect_uri=' . $this->redirectUrl . '&response_type=code';

        return $this->buildAuthUrlFromBase($url, $state);
    }	

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl . '/oauth/access_token';
    }	

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $meUrl = $this->baseUrl . '/' . $this->version . '/users/self?access_token=' . $token;

        $response = $this->getHttpClient()->get($meUrl, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }   	

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Dotenv\Instagram\User
     */
    protected function mapUserToObject(array $user)
    {
    	$user = $user['data'];

        return (new User)->setRaw($user)->map([
            'id'             => $user['id'], 
            'username'       => isset($user['username']) ? $user['username'] : null,
            'fullname'       => isset($user['full_name']) ? $user['full_name'] : null,
            'email'          => isset($user['email']) ? $user['email'] : null, 
            'profilePicture' => isset($user['profile_picture']) ? $user['profile_picture'] : null,
            'website'        => isset($user['website']) ? $user['website'] : null
        ]);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }       
}