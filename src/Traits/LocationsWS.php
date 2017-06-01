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

use Dotenv\Instagram\Exceptions\InvalidParamException;

trait LocationsWS
{
	/**
	* Location url
	*
	* @var $locationUrl
	*/
	private $locationUrl = 'locations/%s';

	/**
	* Location media recent url
	*
	* @var $locationMediaRecentUrl
	*/
	private $locationMediaRecentUrl = 'locations/%s/media/recent';

	/**
	* Location media recent url
	*
	* @var $searchLocationUrl
	*/
	private $searchLocationUrl = 'locations/search';

	/**
	* Get information about a location.
	*
	* @see https://www.instagram.com/developer/endpoints/locations/#get_locations
	*
	* @param string $token
	* @param string $locationId
	* @param array
	*/
	public function getLocation($token, $locationId)
	{
		$url = $this->getBaseUrl($this->locationUrl, $token, $locationId);

		return $this->getRequestResponse($url);
	}

	/**
	* Get a list of recent media objects from a given location.
	*
	* @see https://www.instagram.com/developer/endpoints/locations/#get_locations_media_recent
	*
	* @param string $token
	* @param string $locationId
	* @param array  $params (max_id | min_id)
	* @param array
	*/
	public function getLocationMediaRecent($token, $locationId, $params = [])
	{
		$params = array_filter($params);

		$url = $this->getBaseUrl($this->locationMediaRecentUrl, $token, $locationId);
		$url = $this->buildUrlFromBase($url, '&', $params);

		return $this->getRequestResponse($url);
	}

	/**
	* Search for a location by geographic coordinate.
	*
	* @see https://www.instagram.com/developer/endpoints/locations/#get_locations_search
	*
	* @param string $token
	* @param string $locationId
	* @param array
	*/
	public function searchLocation($token, $params = [])
	{
		$params = array_filter($params);

		if (!isset($params['facebook_places_id']) && (!isset($params['lat']) || !isset($params['lng'])))
		{
			throw new InvalidParamException("You must provide an array with lat and lng or facebook_places_id in the second parameter.");
		}

		$url = $this->getBaseUrl($this->searchLocationUrl, $token, []);
		$url = $this->buildUrlFromBase($url, '&', $params);

		return $this->getRequestResponse($url);
	}
}