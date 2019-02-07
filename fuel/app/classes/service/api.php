<?php
class Service_Api
{
	public static function error($messages)
	{
		if (is_array($messages)) {
			$response = [
				'result' => false,
				'error' => [
					'messages' => $messages,
				],
			];
		} else {
			$response = [
				'result' => false,
				'error' => [
					'message' => $messages,
				],
			];
		}
		return $response;
	}
}