<?php

namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsController extends AbstractController
{
    private function initializeAnalytics(): Response
    {
        $KEY_FILE_LOCATION = '';
        $GOOGLE_KEY_API = 'JJx3bfBFRGiKjUCFEsJZqQ';

        $client = new Google_Client();
        $client->setApplicationName('Analytics Reporting');
        $client->setAuthConfig($KEY_FILE_LOCATION);
        
    }

}