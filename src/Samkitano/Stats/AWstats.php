<?php namespace Samkitano\Stats;

use Illuminate\Filesystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Carbon\Carbon;

/**
 * Class AWstats
 * @package Samkitano\Stats
 */
class AWstats extends Stats
{
	/**
	 * General
	 *
	 * LastLine          - Date of last record processed
	 *                   - Last record line number in last log
	 *                   - Last record offset in last log
	 *                   - Last record signature value
	 *
	 * FirstTime         - Date of first visit for history file
	 *
	 * LastTime          - Date of last visit for history file
	 *
	 * LastUpdate        - Date of last update
	 *                   - Nb of parsed records
	 *                   - Nb of parsed old records
	 *                   - Nb of parsed new records
	 *                   - Nb of parsed corrupted
	 *                   - Nb of parsed dropped
	 *
	 * TotalVisits       - Number of visits
	 *
	 * TotalUnique       - Number of unique visitors
	 *
	 * MonthHostsKnown   - Number of hosts known
	 *
	 * MonthHostsUnKnown - Number of hosts unknown
	 *
	 * @var
	 */
	protected $General;

	/**
	 * Misc
	 *
	 * Fields:  - Misc ID
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *
	 * Records: - QuickTimeSupport
	 *          - JavaEnabled
	 *          - JavascriptDisabled
	 *          - PDFSupport
	 *          - WindowsMediaPlayerSupport
	 *          - AddToFavourites
	 *          - RealPlayerSupport
	 *          - TotalMisc
	 *          - DirectorSupport
	 *          - FlashSupport
	 *
	 * @var
	 */
	protected $Misc;

	/**
	 * Time
	 *
	 * Fields:  - Hour
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *          - Not viewed Pages
	 *          - Not viewed Hits
	 *          - Not viewed Bandwidth
	 *
	 * Records: 24 hours From 0 to 23
	 *
	 * @var
	 */
	protected $Time;

	/**
	 * Domain
	 *
	 * Fields:  - Domain
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Domain;

	/**
	 * Cluster
	 *
	 * Fields:  - Cluster ID
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Cluster;

	/**
	 * Login
	 *
	 * Fields:  - Login
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Login;

	/**
	 * Robot
	 *
	 * Fields:  - Robot ID
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit
	 *          - Hits on robots.txt
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Robot;

	/**
	 * Worms
	 *
	 * Fields:  - Worm ID
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Worms;

	/**
	 * Emailsender
	 *
	 * Fields:  - EMail
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Emailsender;

	/**
	 * Emailreceiver
	 *
	 * Fields:  - EMail
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Emailreceiver;

	/**
	 * Filetypes
	 *
	 * Fields:  - Files type
	 *          - Hits
	 *          - Bandwidth
	 *          - Bandwidth without compression
	 *          - Bandwidth after compression
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Filetypes;

	/**
	 * Downloads
	 *
	 * Fields:  - Downloads
	 *          - Hits
	 *          - Bandwidth
	 *
	 * Records: variable
	 * @var
	 */
	protected $Downloads;

	/**
	 * OS
	 *
	 * Fields:  - OS ID
	 *          - Hits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Os;

	/**
	 * Browser
	 *
	 * Fields:  - Browser ID
	 *          - Hits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Browser;

	/**
	 * Screensize
	 *
	 * Fields:  - Screen size
	 *          - Hits
	 *
	 * Records: Variable
	 *
	 * @var
	 */
	protected $Screensize;

