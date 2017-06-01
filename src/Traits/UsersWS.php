<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram\Traits;

trait UsersWS
{
	/**
	* Logged user media url.
	* 
	* @var $userInfoUrl
	*/	
	private $myMediaUrl = 'users/self/media/recent';

	/**
	* Logged user liked media url.
	* 
	* @var $userInfoUrl
	*/	
	private $myLikedMediaUrl = 'users/self/media/liked';

	/**
	* Public user information url.
	* 
	* @var $userInfoUrl
	*/
	private $userInfoUrl = 'users/%s';

	/**
	* Public user most recent media url.
	*  
	* @var $userMediaUrl
	*/	
	private $userMediaUrl = 'users/%s/media/recent';

	/**
	* User search url
	*
	* @var $userSearchUrl
	*/
	private $userSearchUrl = 'users/search?q=%s';

	/**
	* Get the most recent media published by the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
	*
	* @param string $token
	* @return array
	*/
	public function myRecentMedia($token)
	{
		$url = $this->getBaseUrl($this->myMediaUrl, $token);

		return $this->getRequestResponse($url);
	}

	/**
	* Get the list of recent media liked by the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/users/#get_users_feed_liked
	*
	* @param string $token
	* @return array
	*/
	public function myLikedMedia($token)
	{
		$url = $this->getBaseUrl($this->myLikedMediaUrl, $token);

		return $this->getRequestResponse($url);
	}

	/**
	* Get information about a user.
	* The public_content scope is required if the user is not the owner of the access_token.
	*  
	* @see https://www.instagram.com/developer/endpoints/users/#get_users
	*  
	* @param string $token
	* @param string $userId
	* @return array 
	*/
	public function getUserInformation($token, $userId)
	{
        $url = $this->getBaseUrl($this->userInfoUrl, $token, $userId);

        return $this->getRequestResponse($url);
	}

	/**
	* Get the most recent media published by a user.
	* The public_content scope is required if the user is not the owner of the access_token.
	*  
	* @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent
	*  
	* @param string $token
	* @param string $userId
	* @return array
	*/	
	public function getUserRecentMedia($token, $userId)
	{
		$url = $this->getBaseUrl($this->userMediaUrl, $token, $userId);

		return $this->getRequestResponse($url);
	}

	/**
	* Get a list of users matching the query.
	*
	* @see https://api.instagram.com/v1/users/search?q=jack&access_token=ACCESS-TOKEN
	*
	* @param string $token
	* @param string $userId
	* @return array
	*/
	public function userSearch($token, $param)
	{
		$url = $this->getBaseUrl($this->userSearchUrl, $token, $param);

		return $this->getRequestResponse($url);
	}
}