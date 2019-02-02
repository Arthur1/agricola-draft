<?php

class Controller_Users extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;

	public function after($response)
	{
		$response = parent::after($response);
		$response->status = $this->status_code;
		return $response;
	}
}