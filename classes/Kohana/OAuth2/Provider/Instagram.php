<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_OAuth2_Provider_Instagram extends OAuth2_Provider {

	public $name = 'instagram';

	public function url_authorize()
	{
		return 'https://api.instagram.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.instagram.com/oauth/access_token';
	}

}
