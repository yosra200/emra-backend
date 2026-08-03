<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_be_found_through_global_search(): void
    {
        $user = User::factory()->create([
            'first_name' => 'سارة',
            'last_name' => 'أحمد',
            'email' => 'sara@example.com',
            'phone' => '01012345678',
        ]);

        foreach (['سارة', 'أحمد', 'sara@example.com', '01012345678'] as $term) {
            $results = UserResource::getGlobalSearchResults($term);

            $this->assertCount(1, $results, "Searching for [{$term}] should find the user.");
            $this->assertSame('سارة أحمد', $results->first()->title);
        }
    }

    public function test_global_search_returns_nothing_for_an_unrelated_term(): void
    {
        User::factory()->create(['first_name' => 'منى', 'last_name' => 'علي']);

        $this->assertCount(0, UserResource::getGlobalSearchResults('Commodi'));
    }
}