	/**
	 * Unknownreferer
	 *
	 * Fields:  - Unknown referer OS
	 *          - Last visit date
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $UnknownReferer;

	/**
	 * Unknownrefererbrowser
	 *
	 * Fields:  - Unknown referer Browser
	 *          - Last visit date
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $UnknownRefererBrowser;

	/**
	 * Origin
	 *
	 * Fields:  - Origin
	 *          - Pages
	 *          - Hits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Origin;

	/**
	 * Sereferrals
	 *
	 * Fields:  - Search engine referers ID
	 *          - Pages
	 *          - Hits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Sereferrals;

	/**
	 * Pagerefs
	 *
	 * Fields:  - External page referers
	 *          - Pages
	 *          - Hits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Pagerefs;

	/**
	 * Searchwords
	 *
	 * Fields:  - Search keyphrases
	 *          - Number of search
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Searchwords;

	/**
	 * Keywords
	 *
	 * Fields:  - Search keywords
	 *          - Number of search
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Keywords;

	/**
	 * Errors
	 *
	 * Fields:  - Errors
	 *          - Hits
	 *          - Bandwidth
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Errors;

	/**
	 * Sider404
	 *
	 * Fields:  - URL with 404 errors
	 *          - Hits
	 *          - Last URL referer
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Sider404;

	/**
	 * Visitor
	 *
	 * Fields:  - Host
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *          - Last visit date
	 *          - [Start date of last visit]
	 *          - [Last page of last visit]
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Visitor;

	/**
	 * Day
	 *
	 * Fields:  - Date
	 *          - Pages
	 *          - Hits
	 *          - Bandwidth
	 *          - Visits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Day;

	/**
	 * Session
	 *
	 * Fields:  - Session range
	 *          - Number of visits
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Session;

	/**
	 * Sider
	 *
	 * Fields:  - URL
	 *          - Pages
	 *          - Bandwidth
	 *          - Entry
	 *          - Exit
	 *
	 * Records: variable
	 *
	 * @var
	 */
	protected $Sider;

	/**
	 * Best day of the month (more hits)
	 *
	 * @var date
	 */
	protected $BestDay;




	/**
	 * Class constructor
	 *
	 * @param $file
	 *
	 * @throws FileNotFoundException
	 * @throws NotReadableException
	 */
	public function __construct($file)
	{
		if ( ! \File::exists($file))
			throw new FileNotFoundException("AWstats File '{$file}' Not Found!");

		if ( ! $fd = fopen($file, "r"))
			throw new FileException("AWstats File '{$file}' Is Not Readable!");

		parent::__construct();

		$this->AWdata = $this->AWread($fd);
	}

	/**
	 * Class destructor
	 */
	public function __destruct() {
		clear_object($this);
	}

