<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

class GraphHelper
{
    public static function getAccessToken()
    {
        $clientId = env('GRAPH_CLIENT_ID');
        $clientSecret = env('GRAPH_CLIENT_SECRET');
        $tenantId = env('GRAPH_TENANT_ID');
        $scope = 'https://graph.microsoft.com/.default';
        $tokenUrl = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        $response = Http::asForm()->post($tokenUrl, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => $scope,
            'grant_type' => 'client_credentials',
        ]);

        $data = $response->json();

        return $data['access_token'];
    }
}
