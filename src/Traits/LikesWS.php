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

trait LikesWS
{
	/**
	* Media likes url
	*
	* @var $mediaLikesUrl
	*/
	private $mediaLikesUrl = 'media/%s/likes';

	/**
	* Media likesd delete url
	*
	* @var $mediaLikesDelUrl
	*/	
	private $mediaLikesDelUrl = 'media/%s/likes';

	/**
	* Get a list of users who have liked this media.
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/likes/#get_media_likes
	*
	* @param string $token
	* @param string $mediaId
	* @return array
	*/
	public function mediaLikes($token, $mediaId)
	{
		$url = $this->getBaseUrl($this->mediaLikesUrl, $token, $mediaId);

		return $this->getRequestResponse($url);
	}

	/**
	* Set a like on this media by the currently authenticated user.
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/likes/#post_likes
	*
	* @param string $token
	* @param string $mediaId
	* @return array
	*/
	public function likeThisMedia($token, $mediaId)
	{
		$url = $this->getBaseUrl($this->mediaLikesUrl, $token, $mediaId);

		return $this->postRequestResponse($url, ['access_token' => $token]);
	}

	/**
	* Set a like on this media by the currently authenticated user.
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/likes/#post_likes
	*
	* @param string $token
	* @param string $mediaId
	* @return array
	*/
	public function unlikeThisMedia($token, $mediaId)
	{
		$url = $this->getBaseUrl($this->mediaLikesDelUrl, $token, $mediaId);

		return $this->delRequestResponse($url);
	}
}