	/**
	 * Read Selected AWstats File
	 *
	 * @param $handler
	 */
	private function AWread($handler)
	{
		/**
		 * Line pointer
		 */
		$pointer   = '';

		// walk the file
		do
		{
			$str = trim(fgets($handler)); // walk the line

			// skip useless lines
			if (
				substr($str, 0, 1)      === "#"
				OR substr($str, 0, 2)   === "AW"
				OR substr($str, 0, 4)   === "POS_"
				OR $str                 === ""
			) continue;

			if ( substr($str, 0, 5) === "BEGIN" )   // we have a header
			{
				$exp     = explode (' ', $str);
				$name    = explode ('_', $exp[0]);
				$part    = end     ($name);

				$pointer = $part !== "MAP" ?
					strtolower($part) : // we have a map. set the pointer to this position
					$pointer;
			}
			else
			{
				if ( substr($str, 0, 3) !== "END" ) // skip END map lines
				{
					// RECORD LINES
					$decomp = explode(' ', $str);   // decompose lines. values are separated by spaces
					$line   = $decomp[0];           // First element of array is field name

					switch ($pointer)
					{
						// *general* has 8 lines:
						// LastLine, FirstTime, LastTime, LastUpdate,
						// TotalVisits, TotalUnique, MonthHostsKnown, MonthHostsUnknown
						case 'general':
							switch($line)
							{
								case 'LastLine':
									$general['LastLine'] = $this->getLastLine($decomp);
									break;

								case 'FirstTime':
									$general['FirstTime'] = $this->getFirstTime($decomp);
									break;

								case 'LastTime':
									$general['LastTime'] = $this->getLastTime($decomp);
									break;

								case 'LastUpdate':
									$general['LastUpdate'] = $this->getLastUpdate($decomp);
									break;

								case 'TotalVisits':
									$general['TotalVisits'] = $this->getTotalVisits($decomp);
									break;

								case 'TotalUnique':
									$general['TotalUnique'] = $this->getTotalUnique($decomp);
									break;

								case 'MonthHostsKnown':
									$general['MonthHostsKnown'] = $this->getMonthHostsKnown($decomp);
									break;

								case 'MonthHostsUnknown';
									$general['MonthHostsUnknown'] = $this->getMonthHostsUnknown($decomp);
									break;
								default;
							}
							$this->General = $general;
							break;

						// *misc* has 10 lines:
						// QuickTimeSupport, JavaEnabled, JavascriptDisabled, PDFSupport,
						// WindowsMediaPlayerSupport, AddToFavourites, RealPlayerSupport,
						// TotalMisc, DirectorSupport, FlashSupport
						case 'misc':
							switch ($line)
							{
								case 'QuickTimeSupport':
									$misc['QuickTimeSupport'] = $this->getPHB($decomp);
									break;

								case 'JavaEnabled':
									$misc['JavaEnabled'] = $this->getPHB($decomp);
									break;

								case 'JavascriptDisabled':
									$misc['JavascriptDisabled'] = $this->getPHB($decomp);
									break;

								case 'PDFSupport':
									$misc['PDFsupport'] = $this->getPHB($decomp);
									break;

								case 'WindowsMediaPlayerSupport':
									$misc['WindowsMediaPlayerSupport'] = $this->getPHB($decomp);
									break;

								case 'AddToFavourites':
									$misc['AddToFavourites'] = $this->getPHB($decomp);
									break;

								case 'RealPlayerSupport':
									$misc['RealPlayerSupport'] = $this->getPHB($decomp);
									break;

								case 'TotalMisc':
									$misc['TotalMisc'] = $this->getPHB($decomp);
									break;

								case 'DirectorSupport':
									$misc['DirectorSupport'] = $this->getPHB($decomp);
									break;

								case 'FlashSupport':
									$misc['FlashSupport'] = $this->getPHB($decomp);
									break;
								default;
							}
							$this->Misc = $misc;
							break;

						// *time* has 24 lines (hours of the day) and 7 fields per record:
						// Hour - Pages - Hits - Bandwidth - Not viewed Pages - Not viewed Hits - Not viewed Bandwidth
						case 'time':
							$time[ $decomp[0] ] = $this->getTime($decomp);
							$this->Time = $time;
							break;

						// *domain* has 25 lines and 4 fields per record:
						// Domain - Pages - Hits - Bandwidth

						case 'domain':
							$domain[ $decomp[0] ] = $this->getDomain($decomp);
							$this->Domain = $domain;
							break;

						// *cluster* has undefined lines and 4 fields per record:
						// Cluster ID - Pages - Hits - Bandwidth

						case 'cluster':
							$cluster[ $decomp[0] ] = $this->getPHB($decomp);
							$this->Cluster = $cluster;
							break;

						// *login* has undefined lines and 5 fields per record:
						// Login - Pages - Hits - Bandwidth - Last visit
						case 'login':
							$login[ $decomp[0] ] = $this->getLogin($decomp);
							$this->Login = $login;
							break;

						// *robot* has undefined lines and 5 fields per record:
						// Robot ID - Hits - Bandwidth - Last visit - Hits on robots.txt
						case 'robot':
							$robot[ $decomp[0] ] = $this->getRobots($decomp);
							$this->Robot = $robot;
							break;

						// *worms* has undefined lines and 4 fields per record:
						// Worm ID - Hits - Bandwidth - Last visit
						case 'worms':
							$worms[ $decomp[0] ] = $this->getHBL($decomp);
							$this->Worms = $worms;
							break;

						// *emailsender* has undefined lines and 4 fields per record:
						// EMail - Hits - Bandwidth - Last visit
						case 'emailsender':
							$emailsender[ $decomp[0] ] = $this->getHBL($decomp);
							$this->Emailsender = $emailsender;
							break;

						// *emailreceiver* has undefined lines and 4 fields per record:
						// EMail - Hits - Bandwidth - Last visit
						case 'emailreceiver':
							$emailreceiver[ $decomp[0] ] = $this->getHBL($decomp);
							$this->Emailreceiver = $emailreceiver;
							break;

						// *filetypes* has undefined lines and 5 fields per record:
						// Files type - Hits - Bandwidth - Bandwidth without compression - Bandwidth after compression
						case 'filetypes':
							$filetypes[ $decomp[0] ] = $this->getFiletypes($decomp);
							$this->Filetypes = $filetypes;
							break;

						// *downloads* has undefined lines and 3 fields per record:
						// Downloads - Hits - Bandwidth
						case 'downloads':
							$downloads[ $decomp[0] ] = $this->getDownloads($decomp);
							$this->Downloads = $downloads;
							break;

						// *os* has undefined lines and 2 fields per record:
						// OS ID - Hits
						case 'os':
							$os[ $decomp[0] ] = $this->getOs($decomp);
							$this->Os = $os;
							break;

						// *browser* has undefined lines and 2 fields per record:
						// Browser ID - Hits
						case 'browser':
							$browser[ $decomp[0] ] = $this->getBrowser($decomp);
							$this->Browser = $browser;
							break;

						// *screensize* has undefined lines and 2 fields per record:
						// Screen size - Hits
						case 'screensize':
							$screensize[ $decomp[0] ] = $this->getHits($decomp);
							$this->Screensize = $screensize;
							break;

						// *unknownreferer* has undefined lines and 2 fields per record:
						// Unknown referer OS - Last visit date
						case 'unknownreferer':
							$unknownreferer[ $decomp[0] ] = $this->getLastVisit($decomp);
							$this->UnknownReferer = $unknownreferer;
							break;

						// *unknownrefererbrowser* has undefined lines and 2 fields per record:
						// Unknown referer Browser - Last visit date
						case 'unknownrefererbrowser':
							$unknownrefererbrowser[ $decomp[0] ] = $this->getLastVisit($decomp);
							$this->UnknownRefererBrowser = $unknownrefererbrowser;
							break;

						// *origin* has undefined lines and 3 fields per record:
						// Origin - Pages - Hits
						case 'origin':
							$origin[ $decomp[0] ] = $this->getPH($decomp);
							$this->Origin = $origin;
							break;

						// *serreferals* has undefined lines and 3 fields per record:
						// Search engine referers ID - Pages - Hits
						case 'sereferrals':
							$sereferrals[ $decomp[0] ] = $this->getPH($decomp);
							$this->Sereferrals = $sereferrals;
							break;

						// *pagerefs* has undefined lines and 3 fields per record:
						// External page referers - Pages - Hits
						case 'pagerefs':
							$pagerefs[ $decomp[0] ] = $this->getPH($decomp);
							$this->Pagerefs = $pagerefs;
							break;

						// *searchwords* has undefined lines and 2 fields per record:
						// Search keyphrases - Number of search
						case 'searchwords':
							$searchwords[ $decomp[0] ] = $this->getNumberOfSearch($decomp);
							$this->Searchwords = $searchwords;
							break;

						// *keywords* has undefined lines and 2 fields per record:
						// Search keywords - Number of search
						case 'keywords':
							$keywords[ $decomp[0] ] = $this->getNumberOfSearch($decomp);
							$this->Keywords = $keywords;
							break;

						// *errors* has undefined lines and 3 fields per record:
						// Errors - Hits - Bandwidth
						case 'errors':
							$errors[ $decomp[0] ] = $this->getErrors($decomp);
							$this->Errors = $errors;
							break;

						// *404* has undefined lines and 3 fields per record:
						// URL with 404 errors - Hits - Last URL referer
						case '404':
							$t404[ 'Sider'.$decomp[0] ] = $this->get404($decomp);
							$this->Sider404 = $t404;
							break;

						// *visitor* has undefined lines and 5 fields per record plus 2 optional:
						// Host - Pages - Hits - Bandwidth - Last visit date
						// [Start date of last visit] - [Last page of last visit]
						case 'visitor':
							$visitor[ $decomp[0] ] = $this->getVisitor($decomp);
							$this->Visitor = $visitor;
							break;

						// *day* has undefined lines and 5 fields per record
						// Date - Pages - Hits - Bandwidth - Visits
						case 'day':
							$day[ $decomp[0] ] = $this->getDay($decomp);
							$this->Day = $day;
							break;

						// *session* has undefined lines and 2 fields per record
						// Session range - Number of visits
						case 'session':
							$session[ $decomp[0] ] = $this->getSession($decomp);
							$this->Session = $session;
							break;

						// *sider* has 25 lines and 5 fields per record
						// URL - Pages - Bandwidth - Entry - Exit
						case 'sider':
							$sider[ $decomp[0] ] = $this->getSider($decomp);
							$this->Sider = $sider;
							break;

						default;
					}
				}
			}
		}
		while ( ! feof($handler) );

		$this->BestDay = $this->getBestDay();
	}

