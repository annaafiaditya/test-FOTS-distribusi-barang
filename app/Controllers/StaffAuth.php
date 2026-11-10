<?php

namespace App\Controllers;

use App\Models\StaffUserModel;
use CodeIgniter\HTTP\RedirectResponse;

class StaffAuth extends BaseController
{
    public function login(): string|RedirectResponse
    {
        if (session()->has('staff_user_id')) {
            return redirect()->to(site_url('staff'));
        }

        if ($this->request->getMethod() !== 'POST') {
            return view('staff/login');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $user     = (new StaffUserModel())->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->with('old_username', $username)
                ->with('error', 'Username atau password salah.');
        }

        session()->regenerate();
        session()->set([
            'staff_user_id'       => $user['id'],
            'staff_username'      => $user['username'],
            'staff_guide_pending' => $user['guide_seen_at'] === null,
        ]);

        return redirect()->to(site_url('staff'));
    }

    public function register(): string|RedirectResponse
    {
        if (session()->has('staff_user_id')) {
            return redirect()->to(site_url('staff'));
        }

        if ($this->request->getMethod() !== 'POST') {
            return view('staff/register');
        }

        $username        = trim((string) $this->request->getPost('username'));
        $password        = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');

        if (! $this->validateData([
            'username'         => $username,
            'password'         => $password,
            'password_confirm' => $passwordConfirm,
        ], [
            'username'         => 'required|min_length[3]|max_length[50]|alpha_numeric_punct|is_unique[staff_users.username]',
            'password'         => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            return redirect()->back()
                ->with('old_username', $username)
                ->with('errors', $this->validator->getErrors());
        }

        $model = new StaffUserModel();
        $userId = $model->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        if ($userId === false) {
            log_message('error', 'Gagal membuat akun staff: ' . json_encode($model->errors()));

            return redirect()->back()
                ->with('old_username', $username)
                ->with('error', 'Akun gagal dibuat. Username mungkin sudah digunakan.');
        }

        session()->regenerate();
        session()->set([
            'staff_user_id'       => $userId,
            'staff_username'      => $username,
            'staff_guide_pending' => true,
        ]);

        return redirect()->to(site_url('staff'));
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['staff_user_id', 'staff_username', 'staff_guide_pending']);
        session()->regenerate(true);

        return redirect()->to(site_url('staff/login'))->with('success', 'Anda berhasil logout.');
    }
}
