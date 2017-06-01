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

trait Validates
{
	/**
	* Validate commento according to instagram api docs.
	* 
	* @param string $comment
	* @throws InvalidParamException
	*/
	protected function validateComment($comment)
	{
		if (strlen($comment) > 300)
		{
			throw new InvalidParamException("The total length of the comment cannot exceed 300 characters.");
		}

		if (substr_count($comment, '#') > 4)
		{
			throw new InvalidParamException("The comment cannot contain more than 4 hashtags.");
		}

		if (substr_count($comment, 'http://') > 1)
		{
			throw new InvalidParamException("The comment cannot contain more than 1 URL.");
		}

		if (mb_strtoupper($comment, 'utf-8') == $comment)
		{
			throw new InvalidParamException("The comment cannot consist of all capital letters.");
		}
	} 
}