	/*
	|--------------------------------------------------------------------------
	| HELPER FUNCTIONS
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Last Line Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getLastLine($fields)
	{
		return array_merge(
			$this->_getDateField('DateOfLastRecordProcessed',    isset($fields[1]) ? $fields[1] : null),
			$this->_getStrField('LastRecordLineNumberInLastLog', isset($fields[2]) ? $fields[2] : null),
			$this->_getStrField('LastRecordOffsetInLastLog',     isset($fields[3]) ? $fields[3] : null),
			$this->_getStrField('LastRecordSignatureValue',      isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * First Time Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getFirstTime($fields)
	{
		return $this->_getDateField('DateOfFirstVisitForHistoryFile', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Last Time Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getLastTime($fields)
	{
		return $this->_getDateField('DateOfLastVisitForHistoryFile', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Last Update Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getLastUpdate($fields)
	{
		return $this->_getDateField('DateOfLastUpdate', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Total Visits Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getTotalVisits($fields)
	{
		return $this->_getStrField('NumberOfVisits', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Total Unique Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getTotalUnique($fields)
	{
		return $this->_getStrField('NumberOfUniqueVisitors', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Month Hosts Known Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getMonthHostsKnown($fields)
	{
		return $this->_getStrField('NumberOfHostsKnown', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Month Hosts Unknown Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getMonthHostsUnknown($fields)
	{
		return $this->_getStrField('NumberOfHostsUnknown', isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Time Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getTime($fields)
	{
		if ($this->use_icons)
			return array_merge(
				$this->_getPages      (isset($fields[1]) ? $fields[1] : null),
				$this->_getHits       (isset($fields[2]) ? $fields[2] : null),
				$this->_getBandWidth  (isset($fields[3]) ? $fields[3] : null),
				$this->_getStrField   ('NotViewedPages',     isset($fields[4]) ? $fields[4] : null),
				$this->_getStrField   ('NotViewedHits',      isset($fields[5]) ? $fields[5] : null),
				$this->_getStrField   ('NotViewedBandwidth', isset($fields[6]) ? $fields[6] : null),
				$this->_getClockIcons ($fields[0])
			);

		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth (isset($fields[3]) ? $fields[3] : null),
			$this->_getStrField  ('NotViewedPages',     isset($fields[4]) ? $fields[4] : null),
			$this->_getStrField  ('NotViewedHits',      isset($fields[5]) ? $fields[5] : null),
			$this->_getStrField  ('NotViewedBandwidth', isset($fields[6]) ? $fields[6] : null)
		);
	}

	/**
	 * Domain Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getDomain($fields)
	{
		if ($this->use_icons)
			return array_merge(
				$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
				$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
				$this->_getBandWidth (isset($fields[3]) ? $fields[1] : null),
				$this->_getFlag      (isset($fields[0]) ? $fields[0] : null)
			);

		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth (isset($fields[3]) ? $fields[1] : null)
		);
	}

	/**
	 * Login Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getLogin($fields)
	{
		return array_merge(
			$this->_getPages        (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits         (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth    (isset($fields[3]) ? $fields[3] : null),
			$this->_getLastVisit    (isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * Robots Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getRobots($fields)
	{
		return array_merge(
			$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null),
			$this->_getLastVisit (isset($fields[3]) ? $fields[3] : null),
			$this->_getStrField  ('HitsOnRobotsTxt', isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * Filetypes Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getFiletypes($fields)
	{
		if ( $this->use_icons)
			return array_merge(
				$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
				$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null),
				$this->_getStrField  ('BandWidthWithoutCompression', isset($fields[3]) ? $fields[3] : null),
				$this->_getStrField  ('BandWidthAfterCompression',   isset($fields[4]) ? $fields[4] : null),
				$this->_getMimeIcons ($fields[0])
			);

		return array_merge(
			$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null),
			$this->_getStrField  ('BandWidthWithoutCompression', isset($fields[3]) ? $fields[3] : null),
			$this->_getStrField  ('BandWidthAfterCompression',   isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * Downloads Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getDownloads($fields)
	{
		return array_merge(
			$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null)
		);
	}


	/**
	 * All lines with Hits - Bandwidth - Last Visit fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getHBL($fields)
	{
		return array_merge(
			$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null),
			$this->_getLastVisit (isset($fields[3]) ? $fields[3] : null)
		);
	}

	/**
	 * Os Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getOs($fields)
	{
		if ($this->use_icons)
			return array_merge(
				$this->_getHits(isset($fields[1]) ? $fields[1] : null),
				$this->_getOsIcon($fields[0])
			);

		return $this->_getHits(isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Browser line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getBrowser($fields)
	{
		if ($this->use_icons)
			return array_merge(
				$this->_getHits(isset($fields[1]) ? $fields[1] : null),
				$this->_getBrowserIcons($fields[0])
			);
		return $this->_getHits(isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Errors Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getErrors($fields)
	{
		return array_merge(
			$this->_getHits      (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null)
		);
	}

	/**
	 * Sider 404 Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function get404($fields)
	{
		return array_merge(
			$this->_getHits           (isset($fields[1]) ? $fields[1] : null),
			$this->_getLastUrlReferer (isset($fields[2]) ? $fields[2] : null)
		);
	}

	/**
	 * Visitor Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getVisitor($fields)
	{
		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth (isset($fields[3]) ? $fields[3] : null),
			$this->_getLastVisit (isset($fields[4]) ? $fields[4] : null),
			$this->_getDateField ('StartDateOfLastVisit', isset($fields[5]) ? $fields[5] : null),
			$this->_getStrField  ('LastPageOfLastVisit',  isset($fields[6]) ? $fields[6] : null)
		);
	}

	/**
	 * Day Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getDay($fields)
	{
		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth (isset($fields[3]) ? $fields[3] : null),
			$this->_getVisits    (isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * Session Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getSession($fields)
	{
		return array_merge(
			$this->_getStrField ('SessionRange',   isset($fields[1]) ? $fields[1] : null),
			$this->_getStrField ('NumberOfVisits', isset($fields[2]) ? $fields[2] : null)
		);
	}

	/**
	 * Sider Line
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getSider($fields)
	{
		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getBandWidth (isset($fields[2]) ? $fields[2] : null),
			$this->_getStrField  ('Entry', isset($fields[3]) ? $fields[3] : null),
			$this->_getStrField  ('Exit',  isset($fields[4]) ? $fields[4] : null)
		);
	}

	/**
	 * All lines with Pages - Hits - Bandwidth Fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getPHB($fields)
	{
		return array_merge(
			$this->_getPages     (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits      (isset($fields[2]) ? $fields[2] : null),
			$this->_getBandWidth (isset($fields[3]) ? $fields[1] : null)
		);
	}

	/**
	 * All Lines with Pages - Hits Fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getPH($fields)
	{
		return array_merge(
			$this->_getPages (isset($fields[1]) ? $fields[1] : null),
			$this->_getHits  (isset($fields[2]) ? $fields[2] : null)
		);
	}

	/**
	 * Last Visit Fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getLastVisit($fields)
	{
		return $this->_getLastVisit(isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Number Of Search Fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getNumberOfSearch($fields)
	{
		return $this->_getNumberOfSearch(isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Hits Fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	private function getHits($fields)
	{
		return $this->_getHits(isset($fields[1]) ? $fields[1] : null);
	}

	/**
	 * Pages Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getPages($d)
	{
		return ['Pages' => isset($d) ? $d : null];
	}

	/**
	 * Hits Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getHits($d)
	{
		return ['Hits' => isset($d) ? $d : null];
	}

	/**
	 * Bandwidth Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getBandWidth($d)
	{
		if ($this->use_units)
			return ['Bandwidth' => isset($d) ? $this->formatBytes($d) : null];

		return ['Bandwidth' => isset($d) ? $d : null];
	}

	/**
	 * Last Visit Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getLastVisit($d)
	{
		return ['LastVisit' => isset($d) ? $this->carbonToStr($d) : null];
	}

	/**
	 * Number of Search Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getNumberOfSearch($d)
	{
		return ['NumberOfSearch' => isset($d) ? $d : null];
	}

	/**
	 * Last URL Referer Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getLastUrlReferer($d)
	{
		return ['LastUrlReferer' => isset($d) ? $d : null];
	}

	/**
	 * Visits Fields
	 *
	 * @param $d
	 *
	 * @return array
	 */
	private function _getVisits($d)
	{
		return ['Visits' => isset($d) ? $d : null];
	}

