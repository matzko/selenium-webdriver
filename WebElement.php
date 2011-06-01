<?php

class WebElement extends SearchContext
{
	public $session_id = false;
	public $session_element_id = false;
	
	public function elementExists()
	{
		return (bool) ( false !== $this->session_element_id );
	}

	public function click()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/click';
		$response = json_decode( $this->_post_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.ElementNotVisibleException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}
	}
	
	public function getAttribute( $name = '' )
	{
		if ( empty( $name ) ) {
			throw new InvalidArgumentException( 'Invalid request: No attribute name has been specified.' );
		}
		
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/attribute/' . $name;
		$response = json_decode( $this->_get_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}

		if ( isset( $response->value ) ) {
			return (bool) $response->value;
		}
	}

	public function getValue()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/value';
		$response = json_decode( $this->_get_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}

		if ( isset( $response->value ) ) {
			return $response->value;
		}
	}

	public function isDisplayed()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/displayed';
		$response = json_decode( $this->_get_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}

		if ( isset( $response->value ) ) {
			return (bool) $response->value;
		}
	}

	public function isEnabled()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/enabled';
		$response = json_decode( $this->_get_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}

		if ( isset( $response->value ) ) {
			return (bool) $response->value;
		}
	}

	public function isSelected()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/selected';
		$response = json_decode( $this->_get_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}

		if ( isset( $response->value ) ) {
			return (bool) $response->value;
		}
	}

	public function setSelected()
	{
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/selected';
		$response = json_decode( $this->_post_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			in_array( $response->value->class, array(
				'org.openqa.selenium.StaleElementReferenceException',
				'org.openqa.selenium.ElementIsNotSelectableException',
				'org.openqa.selenium.ElementNotVisibleException',
				'org.openqa.selenium.InvalidElementStateException',
			) )
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}
	}
}
