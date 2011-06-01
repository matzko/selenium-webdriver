<?php

class SearchContext
{
	protected $_driver;
	protected $_session_id;
	protected $_request_url;
	protected $_host;
	protected $_port = '4443';

	public function __construct( $host, $port = '4443', $request_url = null )
	{
		$this->_host = $host;
		$this->_port = $port;

		if ( empty( $request_url ) && empty( $this->_host ) ) {
			throw new InvalidArgumentException( 'The WebDriver API must receive a host URL.' );
		} 

		if ( empty( $request_url ) ) {
			$this->_request_url = 'http://' . $this->_host . ':' . $this->_port . '/wd/hub';
		} else {
			$this->_request_url = $request_url;
		}
	}

	protected function _delete_request( $url = '' )
	{
		if ( empty( $url ) ) {
			throw new InvalidArgumentException( 'Invalid request: No delete URL is set.' );
		}

		$session = curl_init( $url );
		curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
		curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);

		$response = trim( curl_exec( $session ) );
		curl_close( $session );

		return $response;
	}

	protected function _get_request( $url = '' )
	{
		if ( empty( $url ) ) {
			throw new InvalidArgumentException( 'Invalid request: No get URL is set.' );
		}

		$session = curl_init( $url );
		curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);

		$response = trim( curl_exec( $session ) );
		curl_close( $session );

		return $response;
	}

	protected function _post_request( $url = '', $args = array(), $force_json = true )
	{
		$force_json = (bool) $force_json;

		if ( empty( $url ) ) {
			throw new InvalidArgumentException( 'Invalid request: URL must be set.' );
		}

		if ( ! is_array( $args ) ) {
			throw new InvalidArgumentException( 'Invalid request: Arguments must be an array.' );
		}

		if ( $force_json ) {
			$args = json_encode( $args, JSON_FORCE_OBJECT );
		}

		$session = curl_init( $url );
		curl_setopt( $session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8") );
		curl_setopt( $session, CURLOPT_POST, true );
		curl_setopt( $session, CURLOPT_POSTFIELDS, $args );
		curl_setopt( $session, CURLOPT_HEADER, false );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $session, CURLOPT_FOLLOWLOCATION, true );

		$response = trim( curl_exec( $session ) );
		curl_close( $session );

		return $response;
	}

	public function connect( $browser = 'firefox' )
	{
		$args = json_encode( array(
			'desiredCapabilities' => array(
				'browserName' => $browser,
				'javascriptEnabled' => 'true',
				'nativeEvents' => 'true',
			),
		), JSON_FORCE_OBJECT );

		$session = curl_init( $this->_request_url . '/session' );
		curl_setopt( $session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8") );
		curl_setopt( $session, CURLOPT_POST, true );
		curl_setopt( $session, CURLOPT_POSTFIELDS, $args );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $session, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt( $session, CURLOPT_HEADER, true );

		$response = trim( curl_exec( $session ) );
		$header = curl_getinfo($session);
		if ( ! empty( $header['url'] ) ) {
			$this->_request_url = $header['url'];
			$_url_parts = explode('/', $header['url']);
			$this->_session_id = trim( array_pop( $_url_parts ), '/' );
		}
		curl_close( $session );
	}

}

require_once dirname( __FILE__ ) . '/WebDriver.php';
require_once dirname( __FILE__ ) . '/WebElement.php';
