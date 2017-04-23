<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * Based off Light PHP wrapper for the OAuth 2.0 protocol.
 *
 * This client is based on the OAuth2 specification draft v2.15
 * http://tools.ietf.org/html/draft-ietf-oauth-v2-15
 *
 * @author      Pierrick Charron <pierrick@webstart.fr>
 * @author      Anis Berejeb <anis.berejeb@gmail.com>

 *
 * @author      Joe Foster (Ulminia) <ulminia@gmail.com>

 */
//namespace OAuth2;

require_once(ROSTER_LIB . 'api2/GrantType/IGrantType.php');
require_once(ROSTER_LIB . 'api2/GrantType/AuthorizationCode.php');
require_once(ROSTER_LIB . 'api2/api_cache.php');
require_once(ROSTER_LIB . 'api2/api-item.php');

class Client
{
    /**
     * Different AUTH method
     */
    const AUTH_TYPE_URI                 = 0;
    const AUTH_TYPE_AUTHORIZATION_BASIC = 1;
    const AUTH_TYPE_FORM                = 2;

    /**
     * Different Access token type
     */
    const ACCESS_TOKEN_URI      = 0;
    const ACCESS_TOKEN_BEARER   = 1;
    const ACCESS_TOKEN_OAUTH    = 2;
    const ACCESS_TOKEN_MAC      = 3;

    /**
    * Different Grant types
    */
    const GRANT_TYPE_AUTH_CODE          = 'authorization_code';
    const GRANT_TYPE_PASSWORD           = 'password';
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_id';
    const GRANT_TYPE_REFRESH_TOKEN      = 'refresh_token';

    /**
     * HTTP Methods
     */
    const HTTP_METHOD_GET    = 'GET';
    const HTTP_METHOD_POST   = 'POST';
    const HTTP_METHOD_PUT    = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';
    const HTTP_METHOD_HEAD   = 'HEAD';
    const HTTP_METHOD_PATCH  = 'PATCH';

    /**
     * HTTP Form content types
     */
    const HTTP_FORM_CONTENT_TYPE_APPLICATION = 0;
    const HTTP_FORM_CONTENT_TYPE_MULTIPART = 1;

    /**
     * Client ID
     *
     * @var string
     */
    protected $client_id = null;

    /**
     * Client Secret
     *
     * @var string
     */
    protected $client_secret = null;

    /**
     * Client Authentication method
     *
     * @var int
     */
    protected $client_auth = self::AUTH_TYPE_URI;

    /**
     * Access Token
     *
     * @var string
     */
    protected $access_token = null;

    /**
     * Access Token Type
     *
     * @var int
     */
    protected $access_token_type = self::ACCESS_TOKEN_URI;

    /**
     * Access Token Secret
     *
     * @var string
     */
    protected $access_token_secret = null;

    /**
     * Access Token crypt algorithm
     *
     * @var string
     */
    protected $access_token_algorithm = null;

    /**
     * Access Token Parameter name
     *
     * @var string
     */
    protected $access_token_param_name = 'access_token';

    /**
     * The path to the certificate file to use for https connections
     *
     * @var string  Defaults to .
     */
    protected $certificate_file = null;

    /**
     * cURL options
     *
     * @var array
     */
    protected $curl_options = array();

