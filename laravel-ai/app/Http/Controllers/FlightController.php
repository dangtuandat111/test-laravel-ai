<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use App\AI\FlightAgent;
use App\AI\Tools\FlightTool;
use App\Models\Flights;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
//        $response = (new SalesCoach())
//            ->prompt('Analyze this sales transcript to JP: y gọi FlightTool với from là Hà Nội, to là HCM và date là ngày h');
//
//        return (string) $response;
        return view('flights.index');
    }
    //
    public function search(Request $request)
    {
        $prompt = $request->input('query');

        try {
            $response = (new FlightAgent())->prompt('Hãy gọi FlightTool với from là Hà Nội, to là HCM và date là ngày hôm nay và trả về kết quả danh sách chuyến bay');
        } catch (\Exception $e) {
            logger()->debug($e->getMessage());
        }
        
        dd($response);
        
        return response()->json([
            'message' => 'Tôi tìm thấy một vài chuyến bay phù hợp với yêu cầu của bạn:',
            'data' => $response,
        ]);
    }
}
