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

interface Instagram
{
	/**
	* Redirect the user of the application to the provider's authentication screen.
	*
	* @return \Symfony\Component\HttpFoundation\RedirectResponse
	*/
    public function authenticate();

    /**
     * Get the User instance for the authenticated user.
     *
     * @return \Dotenv\Instagram\Contracts\User
     */
    public function retrieveUser();
}