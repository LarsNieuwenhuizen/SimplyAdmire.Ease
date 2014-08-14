<?php
namespace SimplyAdmire\Ease\Controller\Render;

use SimplyAdmire\Ease\View\TypoScriptView;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class NodesController extends \SimplyAdmire\Ease\Controller\NodesController {

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
	 */
	public function renderAction(NodeInterface $node) {
		if ($this->view instanceof TypoScriptView) {
			$this->view->assign('value', $node);
		}
	}
}