<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalChainTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_approval_chain()
    {
        $project = Project::factory()->create();
        $users = User::factory(3)->create();

        $response = $this->postJson("/api/projects/{$project->id}/approval-chain", [
            'users' => $users->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('approval_chains', [
            'project_id' => $project->id,
        ]);

        foreach ($users as $user) {
            $this->assertDatabaseHas('approval_chain_users', [
                'user_id' => $user->id,
            ]);
        }
    }

    /** @test */
    public function user_can_approve_and_forward_project()
    {
        $project = Project::factory()->create();
        $users = User::factory(3)->create();

        // Create approval chain
        $this->postJson("/api/projects/{$project->id}/approval-chain", [
            'users' => $users->pluck('id')->toArray(),
        ]);

        $currentUser = $users[0];

        $response = $this->actingAs($currentUser)->postJson("/api/projects/{$project->id}/approve", [
            'comment' => 'Approved by first user.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('approval_chain_users', [
            'user_id' => $currentUser->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function project_is_completed_after_last_user_approves()
    {
        $project = Project::factory()->create(['status' => 'in_progress']);
        $users = User::factory(3)->create();

        // Create approval chain
        $this->postJson("/api/projects/{$project->id}/approval-chain", [
            'users' => $users->pluck('id')->toArray(),
        ]);

        // Approve by all users sequentially
        foreach ($users as $user) {
            $response = $this->actingAs($user)->postJson("/api/projects/{$project->id}/approve", [
                'comment' => "Approved by user {$user->id}.",
            ]);

            $response->assertStatus(200);
        }

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'completed',
        ]);
    }
}