<?php

class WebDriver extends SearchContext
{
	public function addCookie( $name = '', $value = null )
	{
		$args = array( 
			'cookie' => array(
				'name' => $name,
				'value' => $value,
				'path' => '/',
				'domain' => $this->_domain,
				'secure' => true,
			)
		);

		$response = $this->_post_request( $this->_request_url . '/cookie', $args );
	}

	public function close()
	{
		$response = $this->_delete_request( $this->_request_url . '/window' );
		if ( 
			isset( $response->value->class ) &&
			(
				'org.openqa.selenium.NoSuchWindowException' == $response->value->class
			)
		) {
			throw new Exception( $response->value->localizedMessage );
		}
	}
	
	public function findElementBy( $using = 'xpath', $expression = null )
	{
		if ( ! in_array(
			$using,
			array(
				'id',
				'xpath',
			)
		) ) {
			throw new InvalidArgumentException( 'Invalid "using" argument for findElementBy.' );
		}

		$args = array(
			'using' => $using,
			'value' => $expression,
		);
		$response = json_decode( $this->_post_request( $this->_request_url . '/element', $args ) );
		
		if ( ! isset( $response->value ) || ! isset( $response->value->ELEMENT ) ) {
			if ( 
				isset( $response->value->class ) &&
				(
					'org.openqa.selenium.NoSuchElementException' == $response->value->class || 
					'org.openqa.selenium.StaleElementException' == $response->value->class || 
					'org.openqa.selenium.XPathLookupErrorException' == $response->value->class
				)
			) {
				throw new Exception( $response->value->localizedMessage );
			} else {
				throw new Exception( 'No matching element found.' );
			}
		}

		$element = new WebElement( null, null, $this->_request_url );
		$element->session_id = $this->_session_id; 
		$element->session_element_id = $response->value->ELEMENT;

		return $element;
	}

	public function get( $url = '' )
	{
		if ( empty( $url ) ) {
			throw new InvalidArgumentException( 'Requested URL cannot be empty.' );
		}

		$args = array(
			'url' => $url,
		);
		
		$response = $this->_post_request( $this->_request_url . '/url', $args );
	}
}
