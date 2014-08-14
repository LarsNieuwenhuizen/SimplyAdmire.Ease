<?php
namespace SimplyAdmire\Ease\View;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\View\JsonView;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class NodeJsonView extends JsonView {

	/**
	 * Assigns a node to the NodeView.
	 *
	 * @param NodeInterface $node The node to render
	 * @param array $propertyNames Optional list of property names to include in the JSON output
	 * @return void
	 */
	public function assignNode(NodeInterface $node, array $propertyNames = array('name', 'path', 'identifier', 'properties', 'nodeType')) {
		$this->setConfiguration(
			array(
				'value' => array(
					'node' => array(
						'_only' => array('name', 'path', 'identifier', 'properties', 'nodeType'),
						'_descend' => array('properties' => $propertyNames)
					)
				)
			)
		);

		$this->assign('value', array('node' => $node));
	}

	public function assignNodes(array $nodes, array $propertyNames = array('name', 'path', 'identifier', 'properties', 'nodeType')) {
		$nodeConfiguration = array(
			'_only' => array('name', 'path', 'identifier', 'properties', 'nodeType'),
			'_descend' => array('properties' => $propertyNames)
		);
		$viewConfiguration = array('value' => array('nodes' => array()));
		foreach ($nodes as $index => $node) {
			$viewConfiguration['value']['nodes'][$index] = $nodeConfiguration;
		}
		$this->setConfiguration($viewConfiguration);

		$this->assign('value', array('nodes' => $nodes));
	}


	/**
	 * Traverses the given object structure in order to transform it into an
	 * array structure.
	 *
	 * @param object $object Object to traverse
	 * @param array $configuration Configuration for transforming the given object or NULL
	 * @return array Object structure as an array
	 */
	protected function transformObject($object, array $configuration) {
		$transformedObject = parent::transformObject($object, $configuration);

		\TYPO3\Flow\var_dump($transformedObject);
		return $transformedObject;
	}
}