	/**
	 * Read known string fields
	 *
	 * @param $name
	 * @param $val
	 *
	 * @return array
	 */
	private function _getStrField($name, $val)
	{
		if($name === 'NotViewedBandwidth')
			return [$name => $this->formatBytes($val)];

		return [$name => $val];
	}

	/**
	 * Read known timestamp fields
	 *
	 * @param $name
	 * @param $val
	 *
	 * @return array
	 */
	private function _getDateField($name, $val)
	{
		if (is_null($val)) return [$name => null];
		
		return [$name => $this->carbonToStr($val)];
	}

	/**
	 * Get icons for domains
	 *
	 * @param $domain
	 *
	 * @return array
	 * @throws FileNotFoundException
	 */
	private function _getFlag($domain)
	{
		$srch      = $this->icons;
		$nameicon  = isset($srch['Domains'][$domain]) ? $domain : 'unknown';
		$file      = $this->icon_path . 'flags/'. $nameicon.'.png';

		$this->verifyFile($file);

		if ($this->icon_format === 'tag')
		{
			return ['Icon' => $this->getImgTag($file, $domain), 'Description' => $nameicon];
		}
		elseif ($this->icon_format === 'url')
		{
			return ['Icon' => $file, 'Description' => $srch['Domains'][$domain]];
		}
		else
		{
			return ['Icon' => null, 'Description' => null];
		}
	}

