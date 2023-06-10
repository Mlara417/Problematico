<?php

namespace App\ApiResource;

class ExternalRequests
{
	const CURL_COLLECTIONS = "curl --location --request GET 'https://www.codewars.com/api/v1/collections' \
    --header 'Cookie: CSRF-TOKEN=MIcLCNTFxC%2B4D%2BY37O6Uop6RxtHZ5xL7mmBdcRVtZ3GRefdqVs16xcOxVbMW4JU7u8JhCyrwHxFNjknhwr3W3w%3D%3D; _session_id=90d494baea9b858a4f9f5cf27b9e28de'";
	const CURL_KATA_URL = 'https://www.codewars.com/api/v1/code-challenges/';


	public static function runBashCommand(string $command): array
	{
		return self::normalizeJson(shell_exec($command));
	}

	public static function normalizeJson(string $json): array
	{
		return json_decode($json, true);
	}

	public static function saveAsJson(string $filename, array $data): void
	{
		file_put_contents($filename, json_encode($data));
	}
}