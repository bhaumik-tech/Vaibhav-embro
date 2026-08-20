<?php

namespace Tests\Feature;

use App\Models\Firm;
use App\Models\ThreadBoxSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseBillPayeeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_a_new_payee_name_and_shows_it_on_follow_up_entry()
    {
        $firm = Firm::create([
            'name' => 'Test Firm',
            'gst_no' => '27AAAAA0000A1Z5',
            'address' => 'Mumbai',
            'phone' => '9999999999',
        ]);

        $this->post(route('purchase-bill.store'), [
            'bill_no' => 'INV-1001',
            'bill_date' => '2026-08-19',
            'firm_id' => $firm->id,
            'company_name' => 'Mohan Traders',
            'amount_without_gst' => 1000,
            'gst_percent' => 5,
            'gst_rs' => 50,
            'amount' => 1050,
            'remark' => 'Test remark',
        ]);

        $this->assertDatabaseHas('thread_box_setups', [
            'company_name' => 'Mohan Traders',
        ]);

        $response = $this->get(route('purchase-bill.create'));
        $response->assertStatus(200)
            ->assertSee('Mohan Traders');
    }
}
