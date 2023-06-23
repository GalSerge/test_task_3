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
     *
     * @OA\Get(
     *      path="/api/v1/notebook",
     *      operationId="notebookGetAll",
     *      tags={"Main"},
     *      summary="Получить список заметок",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     *     )
     *
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
     *
     * @OA\Post(
     *      path="/api/v1/notebook",
     *      operationId="notebookAddNote",
     *      tags={"Main"},
     *      summary="Добавить заметку",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request")
     *     )
     *
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
     *
     * @OA\Get(
     *      path="/api/v1/notebook/{id}",
     *      operationId="notebookGetOneNote",
     *      tags={"Main"},
     *      summary="Получить заметку",
     *     @OA\Parameter(
     *          name="id",
     *          description="Идентификатор заметки",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
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
     *
     * @OA\Post(
     *      path="/api/v1/notebook/{id}",
     *      operationId="notebookEditNote",
     *      tags={"Main"},
     *      summary="Редактировать заметку",
     *     @OA\Parameter(
     *          name="id",
     *          description="Идентификатор заметки",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request")
     *     )
     *
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
     *
     * @OA\Delete(
     *      path="/api/v1/notebook/{id}",
     *      operationId="notebookDeleteNote",
     *      tags={"Main"},
     *      summary="Удалить заметку",
     *     @OA\Parameter(
     *          name="id",
     *          description="Идентификатор заметки",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
     *
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
        } else
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
