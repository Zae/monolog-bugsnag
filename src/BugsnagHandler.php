<?php namespace Zae\Monolog\Bugsnag;

use Bugsnag_Client,
	Bugsnag_Configuration,
	Bugsnag_Diagnostics,
	Bugsnag_Error;

use Monolog\Handler\AbstractProcessingHandler,
	Monolog\Logger;

/**
 * @author       Ezra Pool <ezra@tsdme.nl>
 * @copyright (c), 2015 Ezra Pool
 * @license MIT
 */
class BugsnagHandler extends AbstractProcessingHandler
{
	const SEVERITY_INFO = 'info';
	const SEVERITY_WARNING = 'warning';
	const SEVERITY_ERROR = 'error';

	/**
	 * The methods that are called to send
	 *
	 * @var array
	 */
	private $methods = [
		'Zae\Monolog\Bugsnag\BugsnagHandler::write',
		'Monolog\Handler\AbstractProcessingHandler::handle',
		'Monolog\Logger::addRecord',
		'Monolog\Logger::addDebug',
		'Monolog\Logger::addInfo',
		'Monolog\Logger::addNotice',
		'Monolog\Logger::addWarning',
		'Monolog\Logger::addError',
		'Monolog\Logger::addCritical',
		'Monolog\Logger::addAlert',
		'Monolog\Logger::addEmergency',
	];

	/**
	 * @var Bugsnag_Client
	 */
	private $bugsnag;

	/**
	 * @var string
	 */
	private $error_name;

	/**
	 * BugSnagHandler constructor.
	 *
	 * @param int             $level
	 * @param bool            $bubble
	 * @param string          $error_name
	 * @param Bugsnag_Client $bugsnag
	 */
	public function __construct($level = Logger::WARNING, $bubble = true, $error_name, Bugsnag_Client $bugsnag)
	{
		parent::__construct($level, $bubble);

		$this->bugsnag = $bugsnag;
		$this->error_name = $error_name;

		$this->bugsnag->setBeforeNotifyFunction([$this, 'beforeNotifyFunction']);
	}

	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param  array $record
	 *
	 * @return void
	 */
	protected function write(array $record)
	{
		$this->bugsnag->notifyError(
			$this->error_name,
			$record['message'],
			$record['context'],
			$this->levelToSeverity($record['level'])
		);
	}

	/**
	 * Filters the stacktrace to remove any trace of this class.
	 *
	 * _Is called by the bugsnag setBeforeNotifyFunction function_
	 *
	 * @param Bugsnag_Error $error
	 */
	public function beforeNotifyFunction(Bugsnag_Error $error)
	{
		$error->stacktrace->frames = array_values(array_filter($error->stacktrace->frames, [$this, 'filterStackTrace']));
	}

	/**
	 * Will return false if the provided trace is from this lib or monolog
	 *
	 * _Is called by array_filter to filter the stacktrace._
	 *
	 * @param $value
	 * @return bool
	 */
	private function filterStackTrace($value)
	{
		return !$this->inMonologCode($value['method']);
	}

	/**
	 * Is the method a part of monolog or this lib?
	 *
	 * @param string $method
	 * @return bool
	 */
	private function inMonologCode($method)
	{
		return in_array($method, $this->methods, true);
	}

	/**
	 * Convert Monolog Level to Bugsnag Severity.
	 *
	 * @param int $level
	 * @return string
	 */
	private function levelToSeverity($level)
	{
		switch ($level) {
			case 100:
			case 200:
			case 250:
				return self::SEVERITY_INFO;
			case 300:
				return self::SEVERITY_WARNING;
			case 400:
			case 500:
			case 550:
			case 600:
				return self::SEVERITY_ERROR;
		}
	}
}