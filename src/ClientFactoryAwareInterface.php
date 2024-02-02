<?php
/**
 * @package   ThinHTTP
 * @copyright Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DigitalPeak\ThinHTTP;

/**
 * Aware interface for classes which do need a client factory.
 */
interface ClientFactoryAwareInterface
{
	/**
	 * Sets the factory to use.
	 */
	public function setClientFactory(ClientFactoryInterface $factory): void;
}
