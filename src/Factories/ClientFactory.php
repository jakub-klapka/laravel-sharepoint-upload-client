<?php

namespace JakubKlapka\LaravelSharepointUploadClient\Factories;

use JakubKlapka\LaravelSharepointUploadClient\Models\Client;

class ClientFactory {

	/**
	 * Create new instance of Sharepoint client
	 *
	 * @param string|bool $site_url URL of specific site on sharepoint tenant without trailing slash
	 * @param string|bool $app_id App ID registered on <siteurl>/_layouts/15/appRegNew.aspx
	 * @param string|bool $app_secret App secret
	 * @param string|bool $redirect_uri Redirect URI, matching the one, used on appRegNew.aspx
	 *
	 * @return Client
	 * @throws \Exception
	 */
	public function makeClient( $site_url = false, $app_id = false, $app_secret = false, $redirect_uri = false) {

		if( $site_url === false || $app_id === false || $app_secret === false || $redirect_uri === false ) {
			throw new \Exception( 'Sharepoint client is missing required parameters.' );
		}

		$client = new Client( $site_url, $app_id, $app_secret, $redirect_uri );

		return $client;

	}

}