<?php

namespace PHPOnCouch\Adapter;

use InvalidArgumentException,
	PHPOnCouch\CouchClient,
	PHPOnCouch\Exceptions,
	PHPUnit_Framework_TestCase,
	stdClass;

require_once join(DIRECTORY_SEPARATOR, [dirname(__DIR__), '_config', 'config.php']);

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-01 at 15:39:08.
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class CouchHttpAdapterSocketTest extends PHPUnit_Framework_TestCase
{

	private $host = 'localhost';
	private $port = '5984';
	private $dbName = 'couchclienttest';
	private $continuousQueryTriggerFile = __DIR__ . DIRECTORY_SEPARATOR . 'continuousquery.lock';
	private $admin = ["login" => "adm", "password" => "sometest"];
	protected $adapter;

	/**
	 *
	 * @var PHPOnCouch\CouchClient
	 */
	private $client;

	/**
	 *
	 * @var PHPOnCouch\CouchClient
	 */
	private $aclient;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$config = \config::getInstance();
		$this->host = $config->getHost();
		$this->port = $config->getPort();
//        $url = $config->getUrl($this->host, $this->port, null);
		$aUrl = $config->getUrl($this->host, $this->port, $config->getFirstAdmin());
		//$this->client = new CouchClient($url, 'couchclienttest');
		$this->aclient = new CouchClient($aUrl, $this->dbName);
		try {
			$this->aclient->deleteDatabase();
		} catch (\Exception $e) {
			
		}
		$this->aclient->createDatabase();
		$this->adapter = new \PHPOnCouch\Adapter\CouchHttpAdapterSocket("http://".$this->host.':'.$this->port, []);
		$this->adapter->setDsn($this->aclient->dsn());
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		if (file_exists($this->continuousQueryTriggerFile))
			unlink($this->continuousQueryTriggerFile);
		$this->aclient = null;
		$this->adapter = null;
	}

	public function testBuildRequestSendCookie()
	{
		$sessionCookie = "foo=bar";
		$adapter = $this->adapter;
		$adapter->setSessionCookie($sessionCookie);
		$this->assertTrue($adapter->hasSessionCookie());
		$this->assertEquals($sessionCookie, $adapter->getSessionCookie());
		$buildRequest = new \ReflectionMethod($adapter, 'buildRequest');
		$buildRequest->setAccessible(true);
		$httpReq = $buildRequest->invokeArgs($adapter, [
			'COPY',
			'localhost:8080/_files/return_header.php',
			['foo' => 'bar'],
			null
		]);
		$this->assertNotFalse(strpos($httpReq, "Cookie: $sessionCookie"));
		$this->assertNotFalse(strpos($httpReq, "Cookie: " . $adapter->getSessionCookie()));
	}

	public function testBuildRequestSendCustomContentType()
	{
		$contentType = "foo/bar";
		$data = ['foo' => 'bar'];
		$adapter = $this->adapter;
		$buildRequest = new \ReflectionMethod($adapter, 'buildRequest');
		$buildRequest->setAccessible(true);
		$httpReq = $buildRequest->invokeArgs($adapter, [
			'COPY',
			'localhost:8080/_files/return_header.php',
			$data,
		]);
		$this->assertNotFalse(strpos($httpReq, "Destination: " . json_encode($data)));
	}

	public function testBuildRequestSendDefaultContentType()
	{
		$defaultContentType = "application/json";
		$adapter = $this->adapter;
		$buildRequest = new \ReflectionMethod($adapter, 'buildRequest');
		$buildRequest->setAccessible(true);
		$httpRequest = $buildRequest->invokeArgs($adapter, [
			'POST',
			'localhost:8080/_files/return_header.php',
			['foo' => 'bar'],
			null
		]);
		$this->assertNotFalse(strpos($httpRequest, 'application/json'));
		$contentLenght = 'Content-Length: ' . strlen(json_encode(['foo' => 'bar']));
		$this->assertNotFalse(strpos($httpRequest, $contentLenght));
	}

	public function testQuery()
	{
		$response = $this->adapter->query('GET', '/' . $this->dbName . "/_all_docs?limit=5");
		$parsedResponse = \PhpOnCouch\Couch::parseRawResponse($response);
		$this->assertArrayHasKey('status_code', $parsedResponse);
		$this->assertArrayHasKey('status_message', $parsedResponse);
		$this->assertEquals('200', $parsedResponse['status_code']);
		$this->assertEquals('OK', $parsedResponse['status_message']);

		$this->expectException("\Exception");
		$this->adapter->query("NOEXISTING", "something");
	}

	public function testStoreFile()
	{
		$doc = (object) ['_id' => 'test_store_as_file'];

		$file = join(DIRECTORY_SEPARATOR, [dirname(__DIR__), '_config', 'test.txt']);
		$filename = 'GoogleHomepage.html';
		$contentType = 'text/html';

		$url = '/' . $this->dbName . '/' . urlencode($doc->_id) . '/' . urlencode($filename);

		$rawResponse = $this->adapter->storeFile($url, $file, $contentType);
		$parsedResponse = \PhpOnCouch\Couch::parseRawResponse($rawResponse);
		$this->assertArrayHasKey('status_code', $parsedResponse);
		$this->assertArrayHasKey('status_message', $parsedResponse);
		$this->assertEquals('201', $parsedResponse['status_code']);
		$this->assertEquals('Created', $parsedResponse['status_message']);

		$this->expectException("\Exception");
		$this->adapter->storeFile("NOEXISTING", "something", "");
	}

	public function testStoreAsFile()
	{
		$doc = (object) ['_id' => 'test_store_as_file'];

		$data = file_get_contents('http://www.google.com/');
		$filename = 'GoogleHomepage.html';
		$contentType = 'text/html';

		$url = '/' . $this->dbName . '/' . urlencode($doc->_id) . '/' . urlencode($filename);

		$rawResponse = $this->adapter->storeAsFile($url, $data, $contentType);
		$parsedResponse = \PhpOnCouch\Couch::parseRawResponse($rawResponse);
		$this->assertArrayHasKey('status_code', $parsedResponse);
		$this->assertArrayHasKey('status_message', $parsedResponse);
		$this->assertEquals('201', $parsedResponse['status_code']);
		$this->assertEquals('Created', $parsedResponse['status_message']);

		$this->expectException("\Exception");
		$this->adapter->storeAsFile("NOEXISTING", "something", "");
	}

	public function testContinuousQueryInvalid()
	{
		$this->expectException(\Exception::class);
		$this->adapter->continuousQuery(function() {
			
		}, 'UNSUPPORTEDMETHOD', '');
	}

	public function testContinuousQueryInvalidCallable()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->adapter->continuousQuery(new \stdClass(), 'GET', '');
	}

	public function testContinuousQuery()
	{
		$config = \config::getInstance();
		if (file_exists($this->continuousQueryTriggerFile))
			unlink($this->continuousQueryTriggerFile);
		$db = 'continuousquery';
		$cookieClient = new CouchClient($this->adapter->getDsn(), $db, ['cookie_auth' => true]);
		try {
			$cookieClient->deleteDatabase();
		} catch (Exceptions\CouchNotFoundException $ex) {
			
		}
		$cookieClient->createDatabase();

		$this->adapter->setSessionCookie($cookieClient->getSessionCookie());
		$cntr = new stdClass();
		$cntr->cnt = 0;
		$callable = function($row, $client) use ($cntr) {
			$cntr->cnt++;
			if ($cntr->cnt == 3)
				return false;
		};

		$trigger = escapeshellarg($this->continuousQueryTriggerFile);
		$path = escapeshellarg(join(DIRECTORY_SEPARATOR, [dirname(__DIR__), '_config', "simulateChanges.php"]));
		$config->execInBackground("php $path $db $trigger >log.txt");
		touch($this->continuousQueryTriggerFile);
		$this->adapter->continuousQuery($callable, 'GET', "/$db/_changes?feed=continuous");
		$this->assertEquals($cntr->cnt, 3);
	}

}