	/**
	 *	Redirect uri
	 *
	 */
	public $redirect_uri = '';
	
	
	/**
	 *	Base url setting
	 *
	 */
	public $baseurl = array(

			'US' => array(
				'urlbase'					=> 'https://us.api.battle.net/',
				'AUTHORIZATION_ENDPOINT'	=> 'https://us.battle.net/oauth/authorize',
				'TOKEN_ENDPOINT'			=> 'https://us.battle.net/oauth/token',
			),
			'EU' => array(
				'urlbase'					=> 'https://eu.api.battle.net/',
				'AUTHORIZATION_ENDPOINT'	=> 'https://eu.battle.net/oauth/authorize',
				'TOKEN_ENDPOINT'			=> 'https://eu.battle.net/oauth/token',
			),
			'KR' => array(
				'urlbase'					=> 'https://kr.api.battle.net/',
				'AUTHORIZATION_ENDPOINT'	=> 'https://kr.battle.net/oauth/authorize',
				'TOKEN_ENDPOINT'			=> 'https://kr.battle.net/oauth/token',
			),
			'TW' => array(
				'urlbase'					=> 'https://tw.api.battle.net/',
				'AUTHORIZATION_ENDPOINT'	=> 'https://tw.battle.net/oauth/authorize',
				'TOKEN_ENDPOINT'			=> 'https://tw.battle.net/oauth/token',
			),
			'SEA' => array(
				'urlbase'					=> 'https://sea.api.battle.net/',
				'AUTHORIZATION_ENDPOINT'	=> 'https://sea.battle.net/oauth/authorize',
				'TOKEN_ENDPOINT'			=> 'https://sea.battle.net/oauth/token',
			),
	);
	
	public $ignore_cache = false;
	/**
	 *	region setting
	 *
	 */
	public $region = '';
	
	 /**
	 *	Locale setting
	 *
	 */
	public $locale = '';
	
	/*
	*	some tracking bits for people
	*/
	public $usage = array(
				'type'				=> '',
				'url'				=> '',
				'responce_code'		=> '',
				'content_type'		=> '',
				'locale'			=> '',
			);
	public $cache;
	public $item;
	
	public $errno = CURLE_OK;
	public $error = '';
    /**
     * Construct
     *
     * @param string $client_id Client ID
     * @param string $client_secret Client Secret
     * @param int    $client_auth (AUTH_TYPE_URI, AUTH_TYPE_AUTHORIZATION_BASIC, AUTH_TYPE_FORM)
     * @param string $certificate_file Indicates if we want to use a certificate file to trust the server. Optional, defaults to null.
     * @return void
     */
    public function __construct($client_id, $client_secret, $region, $locale, $redirect_uri)
    {
        if (!extension_loaded('curl')) {
            throw new Exception('The PHP exention curl must be installed to use this library.', Exception::CURL_NOT_FOUND);
        }
		if (!$this->ignore_cache)
		{
			$this->cache = new apicache();
		}
		$this->item = new api_item();
		$r = preg_replace('/http:/', 'https:', $redirect_uri);
		$client_auth			= self::AUTH_TYPE_URI;
        $this->client_id		= $client_id;
        $this->client_secret	= $client_secret;
		$this->region			= $region;
		$this->locale			= $locale;
        $this->client_auth		= $client_auth;
		$this->redirect_uri		= $r;
    }

	public function set_region($region)
	{
		$this->region = $region;
	}
	
