<?php

namespace RightSide\Controller;

use Exception;
use Google\Service\Analytics;
use Google\Client as Google_Client;
use Google\Service\TagManager\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnalyticsController extends AbstractController
{
    private function initializeAnalytics(): Analytics
    {
        // Replace with my actual Google API key
        $GOOGLE_KEY_API = 'JJx3bfBFRGiKjUCFEsJZqQ';

        $client = new Google_Client();
        $client->setApplicationName('cv directeur reporting');
        $client->setDeveloperKey($GOOGLE_KEY_API);
        $client->setScopes([
            'https://www.googleapis.com/auth/analytics.readonly',
            'https://www.googleapis.com/auth/analytics.edit',
        ]);
        $analytics = new Analytics($client);
        return $analytics;
    }

    private function getFirstProfileId(Analytics $analytics): string
    {
        $accounts = $analytics->management_accounts->listManagementAccounts();
        if (count($accounts->getItems()) > 0) {
            $firstAccountId = $accounts->getItems()[0]->getId();
            $webProperties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);
            if (count($webProperties->getItems()) > 0) {
                $firstWebPropertyId = $webProperties->getItems()[0]->getId();
                $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstWebPropertyId);
                if (count($profiles->getItems()) > 0) {
                    return $profiles->getItems()[0]->getId();
                }
            }
        }
        throw new Exception('No profile found');
    }

    private function getReport(): Response 
    {
        $analytics = $this->initializeAnalytics();
        $profileId = $this->getFirstProfileId($analytics); // Replace with your actual profile ID

        $optParams = [
            'dimensions' => 'ga:date,ga:pagePath',
            'metrics' => 'ga:pageviews,ga:uniquePageviews',
            'start-date' => '30daysAgo',
            'end-date' => 'today',
        ];

        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            $optParams['start-date'],
            $optParams['end-date'],
            $optParams['metrics'],
            ['dimensions' => $optParams['dimensions']]
        );

        return new Response(json_encode($results));
    }
    
    #[IsGranted('ROLE_USER')]
    public function __invoke(): Response 
    {
        try {
            $report = $this->getReport();
            return $this->render('analytics/index.html', [
                'report' => json_decode($report->getContent(), true)
            ]);
        } catch (\Exception $e) {
            return new Response('Error fetching analytics data: ' . $e->getMessage(), 500);
        }
    }
}