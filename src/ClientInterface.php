<?php
/**
 * @package   ThinHTTP
 * @copyright Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DigitalPeak\ThinHTTP;

/**
 * Wrapper interface to make HTTP requests. Contains helpers for the most popular
 * methods GET, POST, PUT and DELETE.
 */
interface ClientInterface
{
	/**
	 * Helper function to make a GET request.
	 *
	 * @see ThinHTTP::request()
	 * @throws \Exception
	 */
	public function get(
		string $url,
		?string $userOrToken = null,
		?string $password = null,
		array $headers = [],
		array $options = []
	): \stdClass;

	/**
	 * Helper function to make a POST request.
	 *
	 * @see ThinHTTP::request()
	 * @throws \Exception
	 */
	public function post(
		string $url,
		$body,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass;

	/**
	 * Helper function to make a PUT request.
	 *
	 * @see ThinHTTP::request()
	 * @throws \Exception
	 */
	public function put(
		string $url,
		$body,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass;

	/**
	 * Helper function to make a DELETE request.
	 *
	 * @see ThinHTTP::request()
	 * @throws \Exception
	 */
	public function delete(
		string $url,
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = []
	): \stdClass;

	/**
	 * Helper function to get some data from an url. The result is the object from the response. It automatically detects
	 * if the response is a JSON strings and creates a proper object out of it. The resulting object contains a dp property
	 * which contains the following fields:
	 * - body The response body.
	 * - info The transaction information like http response code as object.
	 * - headers The headers of the response.
	 *
	 * If user is set but no password, then it is assumed it is a bearer token.
	 *
	 * @throws \Exception
	 */
	public function request(
		string $url,
		$body = '',
		?string $userOrToken = null,
		?string $password = null,
		array  $headers = [],
		array  $options = [],
		string $method = 'get'
	): \stdClass;
}
