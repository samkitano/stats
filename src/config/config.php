<?php

return [

	/*
	|--------------------------------------------------------------------------
	| AWstats path
	|--------------------------------------------------------------------------
	|
	| Location of your AWstats files.
	| Usually '/home/USER/tmp/awstats/'
	|
	*/
	'AWstats_path' => '/home/USER/tmp/awstats/',


	/*
	|--------------------------------------------------------------------------
	| Icon path
	|--------------------------------------------------------------------------
	|
	| Location of your icons (image files).
	| AWstats provides its own set of icons to represent countries,
	| operative systems, browsers, etc.
	| Use null if you don't want to include AWstats icons on the response object:
	|
	| 'icon_path' => null,
	|
	*/
	'icon_path' => 'packages/Samkitano/Stats/assets/icon',


	/*
	|--------------------------------------------------------------------------
	| Icon format
	|--------------------------------------------------------------------------
	|
	| Use 'tag' (default) if you want an <img/> html tag with base64 encoded icon.
	| Use 'url' if you prefer an url pointing to your icon file.
	|
	| 'icon_format' => 'url',
	|
	*/
	'icon_format' => 'tag',


	/*
	|--------------------------------------------------------------------------
	| Bandwidth Units
	|--------------------------------------------------------------------------
	|
	| Results will be parsed with units, precision = 2 decimal points
	| For example:
	| ['bandwidth' => '1024'] will be outputed as ['bandwidth' => '1,00 KB']
	|
	| Use true if you want units in your results
	| Use false (default) if you don't.
	| Not recommended if you intend to perform further calculations from results.
	|
	*/
    'units' => true,
];