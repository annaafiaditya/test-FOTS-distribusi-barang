<?= $this->include('layouts/header', ['title' => 'Lacak Pengiriman']) ?>

<section class="mx-auto max-w-6xl">
    <div class="overflow-hidden rounded-3xl border border-cyan-100 bg-gradient-to-br from-white via-white to-cyan-50 shadow-sm">
        <div class="grid items-center gap-8 px-6 py-8 sm:px-10 sm:py-10 lg:grid-cols-2 lg:gap-10 lg:px-14 lg:py-12">
            <div>
                <p class="inline-flex rounded-full bg-cyan-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-cyan-800">
                    SELAMAT DATANG DI APLIKASI PELACAKAN PENGIRIMAN
                </p>
                <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl">
                    Paketmu sedang di perjalanan?
                    <span class="text-cyan-700">Yuk, cek di sini.</span>
                </h1>
                <p class="mt-4 max-w-xl leading-relaxed text-slate-600">
                    Masukkan nomor resi untuk melihat posisi terkini dan rangkaian perjalanan barangmu. Semua informasinya bisa dicek dengan mudah di satu tempat.
                </p>

                <form class="mt-7 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm sm:flex sm:gap-2" method="get" action="<?= site_url('/') ?>">
                    <label class="sr-only" for="resi_number">Nomor resi</label>
                    <input id="resi_number" name="resi_number" value="<?= esc($resiNumber) ?>"
                           class="min-w-0 flex-1 rounded-xl border-0 px-3 py-3 text-slate-800 outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600"
                           placeholder="Masukkan nomor resi" required>
                    <button class="rounded-xl bg-cyan-700 px-6 py-3 font-semibold text-white transition hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-600 focus:ring-offset-2" type="submit">
                        Lacak paket
                    </button>
                </form>
                <p class="mt-3 text-sm text-slate-500">Nomor resi dikirim melalui WhatsApp &amp; email.</p>

                <?php if ($resiNumber !== '' && $shipment === null): ?>
                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900" role="status">
                        Nomor resi <strong><?= esc($resiNumber) ?></strong> belum ditemukan. Coba periksa kembali nomor resinya.
                    </div>
                <?php elseif ($shipment !== null): ?>
                    <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-900" role="status">
                        Resi <strong><?= esc($shipment['resi_number']) ?></strong> ditemukan. Detail pengiriman ditampilkan di bawah.
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-center lg:justify-end">
                <img class="h-auto w-full max-w-md" src="/assets/images/gambar.svg"
                     alt="Ilustrasi seseorang memeriksa informasi pengiriman paket">
            </div>
        </div>
    </div>

    <?php if ($shipment !== null): ?>
        <div id="shipment-details" class="mt-8 scroll-mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <p class="text-sm font-medium text-slate-500">Detail pengiriman</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900"><?= esc($shipment['resi_number']) ?></h2>
                </div>
                <span class="rounded-full bg-cyan-100 px-4 py-2 text-sm font-semibold capitalize text-cyan-900">
                    <?= esc(str_replace('_', ' ', $shipment['status'])) ?>
                </span>
            </div>

            <dl class="grid gap-5 py-6 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-sm text-slate-500">Nama barang</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= esc($shipment['item_name']) ?></dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Lokasi saat ini</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= esc($shipment['current_location']) ?></dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Kota asal</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= esc($shipment['origin']) ?></dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Kota tujuan</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= esc($shipment['destination']) ?></dd>
                </div>
            </dl>

            <h3 class="border-t border-slate-100 pt-6 text-lg font-bold text-slate-900">Riwayat perjalanan</h3>
            <?php if ($logs === []): ?>
                <p class="mt-3 text-slate-500">Belum ada pembaruan perjalanan untuk paket ini.</p>
            <?php else: ?>
                <ol class="mt-5 space-y-6 border-l-2 border-cyan-100 pl-5">
                    <?php foreach ($logs as $log): ?>
                        <li class="relative">
                            <span class="absolute -left-[1.63rem] top-1 h-3 w-3 rounded-full border-2 border-white bg-cyan-700 ring-2 ring-cyan-100"></span>
                            <p class="font-semibold text-slate-800"><?= esc($log['location']) ?></p>
                            <?php if ($log['notes'] !== null && $log['notes'] !== ''): ?>
                                <p class="mt-1 text-slate-600"><?= esc($log['notes']) ?></p>
                            <?php endif; ?>
                            <time class="mt-1 block text-sm text-slate-500"><?= esc(format_datetime_wib($log['created_at'])) ?></time>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
        <script>
            document.getElementById('shipment-details')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        </script>
    <?php endif; ?>
</section>

<?= $this->include('layouts/footer') ?>
