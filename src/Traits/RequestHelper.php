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

trait RequestHelper
{
	/**
	* Mount url request
	*
	* @param string $url
	* @param string $token
	* @param string $param
	* @return string
	*/
	private function getBaseUrl($url, $token, $param = null)
	{
		$separator = '?';
	
		if (false !== strpos($url, '?'))
		{
			$separator = '&';
		}

		if (null !== $param)
		{
			$url = sprintf($url, $param);
		}
		
		return $this->baseUrl . '/' . $this->version . $url . $separator . 'access_token=' . $token;
	}
}