	/**
	 * Get icons for operative systems
	 *
	 * @param $os
	 *
	 * @return array
	 * @throws FileNotFoundException
	 */
	private function _getOsIcon($os)
	{
		$file = $this->icon_path . 'os/'. $os.'.png';

		$this->verifyFile($file);

		if ($this->icon_format === 'tag')
		{
			return ['Icon' => $this->getImgTag($file, $os)];
		}
		elseif ($this->icon_format === 'url')
		{
			return ['Icon' => $file];
		}
		else
		{
			return ['Icon' => null];
		}
	}

	/**
	 * Get icons for Mime Types
	 *
	 * @param $mime
	 *
	 * @return array
	 * @throws FileNotFoundException
	 */
	private function _getMimeIcons($mime)
	{
		$srch       = $this->icons;
		$nameicon   = isset($srch['Mimes'][$mime]) ? $srch['Mimes'][$mime] : 'notavailable';
		$file       = $this->icon_path . 'mime/'. $nameicon . '.png';

		$this->verifyFile($file);

		if ($this->icon_format === 'tag')
		{
			return ['Icon' => $this->getImgTag($file, $mime)];
		}
		elseif ($this->icon_format === 'url')
		{
			return ['Icon' => $file];
		}
		else
		{
			return ['Icon' => null];
		}
	}

