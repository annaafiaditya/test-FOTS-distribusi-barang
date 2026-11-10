<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Pelacakan Distribusi') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
<header class="bg-slate-900 text-white">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
        <a class="font-bold" href="<?= site_url('/') ?>">Distribusi Barang</a>
        <div class="flex gap-4 text-sm">
            <a class="hover:text-cyan-300" href="<?= site_url('/') ?>">Lacak Resi</a>
            <?php if (session()->has('staff_user_id')): ?>
                <a class="hover:text-cyan-300" href="<?= site_url('staff') ?>">Dashboard</a>
                <a class="hover:text-cyan-300" href="<?= site_url('staff/guide') ?>">Panduan</a>
                <span class="hidden text-slate-300 sm:inline"><?= esc(session()->get('staff_username')) ?></span>
                <form method="post" action="<?= site_url('staff/logout') ?>">
                    <?= csrf_field() ?>
                    <button class="hover:text-red-300" type="submit">Logout</button>
                </form>
            <?php else: ?>
                <a class="hover:text-cyan-300" href="<?= site_url('staff/login') ?>">Login Staff</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main class="mx-auto max-w-6xl px-4 py-8">
