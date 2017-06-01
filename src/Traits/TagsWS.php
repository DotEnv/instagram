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

trait TagsWS
{
	/**
	* Tag url
	*
	* @var $tagUrl
	*/
	private $tagUrl = 'tags/%s';
	
	/**
	* Tag media recent url
	*
	* @var $tagMediaUrl
	*/
	private $tagMediaUrl = 'tags/%s/media/recent';

	/**
	* Tag search url
	*
	* @var $searchTagUrl
	*/
	private $searchTagUrl = 'tags/search?q=%s';

	/**
	* Get information about a tag object.
	*
	* @see https://www.instagram.com/developer/endpoints/tags/#get_tags
	*
	* @param string $token
	* @param string $tagName
	* @return array 
	*/
	public function getTag($token, $tagName)
	{
		$url = $this->getBaseUrl($this->tagUrl, $token, $tagName);

		return $this->getRequestResponse($url);
	}

	/**
	* Get a list of recently tagged media.
	*
	* @see https://www.instagram.com/developer/endpoints/tags/#get_tags_media_recent
	*
	* @param string $token
	* @param string $tagName
	* @return array
	*/
	public function getTagMediaRecent($token, $tagName)
	{
		$url = $this->getBaseUrl($this->tagMediaUrl, $token, $tagName);

		return $this->getRequestResponse($url);	
	}

	/**
	* Search for tags by name.
	*
	* @see https://www.instagram.com/developer/endpoints/tags/#get_tags_search
	*
	* @param string $token
	* @param string $tagName
	* @return array
	*/
	public function searchTag($token, $tagName)
	{
		$url = $this->getBaseUrl($this->searchTagUrl, $token, $tagName);

		return $this->getRequestResponse($url);	
	}
}