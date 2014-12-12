<?php
namespace SimplyAdmire\Ease\Controller;

use SimplyAdmire\CR\Domain\Repository\NodeReadRepository;
use SimplyAdmire\Ease\View\NodeJsonView;
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;

class NodesController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'html' => 'TYPO3\Fluid\View\TemplateView',
		'json' => 'SimplyAdmire\Ease\View\NodeJsonView'
	);

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array(
		'text/html',
		'application/json'
	);

	/**
	 *
	 */
	public function indexAction() {
		if ($this->view instanceof NodeJsonView) {
			$this->view->assignNodes(array($this->contextFactory->create()->getRootNode()));
		} else {
			$this->view->assign('nodes', array($this->contextFactory->create()->getRootNode()));
		}
	}

	/**
	 * @param NodeInterface $node
	 */
	public function showAction(NodeInterface $node) {
		$flowQuery = new FlowQuery(array($node));

		if ($this->view instanceof NodeJsonView) {
			$this->view->assignNode($node);
		} else {
			$this->view->assignMultiple(array(
				'node' => $node,
				'closestDocumentNode' => $flowQuery->closest('[instanceof TYPO3.Neos:Document]')->get(0)
			));
		}
	}

	/**
	 * @param string $nodeIdentifier
	 */
	public function createAction($nodeIdentifier) {
		// TODO: Move code to command validation code
		$context = $this->contextFactory->create();
		$existingNode = $context->getNodeByIdentifier($nodeIdentifier);
		if ($existingNode !== NULL) {
			$this->throwStatus(409);
		}

		print_r($this->request->getHttpRequest()->getContent());
		die();
	}
}