    /**
     * Get the client Id
     *
     * @return string Client ID
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Get the client Secret
     *
     * @return string Client Secret
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * getAuthenticationUrl
     *
     * @param string $auth_endpoint Url of the authentication endpoint
     * @param string $redirect_uri  Redirection URI
     * @param array  $extra_parameters  Array of extra parameters like scope or state (Ex: array('scope' => null, 'state' => ''))
     * @return string URL used for authentication
     */
    public function getAuthenticationUrl($auth_endpoint, $redirect_uri, array $extra_parameters = array())
    {
        $parameters = array_merge(array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
			'scope'			=> 'wow.profile',
			'auth_flow'		=> 'auth_code',
            'redirect_uri'  => $redirect_uri
        ), $extra_parameters);
        return $auth_endpoint . '?' . http_build_query($parameters, null, '&');
    }

    /**
     * getAccessToken
     *
     * @param string $token_endpoint    Url of the token endpoint
     * @param int    $grant_type        Grant Type ('authorization_code', 'password', 'client_credentials', 'refresh_token', or a custom code (@see GrantType Classes)
     * @param array  $parameters        Array sent to the server (depend on which grant type you're using)
     * @return array Array of parameters required by the grant_type (CF SPEC)
     */
    public function getAccessToken($token_endpoint, $grant_type, array $parameters)
    {
        if (!$grant_type) {
            throw new InvalidArgumentException('The grant_type is mandatory.', InvalidArgumentException::INVALID_GRANT_TYPE);
        }
        $grantTypeClassName = $this->convertToCamelCase($grant_type);
        $grantTypeClass =  __NAMESPACE__ . '' . $grantTypeClassName;
        if (!class_exists($grantTypeClass)) {
            throw new InvalidArgumentException('Unknown grant type \'' . $grant_type . '\'', InvalidArgumentException::INVALID_GRANT_TYPE);
        }
        $grantTypeObject = new $grantTypeClass();
        $grantTypeObject->validateParameters($parameters);
        if (!defined($grantTypeClass . '::GRANT_TYPE')) {
            throw new Exception('Unknown constant GRANT_TYPE for class ' . $grantTypeClassName, Exception::GRANT_TYPE_ERROR);
        }
        $parameters['grant_type'] = $grantTypeClass::GRANT_TYPE;
        $http_headers = array();
        switch ($this->client_auth) {
            case self::AUTH_TYPE_URI:
            case self::AUTH_TYPE_FORM:
                $parameters['client_id'] = $this->client_id;
                $parameters['client_secret'] = $this->client_secret;
                break;
            case self::AUTH_TYPE_AUTHORIZATION_BASIC:
                $parameters['client_id'] = $this->client_id;
                $http_headers['Authorization'] = 'Basic ' . base64_encode($this->client_id .  ':' . $this->client_secret);
                break;
            default:
                throw new Exception('Unknown client auth type.', Exception::INVALID_CLIENT_AUTHENTICATION_TYPE);
                break;
        }

        $result = $this->executeRequest($token_endpoint, $parameters, self::HTTP_METHOD_POST, $http_headers, self::HTTP_FORM_CONTENT_TYPE_APPLICATION);

		return $result;
    }

    /**
     * setToken
     *
     * @param string $token Set the access token
     * @return void
     */
    public function setAccessToken($token)
    {
        $this->access_token = $token;
    }

    /**
     * Set the client authentication type
     *
     * @param string $client_auth (AUTH_TYPE_URI, AUTH_TYPE_AUTHORIZATION_BASIC, AUTH_TYPE_FORM)
     * @return void
     */
    public function setClientAuthType($client_auth)
    {
        $this->client_auth = $client_auth;
    }

    /**
     * Set an option for the curl transfer
     *
     * @param int   $option The CURLOPT_XXX option to set
     * @param mixed $value  The value to be set on option
     * @return void
     */
    public function setCurlOption($option, $value)
    {
        $this->curl_options[$option] = $value;
    }

    /**
     * Set multiple options for a cURL transfer
     *
     * @param array $options An array specifying which options to set and their values
     * @return void
     */
    public function setCurlOptions($options) 
    {
        $this->curl_options = array_merge($this->curl_options, $options);
    }

    /**
     * Set the access token type
     *
     * @param int $type Access token type (ACCESS_TOKEN_BEARER, ACCESS_TOKEN_MAC, ACCESS_TOKEN_URI)
     * @param string $secret The secret key used to encrypt the MAC header
     * @param string $algorithm Algorithm used to encrypt the signature
     * @return void
     */
    public function setAccessTokenType($type, $secret = null, $algorithm = null)
    {
        $this->access_token_type = $type;
        $this->access_token_secret = $secret;
        $this->access_token_algorithm = $algorithm;
    }

	
	protected function _buildUrl($path, $params = array())
    {
		// allways called in all api calls
		$params['apikey'] = $this->client_id;
		if (isset($this->access_token))
		{
			$params['access_token']	= $this->access_token;
		}
		//set for translation
		$params['locale'] = $this->locale;

		$url = $this->baseurl[$this->region]['urlbase'];
		//$url .= $path;
		$url .= self::_buildtype($path,$params);
		$this->usage['url'] = $url;
		unset($params['name']);
		unset($params['server']);
		$url .= (count($params)) ? '?' . $this->_build_strings($params, '&') : '';
		$this->usage['type'] = $path;
		
		$this->usage['locale'] = $this->locale;
		//echo $url;
		return $url;
		
    }
	
	function _build_strings($params, $sep)
	{
		$e = '';
		$r = array();
		foreach($params as $key=>$val)
		{
			$r[] = $key.'='.$val;
		}
		$e = implode($sep, $r);
		return $e;
	}
	
	/**
	*	Type of call uri build
	*	$class - type of call
	*	$fields - array of data (name,server,size)
	**/
	public function _buildtype($class,$fields)
	{
		switch ($class)
		{
			case 'achievement':
						$q = 'wow/achievement/'.$fields['id'];
					break;
			case 'auction':
						$q = 'wow/auction/data/'.$fields['server'];
					break;
			case 'abilities':
						$q = 'wow/pet/ability/'.$fields['id'];
					break;
			case 'species':
						$q = 'wow/pet/species/'.$fields['id'];
					break;
			case 'stats':
						$q = 'wow/pet/stats/'.$fields['id'];
					break;
			case 'realm_leaderboard':
						$q = 'wow/challenge/'.$fields['server'];
					break;
			case 'region_leaderboard':
						$q = 'wow/challenge/region';
					break;
			case 'team':
						$q = 'wow/arena/'.$fields['server'].'/'.$fields['size'].'/'.$fields['name'];
					break;
			case 'character':
						$q = 'wow/character/'.$fields['server'].'/'.$fields['name'];
					break;
			case 'item':
						$c='';
						if (isset($fields['context']))
						{
							$c = '/'.$fields['context'];
						}
						if ($fields['id'] == 0 OR empty($fields['id']))
						{
							return array();
						}
						$q = 'wow/item/'.$fields['id'].$c;
					break;
			case 'item_set':
						$q = 'wow/item/set/'.$fields['id'];
					break;
			case 'guild':
						$q = 'wow/guild/'.$fields['server'].'/'.$fields['name'];
					break;
			case 'leaderboards':
						$q = 'wow/leaderboard/'.$fields['size'];
					break;
			case 'quest':
						$q = 'wow/quest/'.$fields['id'];
					break;
			case 'realmstatus':
						$q = 'wow/realm/status';
					break;
			case '_realmstatus':
						$q = 'wow/realm/status/'.$fields['name'];
					break;
			case 'recipe':
						$q = 'wow/recipe/'.$fields['id'];
					break;
			case 'spell':
						$q = 'wow/spell/'.$fields['id'];
					break;
			case 'battlegroups':
						$q = 'wow/data/battlegroups/';
					break;
			case 'races':
						$q = 'wow/data/character/races';
					break;
			case 'classes':
						$q = 'wow/data/character/classes';
					break;
			case 'achievements':
						$q = 'wow/data/character/achievements';
					break;
			case 'guild_rewards':
						$q = 'wow/data/guild/rewards';
					break;
			case 'guild_perks':
						$q = 'wow/data/guild/perks';
					break;
			case 'guild_achievements':
						$q = 'wow/data/guild/achievements';
					break;
			case 'item_classes':
						$q = 'wow/data/item/classes';
					break;
			case 'talents':
						$q = 'wow/data/talents';
					break;
			case 'pet_types':
						$q = 'wow/data/pet/types';
					break;
			case 'pet':
						$q = 'wow/pet/';
					break;
			case 'mount':
						$q = 'wow/mount/';
					break;
			case 'zone_master':
						$q = 'wow/zone/';
					break;
			case 'zone':
						$q = 'wow/zone/'.$fields['id'];
					break;
			case 'sc2profile':
					if ($this->access_token)
					{
						$q = 'sc2/profile/user';
					}else{
						throw new Exception('Access Token Required for this call.', Exception::MISSING_PARAMETER);
					}
					break;
			case 'wowprofile':
					if ($this->access_token)
					{
						$q = 'wow/user/characters';
					}else{
					//echo $this->access_token.'<br>';
						throw new Exception('Access Token Required for this call.', Exception::MISSING_PARAMETER);
					}
					break;
			case 'account':
					if ($this->access_token)
					{
						$q = 'account/user';
					}else{
						throw new Exception('Access Token Required for this call.', Exception::MISSING_PARAMETER);
					}
					break;
			case 'accountid':
					if ($this->access_token)
					{
						$q = 'account/user/id';
					}else{
						throw new Exception('Access Token Required for this call.', Exception::MISSING_PARAMETER);
					}
					break;
			case 'battletag':
					if ($this->access_token)
					{
						$q = 'account/user/battletag';
					}else{
						throw new Exception('Access Token Required for this call.', Exception::MISSING_PARAMETER);
					}
					break;

			default:
			break;
		}
		$q = str_replace(' ' , '%20' , $q);
		return $q;
	}
	
    /**
     * Fetch a protected ressource
     *
     * @param string $protected_ressource_url Protected resource URL
     * @param array  $parameters Array of parameters
     * @param string $http_method HTTP Method to use (POST, PUT, GET, HEAD, DELETE)
     * @param array  $http_headers HTTP headers
     * @param int    $form_content_type HTTP form content type to use
     * @return array
     */
    public function fetch($protected_resource_url, $parameters = array(), $http_headers = array(), $http_method = self::HTTP_METHOD_GET, $form_content_type = self::HTTP_FORM_CONTENT_TYPE_MULTIPART)
    {
		global $roster;
		$protected_resource_url = self::_buildUrl($protected_resource_url, $parameters);

		//echo $protected_resource_url.'<br>';
		$lmh=null;
		if ( !$this->ignore_cache )
		{
			$ret = date('Y/m/d H:i:s', ($string/1000));
			$roster->debug->_debug( 2, $ret, 'Cache not ignored checking LMH', 'OK' );
		
			$lmh = $this->cache->_get_lmh( $parameters );
			//echo'<pre>~~';print_r($lmh);echo '~~</pre>';
			//if no header info make the call as normal if there is a header apply it
			if (isset($lmh['Last-Modified']))
			{
				$http_headers['If-Modified-Since'] = gmdate('D, d M Y H:i:s \G\M\T',$lmh['Last-Modified']);
			}
		}
		
        if ($this->access_token) {
            switch ($this->access_token_type) {
                case self::ACCESS_TOKEN_URI:
                    if (is_array($parameters)) {
                        $parameters[$this->access_token_param_name] = $this->access_token;
                    } else {
                        throw new InvalidArgumentException(
                            'You need to give parameters as array if you want to give the token within the URI.',
                            InvalidArgumentException::REQUIRE_PARAMS_AS_ARRAY
                        );
                    }
                    break;
                case self::ACCESS_TOKEN_BEARER:
                    $http_headers['Authorization'] = 'Bearer ' . $this->access_token;
                    break;
                case self::ACCESS_TOKEN_OAUTH:
                    $http_headers['Authorization'] = 'OAuth ' . $this->access_token;
                    break;
                case self::ACCESS_TOKEN_MAC:
                    $http_headers['Authorization'] = 'MAC ' . $this->generateMACSignature($protected_resource_url, $parameters, $http_method);
                    break;
                default:
                    throw new Exception('Unknown access token type.', Exception::INVALID_ACCESS_TOKEN_TYPE);
                    break;
            }
        }
		/*
			now some cache-jutsu
		*/
		//are we using the cache
		if ( $this->ignore_cache && empty($lmh) )
		{
			$ret = date('Y/m/d H:i:s', ($string/1000));
			$roster->debug->_debug( 2, $ret, 'No Cache!', 'OK' );
		
			// we are ignoring the cache ignore it make the call return the data
			$result = $this->executeRequest($protected_resource_url, $parameters, $http_method, $http_headers, $form_content_type);
			// we are still going to update lmh and data tho...
			$this->cache->put_lmh($result,$parameters);
			call_user_func(array($this->cache, 'insert'.$this->usage['type']),$result,$this->usage,$parameters);
			return $result;
		}
		else
		{
			$ret = date('Y/m/d H:i:s', ($string/1000));
			$roster->debug->_debug( 2, $ret, 'Return Cache', 'OK' );
		
			//we are using the cacahe
			if ( method_exists($this->cache, $this->usage['type']) && is_callable(array($this->cache, $this->usage['type'])) )
			{
				//poll the cache table if we have data and it has not expired return it else make the call to get the data 
				$data = call_user_func(array($this->cache, $this->usage['type']),$parameters,$this->usage);
				if (is_array($data))
				{
					//echo 'i have data<br>';
					return $data;
				}
				else
				{
					//echo 'no data saved get new<br>';
					$result = $this->executeRequest($protected_resource_url, $parameters, $http_method, $http_headers, $form_content_type);
					if ($result['http_code'] != '304' )
					{
						$this->cache->put_lmh($result,$parameters);
						if (method_exists($this->cache, 'insert'.$this->usage['type']) && is_callable(array($this->cache, 'insert'.$this->usage['type'])))
						{
							call_user_func(array($this->cache, 'insert'.$this->usage['type']),$result,$this->usage,$parameters);
						}
						return $result;
					}
				}
			}
			else
			{
				return false;
			}
		}
    }

    /**
     * Generate the MAC signature
     *
     * @param string $url Called URL
     * @param array  $parameters Parameters
     * @param string $http_method Http Method
     * @return string
     */
    private function generateMACSignature($url, $parameters, $http_method)
    {
        $timestamp = time();
        $nonce = uniqid();
        $parsed_url = parse_url($url);
        if (!isset($parsed_url['port']))
        {
            $parsed_url['port'] = ($parsed_url['scheme'] == 'https') ? 443 : 80;
        }
        if ($http_method == self::HTTP_METHOD_GET) {
            if (is_array($parameters)) {
                $parsed_url['path'] .= '?' . http_build_query($parameters, null, '&');
            } elseif ($parameters) {
                $parsed_url['path'] .= '?' . $parameters;
            }
        }

        $signature = base64_encode(hash_hmac($this->access_token_algorithm,
                    $timestamp . "\n"
                    . $nonce . "\n"
                    . $http_method . "\n"
                    . $parsed_url['path'] . "\n"
                    . $parsed_url['host'] . "\n"
                    . $parsed_url['port'] . "\n\n"
                    , $this->access_token_secret, true));

        return 'id="' . $this->access_token . '", ts="' . $timestamp . '", nonce="' . $nonce . '", mac="' . $signature . '"';
    }

    /**
     * Execute a request (with curl)
     *
     * @param string $url URL
     * @param mixed  $parameters Array of parameters
     * @param string $http_method HTTP Method
     * @param array  $http_headers HTTP Headers
     * @param int    $form_content_type HTTP form content type to use
     * @return array
     */
    private function executeRequest($url, $parameters = array(), $http_method = self::HTTP_METHOD_GET, array $http_headers = null, $form_content_type = self::HTTP_FORM_CONTENT_TYPE_MULTIPART)
    {
		global $roster;
		
		echo $url.'<br>'.$http_method.'<br>';
		
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CUSTOMREQUEST  => $http_method
        );
        switch($http_method) {
            case self::HTTP_METHOD_POST:
                $curl_options[CURLOPT_POST] = true;
				
                /* No break */
            case self::HTTP_METHOD_PUT:
			case self::HTTP_METHOD_PATCH:

                /**
                 * Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data,
                 * while passing a URL-encoded string will encode the data as application/x-www-form-urlencoded.
                 * http://php.net/manual/en/function.curl-setopt.php
                 */
                if(is_array($parameters) && self::HTTP_FORM_CONTENT_TYPE_APPLICATION === $form_content_type) {
                    $parameters = http_build_query($parameters, null, '&');
                }
                $curl_options[CURLOPT_POSTFIELDS] = $parameters;
                break;
            case self::HTTP_METHOD_HEAD:
                $curl_options[CURLOPT_NOBODY] = true;
                /* No break */
            case self::HTTP_METHOD_DELETE:
            case self::HTTP_METHOD_GET:
                
                break;
            default:
                break;
        }
		//echo $url.'<br>';
        $curl_options[CURLOPT_URL] = $url;
		$curl_options[CURLOPT_HEADER] = true;

        if (is_array($http_headers)) {
            $header = array();
            foreach($http_headers as $key => $parsed_urlvalue) {
                $header[] = "$key: $parsed_urlvalue";
            }
            $curl_options[CURLOPT_HTTPHEADER] = $header;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        // https handling
        if (!empty($this->certificate_file))
		{
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->certificate_file);
        } else {
            // bypass ssl verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if (!empty($this->curl_options))
		{
            curl_setopt_array($ch, $this->curl_options);
        }
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$this->errno	= curl_errno($ch);
		$this->error	= curl_error($ch);

        if ($this->errno)
		{
			$roster->set_message( "".print_r($this->errno)."<br/>\n\r [".print_r($this->error).']', 'API Error', 'error' );
			$this->cache->api_error($this->usage['type'], $this->usage['url'], $this->errno, $this->error, $http_code, $content_type);
			return false;
        }
		else
		{
			$header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
			$json_decode = json_decode(substr( $result, $header_size ), true);
			$json_decode['header'] = $this->get_headers_from_curl_response(substr($result, 0, $header_size));
			$json_decode['http_code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			$json_decode['last_url'] = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);

		$this->usage['responce_code'] = $http_code;
		$this->usage['content_type'] = $content_type;
		if (isset($this->usage['type']) && isset($this->usage['url']))
		{
			$this->cache->api_track($this->usage['type'], $this->usage['url'], $this->usage['responce_code'], $this->usage['content_type']);
		}

		if (isset($json_decode['status']) && $json_decode['status'] == 'nok')
		{
			$roster->set_message( "".$json_decode['reason']."<br/>\n\r [".$this->usage['url'].'] : '.$this->usage['responce_code'].'', 'API Error', 'error' );
			$this->cache->api_error($this->usage['type'], $this->usage['url'], $this->errno.' - '.$json_decode['status'], $json_decode['reason'], $http_code, $content_type);
			return;
		}
		return (null === $json_decode) ? $result : $json_decode;
    }

	public function get_headers_from_curl_response($response)
	{
		$headers = array();

		$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

		foreach (explode("\r\n", $header_text) as $i => $line)
			if ($i === 0)
				$headers['http_code'] = $line;
			else
			{
				list ($key, $value) = explode(': ', $line);

				$headers[$key] = $value;
			}

		return $headers;
	}
    /**
     * Set the name of the parameter that carry the access token
     *
     * @param string $name Token parameter name
     * @return void
     */
    public function setAccessTokenParamName($name)
    {
        $this->access_token_param_name = $name;
    }

    /**
     * Converts the class name to camel case
     *
     * @param  mixed  $grant_type  the grant type
     * @return string
     */
    private function convertToCamelCase($grant_type)
    {
        $parts = explode('_', $grant_type);
        array_walk($parts, function(&$item) { $item = ucfirst($item);});
        return implode('', $parts);
    }
}
/*
class Exception extends Exception
{
    const CURL_NOT_FOUND                     = 0x01;
    const CURL_ERROR                         = 0x02;
    const GRANT_TYPE_ERROR                   = 0x03;
    const INVALID_CLIENT_AUTHENTICATION_TYPE = 0x04;
    const INVALID_ACCESS_TOKEN_TYPE          = 0x05;
}

class InvalidArgumentException extends InvalidArgumentException
{
    const INVALID_GRANT_TYPE      = 0x01;
    const CERTIFICATE_NOT_FOUND   = 0x02;
    const REQUIRE_PARAMS_AS_ARRAY = 0x03;
    const MISSING_PARAMETER       = 0x04;
}
*/