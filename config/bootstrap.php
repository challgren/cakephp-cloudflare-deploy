<?php
use Cake\Core\Configure;

// Optionally load additional queue config defaults from local app config
if (file_exists(ROOT . DS . 'config' . DS . 'app_cloudflare.php')) {
	Configure::load('app_cloudflare');
}
