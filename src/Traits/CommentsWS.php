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

use Dotenv\Instagram\Traits\Validates;

trait CommentsWS
{
	use Validates;

	/**
	* Media comments url
	*
	* @var $followsUrl
	*/
	private $mediaCommentsUrl = 'media/%s/comments';
	
	/**
	* Media comments delete url
	*
	* @var $followsUrl
	*/	
	private $mediaCommentsDelUrl = 'media/%s/comments/%s';

	/**
	* Get a list of recent comments on a media object.
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/comments/#get_media_comments
	*
	* @param string $token
	* @param string $mediaId
	* @return array
	*/
	public function getMediaComments($token, $mediaId)
	{
		$url = $this->getBaseUrl($this->mediaCommentsUrl, $token, $mediaId);

        return $this->getRequestResponse($url);
	}

	/**
	* Create a comment on a media object with the following rules:
	* The total length of the comment cannot exceed 300 characters.
	* The comment cannot contain more than 4 hashtags.
	* The comment cannot contain more than 1 URL.
	* The comment cannot consist of all capital letters.
	* The public_content scope is required for media that does not belong to the owner of the access_token
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/comments/#post_media_comments
	*
	* @param string $token
	* @param string $mediaId
	* @param string $coment
	* @return array
	*/
	public function createMediaComment($token, $mediaId, $comment)
	{
		$this->validateComment($comment);

		$url = $this->getBaseUrl($this->mediaCommentsUrl, $token, $mediaId);

		return $this->postRequestResponse($url, ['text' => $comment]);
	}

	/**
	* Remove a comment either on the authenticated user's media object or authored by the authenticated user.
	* The public_content scope is required for media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/comments/#delete_media_comments
	*
	* @param string $token
	* @param string $param
	* @return array
	*/
	public function deleteMediaComment($token, $mediaId, $commentId)
	{
		$url = $this->getBaseUrl($this->mediaCommentsDelUrl, $token, [$mediaId, $commentId]);

		return $this->delRequestResponse($url);
	}	
}