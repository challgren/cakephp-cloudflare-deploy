# CakePHP Cloudflare Deploy Plugin Documentation
## Version Map
See [Version Map](https://github.com/challgren/cakephp-cloudflare-deploy/wiki)

## Installation
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

```sh
$ composer require challgren/cakephp-cloudflare-deploy
```

[Load the plugin](https://book.cakephp.org/3.8/en/plugins.html#loading-a-plugin) in your `src/Application.php`'s boostrap() using:
```sh
$ bin/cake plugin load CloudflareDeploy
```

## Configuration

Set your Cloudflare AAP User, API key and zoneId in `Cloudflare` settings in `app.php`.

_You can get your zone ID by viewing the Overview of the domain controlled by Cloudflare._

```php
return [
	'Cloudflare' => [
		'apiUser' => 'API Username',
		'apiKey' => 'API Key',
		'zoneId' => 'Zone ID'
	]
];
```

## Usage

Run `bin/cake clear_cloudflare` this will enable Development on Cloudflare and purge the entire cache.
