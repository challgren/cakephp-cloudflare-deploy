<?php

namespace App\Test\TestCase\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\Stub\ConsoleInput;
use Cake\TestSuite\Stub\ConsoleOutput;
use Cake\TestSuite\TestCase;
use CloudflareDeploy\Command\ClearCloudflareCommand;

/**
 * App\Command\ClearCloudflareCommand Test Case
 *
 * @uses \CloudflareDeploy\Command\ClearCloudflareCommand
 */
class ClearCloudflareCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Test buildOptionParser method
     *
     * @return void
     */
    public function testBuildOptionParser()
    {
        $this->exec('clear_cloudflare --help');
        $this->assertOutputContains('Sets the Cloudflare zone into dev mode and purges the whole cache.');
    }

    /**
     * Test execute method
     *
     * @return void
     */
    public function testExecute()
    {
        $this->exec('clear_cloudflare');
        $this->assertErrorContains('Cloudflare settings are not configured correctly.');
    }

    public function testMockExecute()
    {
        Configure::write('Cloudflare.apiUser', 'test');
        Configure::write('Cloudflare.apiKey', 'test');
        Configure::write('Cloudflare.zoneId', 'test');
        $command = new ClearCloudflareCommand();
        $this->_out = new ConsoleOutput();
        $this->_err = new ConsoleOutput();
        $this->_in = new ConsoleInput([]);
        $io = new ConsoleIo($this->_out, $this->_err, $this->_in);
        $args = new Arguments([], [], []);

        $mock = $this->createMock('Cloudflare\API\Endpoints\Zones');
        $mock->expects($this->once())
            ->method('changeDevelopmentMode')
            ->willReturn(true);
        $mock->expects($this->once())
            ->method('cachePurgeEverything')
            ->willReturn(true);

        $command->zones = $mock;

        $command->execute($args, $io);
        $this->assertOutputContains('Cloudflare cache has been purged.');
    }

    public function testMockExecuteFailure()
    {
        Configure::write('Cloudflare.apiUser', 'test');
        Configure::write('Cloudflare.apiKey', 'test');
        Configure::write('Cloudflare.zoneId', 'test');
        $command = new ClearCloudflareCommand();
        $this->_out = new ConsoleOutput();
        $this->_err = new ConsoleOutput();
        $this->_in = new ConsoleInput([]);
        $io = new ConsoleIo($this->_out, $this->_err, $this->_in);
        $args = new Arguments([], [], []);

        $mock = $this->createMock('Cloudflare\API\Endpoints\Zones');
        $mock->expects($this->once())
            ->method('changeDevelopmentMode')
            ->willReturn(true);
        $mock->expects($this->once())
            ->method('cachePurgeEverything')
            ->willReturn(false);

        $command->zones = $mock;

        $command->execute($args, $io);
        $this->assertOutputContains('Cloudflare cache could not be purged.');
    }
}
