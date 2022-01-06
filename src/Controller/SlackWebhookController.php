<?php
// src/Controller/SlackWebhookController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SlackApiClient;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\AdminRecipient;

/**
 * @Route("/webhooks/slack")
 */
class SlackWebhookController extends AbstractController
{

/**
 * @Route("/", name="webhook.slack.action")
 * @param Request $request
 * @return Response
 */
public function handleSlackEvent(Request $request)
{
    $data = json_decode($request->getContent(), true);
    $event = $data['event'];

    // Ignore bot messages
    if (array_key_exists('subtype', $event) && $event === 'bot_message') {
        return new JsonResponse();
    }

    // Ignore messages that don't belong to a thread
    if (!array_key_exists('thread_ts', $event)) {
        return new JsonResponse();
    }

    $messageId = $event['ts'];
    $threadId = $event['thread_ts'];

    // This is the thread parent message which we're also not interested in
    if ($messageId === $threadId) {
        return new JsonResponse();
    }

    $supportResponseMessage = $event['text'];

    $notifier->send(
        new Notification($supportResponseMessage, ['sms']), 
        new AdminRecipient('', $customerPhoneNumber)
    );

    $messages = $slackApiClient->getConversationReplies($threadId);
    $parent = $messages[0];
    $firstBlock = $parent['blocks'][0];
    $customerPhoneNumber = $firstBlock['text']['text'];

    return new JsonResponse();
}
}
