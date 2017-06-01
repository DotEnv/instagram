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

trait MediaWS
{
	/**
	* Media url
	*
	* @var $mediaUrl
	*/
	private $mediaUrl = 'media/%s';

	/**
	* Media shortcode url
	*
	* @var $mediaUrl
	*/
	private $mediaShortcodeUrl = 'media/shortcode/%s';

	/**
	* Media search url
	*
	* @var $mediaSearchUrl
	*/
	private $mediaSearchUrl = 'media/search?lat=%s&lng=%s';

	/**
	* Get information about a media object. Use the type field to differentiate between image and video media in the response. 
	* You will also receive the user_has_liked *field which tells you whether the owner of the access_token has liked this media.
	*
	* The public_content permission scope is required to get a media that does not belong to the owner of the access_token.
	*
	* @see https://www.instagram.com/developer/endpoints/media/#get_media
	*
	* @param string $token
	* @param string $mediaId
	* @param string $type (image | video)
	* @return array
	*/
	public function getMediaById($token, $mediaId)
	{
		$url = $this->getBaseUrl($this->mediaUrl, $token, $mediaId);

		return $this->getRequestResponse($url);
	}

	/**
	* This endpoint returns the same response as GET /media/media-id.
 	* A media object's shortcode can be found in its shortlink URL. An example shortlink is http://instagram.com/p/tsxp1hhQTG/. Its corresponding shortcode is tsxp1hhQTG.
	*
	* @see https://www.instagram.com/developer/endpoints/media/#get_media_by_shortcode
	*
	* @param string $token
	* @param string $shortcode
	* @return array
	*/
	public function getMediaByShortcode($token, $shortcode)
	{
		$url = $this->getBaseUrl($this->mediaShortcodeUrl, $token, $shortcode);

		return $this->getRequestResponse($url);
	}

	/**
	* Search for recent media in a given area.
	*
	* @see https://www.instagram.com/developer/endpoints/media/#get_media_search
	*
	* @param string $token
	* @param array  $params
	* @return array
	*/
	public function mediaSearch($token, $params = [])
	{
		$url = $this->getBaseUrl($this->mediaSearchUrl, $token, $params);

		return $this->getRequestResponse($url);
	}
}