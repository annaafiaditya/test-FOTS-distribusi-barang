<?= $this->include('layouts/header', ['title' => 'Login Staff']) ?>

<section class="mx-auto max-w-md rounded-xl bg-white p-6 shadow-sm sm:p-8">
    <h1 class="text-2xl font-bold">Login staff</h1>
    <p class="mt-2 text-slate-600">Masuk untuk mengelola pengiriman barang.</p>
    <?php foreach (['error' => 'bg-red-50 text-red-800', 'success' => 'bg-green-50 text-green-800'] as $key => $style): ?>
        <?php if ($message = session()->getFlashdata($key)): ?>
            <p class="mt-4 rounded-lg p-3 <?= $style ?>" role="alert"><?= esc($message) ?></p>
        <?php endif; ?>
    <?php endforeach; ?>
    <form class="mt-6 space-y-4" method="post" action="<?= site_url('staff/login') ?>">
        <?= csrf_field() ?>
        <div>
            <label class="mb-1 block font-medium" for="username">Username</label>
            <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="username" name="username"
                   value="<?= esc(session()->getFlashdata('old_username') ?? '') ?>" autocomplete="username" maxlength="50" required>
        </div>
        <div>
            <label class="mb-1 block font-medium" for="password">Password</label>
            <input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="password" name="password"
                   type="password" autocomplete="current-password" required>
        </div>
        <button class="w-full rounded-lg bg-cyan-700 px-5 py-3 font-semibold text-white hover:bg-cyan-800" type="submit">Login</button>
    </form>
    <p class="mt-5 text-sm text-slate-600">Belum punya akun? <a class="font-semibold text-cyan-800 hover:underline" href="<?= site_url('staff/register') ?>">Daftar sebagai staff</a>.</p>
</section>

<?= $this->include('layouts/footer') ?>
