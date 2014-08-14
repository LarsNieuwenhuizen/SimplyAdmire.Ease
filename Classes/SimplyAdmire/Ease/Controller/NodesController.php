<?php
namespace SimplyAdmire\Ease\Controller;

use SimplyAdmire\CR\Domain\Repository\NodeReadRepository;
use SimplyAdmire\Ease\View\NodeJsonView;
use SimplyAdmire\Ease\View\TypoScriptView;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class NodesController extends ActionController {

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'html' => 'TYPO3\Fluid\View\TemplateView',
		'json' => 'SimplyAdmire\View\NodeJsonView'
	);

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array(
		'text/html',
		'application/json'
	);

	/**
	 * @Flow\Inject
	 * @var NodeReadRepository
	 */
	protected $nodeReadRepository;

	/**
	 *
	 */
	public function indexAction() {
		if ($this->view instanceof NodeJsonView) {
			$this->view->assignNodes(array($this->nodeReadRepository->getRootNode()));
		} else {
			$this->view->assign('nodes', array($this->nodeReadRepository->getRootNode()));
		}
	}

	/**
	 * @param NodeInterface $node
	 */
	public function showAction(NodeInterface $node) {
		if ($this->view instanceof NodeJsonView) {
			$this->view->assignNode($node);
		} elseif ($this->view instanceof TypoScriptView) {
			$this->view->assign('value', $node->getNode('main/node-53ea1fc2d747a'));
		} else {
			$this->view->assign('node', $node);
		}
	}
}