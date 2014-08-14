<?php
namespace SimplyAdmire\Ease\TypeConverter;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Validation\Validator\UuidValidator;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Exception\NodeException;

class NodeConverter extends \TYPO3\TYPO3CR\TypeConverter\NodeConverter {

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @var integer
	 */
	protected $priority = 10;

	/**
	 * @param string|array $source Either a string or array containing the absolute context node path which identifies the node. For example "/sites/mysitecom/homepage/about@user-admin"
	 * @param string $targetType not used
	 * @param array $subProperties not used
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @return mixed An object or \TYPO3\Flow\Error\Error if the input format is not supported or could not be converted for other reasons
	 * @throws NodeException
	 */
	public function convertFrom($source, $targetType = NULL, array $subProperties = array(), PropertyMappingConfigurationInterface $configuration = NULL) {
		$context = $this->contextFactory->create($this->prepareContextProperties('live', $configuration, array()));

		if (is_array($source) && (isset($source['__identity']) || isset($source['identifier']))) {
			$source = isset($source['__identity']) ? $source['__identity'] : $source['identifier'];
		}

		if (is_string($source) && preg_match(UuidValidator::PATTERN_MATCH_UUID, $source)) {
			$node = $context->getNodeByIdentifier($source);
			if ($node === NULL && $context->getRootNode()->getIdentifier() === $source) {
				$node = $context->getRootNode();
			}
		}

		if (isset($node) && $node instanceof NodeInterface) {
			return $node;
		}

		return parent::convertFrom($source, $targetType, $subProperties, $configuration);
	}

	/**
	 * Additionally add the current site and domain to the Context properties.
	 *
	 * {@inheritdoc}
	 */
	protected function prepareContextProperties($workspaceName, \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL, array $dimensions = NULL) {
		$contextProperties = parent::prepareContextProperties($workspaceName, $configuration, $dimensions);

		if ($this->packageManager->isPackageActive('TYPO3.Neos')) {
			$domainRepository = $this->objectManager->get('TYPO3\Neos\Domain\Repository\DomainRepository');
			$currentDomain = $domainRepository->findOneByActiveRequest();
			if ($currentDomain !== NULL) {
				$contextProperties['currentSite'] = $currentDomain->getSite();
				$contextProperties['currentDomain'] = $currentDomain;
			} else {
				$siteRepository = $this->objectManager->get('TYPO3\Neos\Domain\Repository\SiteRepository');
				$contextProperties['currentSite'] = $siteRepository->findOnline()->getFirst();
			}
		}

		return $contextProperties;
	}

}