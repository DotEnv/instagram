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

use Dotenv\Instagram\Contracts\User as UserContract;

class User implements UserContract
{
    /**
     * The unique identifier for the user.
     *
     * @var mixed
     */
    public $id;
    
    /**
     * The user's nickname / username.
     *
     * @var string
     */
    public $username;
    
    /**
     * The user's full name.
     *
     * @var string
     */
    public $fullname;
    
    /**
     * The user's e-mail address.
     *
     * @var string
     */
    public $email;
    
    /**
     * The user's avatar image URL.
     *
     * @var string
     */
    public $profilePicture;

    /**
     * The user's raw attributes.
     *
     * @var array
     */
    public $user;    

	/**
	* Get user id
	*
	* @return string
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Get user name
	*
	* @return string
	*/
	public function getUserName()
	{
		return $this->username;
	}

	/**
	* Get user full name
	*
	* @return string
	*/
	public function getFullName()
	{
		return $this->fullname;
	}

	/**
	* Get user e-mail
	*
	* @return string
	*/
	public function getEmail()
	{
		return $this->email;
	}

	/**
	* Get user profile picture
	*
	* @return string
	*/
	public function getProfilePicture()
	{
		return $this->profilePicture;
	}

    /**
     * Get the raw user array.
     *
     * @return array
     */
    public function getRaw()
    {
        return $this->user;
    }

    /**
     * Set the raw user array from the provider.
     *
     * @param  array  $user
     * @return $this
     */
    public function setRaw(array $user)
    {
        $this->user = $user;
        return $this;
    }    

    /**
     * Map the given array onto the user's properties.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) 
        {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Set the token on the user.
     *
     * @param  string  $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set the refresh token required to obtain a new access token.
     *
     * @param  string  $refreshToken
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }
    
    /**
     * Set the number of seconds the access token is valid for.
     *
     * @param  int  $expiresIn
     * @return $this
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }    
}