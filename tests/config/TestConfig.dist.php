<?php
return [
	'modules' => [
		'NetglueMaintenanceMode',
	],
	'module_listener_options' => [
		'config_glob_paths'   => [
			__DIR__ . '/{,*.}{global,local}.php',
		],
		'module_paths' => [
			__DIR__.'/../../vendor',
			__DIR__.'/../../',
		],
	],
];
