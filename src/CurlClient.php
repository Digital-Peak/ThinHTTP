<?php
/**
 * @package   ThinHTTP
 * @copyright Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DigitalPeak\ThinHTTP;

/**
 * Curl implementation for the ThinHTTPInterface.
 *
 * Needs curl to work properly.
 */
class CurlClient implements ClientInterface
{
	public function get(
		string $url,
		?string $userOrToken = null,
		?string $password = null,
		array $headers = [],
		array $options = []
	): \stdClass {
		return $this->request($url, null, $userOrToken, $password, $headers, $options, 'get');
	}

	public function post(
		string $url,
		$body,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass {
		return $this->request($url, $body, $userOrToken, $password, $headers, $options, 'post');
	}

	public function put(
		string $url,
		$body,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass {
		return $this->request($url, $body, $userOrToken, $password, $headers, $options, 'put');
	}

	public function delete(
		string $url,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass {
		return $this->request($url, null, $userOrToken, $password, $headers, $options, 'delete');
	}

	public function request(
		string $url,
		$body = '',
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = [],
		string $method = 'get'
	): \stdClass {
		if (!\function_exists('curl_version')) {
			throw new \Exception('Curl must be installed, please contact an administrator!');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_USERAGENT, 'DPCalendar');

		$acceptHeader = array_filter($headers, fn ($header) => strpos($header, 'Accept: ') === 0);
		if (!$acceptHeader) {
			$headers[] = 'Accept: application/json, application/vnd.api+json';
		}

		$contentTypeHeader = array_filter($headers, fn ($header) => strpos($header, 'Content-Type: ') === 0);
		if (!$contentTypeHeader && $body && \is_string($body) && strpos($body, '{') === 0) {
			$headers[] = 'Content-Type: application/json';
		}
		if (!$contentTypeHeader && $body && \is_string($body) && strpos($body, '<') === 0) {
			$headers[] = 'Content-Type: text/xml';
		}

		if ($userOrToken && !$password) {
			$headers[] = 'Authorization: Bearer ' . $userOrToken;
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if ($userOrToken && $password) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $userOrToken . ':' . $password);
		}

		if ($body) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}

		$responseHeaders = [];
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$responseHeaders) {
			$headerData = explode(':', $header, 2);

			// Ignore invalid headers
			if (\count($headerData) < 2) {
				return \strlen($header);
			}

			$responseHeaders[strtolower(trim($headerData[0]))][] = trim(trim($headerData[1]), '\"');

			return \strlen($header);
		});

		foreach ($options as $option => $value) {
			curl_setopt($ch, $option, $value);
		}

		$output = curl_exec($ch);
		$info   = curl_getinfo($ch);
		$error  = curl_errno($ch) ? curl_error($ch) : null;

		curl_close($ch);

		if ($error) {
			throw new \Exception($error, $info['http_code']);
		}

		$data = new \stdClass();
		if (strpos($output, '{') === 0 || strpos($output, '[') === 0) {
			$data = json_decode($output);
			if ($data === null) {
				throw new \Exception('Invalid json data returned!!');
			}
		}

		if (\is_array($data)) {
			$data = (object)['data' => $data];
		}

		$data->dp          = new \stdClass();
		$data->dp->body    = $output;
		$data->dp->info    = (object)$info;
		$data->dp->headers = $responseHeaders;

		return $data;
	}
}
