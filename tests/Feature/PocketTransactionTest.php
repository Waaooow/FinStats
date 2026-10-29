<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Pocket;
use App\Models\PocketTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PocketTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_top_up_pocket(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 0]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/' . $pocket->id . '/top-up', [
                'amount' => 500000,
                'description' => 'Gaji bulanan',
                'date' => today()->format('Y-m-d'),
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/' . $pocket->id);

        $pocket->refresh();
        $this->assertSame('500000.00', $pocket->balance);

        $this->assertDatabaseHas('pocket_transactions', [
            'pocket_id' => $pocket->id,
            'type' => 'top_up',
            'amount' => 500000,
        ]);

        // Requirement 4: top_up juga dicatat di transaksi harian
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'outcome',
            'amount' => 500000,
            'description' => 'Top up ke pocket: ' . $pocket->name,
        ]);
    }

    public function test_top_up_amount_must_be_at_least_100(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/' . $pocket->id . '/top-up', [
                'amount' => 50,
                'date' => today()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_top_up_date_cannot_be_future(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/' . $pocket->id . '/top-up', [
                'amount' => 500000,
                'date' => today()->addDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('date');
    }

    public function test_user_cannot_top_up_other_user_pocket(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $owner->id]);
        $otherUser = User::factory()->create(['email_verified_at' => now()]);

        $response = $this
            ->actingAs($otherUser)
            ->post('/pockets/' . $pocket->id . '/top-up', [
                'amount' => 500000,
                'date' => today()->format('Y-m-d'),
            ]);

        $response->assertForbidden();
    }

    public function test_user_can_withdraw_from_pocket(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/' . $pocket->id . '/withdraw', [
                'amount' => 300000,
                'description' => 'Belanja bulanan',
                'date' => today()->format('Y-m-d'),
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/' . $pocket->id);

        $pocket->refresh();
        $this->assertSame('700000.00', $pocket->balance);

        $this->assertDatabaseHas('pocket_transactions', [
            'pocket_id' => $pocket->id,
            'type' => 'withdraw',
            'amount' => 300000,
        ]);

        // Requirement 4: withdraw juga dicatat di transaksi harian
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 300000,
            'description' => 'Penarikan dari pocket: ' . $pocket->name,
        ]);
    }

    public function test_user_cannot_withdraw_more_than_balance(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 100000]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/' . $pocket->id . '/withdraw', [
                'amount' => 500000,
                'date' => today()->format('Y-m-d'),
            ]);

        $response->assertSessionHas('error');
        $pocket->refresh();
        $this->assertSame('100000.00', $pocket->balance);
    }

    public function test_user_can_transfer_between_own_pockets(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $fromPocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000, 'name' => 'Pocket A']);
        $toPocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 500000, 'name' => 'Pocket B']);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/transfer', [
                'from_pocket_id' => $fromPocket->id,
                'amount' => 400000,
                'description' => 'Pindahkan ke Pocket B',
                'date' => today()->format('Y-m-d'),
                'to_type' => 'pocket',
                'to_pocket_id' => $toPocket->id,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/' . $fromPocket->id);

        $fromPocket->refresh();
        $toPocket->refresh();
        $this->assertSame('600000.00', $fromPocket->balance);
        $this->assertSame('900000.00', $toPocket->balance);

        // Requirement 3: mutasi wajib untuk setiap pocket terlibat
        $this->assertDatabaseHas('pocket_transactions', [
            'pocket_id' => $fromPocket->id,
            'type' => 'transfer_out',
            'amount' => 400000,
            'related_pocket_id' => $toPocket->id,
        ]);

        $this->assertDatabaseHas('pocket_transactions', [
            'pocket_id' => $toPocket->id,
            'type' => 'transfer_in',
            'amount' => 400000,
            'related_pocket_id' => $fromPocket->id,
        ]);
    }

    public function test_user_can_transfer_from_pocket_to_group(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000]);
        $group = Group::factory()->create(['name' => 'Keluarga', 'code' => 'KAS-123']);
        $group->users()->attach($user->id);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/transfer', [
                'from_pocket_id' => $pocket->id,
                'amount' => 300000,
                'description' => 'Iuran keluarga',
                'date' => today()->format('Y-m-d'),
                'to_type' => 'group',
                'to_group_id' => $group->id,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/pockets/' . $pocket->id);

        $pocket->refresh();
        $this->assertSame('700000.00', $pocket->balance);

        // Requirement 5: transfer ke tabungan bersama
        $this->assertDatabaseHas('pocket_transactions', [
            'pocket_id' => $pocket->id,
            'type' => 'transfer_out',
            'amount' => 300000,
            'related_group_id' => $group->id,
        ]);

        // Transaksi grup juga dicatat
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'type' => 'income',
            'amount' => 300000,
        ]);
    }

    public function test_user_cannot_transfer_to_group_not_joined(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000]);
        $group = Group::factory()->create(); // user not a member

        $response = $this
            ->actingAs($user)
            ->post('/pockets/transfer', [
                'from_pocket_id' => $pocket->id,
                'amount' => 300000,
                'date' => today()->format('Y-m-d'),
                'to_type' => 'group',
                'to_group_id' => $group->id,
            ]);

        $response->assertSessionHas('error');
        $pocket->refresh();
        $this->assertSame('1000000.00', $pocket->balance);
    }

    public function test_user_cannot_transfer_to_other_user_pocket(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create(['email_verified_at' => now()]);
        $fromPocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 1000000]);
        $toPocket = Pocket::factory()->create(['user_id' => $otherUser->id]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/transfer', [
                'from_pocket_id' => $fromPocket->id,
                'amount' => 300000,
                'date' => today()->format('Y-m-d'),
                'to_type' => 'pocket',
                'to_pocket_id' => $toPocket->id,
            ]);

        $response->assertSessionHas('error');
        $fromPocket->refresh();
        $this->assertSame('1000000.00', $fromPocket->balance);
    }

    public function test_user_cannot_transfer_more_than_balance(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $fromPocket = Pocket::factory()->create(['user_id' => $user->id, 'balance' => 100000]);
        $toPocket = Pocket::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->post('/pockets/transfer', [
                'from_pocket_id' => $fromPocket->id,
                'amount' => 500000,
                'date' => today()->format('Y-m-d'),
                'to_type' => 'pocket',
                'to_pocket_id' => $toPocket->id,
            ]);

        $response->assertSessionHas('error');
        $fromPocket->refresh();
        $this->assertSame('100000.00', $fromPocket->balance);
    }

    public function test_pocket_transaction_list_is_shown_in_detail(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $pocket = Pocket::factory()->create(['user_id' => $user->id]);
        PocketTransaction::factory()->create([
            'pocket_id' => $pocket->id,
            'type' => 'top_up',
            'amount' => 500000,
            'description' => 'Test top up',
            'transaction_date' => today(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/pockets/' . $pocket->id);

        $response->assertOk()
            ->assertSee('Test top up')
            ->assertSee('Rp 500.000');
    }

    public function test_unauthenticated_user_cannot_top_up(): void
    {
        $pocket = Pocket::factory()->create();

        $response = $this->post('/pockets/' . $pocket->id . '/top-up', [
            'amount' => 500000,
            'date' => today()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/login');
    }
}
