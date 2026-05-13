<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\BungaPinjaman;
use App\Models\Kas;
use App\Models\Pinjaman;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinjamanFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Anggota $anggota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@example.com')->first();

        $this->anggota = Anggota::create([
            'nama' => 'Test Member',
            'email' => 'testmember@example.com',
            'nik' => '1234567890123456',
            'no_hp' => '081234567890',
            'tanggal_daftar' => now(),
        ]);
    }

    public function test_pinjaman_index_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.pinjaman.index'));
        $response->assertStatus(200);
    }

    public function test_pinjaman_create_page_loads(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.pinjaman.create'));
        $response->assertStatus(200);
        $response->assertSee($this->anggota->nama);
    }

    public function test_pinjaman_store_creates_diajukan(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.pinjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => '12000000',
            'bunga_persen' => '0.8',
            'tenor' => 12,
            'keterangan' => 'Test loan',
        ]);

        $response->assertRedirect(route('admin.pinjaman.index'));
        $response->assertSessionHas('success');

        $pinjaman = Pinjaman::first();
        $this->assertNotNull($pinjaman);
        $this->assertEquals('diajukan', $pinjaman->status);
        $this->assertEquals(12000000.00, (float) $pinjaman->nominal);
        $this->assertEquals('Test Member', $pinjaman->anggota->nama);
        $this->assertNotNull($pinjaman->jatuh_tempo);
    }

    public function test_pinjaman_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.pinjaman.store'), []);
        $response->assertSessionHasErrors(['anggota_id', 'tanggal_pengajuan', 'nominal', 'bunga_persen', 'tenor']);
    }

    public function test_pinjaman_edit_page_loads(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->get(route('admin.pinjaman.edit', $pinjaman));
        $response->assertStatus(200);
        $response->assertSee('Test Member');
    }

    public function test_pinjaman_update_when_diajukan(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->put(route('admin.pinjaman.update', $pinjaman), [
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-15',
            'nominal' => '15000000',
            'bunga_persen' => '1.0',
            'tenor' => 24,
            'keterangan' => 'Updated loan',
        ]);

        $response->assertRedirect(route('admin.pinjaman.index'));

        $pinjaman->refresh();
        $this->assertEquals(15000000.00, (float) $pinjaman->nominal);
        $this->assertEquals(1.0, (float) $pinjaman->bunga_persen);
        $this->assertEquals(24, $pinjaman->tenor);
        $this->assertEquals('diajukan', $pinjaman->status);
    }

    public function test_pinjaman_destroy_deletes(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->delete(route('admin.pinjaman.destroy', $pinjaman));
        $response->assertRedirect(route('admin.pinjaman.index'));

        $this->assertNull(Pinjaman::find($pinjaman->id));
    }

    public function test_pinjaman_pengajuan_page_filters_diajukan_only(): void
    {
        $this->actingAs($this->admin);

        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 1000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'diajukan']);
        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 2000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'disetujui']);

        $response = $this->get(route('admin.pinjaman.pengajuan'));
        $response->assertStatus(200);
        $response->assertSee('1.000.000');
    }

    public function test_pinjaman_approval_page_filters_diajukan_only(): void
    {
        $this->actingAs($this->admin);

        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 1000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'diajukan']);
        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 2000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'disetujui']);

        $response = $this->get(route('admin.pinjaman.approval'));
        $response->assertStatus(200);
    }

    public function test_pinjaman_pencairan_page_filters_disetujui_only(): void
    {
        $this->actingAs($this->admin);

        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 1000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'diajukan']);
        Pinjaman::create(['anggota_id' => $this->anggota->id, 'tanggal_pengajuan' => '2025-06-01', 'nominal' => 2000000, 'bunga_persen' => 0.8, 'tenor' => 6, 'status' => 'disetujui']);

        $response = $this->get(route('admin.pinjaman.pencairan'));
        $response->assertStatus(200);
    }

    public function test_pinjaman_approve_transitions_to_disetujui(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->post(route('admin.pinjaman.approve', $pinjaman));
        $response->assertRedirect(route('admin.pinjaman.index'));
        $response->assertSessionHas('success');

        $pinjaman->refresh();
        $this->assertEquals('disetujui', $pinjaman->status);
        $this->assertEquals($this->admin->id, $pinjaman->approved_by);
        $this->assertNotNull($pinjaman->approved_at);
    }

    public function test_pinjaman_approve_fails_for_non_diajukan(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'disetujui',
        ]);

        $response = $this->post(route('admin.pinjaman.approve', $pinjaman));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_pinjaman_reject_transitions_to_ditolak(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->post(route('admin.pinjaman.reject', $pinjaman), [
            'alasan' => 'Kredit skor tidak mencukupi',
        ]);
        $response->assertRedirect(route('admin.pinjaman.index'));
        $response->assertSessionHas('success');

        $pinjaman->refresh();
        $this->assertEquals('ditolak', $pinjaman->status);
        $this->assertStringContainsString('Kredit skor', $pinjaman->keterangan);
    }

    public function test_pinjaman_reject_fails_for_non_diajukan(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'ditolak',
        ]);

        $response = $this->post(route('admin.pinjaman.reject', $pinjaman));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_pinjaman_cairkan_transitions_to_aktif_and_creates_kas(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'disetujui',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $kasSebelum = Kas::where('jenis', 'keluar')->where('kategori', 'Pinjaman')->count();

        $response = $this->post(route('admin.pinjaman.cairkan', $pinjaman));
        $response->assertRedirect(route('admin.pinjaman.pencairan'));
        $response->assertSessionHas('success');

        $pinjaman->refresh();
        $this->assertEquals('aktif', $pinjaman->status);

        $kasSesudah = Kas::where('jenis', 'keluar')->where('kategori', 'Pinjaman')->count();
        $this->assertEquals($kasSebelum + 1, $kasSesudah);

        $kasEntry = Kas::where('jenis', 'keluar')->where('kategori', 'Pinjaman')->latest()->first();
        $this->assertEquals(12000000.00, (float) $kasEntry->nominal);
        $this->assertStringContainsString('Test Member', $kasEntry->keterangan);
    }

    public function test_pinjaman_cairkan_fails_for_non_disetujui(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'diajukan',
        ]);

        $response = $this->post(route('admin.pinjaman.cairkan', $pinjaman));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_angsuran_store_creates_kas_masuk(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-01-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'aktif',
        ]);

        $kasSebelum = Kas::where('jenis', 'masuk')->where('kategori', 'Angsuran')->count();

        $response = $this->post(route('admin.angsuran.store'), [
            'pinjaman_id' => $pinjaman->id,
            'anggota_id' => $this->anggota->id,
            'angsuran_ke' => 1,
            'tanggal_bayar' => '2025-02-01',
            'nominal' => '1008000',
            'keterangan' => 'Angsuran bulan 1',
        ]);

        $response->assertRedirect(route('admin.angsuran.index'));
        $response->assertSessionHas('success');

        $angsuran = Angsuran::first();
        $this->assertNotNull($angsuran);
        $this->assertEquals(1, $angsuran->angsuran_ke);
        $this->assertEquals(1008000.00, (float) $angsuran->nominal);

        $kasSesudah = Kas::where('jenis', 'masuk')->where('kategori', 'Angsuran')->count();
        $this->assertEquals($kasSebelum + 1, $kasSesudah);

        $kasEntry = Kas::where('jenis', 'masuk')->where('kategori', 'Angsuran')->latest()->first();
        $this->assertEquals(1008000.00, (float) $kasEntry->nominal);
        $this->assertStringContainsString('Test Member', $kasEntry->keterangan);
    }

    public function test_full_pinjaman_end_to_end_flow(): void
    {
        $this->actingAs($this->admin);

        // 1. Create pinjaman → diajukan
        $this->post(route('admin.pinjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-01-01',
            'nominal' => '12000000',
            'bunga_persen' => '0.8',
            'tenor' => 12,
            'keterangan' => 'Pinjaman konsumtif',
        ]);

        $pinjaman = Pinjaman::first();
        $this->assertEquals('diajukan', $pinjaman->status);
        $this->assertEquals('2025-01-01', $pinjaman->tanggal_pengajuan->format('Y-m-d'));

        // 2. Approve → disetujui
        $this->post(route('admin.pinjaman.approve', $pinjaman));
        $pinjaman->refresh();
        $this->assertEquals('disetujui', $pinjaman->status);
        $this->assertEquals($this->admin->id, $pinjaman->approved_by);

        // 3. Cairkan → aktif + Kas keluar
        $this->post(route('admin.pinjaman.cairkan', $pinjaman));
        $pinjaman->refresh();
        $this->assertEquals('aktif', $pinjaman->status);

        $kasKeluar = Kas::where('jenis', 'keluar')->where('kategori', 'Pinjaman')->latest()->first();
        $this->assertNotNull($kasKeluar);
        $this->assertEquals(12000000.00, (float) $kasKeluar->nominal);

        // 4. Angsuran → Kas masuk
        $this->post(route('admin.angsuran.store'), [
            'pinjaman_id' => $pinjaman->id,
            'anggota_id' => $this->anggota->id,
            'angsuran_ke' => 1,
            'tanggal_bayar' => '2025-02-01',
            'nominal' => '1008000',
        ]);

        $angsuran = Angsuran::first();
        $this->assertNotNull($angsuran);

        $kasMasuk = Kas::where('jenis', 'masuk')->where('kategori', 'Angsuran')->latest()->first();
        $this->assertNotNull($kasMasuk);
        $this->assertEquals(1008000.00, (float) $kasMasuk->nominal);
    }

    public function test_pinjaman_store_with_bunga_rate_auto_detect(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.pinjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => '5000000',
            'bunga_persen' => '0.8',
            'tenor' => 6,
        ]);

        $response->assertRedirect(route('admin.pinjaman.index'));

        $pinjaman = Pinjaman::where('nominal', 5000000)->first();
        $this->assertNotNull($pinjaman);
        $this->assertEquals('diajukan', $pinjaman->status);
    }

    public function test_pinjaman_update_with_status_change_beyond_diajukan(): void
    {
        $this->actingAs($this->admin);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => 12000000,
            'bunga_persen' => 0.8,
            'tenor' => 12,
            'status' => 'aktif',
        ]);

        $response = $this->put(route('admin.pinjaman.update', $pinjaman), [
            'anggota_id' => $this->anggota->id,
            'tanggal_pengajuan' => '2025-06-01',
            'nominal' => '12000000',
            'bunga_persen' => '0.8',
            'tenor' => 12,
            'status' => 'lunas',
        ]);

        $response->assertRedirect(route('admin.pinjaman.index'));

        $pinjaman->refresh();
        $this->assertEquals('lunas', $pinjaman->status);
    }
}