	/**
	 * Get icons for Time (clocks)
	 *
	 * @param $clock
	 *
	 * @return array
	 * @throws FileNotFoundException
	 */
	private function _getClockIcons($clock)
	{
		$srch       = $this->icons;
		$nameicon   = isset($srch['Clock'][$clock]) ? $srch['Clock'][$clock] : 'notavailable';
		$file       = $this->icon_path . 'clock/'. $nameicon . '.png';

		$this->verifyFile($file);

		if ($this->icon_format === 'tag')
		{
			return ['Icon' => $this->getImgTag($file, $clock)];
		}
		elseif ($this->icon_format === 'url')
		{
			return ['Icon' => $file];
		}
		else
		{
			return ['Icon' => null];
		}

	}

	/**
	 * Get icons for browsers
	 *
	 * @param $browser
	 *
	 * @return array
	 * @throws FileNotFoundException
	 */
	private function _getBrowserIcons($browser)
	{
		$browser  = preg_replace('/[0-9].+/', '', $browser);
		$srch     = $this->icons;
		$nameicon = isset($srch['Browsers'][$browser]) ? $srch['Browsers'][$browser] : 'notavailable';
		$file     = $this->icon_path . 'browser/'. $nameicon . '.png';

		$this->verifyFile($file);

		if ($this->icon_format === 'tag')
		{
			return ['Icon' => $this->getImgTag($file, $browser)];
		}
		elseif ($this->icon_format === 'url')
		{
			return ['Icon' => $file];
		}
		else
		{
			return ['Icon' => null];
		}
	}


