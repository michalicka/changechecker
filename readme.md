ChangeChecker Cron Client
=========================

This application checks a webpage for changes defined by `pattern` 
and when change is detected, an email notification with webpage screenshot 
is sent to defined email address. 

Requirements
------------

- Requires PHP 7.3
- Requires chrome web browser installed


Installation
------------

Download repository from GitHub:

	git clone https://github.com/michalicka/changechecker.git


The best way to install dependencies is using Composer. If you don't have Composer yet,
download it following [the instructions](https://getcomposer.org/). Then use commands:

	cd changechecker
	composer install


Make directory `output/` writable.

Create `config/config.neon` file and add there following lines (updated with your values):

```php
url: 			https://www.newlogic.cz/kontakt/
pattern: 		"/tel:([+\\d]*)/"
screen_height: 	2000
from_email: 	test@newlogic.cz
to_email: 		test@newlogic.cz
subject: 		Website contact info changed
sms_email: 		"776872777@sms.t-mobile.cz"
chrome_path:	"c:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe"
```


Dependencies
------------

This app uses following packages:

- [chrome-php/chrome](https://packagist.org/packages/chrome-php/chrome)
- [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle)
- [phpmailer/phpmailer](https://packagist.org/packages/phpmailer/phpmailer)
- [nette/neon](https://packagist.org/packages/nette/neon)


Usage
-----

Schedule your cron event and point it to file `src\CronJob.php`. 
If you want to use config file other than default, pass it as 1st parameter.

If you want to run it every 1 hour, add this line to your `crontab`:

	0 0 * * *  php [app_path]src/CronJob.php [app_path]config/config.neon

and replace `[app_path]` with path where the application is installed.