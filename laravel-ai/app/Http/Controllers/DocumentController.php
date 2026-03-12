<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Illuminate\Http\Request;
use Laravel\Ai\Embeddings;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $title = $request->input('title', 'Laravel Eloquent Model Conventions');
        $embeddings = Embeddings::for([$title])->generate()->embeddings;
//        dd($embeddings->embeddings);
        // Store the document and its embeddings in the database
        $document = Documents::create([
            'title' => $title,
            'content' => 'This is the content of the document.',
            'embedding' => $embeddings[0],
        ]);
        
        return $document;
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query', 'Learn PHP');
        $queryEmbedding = Embeddings::for([$query])->generate()->embeddings[0];

        $documents = Documents::query()
            ->whereVectorSimilarTo('embedding', $queryEmbedding)
            ->limit(10)
            ->get();
        
        // Retrieve all documents and calculate similarity
//        $documents = Documents::all()->map(function ($doc) use ($queryEmbedding) {
//            $similarity = $this->cosineSimilarity($queryEmbedding, $doc->embedding);
//            return [
//                'document' => $doc,
//                'similarity' => $similarity,
//            ];
//        })->sortByDesc('similarity');
        
        return $documents;
    }
}
