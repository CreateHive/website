<?php

namespace App\Utils;

use Exception;
use JsonException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
	public function __construct(readonly private HttpClientInterface $client)
	{
	}

	private string $baseUrl = "http://createhive.org:58082";

	public function getCurrentPlayers(): array | string
	{
		return $this->parseApi('/players');
	}

	public function getPlayerFromName(string $playerName): array | string
	{
		return $this->parseApi('/player?name='.$playerName);
	}

	/**
	 * @throws RedirectionExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws JsonException
	 */
	private function parseApi(string $endpoint): array | int | string
	{
		$response = $this->client->request(
			'GET',
			$this->baseUrl . $endpoint
		);

		$statusCode = $response->getStatusCode();
		if ($statusCode != 200) {
			return $statusCode;
		}
		// $statusCode = 200
		try {
			return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
		} catch (Exception) {
			return $response->getContent();
		}
	}
}