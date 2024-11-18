<?php

namespace App\Http\Controllers;

use App\Helper\GraphHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeamsPlannerController extends Controller
{
    private $baseUrl = 'https://graph.microsoft.com/v1.0';


    public function listUserTeams()
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/teams";

        try {
            $response = Http::withToken($accessToken)->get($url);

            if ($response->successful()) {
                $teams = $response->json()['value'];
                return view('graph.user_teams', compact('teams'));
            } else {
                // Log detailed error information for failed response
                return response()->json([
                    'error' => 'Failed to fetch teams',
                    'status_code' => $response->status(),
                    'body' => $response->body(), // Captures raw body of the response
                    'headers' => $response->headers(), // Logs response headers
                ]);
            }
        } catch (\Exception $e) {
            // Log detailed exception information
            return response()->json([
                'error' => 'Failed to fetch teams: ' . $e->getMessage(),
                'exception' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        }
    }



    public function listPlannerPlans($teamId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/groups/{$teamId}/planner/plans";

        try {
            $response = Http::withToken($accessToken)->get($url);

            if ($response->successful()) {
                $plans = $response->json()['value'];
                return view('graph.planner_plans', compact('plans'));
            } else {
                return response()->json([
                    'error' => 'Failed to fetch plans: ' . $response->status()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch plans: ' . $e->getMessage()
            ]);
        }
    }
}
