<?php

namespace Tests\Feature;

use App\Models\Kas;
use App\Models\Periode;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NeracaKeuanganUtilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->admin->givePermissionTo('utility-backup', 'utility-import', 'utility-export', 'utility-log');
    }

    public function test_neraca_balance_with_seed_data(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.neraca.index'));
        $response->assertStatus(200);

        $periodeId = Periode::getActiveId();
        $kasBrankas = Kas::where('periode_id', $periodeId)->where('kategori', 'Kas Brankas')->sum('nominal');
        $bri = Kas::where('periode_id', $periodeId)->where('kategori', 'Bank BRI')->sum('nominal');
        $lpd = Kas::where('periode_id', $periodeId)->where('kategori', 'Rekening LPD')->sum('nominal');
        $totalKas = $kasBrankas + $bri + $lpd;

        $modalItems = ['Dana Sosial', 'Cadangan Modal', 'Cadangan Resiko', 'Dana Rapat', 'SHU Periode Lalu', 'Penyertaan'];
        $totalModal = Kas::where('periode_id', $periodeId)->whereIn('kategori', $modalItems)->sum('nominal');

        $pinjamanItems = ['Pinjam Dana Pura', 'Pinjam SUKDUK'];
        $totalPinjaman = Kas::where('periode_id', $periodeId)->whereIn('kategori', $pinjamanItems)->sum('nominal');

        $totalPassiva = $totalModal + $totalPinjaman;

        $this->assertEquals(310_000_000, $totalKas);
        $this->assertEquals(310_000_000, $totalPassiva, 'Aktiva should equal Passiva with seed data');
        $response->assertSee('Balance');
    }

    public function test_keuangan_filtered_views_return_ok(): void
    {
        $this->actingAs($this->admin);

        $routes = [
            'admin.keuangan.kas-brankas',
            'admin.keuangan.rekening-bri',
            'admin.keuangan.rekening-lpd',
            'admin.keuangan.pendapatan',
            'admin.keuangan.pengeluaran',
            'admin.keuangan.dana-sosial',
            'admin.keuangan.dana-pengurus',
            'admin.keuangan.cadangan-modal',
            'admin.keuangan.cadangan-resiko',
            'admin.keuangan.dana-rapat',
            'admin.keuangan.penyertaan',
        ];

        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_keuangan_kas_brankas_shows_correct_balance(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.keuangan.kas-brankas'));
        $response->assertStatus(200);
        $response->assertSee('50.000.000');
    }

    public function test_keuangan_rekening_bri_shows_correct_balance(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.keuangan.rekening-bri'));
        $response->assertStatus(200);
        $response->assertSee('200.000.000');
    }

    public function test_utility_backup_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.utility.backup'));
        $response->assertStatus(200);
    }

    public function test_utility_do_backup_creates_file(): void
    {
        $this->actingAs($this->admin);
        $response = $this->post(route('admin.utility.backup.do'));
        $response->assertSessionHas('success');
        $response->assertRedirect();
    }

    public function test_utility_export_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.utility.export'));
        $response->assertStatus(200);
    }

    public function test_utility_import_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.utility.import'));
        $response->assertStatus(200);
    }

    public function test_utility_activity_log_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.utility.activity-log'));
        $response->assertStatus(200);
    }

    public function test_utility_export_validates_table(): void
    {
        $this->actingAs($this->admin);
        $response = $this->post(route('admin.utility.export.do'), ['table' => 'invalid']);
        $response->assertSessionHasErrors('table');
    }

    public function test_pinjaman_create_page_has_bunga_rates_sorted_by_date(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.pinjaman.create'));
        $response->assertStatus(200);
        $response->assertSee('Baru');
        $response->assertSee('0.8');
    }

    public function test_get_rate_by_date_returns_only_active_rates(): void
    {
        $rate = \App\Models\BungaPinjaman::getRateByDate('2023-06-01');
        $this->assertNull($rate, 'Inactive rate (Freeze) should not be returned');

        $rate = \App\Models\BungaPinjaman::getRateByDate('2025-06-01');
        $this->assertNotNull($rate);
        $this->assertEquals('Baru', $rate->nama);
        $this->assertEquals(0.8, $rate->bunga);
    }

    public function test_current_active_returns_newest_rate(): void
    {
        $rate = \App\Models\BungaPinjaman::currentActive();
        $this->assertNotNull($rate);
        $this->assertEquals('Baru', $rate->nama);
    }
}
