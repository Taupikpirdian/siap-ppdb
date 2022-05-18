<?php

namespace App\Http\Controllers\api;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
	public function index(Document $documents)
    {

        $documents = $documents::paginate(25);
        return $documents;
        $result = $this->response($processed_job['text'], 200);

    }

    public function getTop4(Document $documents)
    {
    	// ->orderBy('name', 'desc')
        $documents = $documents::orderBy('created_at', 'desc')->limit(4)->get();
        return $documents;
        $result = $this->response($processed_job['text'], 200);
    }

    public function show($id)
    {
    	$documents = Document::find($id);
        if ($documents) {
          return response()->json(['status' => 'success', 'data'=> $documents]);
        }
        return response()->json(['status' => 'error', 'message' => 'Data not found'],302);
    }
}
