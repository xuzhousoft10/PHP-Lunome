<?php

require_once("http.php");
require_once("auth_digest.php");

// ----------------------------------------------------------
// class Qiniu_PutExtra

class Qiniu_PutExtra
{
	public $Params = null;
	public $MimeType = null;
	public $Crc32 = 0;
	public $CheckCrc = 0;
}

function Qiniu_Put($upToken, $key, $body, $putExtra) // => ($putRet, $err)
{
	global $QINIU_UP_HOST;

	if ($putExtra === null) {
		$putExtra = new Qiniu_PutExtra;
	}

	$fields = array('token' => $upToken);
	if ($key === null) {
		$fname = '?';
	} else {
		$fname = $key;
		$fields['key'] = $key;
	}
	if ($putExtra->CheckCrc) {
		$fields['crc32'] = $putExtra->Crc32;
	}
	if ($putExtra->Params) {
		foreach ($putExtra->Params as $k=>$v) {
			$fields[$k] = $v;
		}
	}

	$files = array(array('file', $fname, $body, $putExtra->MimeType));

	$client = new Qiniu_HttpClient;
	return Qiniu_Client_CallWithMultipartForm($client, $QINIU_UP_HOST, $fields, $files);
}


// ----------------------------------------------------------

