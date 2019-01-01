<?php
class Constant
{
	const HEADER_INDEX_CSRF = 'X-CSRF-TOKEN';

	const JWT_ALGORITHM = 'RS256';
	const JWT_KEY_PATH = APPPATH . 'keys/jwt.key';
	const JWT_EXPIRATION = 1209600; // 2 week
}