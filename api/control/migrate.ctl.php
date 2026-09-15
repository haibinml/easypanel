<?php
function verificationSkey()
{
	$r = ep_request('r');
	$a = ep_request('a');
	$s = ep_request('s');

	if ($r == '' || $a == '' || $s == '') {
		return false;
	}

	$skey = daocall('setting', 'get', array('skey'));

	if (!$skey) {
		return false;
	}

	$urls = $a . $skey . $r;

	if (ep_hash_equals(md5($urls), $s)) {
		return true;
	}

	return false;
}

if (!verificationskey()) {
	exit('access denied');
}

class MigrateControl extends control
{
	public function list_vhost()
	{
		$vhs = daocall('vhost', 'listVhost', array());

		if (!is_array($vhs) || count($vhs) <= 0) {
			exit();
		}

		$vhs_name = '';

		foreach ($vhs as $vh) {
			$vhs_name .= $vh['name'] . ';';
		}

		exit($vhs_name);
	}

	public function migrate_domain()
	{
		exit('ccc.com=>wwwroot');

		if (!$vh = $_REQUEST['vh']) {
			return false;
		}

		$domain = daocall('vhostinfo', 'getDomain', array($vh));

		if (count($domain) < 0) {
			exit();
		}

		$domain_str = '';

		foreach ($domain as $d) {
			$domain_str .= $d['name'] . '=>' . $d['value'] . ';';
		}

		exit($domain_str);
	}

	public function migrate_hello_vh_web()
	{
		$vh = $_REQUEST['vh'];
		$nolog = intval($_REQUEST['nolog']);
		$save_dir = $GLOBALS['safe_dir'] . '../nodewww/webftp/';

		if ($session = apicall('migrate', 'zipVhWeb', array($vh, $save_dir, $nolog))) {
			exit($session);
		}

		exit();
	}

	public function migrate_hello_vh_sql()
	{
		$vh = $_REQUEST['vh'];
		$save_dir = $GLOBALS['safe_dir'] . '../nodewww/webftp/';

		if ($session = apicall('migrate', 'zipVhSql', array($vh, $save_dir))) {
			exit($session);
		}

		exit();
	}
}

?>
