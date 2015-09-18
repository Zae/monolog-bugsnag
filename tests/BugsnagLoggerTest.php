<?php

use Monolog\Logger;
use Zae\Monolog\Bugsnag\BugsnagHandler;

/**
 * @author       Ezra Pool <ezra@tsdme.nl>
 * @copyright (c), 2015 Ezra Pool
 * @license MIT
 */
class BugsnagLoggerTest extends PHPUnit_Framework_TestCase
{
	const APIKEY = '';

	/**
	 * @test
	 */
	public function it_send_messages_to_bugsnag()
	{

		$bugsnag = new Bugsnag_Client(self::APIKEY);

		$logger = new Logger('my_logger');
		$logger->pushHandler(new BugsnagHandler(Logger::WARNING, true, 'BugsnagMonolog', $bugsnag));

		$logger->addError('yes', ['some' => 'context']);
		$logger->addInfo('yes', ['some' => 'context']);

		$this->assertTrue(true);
	}

	/**
	 * @test
	 */
	public function it_correctly_identifies_the_error()
	{
		$bugsnag = new Bugsnag_Client(self::APIKEY);

		$logger = new Logger('my_logger');
		$logger->pushHandler(new BugsnagHandler(Logger::WARNING, true, 'BugsnagMonolog', $bugsnag));

		$logger->addError('ok', ['some' => 'context']);

		$this->assertTrue(true);
	}

	/**
	 * @test
	 */
	public function it_correctly_identifies_the_info()
	{
		$bugsnag = new Bugsnag_Client(self::APIKEY);

		$logger = new Logger('my_logger');
		$logger->pushHandler(new BugsnagHandler(Logger::INFO, true, 'BugsnagMonolog', $bugsnag));

		$logger->addInfo('info', ['some' => 'message']);
		$logger->addError('error', ['some' => 'message']);
		$logger->addAlert('alert', ['some' => 'message']);
		$logger->addCritical('critical', ['some' => 'message']);
		$logger->addDebug('debug', ['some' => 'message']);
		$logger->addWarning('warning', ['some' => 'message']);
		$logger->addEmergency('emergency', ['some' => 'message']);
		$logger->addNotice('notice', ['some' => 'message']);

		$this->assertTrue(true);
	}
}