<?php
/**
 * This file defines the user controller
 *
 * @category App
 * @package Controller
 * @author Montassar Bouali <montassar.bouali@softeam.fr>
 * @copyright 2023-2024 Softteam
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\Ar24ApiService;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * Create a user
     *
     * @param Request $request
     * @param Ar24ApiService $ar24ApiService
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createUser(Request $request, Ar24ApiService $ar24ApiService)
    {
        $data = $request->request->all();
        // Appeler la méthode du service API pour créer un utilisateur
        $response = $ar24ApiService->createUser($data);

        if (isset($response['error'])) {
            return new JsonResponse(array('error' => $response['error']), 500);
        }

        // Traiter la réponse de l'API et renvoyer une réponse JSON
        return new JsonResponse($response);
    }

    /**
     * Get a info user
     *
     * @param Request $request
     * @param Ar24ApiService $ar24ApiService
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInfo(Request $request, Ar24ApiService $ar24ApiService): JsonResponse
    {
        $params = $request->query->all();
        $response = $ar24ApiService->getUserInfo($params);

        if (isset($response['error'])) {
            return new JsonResponse(array('error' => $response['error']), 500);
        }

        return new JsonResponse($response['data']);
    }

    /**
     * Upload a file as an Attachment
     *
     * @param Request $request
     * @param Ar24ApiService $ar24ApiService
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAttachment(Request $request, Ar24ApiService $ar24ApiService): JsonResponse
    {
        $data = $request->request->all();

        // Get files from request
        $file = $request->files->get('file');
        $response = $ar24ApiService->uploadAttachment($data, $file);

        if (isset($response['error'])) {
            return new JsonResponse(array('error' => $response['error']), 500);
        }

        return new JsonResponse($response);
    }

    /**
     * Send a mail to user
     *
     * @param Request $request
     * @param Ar24ApiService $ar24ApiService
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMail(Request $request, Ar24ApiService $ar24ApiService)
    {
        $data = $request->request->all();
        $response = $ar24ApiService->sendMail($data);

        if (isset($response['error'])) {
            return new JsonResponse(array('error' => $response['error']), 500);
        }

        return new JsonResponse($response);
    }

    /**
     * get a info mail
     *
     * @param Request $request
     * @param Ar24ApiService $ar24ApiService
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMailInfo(Request $request, Ar24ApiService $ar24ApiService)
    {
        $params = $request->query->all();
        $response = $ar24ApiService->getMailInfo($params);

        if (isset($response['error'])) {
            return new JsonResponse(array('error' => $response['error']), 500);
        }

        return new JsonResponse($response['data']);
    }
}
