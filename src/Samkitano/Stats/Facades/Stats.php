<?php namespace Samkitano\Stats\Facades;

use Illuminate\Support\Facades\Facade;

class Stats extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'stats'; }

} 