<?php

namespace App\Controller;

use Stichoza\GoogleTranslate\TranslateClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Gadel Raymanov <raymanovg@gmail.com>
 */
class Translate extends AbstractController
{
    public function translate(Request $request)
    {
        $text      = $request->query->get('text', '');
        $needPopup = (bool) $request->query->get('need_popup', false);
        $showPopup = (bool) $request->query->get('show_popup', false);
        $source = $request->query->get('source', 'en');
        $target = $request->query->get('source', 'ru');

        $response = [
            'success' => true,
        ];

        if (empty($text)) {
            $response['success'] = false;
            $response['message'] = 'There is no text to translate';
        } else {
            try {
                $client = new TranslateClient();
                $client->setSource($source);
                $client->setTarget($target);
                $translate = $client->translate($text);
                if ($needPopup || $showPopup) {
                    $response['popup'] = $this->renderView('translate/popup.html.twig', [
                        'text' => $text,
                        'translate' => $translate
                    ]);
                }

                $response['text'] = $text;
                $response['translate'] = $translate;
            } catch (\Exception $e) {
                $response['success'] = false;
                $response['message'] = 'Exception: ' . $e->getMessage();
            }
        }

        if ($showPopup && isset($response['popup'])) {
            return new Response($response['popup']);
        }

        return new JsonResponse($response);
    }

    public function getIcon()
    {
        $response = [
            'status' => true,
            'icon' => [
                'tag' => 'div',
                'attributes' => [
                    'id' => 'rayman_translate_icon',
                    'style' => [
                        'width' => '19px',
                        'height' => '19px',
                        'border' => '1px solid black',
                        'position' => 'absolute !important',
                        'border-radius' => '5px'
                    ]
                ],
                'content' => $this->renderView('translate/icon.html.twig')
            ]
        ];

        return new JsonResponse($response);
    }
}