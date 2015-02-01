# AWstats Log Files Parser for Laravel

## Reads your AWstats Log Files in [Laravel 4](http://laravel.com/) based applications.

[AWstats](http://www.awstats.org/) is a well known open source application, designed to record and parse visitor statistics on your web site host.
This Package makes those stats available for your Laravel application.

You should **NOT** use this package if you don't know what [AWstats](http://www.awstats.org/) is, how it works, and how to configure it.
You **MUST**, at the very least, know the location of your **AWstats log files** in your host.

Since **AWstats** provides a quite extensive set of data, I would recommend a visit to [AWstats web page](http://www.awstats.org/) in order to get acquainted with it's features, setup and documentation.

# Requirements

	- PHP >= 5.4
	- Laravel 4.2+.

# Installation

Add the following to the **require** section in your composer.json file

```js
{
    "require": {
        "samkitano/stats": "dev-master"
    }
}
```

Update your dependencies

```bash
$ php composer.phar update
```

Open `app/config/app.php`, and add a new item to the providers array:

	'Samkitano\Stats\StatsServiceProvider',

Publish configuration file:

```bash
$ php artisan config:publish samkitano/stats
```

You must also Publish the assets files:

```bash
$ php artisan asset:publish samkitano/stats
```

Assets like **AWstats** native icons will be then available on your `public/packages/Samkitano/Stats/assets` folder.

# Configuration

Package Settings are available in your `app/config/packages/Samkitano/Stats/config.php` file.

**AWstats log files path**

You must specify the path for the **AWstats** log files in your host. Usually something like `/home/USER/tmp/awstats/` where USER should be your host's username.

```php
'AWstats_path' => '/home/USER/tmp/awstats/',
```

**Path to Icon images folder**

Set to `null` if you don't intend to display **AWstats** native icons.

```php
'icon_path' => 'packages/Samkitano/Stats/assets/images/icon/',
```

**Icon Format**

Set to `'url'` if you want an url pointing to the icon files, or `'tag'` (default) if you prefer an <img/> html tag. The html <img/> tag will include the base64 encoded icon.

```php
'icon_format' => 'tag',
```

**Units**

Set to true if you want human readable units for bandwidths. Set to false (default) if you intend to perform further calculations based on this results.

```php
'units' => false,
```

# Usage

To obtain a list of available log files:

```php
	<?php
	// Example
	$available_logs = Stats::AWlist();
```

Results will contain existing AWstats log files structured this way:

```
	\domain
		\year
			\month => file
```

## Retrieve Data

**Retrieve all available data on a given Log File**

Access trough provided array list:

```php
// Facade Example
$file = $available_logs['myhost.com']['2015']['January'];
$data = Stats::Read($file);

// Instantiation Example
$data = App::make('stats');
$data->Read($available_logs['myhost.com']['2015']['January']);
```

Manually, if you know which file to provide (do not include .txt extension):

```php
// Facade Example
$file = '012015.myhost.com';
$data = Stats::Read($file);

// Instantiation Example
$data = App::make('stats');
$data->Read('012015.myhost.com');
```

**Retrieve Current month stats**

```php
// Facade Example
$data = Stats::Current()
```

##Methods

###General

```php
// Retrieve General Section Data
$general = $data->General();
```

**Outputs**

  - **LastLine**
    - Date of last record processed
    - Last record line number in last log
    - Last record offset in last log
    - Last record signature value
  - **FirstTime**
    - Date of first visit for history file
  - **LastTime**
    - Date of last visit for history file
  - **LastUpdate**
    - Date of last update
    - Nb of parsed records
    - Nb of parsed old records
    - Nb of parsed new records
    - Nb of parsed corrupted
    - Nb of parsed dropped
  - **TotalVisits**
    - Number of visits
  - **TotalUnique**
    - Number of unique visitors
  - **MonthHostsKnown**
    - Number of hosts known
  - **MonthHostsUnKnown**
    - Number of hosts unknown

###Misc

```php
// Retrieve Misc Section Data
$misc = $data->Misc();
```

**Outputs**

  - Rows:
	- QuickTimeSupport
	- JavaEnabled
	- JavascriptDisabled
	- PDFSupport
	- WindowsMediaPlayerSupport
	- AddToFavourites
	- RealPlayerSupport
	- TotalMisc
	- DirectorSupport
	- FlashSupport
  - Columns:
	- Misc ID
	- Pages
	- Hits
	- Bandwidth

###Time

```php
// Retrieve Time Section Data
$time = $data->Time();
```

**Outputs**

  - Rows:
	- Hour = 0 to 23
  - Columns:
	- Pages
	- Hits
	- Bandwidth
	- Not viewed Pages
	- Not viewed Hits
	- Not viewed Bandwidth
	- [Icon] Clock representing time period

###Domain

```php
// Retrieve Domain Section Data
$domain = $data->Domain();
```

**Outputs**

  - Rows:
	- Domain (up to 25 top visitor domains)
  - Columns:
	- Pages
	- Hits
	- Bandwidth
	- [Icon] country flag for known tld

###Cluster

```php
// Retrieve Cluster Section Data
// Visit AWstats documentation page for instructions on how to configure and enalble this section
$cluster = $data->Cluster();
```

**Outputs**

  - Rows:
	- Cluster ID
  - Columns:
	- Pages
	- Hits
	- Bandwidth

###Login

```php
// Retrieve Login Section Data
$login = $data->Login();
```

**Outputs**

  - Rows:
	- Login
  - Columns:
	- Pages
	- Hits
	- Bandwidth
	- Last visit

###Robot

```php
// Retrieve Robot Section Data
$robot = $data->Robot();
```

**Outputs**

  - Rows:
	-Robot ID
  - Columns:
	 - Hits
	 - Bandwidth
	 - Last visit
	 - Hits on robots.txt

###Worms

```php
// Retrieve Worms Section Data
$worm = $data->Worm();
```

**Outputs**

  - Rows:
	- Worm ID
  - Columns:
	- Hits
	- Bandwidth
	- Last visit

###Emailsender

```php
// Retrieve Email Sender Section Data
// Visit AWstats documentation page for instructions on how to configure and enalble this section
$emailsender = $data->Emailsender();
```

**Outputs**

  - Rows:
	- EMail
  - Columns:
	- Hits
	- Bandwidth
	- Last visit

###Emailreceiver

```php
// Retrieve Email Receiver Section Data
// Visit AWstats documentation page for instructions on how to configure and enalble this section
$emailreceiver = $data->Emailreceiver();
```

**Outputs**

  - Rows:
	- EMail
  - Columns:
	- Hits
	- Bandwidth
	- Last visit

###File Types

```php
// Retrieve File Types Section Data
$file_types = $data->Filetype();
```

**Outputs**

  - Rows:
	- Files type
  - Columns:
	- Hits
	- Bandwidth
	- Bandwidth without compression
	- Bandwidth after compression
	- [Icon] file type

##Downloads

```php
// Retrieve Downloads Section Data
$downloads = $data->Download();
```

**Outputs**

  - Rows:
	- Downloads
  - Columns:
	- Hits
	- Bandwidth

###Operative Systems

```php
// Retrieve Os Section Data
$os = $data->Os();
```

**Outputs**

  - Rows:
	- OS ID
  - Columns:
	- Hits
	- [Icon] OS logo

###Browsers

```php
// Retrieve Browsers Section Data
$browsers = $data->Browser();
```

**Outputs**

  - Rows:
	- Browser ID
  - Columns:
	- Hits
	- Pages
	- [Icon] Browser logo

###Screen Size

```php
// Retrieve Screen Size Section Data
// Visit AWstats documentation page for instructions on how to configure and enalble this section
$screensize = $data->ScreenSize();
```

**Outputs**

  - Rows:
	- Screen size
  - Columns:
	- Hits

###Unknown Referer Os

```php
// Retrieve Unknown Referer Section Data
$unknown_referer = $data->UnknownReferer();
```

**Outputs**

  - Rows:
	- Unknown referer OS
  - Columns:
	- Last visit date

###Unknown Referer Browser

```php
// Retrieve Unknown Referer Browser Section Data
$unknown_referer_browser = $data->UnknownRefererBrowser();
```

**Outputs**

  - Rows:
	- Unknown referer Browser
  - Columns:
	- Last visit date

###Origin

```php
// Retrieve Origin Section Data
$origin = $data->Origin();
```

**Outputs**

  - Rows:
	- Origin
		- From0 = Direct access / Bookmark / Link in email...
		- From1 = Unknown Origin
		- From2 = Links from an Internet Search Engine
		- From3 = Links from an external page (other web sites except search engines)
		- From4 = Links from an internal page (other page on same site)
		- From5 = Links from a NewsGroup
  - Columns:
	- Pages
	- Hits

###Search Engine Referrals

```php
// Retrieve Search Engine Referrals Section Data
$sereferrals = $data->Sereferrals();
```

**Outputs**

  - Rows:
	- Search engine referers ID
  - Columns:
	- Pages
	- Hits

###External Page Referers

```php
// Retrieve External Page Referers Section Data
$external_page_referers = $data->Pagerefs();
```

**Outputs**

  - Rows:
	- External page referers
  - Columns:
	- Pages
	- Hits

###Search Keyphrases

```php
// Retrieve Search Keyphrases Section Data
$search_keyphrases = $data->Searchwords();
```

**Outputs**

  - Rows:
	- Search keyphrases
  - Columns:
	- Number of search

###Search Keywords

```php
// Retrieve Search Keywords Section Data
$search_keywords = $data->Keywords();
```

**Outputs**

  - Rows:
	- Search keywords
  - Columns:
	- Number of search

###Errors

```php
// Retrieve Errors Section Data
$errors = $data->Errors();
```

**Outputs**

  - Rows:
	- Errors (HTTP code)
  - Columns:
	- Hits
	- Bandwidth

###Sider 404 Errors

```php
// Retrieve 404 Errors Section Data
$page_not_found_errors = $data->Sider404();
```

**Outputs**

  - Rows:
	- URL with 404 errors
  - Columns:
	- Hits
	- Last URL referer

##Visitor

```php
// Retrieve Visitor Section Data
$visitors = $data->Visitor();
```

**Outputs**

  - Rows:
	- Host
  - Columns:
	- Pages
	- Hits
	- Bandwidth
	- Last visit date
	- [Start date of last visit]
	- [Last page of last visit]

##Day

```php
// Retrieve Day Section Data
$days = $data->Day();
```

**Outputs**

  - Rows:
	- Date
  - Columns:
	- Pages
	- Hits
	- Bandwidth
	- Visits

###Session

```php
// Retrieve Session Section Data
$sessions = $data->Session();
```

**Outputs**

  - Rows:
	- Session range
  - Columns:
	- Number of visits

###Sider (Internal links)

```php
// Retrieve Sider Section Data
$internal_links = $data->Sider();
```

**Outputs**

  - Rows:
	- URL
  - Columns:
	- Pages
	- Bandwidth
	- Entry
	- Exit

###Best Day

```php
// Retrieve Best day of month (by hits)
$best_day = $data->Bestday();
```

**Outputs**

  - Rows:
	- Date
  - Columns:
	- Hits

#License
Open Source suftware under [MIT License](http://opensource.org/licenses/MIT)