<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Dotenv\Instagram\Exceptions\InvalidStateException;

abstract class Provider
{
    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * The client ID.
     *
     * @var string
     */
    protected $clientId;
    
    /**
     * The client secret.
     *
     * @var string
     */
    protected $clientSecret;
    
    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectUrl;

    /**
     * The custom parameters to be sent with the request.
     *
     * @var array
     */
    protected $parameters = [];
    
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [];    

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ',';    

    /**
     * The type of the encoding in the query.
     *
     * @var int Can be either PHP_QUERY_RFC3986 or PHP_QUERY_RFC1738.
     */
    protected $encodingType = PHP_QUERY_RFC1738;    

    /**
     * Indicates if the session state should be utilized.
     *
     * @var bool
     */
    protected $stateless = false;

    /**
     * The custom Guzzle configuration options.
     *
     * @var bool
     */
    protected $guzzle = [];    

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clientId
     * @param  string  $clientSecret
     * @param  string  $redirectUrl
     * @param  array  $guzzle
     * @return void
     */
    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $scopes = [], $guzzle = [])
    {
        $this->guzzle       = $guzzle;
        $this->scopes       = $scopes;
        $this->request      = $request;
        $this->clientId     = $clientId;
        $this->redirectUrl  = $redirectUrl;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    abstract protected function getAuthUrl($state);
    
    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    abstract protected function getTokenUrl();    

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    abstract protected function getUserByToken($token);
    
    /**
     * Map the raw user array to a Instagram User instance.
     *
     * @param  array  $user
     * @return \Dotenv\Instagram\User
     */
    abstract protected function mapUserToObject(array $user);    

    /**
     * Redirect the user of the application to the provider's authentication screen.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authenticate()
    {
        $state = null;

        if ($this->usesState()) {
            $this->request->session()->put('state', $state = $this->getState());
        }

        return new RedirectResponse($this->getAuthUrl($state));
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $url
     * @param  string  $state
     * @return string
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        return $url.'?'.http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);
    }

    /**
     * Get the URL for the provider.
     *
     * @param  string  $url
     * @param  string  $separator
     * @param  array   $params
     * @return string
     */
    protected function buildUrlFromBase($url, $separator = '?', $params = [])
    {
    	return $url . $separator . http_build_query($params, '', '&', $this->encodingType);
    }

    /**
     * Get the GET parameters for the code request.
     *
     * @param  string|null  $state
     * @return array
     */
    protected function getCodeFields($state = null)
    {
        $fields = [
            'client_id'     => $this->clientId, 
            'redirect_uri'  => $this->redirectUrl,
            'scope'   	    => $this->formatScopes($this->getScopes(), $this->scopeSeparator),
            'response_type' => 'code',
        ];
        
        if ($this->usesState()) 
        {
            $fields['state'] = $state;
        }
        
        return array_merge($fields, $this->parameters);
    }

    /**
     * Format the given scopes.
     *
     * @param  array  $scopes
     * @param  string  $scopeSeparator
     * @return string
     */
    protected function formatScopes(array $scopes, $scopeSeparator)
    {
        return implode($scopeSeparator, $scopes);
    }    

    /**
     * {@inheritdoc}
     */
    public function retrieveUser()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }
        
        $response = $this->getAccessTokenResponse($this->getCode());
        
        $user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        ));

        return $user->setToken($token)
                    ->setRefreshToken(Arr::get($response, 'refresh_token'))
                    ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    /**
     * Get a Social User instance from a known access token.
     *
     * @param  string  $token
     * @return \Dotenv\Instagram\User
     */
    public function userFromToken($token)
    {
        $user = $this->mapUserToObject($this->getUserByToken($token));
        return $user->setToken($token);
    }
    
    /**
     * Determine if the current request / session has a mismatching "state".
     *
     * @return bool
     */
    protected function hasInvalidState()
    {
        if ($this->isStateless()) {
            return false;
        }

        $state = $this->request->session()->pull('state');
        return ! (strlen($state) > 0 && $this->request->input('state') === $state);
    }    

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            $postKey => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'client_id'     => $this->clientId, 
            'client_secret' => $this->clientSecret,
            'code' 		    => $code, 
            'redirect_uri'  => $this->redirectUrl,
        ];
    }

    /**
     * Get the code from the request.
     *
     * @return string
     */
    protected function getCode()
    {
        return $this->request->input('code');
    }    

    /**
     * Get the current scopes.
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) 
        {
            $this->httpClient = new Client($this->guzzle);
        }

        return $this->httpClient;
    }    

    /**
     * Determine if the provider is operating with state.
     *
     * @return bool
     */
    protected function usesState()
    {
        return ! $this->stateless;
    }
    
    /**
     * Determine if the provider is operating as stateless.
     *
     * @return bool
     */
    protected function isStateless()
    {
        return $this->stateless;
    }
    
    /**
     * Indicates that the provider should operate as stateless.
     *
     * @return $this
     */
    public function stateless()
    {
        $this->stateless = true;
        return $this;
    }
    
    /**
     * Get the string used for session state.
     *
     * @return string
     */
    protected function getState()
    {
        return Str::random(40);
    }

    /**
    * Retrieve GET request to the provided url
    * 
    * @param string $url
    * @return array
    */
    protected function getRequestResponse($url)
    {
		$response = $this->getHttpClient()->get($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
    * Retrieve POST request to the provided url
    * 
    * @param string $url
    * @return array
    */
    protected function postRequestResponse($url, $params = [])
    {
    	$postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $response = $this->getHttpClient()->post($url, [
            'headers' => [
            	'Accept' => 'application/json'
            ],
            $postKey => $params
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
    * Retrieve DELETE request to the provided url
    * 
    * @param string $url
    * @return array
    */
    protected function delRequestResponse($url)
    {
    	$response = $this->getHttpClient()->delete($url, [
            'headers' => ['Accept' => 'application/json']
        ]);

    	return json_decode($response->getBody(), true);
    }

	/**
	* Mount url request
	*
	* @todo factoring
	*
	* @param string $endpoint
	* @param string $token
	* @param string|array $params
	* @return string
	*/
	protected function getBaseUrl($endpoint, $token, $params = null)
	{
		$url = $this->baseUrl . '/' . $this->version . '/'. $endpoint;

		$separator = '?';
	
		if (false !== strpos($endpoint, '?'))
		{
			$separator = '&';
		}

		$params = !is_array($params) ? (array) $params : $params;

		$url = vsprintf($url, $params);

		return $url .= $separator . 'access_token=' . $token;
	}
}