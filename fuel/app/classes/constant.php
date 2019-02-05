<?php
class Constant
{
	const HEADER_INDEX_CSRF = 'X-CSRF-TOKEN';

	const JWT_ALGORITHM = 'RS256';
	const JWT_PRIVATE_KEY_PATH = APPPATH . 'keys/jwt.key';
	const JWT_PUBLIC_KEY_PATH = APPPATH . 'keys/jwt.key.pub';
	const JWT_EXPIRATION = 1209600; // 2 week
	const JWT_HEADER_KEY = 'Authorization';
}