	/**
	 * Convert timestamp to Date-time string
	 *
	 * @param      $timestamp
	 * @param bool $full
	 *
	 * @return mixed
	 */
	private function carbonToStr($timestamp, $full = true)
	{
		$d = $full ?
			(array)Carbon::createFromFormat('YmdHis', $timestamp)
			:
			(array)Carbon::createFromFormat('Ymd', $timestamp);

		return $d['date'];
	}

	/**
	 * Check if a file exists
	 * @param $theFile
	 *
	 * TODO: make blank image 14x14 instead of throwing an error if no image is found. This can happen with new Os's, browsers, or flags
	 * @throws FileNotFoundException
	 */
	private function verifyFile($theFile)
	{
		if ( ! \File::exists($theFile))
			throw new FileNotFoundException("Image File '{$theFile}' not found!");

		$this->verifyMime(mime_content_type($theFile), $theFile);
	}

	/**
	 * Check is a mime is acceptabe
	 *
	 * @param $mime
	 * @param $thisFile
	 *
	 * @throws FileException
	 */
	private function verifyMime($mime, $thisFile)
	{
		if ( ! in_array($mime, $this->valid_img_mimes))
			throw new FileException("File '{$thisFile}' is not a valid image format. Please use png jpg or gif");

	}

	/**
	 * Build html img tag for icons
	 *
	 * @param $file
	 * @param $alt
	 *
	 * @return null|string
	 */
	private function getImgTag($file, $alt)
	{
		/**
		 * @author Krasimir Tsonev
		 * Author URI <http://krasimirtsonev.com/blog/article/how-to-import-images-directly-into-the-html-code-base64>
		 */
		if($fp = fopen($file, "rb", 0))
		{
			$picture = fread($fp, filesize($file));
			fclose($fp);
			// base64 encode the binary data, then break it
			// into chunks according to RFC 2045 semantics
			return '<img src="data:i'
			. mime_content_type($file)
			. ';base64,'
			. chunk_split(base64_encode($picture))
			. '" alt="'
			. $alt
			.'"/>';
		}
		return null;// TODO: throw could not read error
	}

	/**
	 * Convert Bytes to human readable units
	 *
	 * @author      Rommel Santor
	 * Author Email <rommel@rommelsantor.com>
	 * Source URI   <http://php.net/manual/de/function.filesize.php>
	 *
	 * @param   string    $bytes
	 * @param   int       $precision
	 *
	 * @return string
	 */
	private function formatBytes($bytes, $precision = 2) {
		$factor = floor((strlen($bytes) - 1) / 3);
		$units  = [0=>'B', 1 => 'KB', 2 => 'MB', 3 => 'GB', 4 => 'TB', 5 => 'PB'];
		return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . ' '. $units[$factor];
	}

	/**
	 * Get Best Day (more page views)
	 *
	 * @return array
	 */
	private function getBestDay(){
		$max  = 0;
		$date = null;
		foreach ($this->Day as $k => $day) {
			if ($day['Hits'] > $max)
			{
				$max  = $day['Hits'];
				$date = $k;
			}
		}
		$d    = $this->carbonToStr($date, false);
		$dexp = explode(' ', $d);

		return ['Date'=>$dexp[0], 'Hits'=>$max];
	}

} 