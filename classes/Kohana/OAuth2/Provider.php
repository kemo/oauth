<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_OAuth2_Provider {

	public static function factory($name, array $options = NULL)
	{
		$class = 'OAuth2_Provider_'.$name;

		return new $class($options);
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the provider signature
	 *     $signature = $provider->signature;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	abstract public function url_authorize();

	abstract public function url_access_token();

	public $name;

	public function url_refresh_token()
	{
		// By default its the same as access token URL
		return $this->url_access_token();
	}

	public function authorize_url(OAuth2_Client $client, array $params = NULL)
	{
		// Create a new GET request for a request token with the required parameters
		$request = OAuth2_Request::factory('authorize', 'GET', $this->url_authorize(), array(
			'response_type' => 'code',
			'client_id'     => $client->id,
			'redirect_uri'  => $client->callback,
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $request->as_url();
	}

	public function access_token(OAuth2_Client $client, $code, array $params = NULL, array $options = NULL)
	{
		$request = OAuth2_Request::factory('token', 'POST', $this->url_access_token(), array(
			'grant_type'    => 'authorization_code',
			'code'          => $code,
			'client_id'     => $client->id,
			'client_secret' => $client->secret,
		));

		if ($client->callback)
		{
			$request->param('redirect_uri', $client->callback);
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		$response = $request->execute($options);

		return OAuth2_Token::factory('access', array(
			'token' => $response->param('access_token')
		));
	}

	/**
	 * Execute an OAuth2 request, apply any provider-specfic options to the request.
	 *
	 * @param   object  request object
	 * @param   array   request options
	 * @return  mixed
	 */
	public function execute(OAuth2_Request $request, array $options = NULL)
	{
		return $request->execute($options);
	}

}
