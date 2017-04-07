<?php

use JakubKlapka\LaravelSharepointUploadClient\Models\Client;

class ClientTest extends TestCase {

	public function testGetUserConsentUri() {

		$client = new Client( 'url', 'id', 'secret', 'redirect' );

		$this->assertSame( "url/_layouts/15/oauthauthorize.aspx?client_id=id&response_type=code&scope=List.Write&redirect_uri=redirect"
			, $client->getUserConsentUri() );

	}

	public function testGetTokenFromAuthCodeValid() {

		$mocked_client = new class( 'url', 'id', 'secret', 'redirect' ) extends Client {

			public function createGuzzleClient() {

				return new class extends \GuzzleHttp\Client {

					public function request( $method, $uri = '', array $options = [] ) {
						return new class {
							public function getBody() {
								return json_encode( [ 'refresh_token' => 'testtoken' ] );
							}
						};
					}

				};

			}

			public function getRealm() {
				return 'realm';
			}
		};

		$client = new $mocked_client( 'url', 'id', 'app', 'redirect' );

		$token = $client->getRefreshTokenFromAuthCode( 'auth' );

		$this->assertSame( 'testtoken', $token );

	}

	public function testGetTokenFromRefreshTokenValid() {

		$mocked_client = new class( 'url', 'id', 'secret', 'redirect' ) extends Client {

			public function createGuzzleClient() {

				return new class extends \GuzzleHttp\Client {

					public function request( $method, $uri = '', array $options = [] ) {
						return new class {
							public function getBody() {
								return json_encode( [ 'access_token' => 'testtoken' ] );
							}
						};
					}

				};

			}

			public function getRealm() {
				return 'realm';
			}
		};

		$client = new $mocked_client( 'url', 'id', 'app', 'redirect' );

		$token = $client->getAccessToken( 'auth' );

		$this->assertSame( 'testtoken', $token );

	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Refresh token not present in Oauth response.
	 */
	public function testGetTokenFromRefreshTokenInvalidResponse() {

		$mocked_client = new class( 'url', 'id', 'secret', 'redirect' ) extends Client {

			public function createGuzzleClient() {

				return new class extends \GuzzleHttp\Client {

					public function request( $method, $uri = '', array $options = [] ) {
						return new class {
							public function getBody() {
								return json_encode( [ 'invalid' => 'response' ] );
							}
						};
					}

				};

			}

			public function getRealm() {
				return 'realm';
			}
		};

		$client = new $mocked_client( 'url', 'id', 'app', 'redirect' );

		$token = $client->getAccessToken( 'auth' );

	}

	public function testGetRealm() {

		$mock = new class( 'url', 'id', 'secret', 'redirect' ) extends Client {

			public function createGuzzleClient() {

				return new class extends \GuzzleHttp\Client {

					public function request( $method, $uri = '', array $options = [] ) {
						return new class {
							public function getHeader( $header ) {
								return [ 'Bearer realm="realm" teststring' ];
							}
						};
					}

				};

			}

			public function getRealm() {
				return parent::getRealm();
			}
		};

		$this->assertSame( 'realm', $mock->getRealm() );

	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Invalid response for Realm lookup. No realm id in header.
	 */
	public function testGetRealmInvalid() {

		$mock = new class( 'url', 'id', 'secret', 'redirect' ) extends Client {

			public function createGuzzleClient() {

				return new class extends \GuzzleHttp\Client {

					public function request( $method, $uri = '', array $options = [] ) {
						return new class {
							public function getHeader( $header ) {
								return [ 'invalid' ];
							}
						};
					}

				};

			}

			public function getRealm() {
				return parent::getRealm();
			}
		};

		$this->assertSame( 'realm', $mock->getRealm() );

	}

}