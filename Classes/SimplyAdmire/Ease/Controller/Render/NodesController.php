<?php
namespace SimplyAdmire\Ease\Controller\Render;

use SimplyAdmire\Ease\View\TypoScriptView;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class NodesController extends \SimplyAdmire\Ease\Controller\NodesController {

	/**
	 * @var SiteRepository
	 * @Flow\Inject
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject(setting="contentDimensions.language.presets", package="TYPO3.TYPO3CR")
	 * @var array
	 */
	protected $languages;

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'html' => 'SimplyAdmire\Ease\View\TypoScriptView'
	);

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array(
		'application/json'
	);

	/**
	 * @param NodeInterface $node
	 * @param string $mainLanguage
	 */
	public function renderAction(NodeInterface $node, $mainLanguage = '') {
		$dimensions = $this->defineLanguages($mainLanguage);
		$site = $this->siteRepository->findFirst();
		$context = $this->contextFactory->create(
			[
				'workspace' => 'live',
				'currentSite' => $site,
				'dimensions' => $dimensions
			]
		);
		$node = $context->getNodeByIdentifier($node->getIdentifier());
		if ($this->view instanceof TypoScriptView) {
			$this->view->assign('value', $node);
		}
	}

	/**
	 * @param string $mainLanguage
	 * @return array
	 */
	protected function defineLanguages($mainLanguage) {
		$dimensions = ['language' => []];
		foreach ($this->languages as $languageLabel => $language) {
			$dimensions['language'][] = $languageLabel;
		}
		if ($mainLanguage !== '') {
			if (($key = array_search($mainLanguage, $dimensions['language'])) !== FALSE) {
				unset($dimensions['language'][$key]);
			}
			array_unshift($dimensions['language'], $mainLanguage);
		}
		return $dimensions;
	}

}