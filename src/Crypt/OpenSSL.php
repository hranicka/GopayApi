<?php

/**
 * Copyright © 2016 Jaroslav Hranička <hranicka@outlook.com>
 */

namespace Markette\Gopay\Crypt;

use Markette\Gopay\Api\GopayHelper;

/**
 * OpenSSL alternative for GoPay's original mcrypt immplementation in API v2.5.
 * Requires openssl installed.
 */
class OpenSSL implements Crypt
{

	public function encrypt($data, $secureKey)
	{
		// Strip a key
		$secureKey = substr($secureKey, 0, 24);

		// Fill bytes
		$fullFill = 8 - strlen($data) % 8;
		for ($i = 0; $i < $fullFill; $i++) {
			$data .= "\0";
		}

		// Crypt
		$ecrypted = openssl_encrypt($data, 'des-ede3', $secureKey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

		return bin2hex($ecrypted);
	}

	public function decrypt($data, $secureKey)
	{
		// Strip a key
		$secureKey = substr($secureKey, 0, 24);

		// Convert HEX -> string
		$data = GopayHelper::convert($data);

		// Decrypt
		$decrypted = openssl_decrypt($data, 'des-ede3', $secureKey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

		return trim($decrypted);
	}

}
