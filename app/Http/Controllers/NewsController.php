<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}

    public function index(Request $request)
    {
        $sortBy = $request->input('sort', 'publishedAt');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $articles = $this->newsService->getNews($sortBy, $from, $to);

        return view('news.index', compact('articles', 'sortBy', 'from', 'to'));
    }

    public function apiIndex(Request $request)
    {
        $sortBy = $request->input('sort', 'publishedAt');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $articles = $this->newsService->getNews($sortBy, $from, $to);

        return response()->json($articles);
    }
}