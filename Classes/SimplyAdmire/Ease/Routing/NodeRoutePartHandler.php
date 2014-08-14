<?php
namespace SimplyAdmire\Ease\Routing;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\DynamicRoutePart;
use TYPO3\Flow\Validation\Validator\UuidValidator;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;

class NodeRoutePartHandler extends DynamicRoutePart {

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @param string $requestPath
	 * @return boolean
	 */
	protected function matchValue($requestPath) {
		if (empty($requestPath) || !preg_match(UuidValidator::PATTERN_MATCH_UUID, $requestPath)) {
			return FALSE;
		}

		$options = $this->getOptions();
		$context = $this->contextFactory->create(isset($options['context']) ? $options['context'] : array());

		try {
			$node = $context->getNodeByIdentifier($requestPath);

			if ($node instanceof NodeInterface) {
				$this->value = $node->getIdentifier();
				return TRUE;
			}

			if ($context->getRootNode()->getIdentifier() === $requestPath) {
				$this->value = $context->getRootNode()->getIdentifier();
				return TRUE;
			}
		} catch (\Exception $exception) {}

		return FALSE;
	}

	/**
	 * @param string $node
	 * @return boolean
	 */
	protected function resolveValue($node) {
		if (!$node instanceof NodeInterface) {
			return FALSE;
		}

		$this->value = $node->getIdentifier();
		return TRUE;
	}

}