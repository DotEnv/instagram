<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//https://www.quora.com/Why-doesnt-Instagram-allow-3rd-party-applications-to-upload-photos-through-its-API
//http://lancenewman.me/posting-a-photo-to-instagram-without-a-phone/

namespace Dotenv\Instagram\Traits;

use GuzzleHttp\ClientInterface;

trait RelationshipsWS
{
	/**
	* Logged user follows url 
	*
	* @var $followsUrl
	*/
	private $followsUrl = 'users/self/follows';

	/**
	* Logged user followed by url
	* 
	* @var $followedByUrl
	*/
	private $followedByUrl = 'users/self/followed-by';

	/**
	* Logged user request by url
	* 
	* @var $requestByUrl
	*/
	private $requestedByUrl = 'users/self/requested-by';	

	/**
	* Public user relationship url
	*
	* @var $requestByUrl
	*/
	private $userRelationshipUrl = 'users/%s/relationship';

	/**
	* Get the list of users this user follows.
	*
	* @see https://www.instagram.com/developer/endpoints/relationships/#get_users_follows
	*
	* @param string $token
	* @return array
	*/
	public function whoIFollow($token)
	{
		$url = $this->getBaseUrl($this->followsUrl, $token);

		return $this->getRequestResponse($url);
	}

	/**
	* Get the list of users this user is followed by.
	*
	* @see https://www.instagram.com/developer/endpoints/relationships/#get_users_followed_by
	*
	* @param string $token
	* @return array 
	*/
	public function followedByMe($token)
	{
		$url = $this->getBaseUrl($this->followedByUrl, $token);

		return $this->getRequestResponse($url);
	}

	/**
	* List the users who have requested this user's permission to follow.
	*
	* @see https://www.instagram.com/developer/endpoints/relationships/#get_incoming_requests
	*
	* @param string $token
	* @return array
	*/
	public function whoIRequestedBy($token)
	{
		$url = $this->getBaseUrl($this->requestedByUrl, $token);
		
		return $this->getRequestResponse($url);
	}

	/**
	* Get information about a relationship to another user. 
	* Relationships are expressed using the following terms in the response:
	*
	* outgoing_status: Your relationship to the user. Can be 'follows', 'requested', 'none'.
	* incoming_status: A user's relationship to you. Can be 'followed_by', 'requested_by', 'blocked_by_you', 'none'.
	*
	* @see https://www.instagram.com/developer/endpoints/relationships/#get_relationship
	*
	* @param string $token
	* @param string $userId
	* @return array
	*/	
	public function userRelationship($token, $userId)
	{
		$url = $this->getBaseUrl($this->userRelationshipUrl, $token, $userId);

		return $this->getRequestResponse($url);
	}

	/**
	* Modify the relationship between the current user and the target user. 
	* You need to include an action parameter to specify the relationship action you want to perform. 
	* Valid actions are: 'follow', 'unfollow' 'approve' or 'ignore'. Relationships are expressed using the following terms in the response:
	* outgoing_status: Your relationship to the user. Can be 'follows', 'requested', 'none'.
 	* incoming_status: A user's relationship to you. Can be 'followed_by', 'requested_by', 'blocked_by_you', 'none'.
	*
	* @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
	* @todo validate action according to the param specification.
	*
	* @param string $token
	* @param string $userId
	* @param string $action  (follow | unfollow | approve | ignore)
	* @return array
	*/
	public function modifyUserRelationship($token, $userId, $action)
	{
		$url = $this->getBaseUrl($this->userRelationshipUrl, $token, $userId);

        return $this->postRequestResponse($url, ['action' => $action]);
	}
}