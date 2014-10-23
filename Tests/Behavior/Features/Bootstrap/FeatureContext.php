<?php

use Behat\MinkExtension\Context\MinkContext;

require_once(__DIR__ . '/../../../../../Flowpack.Behat/Tests/Behat/FlowContext.php');
require_once(__DIR__ . '/../../../../../TYPO3.TYPO3CR/Tests/Behavior/Features/Bootstrap/StepDefinitionsTrait.php');
require_once(__DIR__ . '/HttpTrait.php');

/**
 * Features context
 */
class FeatureContext extends MinkContext {

	use StepDefinitionsTrait;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * Initializes the context
	 *
	 * @param array $parameters Context parameters (configured through behat.yml)
	 */
	public function __construct(array $parameters) {
		$this->useContext('flow', new \Flowpack\Behat\Tests\Behat\FlowContext($parameters));
		$this->objectManager = $this->getSubcontext('flow')->getObjectManager();
	}

	/**
	 * @return \Behat\Mink\Driver\GoutteDriver
	 */
	protected function getDriver() {
		return $this->getSession()->getDriver();
	}

	/**
	 * @return \Symfony\Component\BrowserKit\Response
	 */
	protected function getResponse() {
		return $this->getDriver()->getClient()->getResponse();
	}

	/**
	 * @Given /^I accept "([^"]*)"$/
	 */
	public function iAccept($acceptHeader) {
		$this->getDriver()->setRequestHeader('Accept', $acceptHeader);
	}


	/**
	 * @Given /^I send a "([^"]*)" request to "([^"]*)"$/
	 * @Given /^I send a "([^"]*)" request to "([^"]*)" with the following data::$/
	 */
	public function iSendARequestToUri($requestMethod, $uri, \Behat\Gherkin\Node\TableNode $data = NULL) {
		if ($data !== NULL) {
			$postVars = array();
			foreach ($data->getHash() as $value) {
				$postVars[$value['Name']] = $value['Value'];
			}
		}
		$this->getDriver()->getClient()->request(
			$requestMethod,
			$this->locatePath('/') . $uri,
			array(),
			array(),
			array(),
			$data !== NULL ? http_build_query($postVars) : NULL
		);
	}

	/**
	 * @Given /^the response is JSON$/
	 */
	public function theResponseIsJson() {
		$data = json_decode($this->getResponse()->getContent());

		if (empty($data)) {
			throw new Exception('Response was not JSON' . chr(10) . $this->getDriver()->getClient()->getResponse());
		}

		return $data;
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	protected function readNodeFromJsonResponse() {
		$data = $this->theResponseIsJson();

		if (property_exists($data, 'nodes')) {
			$nodes = $data->nodes;
		} elseif (property_exists($data, 'node')) {
			$nodes = array($data->node);
		} else {
			$nodes = NULL;
		}

		if (!is_array($nodes) || $nodes === array()) {
			throw new Exception('The response did not contain any nodes' . chr(10) . $this->getDriver()->getClient()->getResponse());
		}

		if (count($nodes) !== 1) {
			throw new Exception(sprintf('The response contained %s nodes where we expected 1 node', count($nodes)) . chr(10) . $this->getDriver()->getClient()->getResponse());
		}

		return $nodes[0];
	}

	/**
	 * @Given /^the response is HTML$/
	 */
	public function theResponseIsHtml() {
		$this->assertElementOnPage('body');
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	protected function readNodeFromHtmlResponse() {
		$this->theResponseIsHtml();

		$nodes = array();

		/** @var \Behat\Mink\Element\NodeElement $nodeElement */
		foreach ($this->getSession()->getPage()->findAll('css', '.node') as $nodeElement) {
			if ($nodeElement->find('css', '[class="node-identifier"]') === NULL) {
				continue;
			}

			$node = array();

			/** @var \Behat\Mink\Element\NodeElement $nodePropertyElement */
			foreach ($nodeElement->findAll('css', '[class^="node-"]:not([class^="node-property"])') as $nodePropertyElement) {
				$node['@' . substr($nodePropertyElement->getAttribute('class'), 5)] = $nodePropertyElement->getHtml();
			}

			$nodes[] = (object)$node;
		}

		if (!is_array($nodes) || $nodes === array()) {
			throw new Exception('The response did not contain any nodes' . chr(10) . $this->getDriver()->getClient()->getResponse());
		}

		if (count($nodes) !== 1) {
			throw new Exception(sprintf('The response contained %s nodes where we expected 1 node', count($nodes)) . chr(10) . $this->getDriver()->getClient()->getResponse());
		}

		return $nodes[0];
	}

	/**
	 * @Given /^the response should contain 1 node?$/
	 */
	public function weHaveOneNode()  {
		try {
			$node = $this->readNodeFromHtmlResponse();
		} catch (\Exception $exception) {
			try {
				$node = $this->readNodeFromJsonResponse();
			} catch (\Exception $exception) {
				throw new Exception('Could not read node from response' . chr(10) . $this->getDriver()->getClient()->getResponse());
			}
		}
		return $node;
	}

	/**
	 * @Given /^the node should have identifier "([^"]*)"$/
	 */
	public function theNodeShouldHaveIdentifier($identifier) {
		$this->theNodeShouldHaveGivenPropertyWithGivenValue('@identifier', $identifier);
	}

	/**
	 * @Given /^the node should have property "([^"]*)" with value "([^"]*)"$/
	 */
	public function theNodeShouldHaveGivenPropertyWithGivenValue($propertyName, $value) {
		$node = $this->weHaveOneNode();
		if (!property_exists($node, $propertyName)) {
			throw new Exception(sprintf('Given node does not have property %s', $propertyName) . chr(10) . json_encode($node));
		}
		if ($node->$propertyName !== $value) {
			throw new Exception(sprintf('Given node does not have property %s with value %s', $propertyName, $value) . chr(10) . json_encode($node));
		}
	}
}
