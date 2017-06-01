<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram\Contracts;

interface User
{
	/**
	* Get user id
	*
	* @return string
	*/
	public function getId();

	/**
	* Get user name
	*
	* @return string
	*/
	public function getUserName();

	/**
	* Get user full name
	*
	* @return string
	*/
	public function getFullName();

	/**
	* Get user e-mail
	*
	* @return string
	*/
	public function getEmail();

	/**
	* Get user profile picture
	*
	* @return string
	*/
	public function getProfilePicture();
}