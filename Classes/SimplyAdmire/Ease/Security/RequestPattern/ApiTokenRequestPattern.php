<?php
namespace SimplyAdmire\Ease\Security\RequestPattern;

use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Mvc\RequestInterface;
use TYPO3\Flow\Security\RequestPattern\Uri;
use TYPO3\Flow\Security\RequestPatternInterface;

class ApiTokenRequestPattern implements RequestPatternInterface {

	/**
	 * @var array
	 */
	protected $patternConfiguration;

	/**
	 * Returns the pattern configuration
	 *
	 * @return string The pattern configuration
	 */
	public function getPattern() {
		return $this->patternConfiguration;
	}

	/**
	 * Sets the pattern (match) configuration
	 *
	 * @param object $patternConfiguration The pattern (match) configuration
	 * @return void
	 */
	public function setPattern($patternConfiguration) {
		$this->patternConfiguration = $patternConfiguration;
	}

	/**
	 * @param RequestInterface $request The request that should be matched
	 * @return boolean TRUE if the pattern matched, FALSE otherwise
	 */
	public function matchRequest(RequestInterface $request) {
		/** @var ActionRequest $request */

		if (isset($this->patternConfiguration['uriPattern'])) {
			$uri = new Uri();
			$uri->setPattern($this->patternConfiguration['uriPattern']);
			if ($uri->matchRequest($request) === FALSE) {
				return FALSE;
			}
		}

		// Skip firewall for local requests
		if ($request->getHttpRequest()->hasHeader('Referer') && $request->getHttpRequest()->hasHeader('Host')) {
			$refererParts = parse_url($request->getHttpRequest()->getHeader('Referer'));
			if ($request->getHttpRequest()->getHeader('Host') === $refererParts['host']) {
				return FALSE;
			}
		}

		$apiToken = $request->getHttpRequest()->getHeader('Api-Token');
		$apiTokenValid = FALSE;
		if ($apiToken !== NULL) {
			$apiTokenValid = TRUE;
		}

		return !$apiTokenValid;
	}

}