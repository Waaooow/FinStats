<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Pocket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PocketTest extends TestCase
{
    use RefreshDatabase;

    public function test_pocket_index_page_is_displayed(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($user)
            ->get('/pockets');

        $response->assertOk();
    }

    public function test_pocket_create_page_is_displayed(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($user)
            ->get('/pockets/create');

        $response->assertOk();
    }

    public function test_user_can_create_pocket_with_name(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets', [
                'name' => 'Tabungan Liburan',
                'target' => 5000000,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/1');

        $this->assertDatabaseHas('pockets', [
            'user_id' => $user->id,
            'name' => 'Tabungan Liburan',
            'target' => 5000000,
            'balance' => 0,
        ]);
    }

    public function test_user_can_create_pocket_without_target(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets', [
                'name' => 'Saku Harian',
                'target' => '',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pockets', [
            'user_id' => $user->id,
            'name' => 'Saku Harian',
            'target' => null,
            'balance' => 0,
        ]);
    }

    public function test_pocket_name_is_required(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets', [
                'name' => '',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_view_pocket_detail(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->get('/pockets/' . $pocket->id);

        $response->assertOk()
            ->assertSee($pocket->name);
    }

    public function test_user_cannot_view_other_user_pocket(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $owner->id]);
        $otherUser = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($otherUser)
            ->get('/pockets/' . $pocket->id);

        $response->assertForbidden();
    }

    public function test_user_can_update_pocket(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'name' => 'Lama']);

        $response = $this
            ->actingAs($user)
            ->patch('/pockets/' . $pocket->id, [
                'name' => 'Baru',
                'target' => 1000000,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/' . $pocket->id);

        $this->assertDatabaseHas('pockets', [
            'id' => $pocket->id,
            'name' => 'Baru',
            'target' => 1000000,
        ]);
    }

    public function test_user_can_remove_pocket_target(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'target' => 5000000]);

        $response = $this
            ->actingAs($user)
            ->patch('/pockets/' . $pocket->id, [
                'name' => $pocket->name,
                'target' => '',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pockets', [
            'id' => $pocket->id,
            'target' => null,
        ]);
    }

    public function test_user_cannot_delete_pocket_with_balance(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 100000]);

        $response = $this
            ->actingAs($user)
            ->delete('/pockets/' . $pocket->id);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('pockets', ['id' => $pocket->id]);
    }

    public function test_user_can_delete_empty_pocket_without_transactions(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 0]);

        $response = $this
            ->actingAs($user)
            ->delete('/pockets/' . $pocket->id);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets');

        $this->assertDatabaseMissing('pockets', ['id' => $pocket->id]);
    }

    public function test_user_can_see_total_pocket_balance(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000]);
        Pocket::factory()->create(['user_id' => $user->id, 'balance' => 2000000]);

        $response = $this
            ->actingAs($user)
            ->get('/pockets');

        $response->assertOk()
            ->assertSee('Rp 3.000.000');
    }

    public function test_unauthenticated_user_cannot_access_pocket(): void
    {
        $response = $this->get('/pockets');
        $response->assertRedirect('/login');
    }
}
