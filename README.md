# Instagram

A simple inspired by socialite library for laravel to authenticate users and obtain data from instagram api.

[![Total Downloads](https://poser.pugx.org/dotenv/instagram/downloads.svg)](https://packagist.org/packages/dotenv/instagram)
[![Latest Stable Version](https://poser.pugx.org/dotenv/instagram/v/stable.svg)](https://packagist.org/packages/dotenv/instagram)
[![Build Status](https://travis-ci.org/dotenv/instagram.svg?branch=master)](https://travis-ci.org/dotenv/instagram) 

### Installing
The package installation can be done with composer by the following command:

```shell
composer require dotenv/instagram
```

## Usage

### 1 - Add the ServiceProvider in the app/config.php file.

```php
Dotenv\Instagram\Providers\InstagramServiceProvider::class,

```

### 2 - Register an alias in the app/config.php file.

```php
'Instagram' => Dotenv\Instagram\Facades\Instagram::class,

```

### 3 - How to use it?

```php
<?php

Route::get('auth/', function() {
	
	return \Instagram::authenticate();
});

Route::ge('auth/callback', function() {
	
	$user = \Instagram::retrieveUser();

   	$userFromToken = \Instagram::userFromToken($user->token);
});

```

## License

Instagram library is licensed under [The MIT License (MIT)] (https://github.com/dotenv/instagram/blob/master/LICENSE)(LICENSE).
