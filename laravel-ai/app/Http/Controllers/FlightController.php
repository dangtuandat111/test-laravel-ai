<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use App\Ai\FlightAgent;
use App\Ai\Tools\FlightTool;
use App\Models\Flights;
use App\Models\User;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        return view('flights.index');
    }
    //
    public function search(Request $request)
    {   
        $prompt = $request->input('query');
        $conversationId = $request->input('conversationId');

        try {
            if ($conversationId) {
                $response = (new FlightAgent())->continue($conversationId, as: User::find(1))->prompt($prompt);
            } else {
                $response = (new FlightAgent())->forUser(User::find(1))->prompt($prompt);
            }
        } catch (\Exception $e) {
            logger()->debug($e->getMessage());
        }
//        dd($response);
        logger()->debug($response);
        
        $data = [];
        if ($response->toolResults->isNotEmpty()) {
            logger()->debug('Tool results: ' . $response->toolResults->toJson());
            logger()->debug('Tool results: '. $response->toolResults[0]->result);
            $data = json_decode($response->toolResults[0]->result);
        }
        
        return response()->json([
            'message' => $response->text,
            'conversationId' => $response->conversationId,
            'data' => $data
        ]);
    }
}
