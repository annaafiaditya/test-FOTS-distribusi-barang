<?= $this->include('layouts/header', ['title' => 'Daftar Staff']) ?>

<section class="mx-auto max-w-md rounded-xl bg-white p-6 shadow-sm sm:p-8">
    <h1 class="text-2xl font-bold">Buat akun staff</h1>
    <p class="mt-2 text-slate-600">Password minimal 8 karakter. Setelah daftar, panduan penggunaan akan ditampilkan.</p>
    <?php if ($message = session()->getFlashdata('error')): ?>
        <p class="mt-4 rounded-lg bg-red-50 p-3 text-red-800" role="alert"><?= esc($message) ?></p>
    <?php endif; ?>
    <?php $errors = session()->getFlashdata('errors') ?? []; ?>
    <?php if ($errors !== []): ?>
        <div class="mt-4 rounded-lg bg-red-50 p-3 text-red-800" role="alert">
            <ul class="list-inside list-disc"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>
    <form class="mt-6 space-y-4" method="post" action="<?= site_url('staff/register') ?>">
        <?= csrf_field() ?>
        <div>
            <label class="mb-1 block font-medium" for="username">Username</label>
            <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="username" name="username"
                   value="<?= esc(session()->getFlashdata('old_username') ?? '') ?>" autocomplete="username" minlength="3" maxlength="50" required>
        </div>
        <div>
            <label class="mb-1 block font-medium" for="password">Password</label>
            <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="password" name="password"
                   type="password" autocomplete="new-password" minlength="8" maxlength="72" required>
        </div>
        <div>
            <label class="mb-1 block font-medium" for="password_confirm">Konfirmasi password</label>
            <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="password_confirm" name="password_confirm"
                   type="password" autocomplete="new-password" minlength="8" maxlength="72" required>
        </div>
        <button class="w-full rounded-lg bg-cyan-700 px-5 py-3 font-semibold text-white hover:bg-cyan-800" type="submit">Buat akun</button>
    </form>
    <p class="mt-5 text-sm text-slate-600">Sudah punya akun? <a class="font-semibold text-cyan-800 hover:underline" href="<?= site_url('staff/login') ?>">Login</a>.</p>
</section>

<?= $this->include('layouts/footer') ?>
