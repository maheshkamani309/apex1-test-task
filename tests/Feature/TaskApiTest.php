<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test_token')->plainTextToken;
    }

    public function test_can_create_task_with_valid_data(): void
    {
        $payload = [
            'title' => 'Write unit tests',
            'description' => 'Cover task API endpoints with tests.',
            'status' => 'pending',
            'due_date' => Carbon::today()->addDay()->toDateString(),
        ];

        $response = $this->postJson('/api/tasks', $payload, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully.',
                'task' => [
                    'title' => $payload['title'],
                    'status' => $payload['status'],
                    'due_date' => $payload['due_date'],
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'status' => $payload['status'],
            'due_date' => $payload['due_date'],
        ]);
    }

    public function test_validation_errors_are_returned_for_invalid_task_data(): void
    {
        $payload = [
            'title' => '',
            'status' => 'not-a-valid-status',
            'due_date' => Carbon::yesterday()->toDateString(),
        ];

        $response = $this->postJson('/api/tasks', $payload, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status', 'due_date']);
    }

    public function test_can_fetch_tasks_with_status_filter_and_sorting(): void
    {
        Task::factory()->create([
            'title' => 'Pending task',
            'status' => 'pending',
            'due_date' => Carbon::today()->addDays(5)->toDateString(),
        ]);

        Task::factory()->create([
            'title' => 'Completed task',
            'status' => 'completed',
            'due_date' => Carbon::today()->addDays(1)->toDateString(),
        ]);

        Task::factory()->create([
            'title' => 'Another pending task',
            'status' => 'pending',
            'due_date' => Carbon::today()->addDays(2)->toDateString(),
        ]);

        $response = $this->getJson('/api/tasks?status=pending&sort=asc', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('tasks.0.title', 'Another pending task')
            ->assertJsonPath('tasks.1.title', 'Pending task');
    }

    public function test_can_update_existing_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Initial title',
            'status' => 'pending',
            'due_date' => Carbon::today()->addDays(3)->toDateString(),
        ]);

        $payload = [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'status' => 'in_progress',
            'due_date' => Carbon::today()->addDays(4)->toDateString(),
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([ 'success' => true, 'task' => ['title' => $payload['title'], 'status' => $payload['status']] ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $payload['title'],
            'status' => $payload['status'],
            'due_date' => $payload['due_date'],
        ]);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([ 'success' => true, 'message' => 'Task deleted successfully.' ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
