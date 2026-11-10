<?= $this->include('layouts/header', ['title' => 'Panduan Staff']) ?>

<section class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow-sm sm:p-8">
    <p class="text-sm font-semibold uppercase tracking-wide text-cyan-800">Panduan penggunaan</p>
    <h1 class="mt-2 text-2xl font-bold">Selamat datang, <?= esc(session()->get('staff_username')) ?></h1>
    <p class="mt-2 text-slate-600">
        <?= $isFirstLogin ? 'Ini adalah panduan singkat sebelum Anda mulai menggunakan dashboard.' : 'Berikut panduan singkat pengelolaan distribusi barang.' ?>
    </p>

    <ol class="mt-6 space-y-5">
        <li class="rounded-lg bg-slate-50 p-4">
            <h2 class="font-bold">1. Buat pengiriman</h2>
            <p class="mt-1 text-slate-600">Buka dashboard lalu pilih <strong>Pengiriman baru</strong>. Isi nama barang, kota asal, dan kota tujuan. Nomor resi dibuat otomatis dan lokasi pertama dicatat di kota asal.</p>
        </li>
        <li class="rounded-lg bg-slate-50 p-4">
            <h2 class="font-bold">2. Perbarui checkpoint</h2>
            <p class="mt-1 text-slate-600">Di kartu pengiriman, isi lokasi checkpoint dan catatan singkat. Setiap checkpoint tersimpan di histori yang dapat dilihat pelanggan melalui nomor resi.</p>
        </li>
        <li class="rounded-lg bg-slate-50 p-4">
            <h2 class="font-bold">3. Selesaikan pengiriman</h2>
            <p class="mt-1 text-slate-600">Setelah barang diterima dan disetujui di kota tujuan, pilih <strong>ACC &amp; selesai</strong>. Status berubah menjadi sampai di tujuan dan lokasi akhir dicatat.</p>
        </li>
        <li class="rounded-lg bg-slate-50 p-4">
            <h2 class="font-bold">4. Cari pengiriman</h2>
            <p class="mt-1 text-slate-600">Gunakan kata kunci untuk mencari berdasarkan resi, barang, kota asal/tujuan, lokasi, status, ID, atau tanggal. Filter status dan rentang tanggal dapat dikombinasikan.</p>
        </li>
        <li class="rounded-lg bg-slate-50 p-4">
            <h2 class="font-bold">5. Arsipkan atau hapus pengiriman</h2>
            <p class="mt-1 text-slate-600">Pilih <strong>Arsipkan</strong> untuk menyembunyikan pengiriman tanpa menghapus datanya. Buka filter <strong>Arsip</strong> untuk memulihkan atau menghapusnya secara permanen, termasuk seluruh histori checkpoint.</p>
        </li>
    </ol>

    <form class="mt-7" method="post" action="<?= site_url('staff/guide/complete') ?>">
        <?= csrf_field() ?>
        <button class="rounded-lg bg-cyan-700 px-5 py-3 font-semibold text-white hover:bg-cyan-800" type="submit">
            <?= $isFirstLogin ? 'Saya mengerti, buka dashboard' : 'Kembali ke dashboard' ?>
        </button>
    </form>
</section>

<?= $this->include('layouts/footer') ?>
