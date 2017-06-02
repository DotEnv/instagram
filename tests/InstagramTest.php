<?php

namespace Tests;

use Mockery as m;

use Illuminate\Http\Request;
use GuzzleHttp\ClientInterface;
use PHPUnit_Framework_TestCase;

require 'Fixtures/InstagramStub.php';

class InstagramTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}	

	public function testRedirectGeneratesTheProperIlluminateRedirectResponse()
	{
        $request = Request::create('foo');
        $request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));

      	$session->shouldReceive('put')->once();
        $provider = new \Tests\Fixtures\InstagramStub($request, 'client_id', 'client_secret', 'redirect');
        $response = $provider->authenticate();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertSame('http://auth.url', $response->getTargetUrl());
	}

    public function testUserReturnsAUserInstanceForTheAuthenticatedRequest()
    {
      	$request = Request::create('foo', 'GET', ['state' => str_repeat('A', 40), 'code' => 'code']);
        $request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
        $session->shouldReceive('pull')->once()->with('state')->andReturn(str_repeat('A', 40));
        
        $provider = new \Tests\Fixtures\InstagramStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->http = m::mock('StdClass');
        
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';
        $provider->http->shouldReceive('post')->once()->with('http://token.url', [
            'headers' => ['Accept' => 'application/json'], $postKey => ['client_id' => 'client_id', 'client_secret' => 'client_secret', 'code' => 'code', 'redirect_uri' => 'redirect_uri'],
        ])->andReturn($response = m::mock('StdClass'));
        
        $response->shouldReceive('getBody')->once()->andReturn('{ "access_token" : "access_token", "refresh_token" : "refresh_token", "expires_in" : 3600 }');
        
        $user = $provider->retrieveUser();
        $this->assertInstanceOf('Dotenv\Instagram\User', $user);
        $this->assertSame('foo', $user->id);
        $this->assertSame('access_token', $user->token);
        $this->assertSame('refresh_token', $user->refreshToken);
        $this->assertSame(3600, $user->expiresIn);
    }
    
    /**
     * @expectedException \Dotenv\Instagram\Exceptions\InvalidStateException
     */
    public function testExceptionIsThrownIfStateIsInvalid()
    {    	
        $request = Request::create('foo', 'GET', ['state' => str_repeat('B', 40), 'code' => 'code']);

        $request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
        $session->shouldReceive('pull')->once()->with('state')->andReturn(str_repeat('A', 40));
        $provider = new \Tests\Fixtures\InstagramStub($request, 'client_id', 'client_secret', 'redirect');
        $provider->retrieveUser();
    }
 
    /**
     * @expectedException \Dotenv\Instagram\Exceptions\InvalidStateException
     */
    public function testExceptionIsThrownIfStateIsNotSet()
    {
        $request = Request::create('foo', 'GET', ['state' => 'state', 'code' => 'code']);
        
        $request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
        $session->shouldReceive('pull')->once()->with('state');
        $provider = new \Tests\Fixtures\InstagramStub($request, 'client_id', 'client_secret', 'redirect');
        $provider->retrieveUser();
    }    
}