<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Notes;

class ApiTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_root()
    {
        $response = $this->get('api/v1/');

        $response->assertStatus(200);
        $response->assertSee('1');
    }

    /**
     * Тестириует получение списка заметок
     *
     * @return void
     */
    public function testGetAll()
    {
        // общее число
        $response = $this->get('api/v1/notebook');
        $response->assertJsonCount(Notes::count(), 'data');

        // первая заметка
        $response = $this->get('api/v1/notebook?limit=1');
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Тестирует получение одной заметки
     *
     * @return void
     */
    public function getOneNote()
    {
        $note = Notes::inRandomOrder()->first();

        $response = $this->get('api/v1/notebook/'.$note->id);

        $response->assertStatus(200);
        $response->assertJsonPath('data.note_id', $note->id);
    }

    /**
     * Теститует добавление новой заметки
     *
     * @return void
     */
    public function testAddNote()
    {
        $data = [
            'fio' => $this->faker->name(),
            'company' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_birth' => $this->faker->date()
        ];

        $response = $this->post('api/v1/notebook', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notes', ['email' => $data['email']]);
    }

    /**
     * Тестирует редактирование заметок
     *
     * @return void
     */
    public function testEditNote()
    {
        $note = Notes::inRandomOrder()->first();

        $data = [
            'fio' => $note->fio,
            'company' => $note->company,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $note->phone,
        ];

        $response = $this->post('api/v1/notebook/'.$note->id, $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notes', ['email' => $data['email']]);
        $this->assertDatabaseMissing('notes', ['email' => $note->email]);

    }

    /**
     * Тестирует удаление заметки
     * @return void
     */
    public function testDelNote()
    {
        $note = Notes::inRandomOrder()->first();

        $response = $this->delete('api/v1/notebook/'.$note->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notes', ['email' => $note->id]);
    }
}
