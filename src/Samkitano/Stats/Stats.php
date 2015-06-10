<?php namespace Samkitano\Stats;

use Illuminate\Support\Facades\Config as Config;
use Illuminate\Support\Facades\File;


class Stats
{
	/**
	 * Available configuration optons for icon output
	 * 'tag' will output <img> tag with base64 encoded icon
	 * 'url' will output url. No header, so we can use Laravel's URL builder
	 *
	 * @var array
	 */
	private $allowed_formats = ['tag', 'url'];

	/**
	 * Allowed image formats for icons
	 *
	 * @var array
	 */
	protected $valid_img_mimes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];

	/**
	 * Config output of units with bandwidth fields
	 *
	 * @var bool
	 */
	protected $use_units;

	/**
	 * Config output of icons
	 * @var bool
	 */
	protected $use_icons;

	/**
	 * Icon List
	 *
	 * @var array|null
	 */
	protected $icons;

	/**
	 * Icon Path in public folder
	 *
	 * @var string
	 */
	protected $icon_path;

	/**
	 * Icon Format to output
	 *
	 * @var string
	 */
	protected $icon_format;

	/**
	 * Instantiated Class
	 *
	 * @var object
	 */
	protected $awstats;

	/**
	 * Path for awstats files
	 *
	 * @var string
	 */
	protected $aw_path;


	public function __construct()
	{
		$icon_format     = strtolower( rtrim( \Config::get('stats::icon_format')       )       );
		$this->aw_path   = strtolower( rtrim( \Config::get('stats::AWstats_path'), '/' ) . '/' );
		$this->icon_path = strtolower( rtrim( \Config::get('stats::icon_path'),    '/' ) . '/' );

		$this->setUseUnits   ( \Config::get('stats::units') );
		$this->setIconFormat ($icon_format);
		$this->setUseIcons   ($this->icon_path);

		$this->icons = $this->use_icons ? $this->getIcons() : null;
	}

	/**
	 * Access by Facade
	 *
	 * @param $file
	 *
	 * @return object|AWstats
	 */
	public function Read($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');

		return $this->awstats;
	}

	public function Current()
	{
		$m = date('m');
		$y = date('Y');
		$d = $this->getCurrentHost();
		$this->awstats = new AWstats($this->aw_path . 'awstats' .  $m . $y . '.' . $d . '.txt');
		return $this->awstats;
	}


	/**
	 * Returns General Section Stats:
	 *
	 * LastLine =
	 *      - Date of last record processed
	 *      - Last record line number in last log
	 *      - Last record offset in last log
	 *      - Last record signature value
	 * FirstTime =
	 *      - Date of first visit for history file
	 * LastTime =
	 *      - Date of last visit for history file
	 * LastUpdate =
	 *      - Date of last update
	 *      - Nb of parsed records
	 *      - Nb of parsed old records
	 *      - Nb of parsed new records
	 *      - Nb of parsed corrupted
	 *      - Nb of parsed dropped
	 * TotalVisits =
	 *      - Number of visits
	 * TotalUnique =
	 *      - Number of unique visitors
	 * MonthHostsKnown =
	 *      - Number of hosts known
	 * MonthHostsUnknown =
	 *      - Number of hosts unknown
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function General($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->General;
	}

	/**
	 * Returns Misc Section Stats:
	 *
	 * Columns:
	 *      - Misc ID
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *
	 * Rows:
	 *      - QuickTimeSupport
	 *      - JavaEnabled
	 *      - JavascriptDisabled
	 *      - PDFSupport
	 *      - WindowsMediaPlayerSupport
	 *      - AddToFavourites
	 *      - RealPlayerSupport
	 *      - TotalMisc
	 *      - DirectorSupport
	 *      - FlashSupport
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Misc($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Misc;
	}

	/**
	 * Return Time Section Stats
	 *
	 * Columns:
	 *      - Hour
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *      - Not viewed Pages
	 *      - Not viewed Hits
	 *      - Not viewed Bandwidth
	 *      - [Icon]
	 *
	 * Rows:
	 *      - 0 to 23
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Time($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Time;
	}

	/**
	 * Returns Domain Section Stats
	 *
	 * Columns:
	 *      - Domain
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *      - [Icon]
	 *
	 * Rows:
	 *      - 25 top domains
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Domain($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Domain;
	}

	/**
	 * Returns Cluster Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - Cluster ID
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Cluster($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Cluster;
	}

	/**
	 * Returns Login Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - Login
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *      - Last visit
	 *
	 * Rows:
	 *
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Login($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Login;
	}

	/**
	 * Returns Robot Section Stats
	 *
	 * Columns:
	 *      - Robot ID
	 *      - Hits
	 *      - Bandwidth
	 *      - Last visit
	 *      - Hits on robots.txt
	 *
	 * Rows:
	 *      - Up to 25
	 *
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Robot($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Robot;
	}

	/**
	 * Returns Worms Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - Worm ID
	 *      - Hits
	 *      - Bandwidth
	 *      - Last visit
	 *
	 * Rows:
	 *      -
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Worm($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Worms;
	}

	/**
	 * Returns Emailsender Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - EMail
	 *      - Hits
	 *      - Bandwidth
	 *      - Last visit
	 * Rows:
	 *      -
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Emailsender($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Emailsender;
	}

	/**
	 * Returns Emailreceiver Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - EMail - Hits - Bandwidth - Last visit
	 *
	 * Rows:
	 *      -
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Emailreceiver($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Emailreceiver;
	}

	/**
	 * Returns Filetypes Section Stats
	 *
	 * Columns:
	 *      - Files type
	 *      - Hits
	 *      - Bandwidth
	 *      - Bandwidth without compression
	 *      - Bandwidth after compression
	 *      - [Icon]
	 *
	 * Rows:
	 *      -
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Filetype($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Filetypes;
	}

	/**
	 * Returns Downloads Section Stats
	 *
	 * Columns:
	 *      - Downloads
	 *      - Hits
	 *      - Bandwidth
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Download($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Downloads;
	}

	/**
	 * Returns Os Section Stats
	 *
	 * Columns:
	 *      - OS ID
	 *      - Hits
	 *      - [Icon]
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Os($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Os;
	}

	/**
	 * Returns Browser Section Stats
	 *
	 * Columns:
	 *      - Browser ID
	 *      - Hits
	 *      - [Icon]
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Browser($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Browser;
	}

	/**
	 * Returns Screensize Section Stats
	 * Visit AWstats official webpage for instructions on how to configure and enalble this section
	 *
	 * Columns:
	 *      - Screen size
	 *      - Hits
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function ScreenSize($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Screensize;
	}

	/**
	 * Returns UnknownReferer Os Section Stats
	 *
	 * Columns:
	 *      - Unknown referer OS
	 *      - Last visit date
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function UnknownReferer($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->UnknownReferer;
	}

	/**
	 * Returns UnknownReferer Browser Section Stats
	 *
	 * Columns:
	 *      - Unknown referer Browser
	 *      - Last visit date
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function UnknownRefererBrowser($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->UnknownRefererBrowser;
	}

	/**
	 * Returns Origin Section Stats
	 *
	 * Columns:
	 *      - Origin
	 *      - Pages
	 *      - Hits
	 * Rows:
	 *      - From0 : Direct access / Bookmark / Link in email...
	 *      - From1 : Unknown Origin
	 *      - From2 : Links from an Internet Search Engine
	 *      - From3 : Links from an external page (other web sites except search engines)
	 *      - From4 : Links from an internal page (other page on same site)
	 *      - From5 : Links from a NewsGroup
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Origin($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Origin;
	}

	/**
	 * Returns Search Engine Referrals Section Stats
	 *
	 * Columns:
	 *      - Search engine referers ID
	 *      - Pages
	 *      - Hits
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Sereferrals($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Sereferrals;
	}

	/**
	 * Returns External Page Referrals Section Stats
	 *
	 * Columns:
	 *      - External page referers
	 *      - Pages
	 *      - Hits
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Pagerefs($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Pagerefs;
	}

	/**
	 * Returns Search Words Section Stats
	 *
	 * Columns:
	 *      - Search keyphrases
	 *      - Number of search
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Searchwords($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Searchwords;
	}

	/**
	 * Returns Key Words Section Stats
	 *
	 * Columns:
	 *      - Search keywords
	 *      - Number of search
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Keywords($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Keywords;
	}

	/**
	 * Returns HTTP Erros Section Stats
	 *
	 * Columns:
	 *      - Errors
	 *      - Hits
	 *      - Bandwidth
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Errors($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Errors;
	}

	/**
	 * Returns 404 Errors Section Stats
	 *
	 * Columns:
	 *      - URL with 404 errors
	 *      - Hits
	 *      - Last URL referer
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Sider404($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Sider404;
	}

	/**
	 * Returns Visitor Section Stats
	 *
	 * Columns:
	 *      - Host
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *      - Last visit date
	 *      - [Start date of last visit]
	 *      - [Last page of last visit]
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Visitor($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Visitor;
	}

	/**
	 * Returns Day Section Stats
	 *
	 * Columns:
	 *      - Date
	 *      - Pages
	 *      - Hits
	 *      - Bandwidth
	 *      - Visits
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Day($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Day;
	}

	/**
	 * Returns Session Section Stats
	 *
	 * Columns:
	 *      - Session range
	 *      - Number of visits
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Session($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Session;
	}

	/**
	 * Returns Sider (internal links) Section Stats
	 *
	 * Columns:
	 *      - URL
	 *      - Pages
	 *      - Bandwidth
	 *      - Entry
	 *      - Exit
	 *
	 * Rows:
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function Sider($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->Sider;
	}

	/**
	 * Returns best day (by hits)
	 *
	 * @param  string $file
	 * @return mixed
	 */
	public function BestDay($file)
	{
		$this->awstats = new AWstats($this->aw_path .'/' . $file. '.txt');
		return $this->awstats->BestDay;
	}


	/**
	 * List of available AWstats files
	 * Ordered by (sub)Domain->Year->Month
	 *
	 * @return array
	 */
	public function AWlist()
	{
		$patterns   = $this->aw_path .'*.txt';
		$awfiles    = \File::glob($patterns);

		if ( sizeof($awfiles) === 0 ) return null;

		foreach($awfiles as $awfile)
		{
			// remove path
			$awfile = str_replace($this->aw_path, '', $awfile);

			// remove extension
			$awfile = str_replace('.txt', '', $awfile);

			// split awstats cleared filename
			$parts  = explode('.', $awfile);
			$partNo = sizeof($parts);

			// set domain
			$domain = $partNo === 3 ?
				$parts[$partNo - 2] . '.' . $parts[$partNo - 1] :
				$parts[$partNo - 3] . '.' . $parts[$partNo - 2] . '.' . $parts[$partNo - 1];

			// set date mmyyyy
			$file_date  = str_replace('awstats', '', $parts[0]);
			$month      = date("F", mktime(0, 0, 0, substr($file_date, 0, 2), 10));
			$year       = substr($file_date, 2, 4);

			// fill array
			( ! isset($flist[$domain]) ) ?
				( ! isset($flist[$domain][$year]) ) ?
					( ! isset($flist[$domain][$month]) ) ?
						$flist[$domain][$year][$month] = $awfile
						: null
					: $flist[$domain][$year][$month] = $awfile
				: $flist[$domain][$year][$month] = $awfile;
		}

		return $flist;
	}

	/**
	 * get current host name
	 * TODO: check reliability
	 *
	 * @return string
	 */
	private function getCurrentHost()
	{
		if ( ! $host = $_SERVER['HTTP_HOST'])
		{
			if ( ! $host = $_SERVER['SERVER_NAME'])
			{
				$host = ! empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
			}
		}
		// Remove port number from host
		$host = preg_replace('/:\d+$/', '', $host);

		return trim($host);
	}

	/**
	 * Set Icon Format Option
	 * Defaults to 'tag' if null or invalid option
	 *
	 * @param string    $format
	 */
	public function setIconFormat($format = 'tag')
	{
		$this->icon_format = in_array($format, $this->allowed_formats) ? $format : 'tag';
	}

	/**
	 * Set Use Icons
	 *
	 * @param string $path
	 */
	private function setUseIcons($path)
	{
		$this->use_icons = ($path === '/' OR $path === null) ? false : true;
	}

	/**
	 * Set Use Units
	 *
	 * @param bool $yn
	 */
	public function setUseUnits($yn = false)
	{
		$this->use_units = $yn;
	}

	/**
	 * List of available icons
	 * TODO: should we migrate this arrays to a table?
	 *
	 * @return array
	 */
	private function getIcons()
	{

		$Browsers =
			[
				'firefox'   => 'firefox',
				'opera'     => 'opera',
				'chrome'    => 'chrome',
				'safari'    => 'safari',
				'konqueror' => 'konqueror',
				'svn'       => 'subversion',
				'msie'      => 'msie',
				'netscape'  => 'netscape',
				'firebird'  => 'phoenix',
				'go!zilla'  => 'gozilla',
				'icab'      => 'icab',
				'lynx'      => 'lynx',
				'omniweb'   => 'omniweb',

				// Other standard web browsers
				'amaya'              => 'amaya',
				'amigavoyager'       => 'amigavoyager',
				'avantbrowser'       => 'avant',
				'aweb'               => 'aweb',
				'bonecho'            => 'firefox',
				'minefield'          => 'firefox',
				'granparadiso'       => 'firefox',
				'donzilla'           => 'mozilla',
				'songbird'           => 'mozilla',
				'strata'             => 'mozilla',
				'sylera'             => 'mozilla',
				'kazehakase'         => 'mozilla',
				'prism'              => 'mozilla',
				'iceape'             => 'mozilla',
				'seamonkey'          => 'seamonkey',
				'flock'              => 'flock',
				'icecat'             => 'icecat',
				'iceweasel'          => 'iceweasel',
				'bpftp'              => 'bpftp',
				'camino'             => 'chimera',
				'chimera'            => 'chimera',
				'cyberdog'           => 'cyberdog',
				'dillo'              => 'dillo',
				'doris'              => 'doris',
				'dreamcast'          => 'dreamcast',
				'xbox'               => 'winxbox',
				'ecatch'             => 'ecatch',
				'encompass'          => 'encompass',
				'epiphany'           => 'epiphany',
				'fresco'             => 'fresco',
				'galeon'             => 'galeon',
				'flashget'           => 'flashget',
				'freshdownload'      => 'freshdownload',
				'getright'           => 'getright',
				'leechget'           => 'leechget',
				'hotjava'            => 'hotjava',
				'ibrowse'            => 'ibrowse',
				'k\-meleon'          => 'kmeleon',
				'lotus\-notes'       => 'lotusnotes',
				'macweb'             => 'macweb',
				'multizilla'         => 'multizilla',
				'msfrontpageexpress' => 'fpexpress',
				'ncsa_mosaic'        => 'ncsa_mosaic',
				'netpositive'        => 'netpositive',
				'phoenix'            => 'phoenix',

				// Site grabbers
				'grabber'    => 'grabber',
				'teleport'   => 'teleport',
				'webcapture' => 'adobe',
				'webcopier'  => 'webcopier',

				// Media only browsers
				'real'                   => 'real',
				'winamp'                 => 'mediaplayer', // Works for winampmpeg and winamp3httprdr
				'windows\-media\-player' => 'mplayer',
				'audion'                 => 'mediaplayer',
				'freeamp'                => 'mediaplayer',
				'itunes'                 => 'mediaplayer',
				'jetaudio'               => 'mediaplayer',
				'mint_audio'             => 'mediaplayer',
				'mpg123'                 => 'mediaplayer',
				'mplayer'                => 'mediaplayer',
				'nsplayer'               => 'netshow',
				'qts'                    => 'mediaplayer',
				'sonique'                => 'mediaplayer',
				'uplayer'                => 'mediaplayer',
				'xaudio'                 => 'mediaplayer',
				'xine'                   => 'mediaplayer',
				'xmms'                   => 'mediaplayer',

				// RSS Readers
				'abilon'                  => 'abilon',
				'aggrevator'              => 'rss',
				'aiderss'                 => 'rss',
				'akregator'               => 'rss',
				'applesyndication'        => 'rss',
				'betanews_reader'         => 'rss',
				'blogbridge'              => 'rss',
				'feeddemon'               => 'rss',
				'feedreader'              => 'rss',
				'feedtools'               => 'rss',
				'greatnews'               => 'rss',
				'gregarius'               => 'rss',
				'hatena_rss'              => 'rss',
				'jetbrains_omea'          => 'rss',
				'liferea'                 => 'rss',
				'netnewswire'             => 'rss',
				'newsfire'                => 'rss',
				'newsgator'               => 'rss',
				'newzcrawler'             => 'rss',
				'plagger'                 => 'rss',
				'pluck'                   => 'rss',
				'potu'                    => 'rss',
				'pubsub\-rss\-reader'     => 'rss',
				'pulpfiction'             => 'rss',
				'rssbandit'               => 'rss',
				'rssreader'               => 'rss',
				'rssowl'                  => 'rss',
				'rss\sxpress'             => 'rss',
				'rssxpress'               => 'rss',
				'sage'                    => 'rss',
				'sharpreader'             => 'rss',
				'shrook'                  => 'rss',
				'straw'                   => 'rss',
				'syndirella'              => 'rss',
				'vienna'                  => 'rss',
				'wizz\srss\snews\sreader' => 'wizz',

				// PDA/Phonecell browsers
				'alcatel'      => 'pdaphone', // Alcatel
				'lg\-'         => 'pdaphone', // LG
				'ericsson'     => 'pdaphone', // Ericsson
				'mot\-'        => 'pdaphone', // Motorola
				'nokia'        => 'pdaphone', // Nokia
				'panasonic'    => 'pdaphone', // Panasonic
				'philips'      => 'pdaphone', // Philips
				'sagem'        => 'pdaphone', // Sagem
				'samsung'      => 'pdaphone', // Samsung
				'sie\-'        => 'pdaphone', // SIE
				'sec\-'        => 'pdaphone', // Sony/Ericsson
				'sonyericsson' => 'pdaphone', // Sony/Ericsson
				'mmef'         => 'pdaphone',
				'mspie'        => 'pdaphone',
				'vodafone'     => 'pdaphone',
				'wapalizer'    => 'pdaphone',
				'wapsilon'     => 'pdaphone',
				'wap'          => 'pdaphone', // Generic WAP phone (must be after 'wap*')
				'webcollage'   => 'pdaphone',
				'up\.'         => 'pdaphone', // Works for UP.Browser and UP.Link

				// PDA/Phonecell browsers
				'android'    => 'android',
				'blackberry' => 'pdaphone',
				'docomo'     => 'pdaphone',
				'iphone'     => 'pdaphone',
				'portalmmm'  => 'pdaphone',

				// Others (TV)
				'webtv' => 'webtv',

				// Anonymous Proxy Browsers (can be used as grabbers as well...)
				'cjb\.net' => 'cjbnet',

				// Other kind of browsers
				'adobeair'                                                                      => 'adobe',
				'apt'                                                                           => 'apt',
				'analogx_proxy'                                                                 => 'analogx',
				'microsoft\-webdav\-miniredir'                                                  => 'frontpage',
				'microsoft\sdata\saccess\sinternet\spublishing\sprovider\scache\smanager'       => 'frontpage',
				'microsoft\sdata\saccess\sinternet\spublishing\sprovider\sdav'                  => 'frontpage',
				'microsoft\sdata\saccess\sinternet\spublishing\sprovider\sprotocol\sdiscovery'  => 'frontpage',
				'microsoft\soffice\sprotocol\sdiscovery'                                        => 'frontpage',
				'microsoft\soffice\sexistence\sdiscovery'                                       => 'frontpage',
				'gnome\-vfs'                                                                    => 'gnome',
				'neon'                                                                          => 'neon',
				'javaws'                                                                        => 'java',
				'webzip'                                                                        => 'webzip',
				'webreaper'                                                                     => 'webreaper',
				'httrack'                                                                       => 'httrack',
				'staroffice'                                                                    => 'staroffice',
				'gnus'                                                                          => 'gnus',
				'mozilla'                                                                       => 'mozilla'
			];

		$mimes =
			[
				// Text file
				'txt'   => 'text',
				'log'   => 'text',

				// HTML Static page
				'chm'   => 'page',
				'html'  => 'page',
				'htm'   => 'page',
				'mht'   => 'page',
				'wml'   => 'page',
				'wmlp'  => 'page',
				'xhtml' => 'page',
				'xml'   => 'page',
				'vak'   => 'page',
				'sgm'   => 'page',
				'sgml'  => 'page',

				// HTML Dynamic pages or script
				'asp'   => 'script',
				'aspx'  => 'dotnet',
				'ashx'  => 'dotnet',
				'asmx'  => 'dotnet',
				'axd'   => 'dotnet',
				'cfm'   => 'script',
				'class' => 'script',
				'js'    => 'jscript',
				'jsp'   => 'script',
				'cgi'   => 'script',
				'ksh'   => 'script',
				'php'   => 'php',
				'php3'  => 'php',
				'php4'  => 'php',
				'pl'    => 'pl',
				'py'    => 'script',
				'rss'   => 'rss',
				'sh'    => 'script',
				'shtml' => 'script',
				'tcl'   => 'script',
				'xsp'   => 'script',
				'vbs'   => 'vbs',

				// Image
				'gif'   => 'image',
				'png'   => 'image',
				'bmp'   => 'image',
				'jpg'   => 'image',
				'jpeg'  => 'image',
				'cdr'   => 'image',
				'ico'   => 'image',
				'svg'   => 'image',

				// Document
				'ai'    => 'document',
				'doc'   => 'document',
				'wmz'   => 'document',
				'rtf'   => 'document',
				'mso'   => 'document',
				'pdf'   => 'pdf',
				'frl'   => 'pdf',
				'xls'   => 'document',
				'ppt'   => 'document',
				'pps'   => 'document',
				'psd'   => 'document',
				'sxw'   => 'ooffice',
				'sxc'   => 'ooffice',
				'sxi'   => 'ooffice',
				'sxd'   => 'ooffice',
				'sxm'   => 'ooffice',
				'sxg'   => 'ooffice',
				'csv'   => 'csv',
				'xsl'   => 'xsl',
				'lit'   => 'lit',
				'mdb'   => 'mdb',
				'rpt'   => 'crystal',

				// Package
				'rpm'   => 'package',
				'deb'   => 'package',
				'msi'   => 'package',

				// Archive
				'7z'    => 'archive',
				'ace'   => 'archive',
				'bz2'   => 'archive',
				'cab'   => 'archive',
				'emz'   => 'archive',
				'gz'    => 'archive',
				'jar'   => 'archive',
				'lzma'  => 'archive',
				'rar'   => 'archive',
				'tar'   => 'archive',
				'tgz'   => 'archive',
				'tbz2'  => 'archive',
				'z'     => 'archive',
				'zip'   => 'archive',

				// Audio
				'aac'   => 'audio',
				'flac'  => 'audio',
				'mp3'   => 'audio',
				'oga'   => 'audio',
				'ogg'   => 'audio',
				'wav'   => 'audio',
				'wma'   => 'audio',
				'm4a'   => 'audio',
				'm3u'   => 'audio',
				'asf'   => 'audio',

				// Video
				'avi'   => 'video',
				'divx'  => 'video',
				'mp4'   => 'video',
				'm4v'   => 'video',
				'mpeg'  => 'video',
				'mpg'   => 'video',
				'ogv'   => 'video',
				'ogx'   => 'video',
				'rm'    => 'video',
				'swf'   => 'flash',
				'flv'   => 'flash',
				'f4v'   => 'flash',
				'wmv'   => 'video',
				'wmf'   => 'video',
				'mov'   => 'video',
				'qt'    => 'video',

				// Config
				'cf'    => 'conf',
				'conf'  => 'conf',
				'css'   => 'css',
				'ini'   => 'conf',
				'dtd'   => 'dtd',

				// Program
				'exe'   => 'runtime',
				'jnlp'  => 'jnlp',
				'dll'   => 'library',
				'bin'   => 'library',

				// Font
				'ttf'   => 'ttf',
				'fon'   => 'fon',

				// Encrypted files
				'pgp'   => 'encrypt',
				'gpg'   => 'encrypt',
			];

		$clock =
			[
				0  => 'hr1',
				1  => 'hr2',
				2  => 'hr3',
				3  => 'hr4',
				4  => 'hr5',
				5  => 'hr6',
				6  => 'hr7',
				7  => 'hr8',
				8  => 'hr9',
				9  => 'hr10',
				10 => 'hr11',
				11 => 'hr12',
				12 => 'hr1',
				13 => 'hr2',
				14 => 'hr3',
				15 => 'hr4',
				16 => 'hr5',
				17 => 'hr6',
				18 => 'hr7',
				19 => 'hr8',
				20 => 'hr9',
				21 => 'hr10',
				22 => 'hr11',
				23 => 'hr12'
			];

		$Domains =
			[
				'localhost' => 'localhost',
				'i0'        => 'Local network host',
				'a2'        => 'Satellite access host',

				'ac'        => 'Ascension Island',
				'ad'        => 'Andorra',
				'ae'        => 'United Arab Emirates',
				'aero'      => 'Aero/Travel domains',
				'af'        => 'Afghanistan',
				'ag'        => 'Antigua and Barbuda',
				'ai'        => 'Anguilla',
				'al'        => 'Albania',
				'am'        => 'Armenia',
				'an'        => 'Netherlands Antilles',
				'ao'        => 'Angola',
				'aq'        => 'Antarctica',
				'ar'        => 'Argentina',
				'arpa'      => 'Old style Arpanet',
				'as'        => 'American Samoa',
				'at'        => 'Austria',
				'au'        => 'Australia',
				'aw'        => 'Aruba',
				'ax'        => 'Aland islands',
				'az'        => 'Azerbaidjan',
				'ba'        => 'Bosnia-Herzegovina',
				'bb'        => 'Barbados',
				'bd'        => 'Bangladesh',
				'be'        => 'Belgium',
				'bf'        => 'Burkina Faso',
				'bg'        => 'Bulgaria',
				'bh'        => 'Bahrain',
				'bi'        => 'Burundi',
				'biz'       => 'Biz domains',
				'bj'        => 'Benin',
				'bm'        => 'Bermuda',
				'bn'        => 'Brunei Darussalam',
				'bo'        => 'Bolivia',
				'br'        => 'Brazil',
				'bs'        => 'Bahamas',
				'bt'        => 'Bhutan',
				'bv'        => 'Bouvet Island',
				'bw'        => 'Botswana',
				'by'        => 'Belarus',
				'bz'        => 'Belize',
				'ca'        => 'Canada',
				'cc'        => 'Cocos (Keeling) Islands',
				'cd'        => 'Congo, Democratic Republic of the',
				'cf'        => 'Central African Republic',
				'cg'        => 'Congo',
				'ch'        => 'Switzerland',
				'ci'        => 'Ivory Coast (Cote D\'Ivoire)',
				'ck'        => 'Cook Islands',
				'cl'        => 'Chile',
				'cm'        => 'Cameroon',
				'cn'        => 'China',
				'co'        => 'Colombia',
				'com'       => 'Commercial',
				'coop'      => 'Coop domains',
				'cr'        => 'Costa Rica',
				'cs'        => 'Former Czechoslovakia',
				'cu'        => 'Cuba',
				'cv'        => 'Cape Verde',
				'cw'        => 'Curacao',
				'cx'        => 'Christmas Island',
				'cy'        => 'Cyprus',
				'cz'        => 'Czech Republic',
				'de'        => 'Germany',
				'dj'        => 'Djibouti',
				'dk'        => 'Denmark',
				'dm'        => 'Dominica',
				'do'        => 'Dominican Republic',
				'dz'        => 'Algeria',
				'ec'        => 'Ecuador',
				'edu'       => 'USA Educational',
				'ee'        => 'Estonia',
				'eg'        => 'Egypt',
				'eh'        => 'Western Sahara',
				'er'        => 'Eritrea',
				'es'        => 'Spain',
				'et'        => 'Ethiopia',
				'eu'        => 'European country',
				'fi'        => 'Finland',
				'fj'        => 'Fiji',
				'fk'        => 'Falkland Islands',
				'fm'        => 'Micronesia',
				'fo'        => 'Faroe Islands',
				'fr'        => 'France',
				'fx'        => 'France (European Territory)',
				'ga'        => 'Gabon',
				'gb'        => 'Great Britain',
				'gd'        => 'Grenada',
				'ge'        => 'Georgia',
				'gf'        => 'French Guyana',
				'gg'        => 'Guernsey',
				'gh'        => 'Ghana',
				'gi'        => 'Gibraltar',
				'gl'        => 'Greenland',
				'gm'        => 'Gambia',
				'gn'        => 'Guinea',
				'gov'       => 'USA Government',
				'gp'        => 'Guadeloupe (French)',
				'gq'        => 'Equatorial Guinea',
				'gr'        => 'Greece',
				'gs'        => 'S. Georgia &amp; S. Sandwich Isls.',
				'gt'        => 'Guatemala',
				'gu'        => 'Guam (USA)',
				'gw'        => 'Guinea Bissau',
				'gy'        => 'Guyana',
				'hk'        => 'Hong Kong',
				'hm'        => 'Heard and McDonald Islands',
				'hn'        => 'Honduras',
				'hr'        => 'Croatia',
				'ht'        => 'Haiti',
				'hu'        => 'Hungary',
				'id'        => 'Indonesia',
				'ie'        => 'Ireland',
				'il'        => 'Israel',
				'im'        => 'Isle of Man',
				'in'        => 'India',
				'info'      => 'Info domains',
				'int'       => 'International',
				'io'        => 'British Indian Ocean Territory',
				'iq'        => 'Iraq',
				'ir'        => 'Iran',
				'is'        => 'Iceland',
				'it'        => 'Italy',
				'je'        => 'Jersey',
				'jm'        => 'Jamaica',
				'jo'        => 'Jordan',
				'jobs'      => 'Jobs domains',
				'jp'        => 'Japan',
				'ke'        => 'Kenya',
				'kg'        => 'Kyrgyzstan',
				'kh'        => 'Cambodia',
				'ki'        => 'Kiribati',
				'km'        => 'Comoros',
				'kn'        => 'Saint Kitts &amp; Nevis Anguilla',
				'kp'        => 'North Korea',
				'kr'        => 'South Korea',
				'kw'        => 'Kuwait',
				'ky'        => 'Cayman Islands',
				'kz'        => 'Kazakhstan',
				'la'        => 'Laos',
				'lb'        => 'Lebanon',
				'lc'        => 'Saint Lucia',
				'li'        => 'Liechtenstein',
				'lk'        => 'Sri Lanka',
				'lr'        => 'Liberia',
				'ls'        => 'Lesotho',
				'lt'        => 'Lithuania',
				'lu'        => 'Luxembourg',
				'lv'        => 'Latvia',
				'ly'        => 'Libya',
				'ma'        => 'Morocco',
				'mc'        => 'Monaco',
				'md'        => 'Moldova',
				'me'        => 'Montenegro',
				'mg'        => 'Madagascar',
				'mh'        => 'Marshall Islands',
				'mil'       => 'USA Military',
				'mk'        => 'Macedonia',
				'ml'        => 'Mali',
				'mm'        => 'Myanmar',
				'mn'        => 'Mongolia',
				'mo'        => 'Macau',
				'mobi'      => 'Mobi domains',
				'mp'        => 'Northern Mariana Islands',
				'mq'        => 'Martinique (French)',
				'mr'        => 'Mauritania',
				'ms'        => 'Montserrat',
				'mt'        => 'Malta',
				'mu'        => 'Mauritius',
				'museum'    => 'Museum domains',
				'mv'        => 'Maldives',
				'mw'        => 'Malawi',
				'mx'        => 'Mexico',
				'my'        => 'Malaysia',
				'mz'        => 'Mozambique',
				'na'        => 'Namibia',
				'name'      => 'Name domains',
				'nato'      => 'NATO',
				'nc'        => 'New Caledonia (French)',
				'ne'        => 'Niger',
				'net'       => 'Network',
				'nf'        => 'Norfolk Island',
				'ng'        => 'Nigeria',
				'ni'        => 'Nicaragua',
				'nl'        => 'Netherlands',
				'no'        => 'Norway',
				'np'        => 'Nepal',
				'nr'        => 'Nauru',
				'nt'        => 'Neutral Zone',
				'nu'        => 'Niue',
				'nz'        => 'New Zealand',
				'om'        => 'Oman',
				'org'       => 'Non-Profit Organizations',
				'pa'        => 'Panama',
				'pe'        => 'Peru',
				'pf'        => 'Polynesia (French)',
				'pg'        => 'Papua New Guinea',
				'ph'        => 'Philippines',
				'pk'        => 'Pakistan',
				'pl'        => 'Poland',
				'pm'        => 'Saint Pierre and Miquelon',
				'pn'        => 'Pitcairn Island',
				'pr'        => 'Puerto Rico',
				'pro'       => 'Professional domains',
				'ps'        => 'Palestinian Territories',
				'pt'        => 'Portugal',
				'pw'        => 'Palau',
				'py'        => 'Paraguay',
				'qa'        => 'Qatar',
				're'        => 'Reunion (French)',
				'ro'        => 'Romania',
				'rs'        => 'Republic of Serbia',
				'ru'        => 'Russian Federation',
				'rw'        => 'Rwanda',
				'sa'        => 'Saudi Arabia',
				'sb'        => 'Solomon Islands',
				'sc'        => 'Seychelles',
				'sd'        => 'Sudan',
				'se'        => 'Sweden',
				'sg'        => 'Singapore',
				'sh'        => 'Saint Helena',
				'si'        => 'Slovenia',
				'sj'        => 'Svalbard and Jan Mayen Islands',
				'sk'        => 'Slovak Republic',
				'sl'        => 'Sierra Leone',
				'sm'        => 'San Marino',
				'sn'        => 'Senegal',
				'so'        => 'Somalia',
				'sr'        => 'Suriname',
				'st'        => 'Sao Tome and Principe',
				'su'        => 'Former USSR',
				'sv'        => 'El Salvador',
				'sx'        => 'Sint Maarten',
				'sy'        => 'Syria',
				'sz'        => 'Swaziland',
				'tc'        => 'Turks and Caicos Islands',
				'td'        => 'Chad',
				'tf'        => 'French Southern Territories',
				'tg'        => 'Togo',
				'th'        => 'Thailand',
				'tj'        => 'Tadjikistan',
				'tk'        => 'Tokelau',
				'tm'        => 'Turkmenistan',
				'tn'        => 'Tunisia',
				'to'        => 'Tonga',
				'tp'        => 'East Timor',
				'tr'        => 'Turkey',
				'tt'        => 'Trinidad and Tobago',
				'tv'        => 'Tuvalu',
				'tw'        => 'Taiwan',
				'tz'        => 'Tanzania',
				'ua'        => 'Ukraine',
				'ug'        => 'Uganda',
				'uk'        => 'United Kingdom',
				'um'        => 'USA Minor Outlying Islands',
				'us'        => 'United States',
				'uy'        => 'Uruguay',
				'uz'        => 'Uzbekistan',
				'va'        => 'Vatican City State',
				'vc'        => 'Saint Vincent &amp; Grenadines',
				've'        => 'Venezuela',
				'vg'        => 'Virgin Islands (British)',
				'vi'        => 'Virgin Islands (USA)',
				'vn'        => 'Vietnam',
				'vu'        => 'Vanuatu',
				'wf'        => 'Wallis and Futuna Islands',
				'ws'        => 'Samoa Islands',
				'ye'        => 'Yemen',
				'yt'        => 'Mayotte',
				'yu'        => 'Yugoslavia',
				'za'        => 'South Africa',
				'zm'        => 'Zambia',
				'zr'        => 'Zaire',
				'zw'        => 'Zimbabwe'
			];

		return array_merge(
			['Browsers'                 => $Browsers],
			['Mimes'                    => $mimes],
			['Clock'                    => $clock],
			['Domains'                  => $Domains]
		);
	}
} 