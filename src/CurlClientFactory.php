<?php
/**
 * @package   ThinHTTP
 * @copyright Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DigitalPeak\ThinHTTP;

class CurlClientFactory implements ClientFactoryInterface
{
	public function create(): ClientInterface
	{
		return new CurlClient();
	}
}
