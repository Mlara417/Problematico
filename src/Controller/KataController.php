<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class KataController extends AbstractController
{
    const CODEWARS_COLLECTION_KATAS = 'code_challenges';
    const CODEWARS_COLLECTIONS_LABEL = 'items';
    const CODEWARS_KATA_DESCRIPTION_LABEL = 'description';
    const CODEWARS_KATA_ID_LABEL = 'code_challenge_id';
    const CODEWARS_KATA_NAME_LABEL = 'name';
    const CURL_COLLECTION_URL = 'https://www.codewars.com/api/v1/collections';
    const CURL_GET_REQUEST_PREFIX = "curl --location --request GET ";
    const CURL_HEADER = " \
    --header 'Cookie: CSRF-TOKEN=MIcLCNTFxC%2B4D%2BY37O6Uop6RxtHZ5xL7mmBdcRVtZ3GRefdqVs16xcOxVbMW4JU7u8JhCyrwHxFNjknhwr3W3w%3D%3D; _session_id=90d494baea9b858a4f9f5cf27b9e28de'";
    const CURL_HEADER_COOKIE = "Cookie: CSRF-TOKEN=MIcLCNTFxC%2B4D%2BY37O6Uop6RxtHZ5xL7mmBdcRVtZ3GRefdqVs16xcOxVbMW4JU7u8JhCyrwHxFNjknhwr3W3w%3D%3D; ";
    const CURL_KATA_URL = 'https://www.codewars.com/api/v1/code-challenges/';
    const EASY_DIFFICULTY_LABEL = 'easy';
    const EASY_KATAS_FILE = 'easy-katas.json';
    const ERROR_NOT_FOUND_MESSAGE = 'Kata not found';
    const SUCCESSFUL_SYNC_MESSAGE = 'Katas sync\'d successfully';

    #[Route('/kata', name: 'kata_list', methods: ['GET'])]
    public function list(#[MapQueryParameter] ?array $ids): Response
    {
        $katas = $this->getCachedData();

        //Show all katas if no ids are passed
        if ($ids === null) {
            return $this->json($katas);
        }

        //Show only the katas that match the ids passed
        foreach ($ids as $id) {
            $kataIndex = $this->kataExists($katas, $id);

            if ($kataIndex !== false) {
                $searchList[] = $katas[$kataIndex];
            }
        }

        if (isset($searchList)) {
            return $this->json($searchList);
        }

        //Show error if no katas are found
        $response = new Response(self::ERROR_NOT_FOUND_MESSAGE, Response::HTTP_NOT_FOUND);

        return $response->send();
    }

    #[Route('/kata/sync', name: 'kata_sync', methods: ['GET'])]
    public function sync(): Response //TODO: Add a cron job to sync the katas every 24 hours
    {
        $katas = $this->getKatasList();

        foreach ($katas as $kata) {
            $codewarsKata = $this->getKata($kata[self::CODEWARS_KATA_ID_LABEL]);

            $kataCache[] = $codewarsKata;

            $normalizedKatas[] = [
                'id' => $kata[self::CODEWARS_KATA_ID_LABEL],
                'name' => $kata[self::CODEWARS_KATA_NAME_LABEL],
                'question' => $codewarsKata[self::CODEWARS_KATA_DESCRIPTION_LABEL],
                'type' => self::EASY_DIFFICULTY_LABEL,
            ];
        }
        file_put_contents(self::EASY_KATAS_FILE, json_encode($normalizedKatas));

        $response = new Response(self::SUCCESSFUL_SYNC_MESSAGE);

        return $response->send();
    }

    public function getCachedData()
    {
        try{
            $json = file_get_contents(self::EASY_KATAS_FILE) ?? '';
        } catch(Exception $e) {
            $json = '';
        }
        
        return json_decode($json, true);
    }

    private function getKata($id)
    {
        $requestURL = self::CURL_KATA_URL . $id;
        $codewarsKata = shell_exec(self::CURL_GET_REQUEST_PREFIX . $requestURL);
        return json_decode($codewarsKata, true);
    }

    private function getKatasList()
    {
        $output = shell_exec(self::CURL_GET_REQUEST_PREFIX . self::CURL_COLLECTION_URL . self::CURL_HEADER);
        $output = json_decode($output, true);
        return $output[self::CODEWARS_COLLECTIONS_LABEL][0][self::CODEWARS_COLLECTION_KATAS];
    }

    private function kataExists($katas, $search, $searchKey = 'id')
    {
        return array_search($search, array_column($katas, $searchKey));
    }
}
