<?php
namespace SimplyAdmire\Ease\View;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\View\AbstractView;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class TypoScriptView extends AbstractView {

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	public function render() {
		if ($this->packageManager->isPackageActive('TYPO3.Neos')) {
			/** @var \TYPO3\Neos\View\TypoScriptView $view */
			$view = new \TYPO3\Neos\View\TypoScriptView($this->options);
			$view->setControllerContext($this->controllerContext);

			if ($this->controllerContext->getRequest()->getHttpRequest()->hasHeader('Typoscript-Path')) {
				$view->setTypoScriptPath($this->controllerContext->getRequest()->getHttpRequest()->getHeader('Typoscript-Path'));
			}

			foreach ($this->variables as $variableName => $variableValue) {
				$view->assign($variableName, $variableValue);
			}

			return $view->render();
		}
	}
}