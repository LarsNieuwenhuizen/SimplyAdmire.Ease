<?php
namespace SimplyAdmire\Ease\Tests\Behavior\Features\Bootstrap;

use Behat\Gherkin\Node\PyStringNode;
use PHPUnit_Framework_Assert as Assert;
use Symfony\Component\Yaml\Yaml;
use Behat\Gherkin\Node\TableNode;

trait HttpTrait {

	/**
	 * @var \TYPO3\Flow\Http\Client\CurlEngine
	 */
	protected $curlEngine;

	/**
	 * @var \TYPO3\Flow\Http\Request
	 */
	protected $httpRequest;

	/**
	 * @var \TYPO3\Flow\Http\Response
	 */
	protected $httpResponse;

	/**
	 * @var array
	 */
	protected $requestHeaders = array();




	/**
	 * @Then /^the response status code should be ([0-9]{3})$/
	 */
	public function iExpectReturnStatusCode($statusCode) {
		Assert::assertEquals($statusCode, $this->httpResponse->getStatusCode(), $this->httpRequest->getMethod() . ' - ' . $this->httpRequest->getUri());
	}


//
//
//
//
//
//	/**
//	 * @When /^I create a "([^"]*)" request to "([^"]*)"$/
//	 */
//	public function iCreateARequest($requestMethod, $url) {
//		$this->curlEngine = new \TYPO3\Flow\Http\Client\CurlEngine();
//
//		$urlParts = parse_url($this->locatePath('/') . $url);
//
//		$this->httpRequest = new \TYPO3\Flow\Http\Request(array(), array(), array(),
//			array(
//				'HTTP_HOST' => $urlParts['host'],
//				'REQUEST_URI' => $urlParts['path']
//			)
//		);
//
//		$this->httpRequest->setMethod($requestMethod);
//	}
//
//	/**
//	 * @Given /^It has the following headers:$/
//	 */
//	public function itHasTheFollowingHeaders(PyStringNode $headers) {
//		$headers = Yaml::parse($headers->getRaw());
//		foreach ($headers as $headerName => $headerValue) {
//			$this->httpRequest->setHeader($headerName, $headerValue);
//		}
//	}
//
//	/**
//	 * @Given /^It has the following content:$/
//	 */
//	public function itHasTheFollowingContent($content) {
//		if ($content instanceof PyStringNode) {
//			$content = $content->getRaw();
//		}
//		$this->httpRequest->setContent($content);
//	}
//
//	/**
//	 * @Given /^I send the request$/
//	 */
//	public function iSendTheRequest() {
//		$this->httpResponse = $this->curlEngine->sendRequest($this->httpRequest);
//	}
//
//
//
//	/**
//	 * @Given /^I send the following requests:$/
//	 * @param TableNode $table
//	 */
//	public function iSendTheFollowingRequests(TableNode $table) {
//		$rows = $table->getHash();
//		foreach ($rows as $row) {
//			$requestMethod = isset($row['Request Method']) ? trim($row['Request Method']) : 'GET';
//			$path = isset($row['Path']) ? trim($row['Path']) : '';
//
//			$content = isset($row['Content']) ? trim($row['Content']) : NULL;
//			if (substr($content, 0, 11) === 'resource://') {
//				$fileName = str_replace('resource://', __DIR__ . '/../../', $content);
//				if (is_file($fileName)) {
//					$content = file_get_contents($fileName);
//				}
//			}
//
//			$this->iCreateARequest($requestMethod, $path);
//			$this->itHasTheFollowingContent($content);
//			$this->iSendTheRequest();
//
//			if (isset($row['Status'])) {
//				$this->iExpectReturnStatusCode((int)$row['Status']);
//			}
//		}
//	}

}