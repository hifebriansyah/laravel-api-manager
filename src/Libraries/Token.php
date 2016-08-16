<?php

namespace MFebriansyah\LaravelAPIManager\Libraries;

class Token
{
	public static function create($key = TOKEN_KEY)
	{
		$encryptedText = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, NOW, MCRYPT_MODE_ECB)); // encrypt with AES 128

		return $encryptedText;
	}

	public static function check($encryptedText, $key = TOKEN_KEY)
	{
		$access = false;

		if($encryptedText == DEBUG_TOKEN_KEY){
			$access = true;
		}else{
			$clientTimeStamp = mcrypt_decrypt(MCRYPT_RIJNDAEL_128 , $key , base64_decode($encryptedText) , MCRYPT_MODE_ECB); // decrypt with AES 128
			$timestampDiff = NOW - $clientTimeStamp;
			
			if($timestampDiff <= TOKEN_TIME){
				$access = true;
			}
		}

		return $access;
	}
}