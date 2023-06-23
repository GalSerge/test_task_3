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
     *
     * @param Request $request
     * @return int
     */
    public function index(Request $request): int
    {
        return 1;
    }

    /**
     * Возвращает список заметок
     *
     * @param Request $request ожидаются параметры offset и limit
     * @return mixed
     */
    public function getNotes(Request $request)
    {
        $notes = Notes::select('*')
            ->orderBy('created_at', 'desc')
            ->offset($request->input('offset', '0'))
            ->limit($request->input('limit', '10'))
            ->get();

        return NotebookResource::collection($notes);
    }

    /**
     * Добавляет новую заметку
     *
     * @param Request $request данные формы
     * @return mixed
     */
    public function addNote(Request $request)
    {
        $status = $this->validateRequest($request);

        if ($status)
        {
            $data = $request->toArray();
            if (isset($data['photo']))
                $data['photo'] = $request->file('photo')->store('photos');

            Notes::create($data);
            return response(['msg' => 'Заметка добавлена'], 200);
        } else
            return response(['msg' => 'Неверный формат данных'], 400);
    }

    /**
     * Возвращает одну заметку
     *
     * @param int $id идентификатор заметки
     * @return NotebookResource
     */
    public function getOneNote(int $id)
    {
        $note = Notes::where('id', $id)->first();

        if ($note !== null)
            return new NotebookResource($note);
        else
            return response(['msg' => 'Заметка отсутствует'], 404);
    }

    /**
     * Редактирует заметку
     *
     * @param Request $request данные формы
     * @param int $id идентификатор заметки
     * @return mixed
     */
    public function editNote(Request $request, int $id)
    {
        $status = $this->validateRequest($request);

        if ($status)
        {
            $data = $request->toArray();
            if (isset($data['photo']))
                $data['photo'] = $request->file('photo')->store('photos');

            Notes::where('id', $id)
                ->update($data);
            return response(['msg' => 'Заметка изменена'], 200);
        } else
            return response(['msg' => 'Неверный формат данных'], 400);
    }

    /**
     * Удаляет заметку
     *
     * @param int $id идентификатор заметки
     * @return mixed
     */
    public function deleteNote(int $id)
    {
        $note = Notes::find($id);

        if ($note !== null)
        {
            $note->delete();
            return response(['msg' => 'Заметка удалена'], 200);
        }
        else
            return response(['msg' => 'Заметка отсутствует'], 404);
    }

    /**
     * Проверяет корректность полученных из формы данных
     *
     * @param Request $request данные формы
     * @return bool
     */
    public function validateRequest(Request $request)
    {
        try
        {
            $request->validate([
                'fio' => 'required|max:255',
                'company' => 'max:255',
                'email' => 'required|max:255',
                'phone' => 'required|max:255',
                'date_birth' => 'nullable|date',
                'photo' => 'image|mimes:jpeg,png,jpg'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e)
        {
            return false;
        }

        return true;
    }
}
