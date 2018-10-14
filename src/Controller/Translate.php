<?php

namespace App\Controller;

use Stichoza\GoogleTranslate\TranslateClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gadel Raymanov <raymanovg@gmail.com>
 */
class Translate extends AbstractController
{
    public function translate(Request $request)
    {
        $text = $request->query->get('text', '');
        $needPopup = $request->query->get('need_popup', false);

        $response = [
            'success' => true,
        ];

        if (empty($text)) {
            $response['success'] = false;
            $response['message'] = 'There is no text to translate';
        } else {
            try {
                $client = new TranslateClient();
                $client->setSource('en');
                $client->setTarget('ru');
                $translate = $client->translate($text);
                $response['text'] = $text;
                $response['translate'] = $translate;
                if ($needPopup) {
                    $response['popup'] = $this->renderView('translate/popup.html.twig', [
                        'text' => $text,
                        'translate' => $translate
                    ]);
                }
            } catch (\Exception $e) {
                $response['success'] = false;
                $response['message'] = 'Exception: ' . $e->getMessage();
            }
        }

        return new JsonResponse($response);
    }
}