<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config-library.php';

// Parse only a subset of dependencies
$cfg['directory_list'] = array_diff( $cfg['directory_list'], [ 'vendor/' ] );

$cfg['directory_list'] = array_merge(
	$cfg['directory_list'],
	[
		'tests',
		'vendor/phpunit',
		'vendor/wikimedia',
		'.phan/stubs',
	]
);

$cfg['strict_method_checking'] = true;
$cfg['strict_object_checking'] = true;
$cfg['strict_property_checking'] = true;

$cfg['exclude_analysis_directory_list'] = [
	'vendor/',
	'.phan/',
	'tests/',
];

return $cfg;
