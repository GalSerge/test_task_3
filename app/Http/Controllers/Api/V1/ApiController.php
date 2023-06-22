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
            ->orderBy('created_at', 'desc')
            ->offset($request->input('offset', '0'))
            ->limit($request->input('limit', '10'))
            ->get();

        return NotebookResource::collection($notes);
    }

    public function add_note(Request $request)
    {
        $status = $this->validate_request($request);

        if ($status)
        {
            Notes::create($request->toArray());
            return response(['msg' => 'Заметка добавлена'], 200);
        } else
            return response(['msg' => 'Неверный формат данных'], 400);
    }

    public function validate_request(Request $request)
    {
        try
        {
            $request->validate([
                'fio' => 'required|max:255',
                'company' => 'max:255',
                'email' => 'required|max:255',
                'phone' => 'required|max:255',
                'date_birth' => 'nullable|date',
                'photo' => 'max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e)
        {
            return false;
        }

        return true;
    }
}
