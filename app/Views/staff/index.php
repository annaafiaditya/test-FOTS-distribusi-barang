<?= $this->include('layouts/header', ['title' => 'Dashboard Staff']) ?>

<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold">Dashboard distribusi</h1>
        <p class="mt-1 text-slate-600">Kelola pengiriman dan checkpoint perjalanan barang.</p>
    </div>
    <a class="rounded-lg bg-cyan-700 px-5 py-3 font-semibold text-white hover:bg-cyan-800" href="<?= site_url('staff/create') ?>">
        + Pengiriman baru
    </a>
</div>

<section class="mt-6 grid gap-4 sm:grid-cols-3" aria-label="Statistik pengiriman aktif">
    <article class="rounded-xl border border-cyan-100 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Total pengiriman aktif</p>
        <p class="mt-2 text-3xl font-bold text-slate-900"><?= esc($shipmentStats['total']) ?></p>
    </article>
    <article class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Sedang dikirim</p>
        <p class="mt-2 text-3xl font-bold text-blue-700"><?= esc($shipmentStats['inTransit']) ?></p>
    </article>
    <article class="rounded-xl border border-green-100 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Sampai tujuan</p>
        <p class="mt-2 text-3xl font-bold text-green-700"><?= esc($shipmentStats['delivered']) ?></p>
    </article>
</section>

