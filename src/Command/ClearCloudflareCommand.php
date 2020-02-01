<?php
namespace CloudflareDeploy\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;

/**
 * ClearCloudflare command.
 */
class ClearCloudflareCommand extends Command
{
    /**
     * @var \Cloudflare\API\Endpoints\Zones|null $zones
     */
    public $zones;

    /**
     * @var \Cloudflare\API\Auth\APIKey|null $api_key
     */
    public $api_key;

    /**
     * @var \Cloudflare\API\Adapter\Guzzle $adapter
     */
    public $adapter;

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        $apiUser = Configure::read('Cloudflare.apiUser');
        $apiKey = Configure::read('Cloudflare.apiKey');

        if (!$apiKey || !$apiUser) {
            return;
        }

        $this->api_key = new APIKey($apiUser, $apiKey);
        $this->adapter = new Guzzle($this->api_key);

        $this->zones = new Zones($this->adapter);
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        $parser->setDescription('Sets the Cloudflare zone into dev mode and purges the whole cache.');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $apiUser = Configure::read('Cloudflare.apiUser');
        $apiKey = Configure::read('Cloudflare.apiKey');
        $zoneId = Configure::read('Cloudflare.zoneId');
        if (!$apiUser || !$apiKey || !$zoneId) {
            $io->error('Cloudflare settings are not configured correctly.');

            return 489;
        }

        $this->zones->changeDevelopmentMode($zoneId, true);

        if ($this->zones->cachePurgeEverything($zoneId)) {
            $io->out('Cloudflare cache has been purged.');
        } else {
            $io->out('Cloudflare cache could not be purged.');
        }

        return null;
    }
}
