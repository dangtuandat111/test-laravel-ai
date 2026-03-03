<?php

namespace App\Http\Controllers;

use App\AI\FlightAgent;
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

        try {
            $response = (new FlightAgent())->prompt($prompt);
        } catch (\Exception $e) {
            logger()->debug($e->getMessage());
        }
        

        dd($response);

        return response()->json([
            'message' => $response->content(), // "Dưới đây là các chuyến bay đến SGN ngày..."
            'data' => $response->toolResults(), // Danh sách chuyến bay từ DB
        ]);
    }
}
