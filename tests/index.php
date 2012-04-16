<?php

	require_once '../src/cmsconf.php';
	
	$tests_dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'testdata'.DIRECTORY_SEPARATOR;
	
	ob_start();
	
	echo 'Tests Directory: '.$tests_dir.PHP_EOL;

	foreach(glob($tests_dir.'*') as $path){
		$conf = CmsConf($path);
		echo PHP_EOL;
		print_r(array(
			'path'    => $path,
			'type'    => $conf->type(),
			'version' => $conf->version(),
			'db'      => $conf->db(),
			'ftp'     => $conf->ftp(),
			'raw'     => $conf->rawlist(),
		));
	}
	
	$html = htmlspecialchars(str_replace('    ', '  ', ob_get_clean()), ENT_QUOTES);
	echo '<pre style="font: 12px Consolas, Lucida Console;">'.$html.'</pre>';

?>