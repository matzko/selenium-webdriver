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
		$args = array(
			'sessionId' => $this->session_id,
			'id' => $this->session_element_id,
		);
		$url = $this->_request_url . '/element/' . $this->session_element_id . '/click';
		$response = json_decode( $this->_post_request( $url ) );
		if ( 
			isset( $response->value->class ) &&
			'org.openqa.selenium.ElementNotVisibleException' == $response->value->class  
		) {
			if ( ! empty( $response->value->localizedMessage ) ) {
				throw new Exception( $response->value->localizedMessage );
			}
		}
	}
}
