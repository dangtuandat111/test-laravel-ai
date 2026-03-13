<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use App\Ai\FlightAgent;
use App\Ai\Tools\FlightTool;
use App\Models\Flights;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload()
    {
        return view('documents.documents');
    }
    
    public function chat()
    {
        
//        dd(Storage::disk('public')->exists('uploads/client.txt'));
        return view('documents.chat');
    }
    
    public function store(Request $request)
    {
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');

            $originalName = $file->getClientOriginalName();

            // Lưu file vào thư mục 'uploads' trong storage
            $path = $file->storeAs('uploads', $originalName, 'public');

            return response()->json([
                'message' => 'File đã được lưu tại: ' . $path,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['message' => 'Không tìm thấy file'], 400);
    }
    
    public function ask(Request $request) {
        $prompt = $request->input('query');
        $conversationId = $request->input('conversationId');

        try {
            if ($conversationId) {
                $response = (new SalesCoach())->continue($conversationId, as: User::find(1))->prompt($prompt);
            } else {
                $response = (new SalesCoach())->forUser(User::find(1))->prompt($prompt);
            }
        } catch (\Exception $e) {
            logger()->debug($e->getMessage());
        }
        logger()->debug($response);

        return response()->json([
            'message' => $response->text,
            'conversationId' => $response->conversationId,
        ]);
    }
}