<section class="mt-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="text-lg font-bold">Cari dan filter pengiriman</h2>
    <p class="mt-1 text-sm text-slate-600">Cari resi, nama barang, kota asal/tujuan, lokasi, status, ID, atau tanggal dibuat/diubah.</p>
    <?php if ($searchError !== null): ?>
        <p class="mt-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-800" role="alert"><?= esc($searchError) ?></p>
    <?php endif; ?>
    <form class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-5" method="get" action="<?= site_url('staff') ?>">
        <label class="lg:col-span-2">
            <span class="mb-1 block text-sm font-medium">Kata kunci</span>
            <input class="w-full rounded-lg border border-slate-300 px-3 py-2" name="q" value="<?= esc($filters['q']) ?>"
                   placeholder="No. resi, barang, kota, lokasi...">
        </label>
        <label>
            <span class="mb-1 block text-sm font-medium">Status</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="status">
                <option value="">Semua status</option>
                <?php foreach (['DIKIRIM', 'SAMPAI_DI_TUJUAN'] as $status): ?>
                    <option value="<?= esc($status) ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>>
                        <?= esc(str_replace('_', ' ', $status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span class="mb-1 block text-sm font-medium">Dibuat sejak</span>
            <input class="w-full rounded-lg border border-slate-300 px-3 py-2" type="date" name="date_from"
                   value="<?= esc($filters['date_from']) ?>">
        </label>
        <label>
            <span class="mb-1 block text-sm font-medium">Dibuat sampai</span>
            <input class="w-full rounded-lg border border-slate-300 px-3 py-2" type="date" name="date_to"
                   value="<?= esc($filters['date_to']) ?>">
        </label>
        <label>
            <span class="mb-1 block text-sm font-medium">Data</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="deleted">
                <option value="" <?= $filters['deleted'] === '' ? 'selected' : '' ?>>Aktif</option>
                <option value="1" <?= $filters['deleted'] === '1' ? 'selected' : '' ?>>Arsip</option>
            </select>
        </label>
        <div class="flex items-end gap-2 lg:col-span-5">
            <button class="rounded-lg bg-cyan-700 px-4 py-2 font-semibold text-white hover:bg-cyan-800" type="submit">Cari</button>
            <a class="rounded-lg border border-slate-300 px-4 py-2 font-semibold hover:bg-slate-50" href="<?= site_url('staff') ?>">Reset</a>
        </div>
    </form>
</section>

<?php foreach (['success' => 'bg-green-50 text-green-800', 'error' => 'bg-red-50 text-red-800'] as $key => $style): ?>
    <?php if ($message = session()->getFlashdata($key)): ?>
        <div class="mt-5 rounded-lg p-4 <?= $style ?>" role="status"><?= esc($message) ?></div>
    <?php endif; ?>
<?php endforeach; ?>
<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<?php if ($errors !== []): ?>
    <div class="mt-5 rounded-lg bg-red-50 p-4 text-red-800" role="alert">
        <ul class="list-inside list-disc">
            <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="mt-6 space-y-5">
    <?php if ($shipments === []): ?>
        <div class="rounded-xl bg-white p-8 text-center text-slate-500 shadow-sm">
            <?= $showDeleted ? 'Tidak ada pengiriman di arsip yang cocok dengan pencarian.' : 'Tidak ada pengiriman aktif yang cocok dengan pencarian.' ?>
        </div>
    <?php else: ?>
        <p class="text-sm text-slate-600">Ditemukan <?= count($shipments) ?> pengiriman.</p>
    <?php endif; ?>

    <?php foreach ($shipments as $shipment): ?>
        <article class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold"><?= esc($shipment['item_name']) ?></h2>
                    <p class="mt-1 text-sm text-slate-500">Resi: <?= esc($shipment['resi_number']) ?></p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold">
                    <?= esc(str_replace('_', ' ', $shipment['status'])) ?>
                </span>
            </div>
            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                <div><dt class="text-slate-500">Rute</dt><dd class="font-medium"><?= esc($shipment['origin']) ?> &rarr; <?= esc($shipment['destination']) ?></dd></div>
                <div><dt class="text-slate-500">Lokasi saat ini</dt><dd class="font-medium"><?= esc($shipment['current_location']) ?></dd></div>
                <div><dt class="text-slate-500">Dibuat</dt><dd class="font-medium"><?= esc(format_datetime_wib($shipment['created_at'])) ?></dd></div>
            </dl>

            <?php if ($showDeleted): ?>
                <div class="mt-5 flex flex-wrap gap-3 border-t border-slate-100 pt-5">
                    <form method="post" action="<?= site_url('staff/restore/' . $shipment['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="rounded-lg border border-cyan-700 px-4 py-2 font-semibold text-cyan-800 hover:bg-cyan-50" type="submit">
                            Pulihkan
                        </button>
                    </form>
                    <form method="post" action="<?= site_url('staff/delete-permanently/' . $shipment['id']) ?>"
                          onsubmit="return confirm('Hapus pengiriman dan seluruh histori checkpoint secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                        <?= csrf_field() ?>
                        <button class="rounded-lg bg-red-700 px-4 py-2 font-semibold text-white hover:bg-red-800" type="submit">
                            Hapus permanen
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="mt-5 space-y-4 border-t border-slate-100 pt-5">
                    <?php if ($shipment['status'] !== 'SAMPAI_DI_TUJUAN'): ?>
                        <div class="grid gap-5 lg:grid-cols-[1fr_auto]">
                            <form class="grid gap-3 sm:grid-cols-2" method="post"
                                  action="<?= site_url('staff/update-location/' . $shipment['id']) ?>">
                                <?= csrf_field() ?>
                                <div>
                                    <label class="mb-1 block text-sm font-medium" for="location-<?= esc($shipment['id']) ?>">Checkpoint lokasi</label>
                                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2" id="location-<?= esc($shipment['id']) ?>"
                                           name="location" maxlength="255" placeholder="Contoh: Pos Transit Purwakarta" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium" for="notes-<?= esc($shipment['id']) ?>">Catatan</label>
                                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2" id="notes-<?= esc($shipment['id']) ?>"
                                           name="notes" maxlength="1000" placeholder="Catatan singkat (opsional)">
                                </div>
                                <div class="sm:col-span-2">
                                    <button class="rounded-lg border border-cyan-700 px-4 py-2 font-semibold text-cyan-800 hover:bg-cyan-50" type="submit">
                                        Simpan checkpoint
                                    </button>
                                </div>
                            </form>
                            <form class="self-end" method="post" action="<?= site_url('staff/complete/' . $shipment['id']) ?>"
                                  onsubmit="return confirm('Tandai pengiriman ini sudah diterima di kota tujuan?')">
                                <?= csrf_field() ?>
                                <button class="rounded-lg bg-green-700 px-4 py-2 font-semibold text-white hover:bg-green-800" type="submit">
                                    ACC &amp; selesai
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-end">
                        <form method="post" action="<?= site_url('staff/delete/' . $shipment['id']) ?>"
                              onsubmit="return confirm('Pindahkan pengiriman ini ke arsip?')">
                            <?= csrf_field() ?>
                            <button class="rounded-lg border border-red-300 px-4 py-2 font-semibold text-red-700 hover:bg-red-50" type="submit">
                                Arsipkan
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</section>

<?= $this->include('layouts/footer') ?>
