--TEST--
Test CURL_READFUNC_PAUSE and curl_impersonate_pause()
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

class Input {
	private static $RESPONSES = [
		'Foo bar ',
		CURL_READFUNC_PAUSE,
		'baz qux',
		null
	];
	private int $res = 0;
	public function __invoke($ch, $hReadHandle, $iMaxOut)
	{
		return self::$RESPONSES[$this->res++];
	}
}

$inputHandle = fopen(__FILE__, 'r');

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc?test=input");
curl_impersonate_setopt($ch, CURLOPT_UPLOAD,       1);
curl_impersonate_setopt($ch, CURLOPT_READFUNCTION, new Input);
curl_impersonate_setopt($ch, CURLOPT_INFILE,       $inputHandle);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$mh = curl_impersonate_multi_init();
curl_impersonate_multi_add_handle($mh, $ch);
do {
	$status = curl_impersonate_multi_exec($mh, $active);
	curl_impersonate_pause($ch, CURLPAUSE_CONT);
	if ($active) {
		usleep(100);
		curl_impersonate_multi_select($mh);
	}
} while ($active && $status == CURLM_OK);

echo curl_impersonate_multi_getcontent($ch);
?>
--EXPECT--
string(15) "Foo bar baz qux"
