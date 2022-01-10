<?php
// src/Controller/SlackWebhookController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhooks/slack")
 */
class SlackWebhookController extends AbstractController
{
    /**
     * @Route("/events", name="webhook.slack.action")
     * @param Request $request
     * @return Response
     */
    public function handleSlackEvent(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        return new JsonResponse(['challenge' => $data['challenge']]);
    }
}
