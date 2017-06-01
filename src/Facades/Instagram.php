<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Instagram.
 */
class Instagram extends Facade
{
    protected static function getFacadeAccessor() { return 'instagram'; }
}
