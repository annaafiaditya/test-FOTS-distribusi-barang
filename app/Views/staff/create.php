<?= $this->include('layouts/header', ['title' => 'Buat Pengiriman']) ?>

<div class="mx-auto max-w-2xl">
    <a class="text-sm font-medium text-cyan-800 hover:underline" href="<?= site_url('staff') ?>">&larr; Kembali ke dashboard</a>
    <section class="mt-4 rounded-xl bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold">Buat pengiriman baru</h1>
        <p class="mt-2 text-slate-600">Nomor resi dibuat otomatis. Lokasi awal dicatat sebagai kota asal.</p>

        <?php $errors = session()->getFlashdata('errors') ?? []; ?>
        <?php if ($errors !== []): ?>
            <div class="mt-5 rounded-lg bg-red-50 p-4 text-red-800" role="alert">
                <ul class="list-inside list-disc">
                    <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="mt-6 space-y-5" method="post" action="<?= site_url('staff/create') ?>">
            <?= csrf_field() ?>
            <div>
                <label class="mb-1 block font-medium" for="item_name">Nama barang</label>
                <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="item_name" name="item_name"
                       value="<?= esc(old('item_name', '', 'raw')) ?>" maxlength="150" required>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block font-medium" for="origin">Kota asal</label>
                    <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="origin" name="origin"
                           value="<?= esc(old('origin', '', 'raw')) ?>" maxlength="150" placeholder="Contoh: Bandung" required>
                </div>
                <div>
                    <label class="mb-1 block font-medium" for="destination">Kota tujuan</label>
                    <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="destination" name="destination"
                           value="<?= esc(old('destination', '', 'raw')) ?>" maxlength="150" placeholder="Contoh: Bogor" required>
                </div>
            </div>
            <button class="rounded-lg bg-cyan-700 px-5 py-3 font-semibold text-white hover:bg-cyan-800" type="submit">
                Buat pengiriman
            </button>
        </form>
    </section>
</div>

<?= $this->include('layouts/footer') ?>
