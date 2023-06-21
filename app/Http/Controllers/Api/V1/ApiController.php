<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\V1\NotebookResource;
use App\Models\Notes;

class ApiController extends Controller
{
    /**
     * Обрабатывает тестовый маршрут
     * @param Request $request
     * @return int
     */
    public function index(Request $request): int
    {
        return 1;
    }

    public function get_notes(Request $request)
    {
        $notes = Notes::select('*')
            ->offset($request->input('offset', '0'))
            ->limit($request->input('limit', '10'))
            ->get();

        return NotebookResource::collection($notes);
    }
}
