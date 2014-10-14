<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode,
	Behat\MinkExtension\Context\MinkContext;
use SimplyAdmire\Ease\Tests\Behavior\Features\Bootstrap\HttpTrait;
use TYPO3\Flow\Utility\Arrays;
use PHPUnit_Framework_Assert as Assert;

require_once(__DIR__ . '/../../../../../Flowpack.Behat/Tests/Behat/FlowContext.php');
require_once(__DIR__ . '/../../../../../TYPO3.TYPO3CR/Tests/Behavior/Features/Bootstrap/StepDefinitionsTrait.php');
require_once(__DIR__ . '/HttpTrait.php');

/**
 * Features context
 */
class FeatureContext extends MinkContext {

	use StepDefinitionsTrait;
//	use HttpTrait;

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
	 * @Given /^I accept "([^"]*)"$/
	 */
	public function iAccept($acceptHeader) {
		$this->getSession()->setRequestHeader('Accept', $acceptHeader);
	}


	/**
	 * @When /^I send a "([^"]*)" request to "([^"]*)"$/
	 */
	public function iSendARequestToUri($requestMethod, $uri) {

		$this->getSession()->getDriver()->getClient()->request(
			$requestMethod,
			$this->locatePath('/') . $uri
		);
	}

}
