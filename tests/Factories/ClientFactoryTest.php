<?php

use JakubKlapka\LaravelSharepointUploadClient\Factories\ClientFactory;
use JakubKlapka\LaravelSharepointUploadClient\Models\Client;

class ClientFactoryTest extends TestCase {

	/**
	 * @expectedException \Exception
	 */
	public function testMakeClientWithoutParameters() {

		$client_factory = new ClientFactory();
		$client_factory->makeClient();

	}

	public function testMakeClientWithParameters() {

		$client_factory = new ClientFactory();
		$client = $client_factory->makeClient( 'test', 'test', 'test', 'test' );

		$this->assertTrue( $client instanceof Client );

	}

}