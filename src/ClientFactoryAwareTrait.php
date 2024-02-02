<?php
/**
 * @package   ThinHTTP
 * @copyright Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DigitalPeak\ThinHTTP;

/**
 * Trait to handle a client factory.
 */
trait ClientFactoryAwareTrait
{
	/**
	 * The internal factory.
	 */
	private ClientFactoryInterface $thinHTTPClientFactory;

	/**
	 * Returns the client factory, if none is set an UnexpectedValueException is thrown.
	 *
	 * @throws \UnexpectedValueException
	 */
	protected function getClientFactory(): ClientFactoryInterface
	{
		if ($this->thinHTTPClientFactory === null) {
			throw new \UnexpectedValueException('ClientFactory not set in ' . __CLASS__);
		}

		return $this->thinHTTPClientFactory;
	}

	/**
	 * Sets the internal client factory.
	 */
	public function setClientFactory(ClientFactoryInterface $factory): void
	{
		$this->thinHTTPClientFactory = $factory;
	}
}
