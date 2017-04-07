# Laravel Sharepoint Upload Client

Simple Client for uploading files to Sharepoint Online library. Integrated Laravel Service Provider.

Uses accesscontrol.windows.net OAuth2, so it works with MS ADFS or Azure AAD Authentication.
 
## Usage:

#### Register ServiceProvider in your app.php:

```php
\JakubKlapka\LaravelSharepointUploadClient\LaravelSharepointUploadProvider::class
```

#### Use Client factory to make Client instance:

```php
class Controller {

	protected $sharepointClient;

	public function __construct( \JakubKlapka\LaravelSharepointUploadClient\Factories\ClientFactory $sharepointClient ) {
		$this->sharepointClient = $sharepointClient;
	}
	
	public function upload() {
		$client = $this->sharepointClient->makeClient( $site_url, $app_id, $app_secret, $redirect_uri );
	}

}
```

You will need those attributes, you can register Sharepoint App on: https://contoso.sharepoint.com/sites/MySite/_layouts/15/appregnew.aspx 
 
Attribute | Description | Example 
:---: | --- | ---
*site_url* | Abosolute path to root of your sharepoint site | https://contoso.sharepoint.com/sites/MySite
*app_id* | ID of your app | 
*app_secret* | Secret of your app | 
*redirect_uri* | Redirect URI has to match the one, entered on appregnew.aspx | https://myapp.com/shp-oauth/

#### Point you user to retrieve Consent:

```php
$url = $client->getUserConsentUri()
```

#### Get refresh token from auth code, returned based on Consent:

```php
$auth_code = $_REQUEST['code'];
$refreshToken = $client->getRefreshTokenFromAuthCode( $auth_code );
```
*This client does not handle token persistence, feel free to save it to file/db/redis or wherever it is fit for your app.*

Refresh token is valid for 6 months.

#### Use refresh token to upload a file

```php
$upload_result = $client->uploadFile(
	$refresh_token,
	'/sites/mySite/myLibrary/',
	'file.txt',
	$this->transfer_storage->readStream( 'file.txt' )
);
```

## Resources
 - [https://medium.com/@yash_agarwal2/performing-oauth-and-rest-calls-with-sharepoint-online-without-creating-an-add-in-677e15c8d6ab](https://medium.com/@yash_agarwal2/performing-oauth-and-rest-calls-with-sharepoint-online-without-creating-an-add-in-677e15c8d6ab)
 - [https://dev.office.com/blogs/introducing-bulk-upa-custom-profile-properties-update-api](https://dev.office.com/blogs/introducing-bulk-upa-custom-profile-properties-update-api)
