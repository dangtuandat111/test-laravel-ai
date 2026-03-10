<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use App\Ai\FlightAgent;
use App\Ai\Tools\FlightTool;
use App\Models\Flights;
use App\Models\User;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index()
    {
        return view('chat');
    }
    //
    public function store(Request $request)
    {
        $res = (new SalesCoach)->forUser(\App\Models\User::find(1))->stream(request('message'));
        logger()->debug($res->conversationId);
      
        return $res;
    }
}
