<?php

namespace App\Controllers;

use App\Models\ShipmentLogModel;
use App\Models\ShipmentModel;
use App\Models\StaffUserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use RuntimeException;

class Staff extends BaseController
{
    public function index(): string
    {
        $filters = [
            'q'         => trim((string) $this->request->getGet('q')),
            'status'    => (string) $this->request->getGet('status'),
            'date_from' => (string) $this->request->getGet('date_from'),
            'date_to'   => (string) $this->request->getGet('date_to'),
            'deleted'   => (string) $this->request->getGet('deleted'),
        ];
        $model = new ShipmentModel();
        $shipmentStats = [
            'total'     => (new ShipmentModel())->countAllResults(),
            'inTransit' => (new ShipmentModel())->where('status', 'DIKIRIM')->countAllResults(),
            'delivered' => (new ShipmentModel())->where('status', 'SAMPAI_DI_TUJUAN')->countAllResults(),
        ];

        if ($filters['deleted'] === '1') {
            $model->onlyDeleted();
        } else {
            $filters['deleted'] = '';
        }

        if ($filters['q'] !== '') {
            $term = $filters['q'];
            $model->groupStart()
                ->like('resi_number', $term)
                ->orLike('item_name', $term)
                ->orLike('origin', $term)
                ->orLike('destination', $term)
                ->orLike('current_location', $term)
                ->orLike('status', $term)
                ->orLike('created_at', $term)
                ->orLike('updated_at', $term)
                ->orWhereIn('id', static function ($builder) use ($term): void {
                    $builder->select('shipment_id')
                        ->from('shipment_logs')
                        ->groupStart()
                        ->like('location', $term)
                        ->orLike('notes', $term)
                        ->groupEnd();
                });
            if (ctype_digit($term)) {
                $model->orWhere('id', (int) $term);
            }
            $model->groupEnd();
        }

        $statuses = ['DRAFT', 'DIKIRIM', 'SAMPAI_DI_TUJUAN'];
        if (in_array($filters['status'], $statuses, true)) {
            $model->where('status', $filters['status']);
        } else {
            $filters['status'] = '';
        }

        $searchError = null;
        if ($filters['date_from'] !== '' && ! $this->isValidDate($filters['date_from'])) {
            $searchError = 'Tanggal mulai tidak valid.';
            $filters['date_from'] = '';
        }
        if ($filters['date_to'] !== '' && ! $this->isValidDate($filters['date_to'])) {
            $searchError = 'Tanggal akhir tidak valid.';
            $filters['date_to'] = '';
        }
        if (
            $searchError === null
            && $filters['date_from'] !== ''
            && $filters['date_to'] !== ''
            && $filters['date_from'] > $filters['date_to']
        ) {
            $searchError = 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.';
            $filters['date_from'] = '';
            $filters['date_to'] = '';
        }

        if ($filters['date_from'] !== '') {
            $model->where('created_at >=', local_date_boundary_to_utc($filters['date_from']));
        }
        if ($filters['date_to'] !== '') {
            $model->where('created_at <=', local_date_boundary_to_utc($filters['date_to'], true));
        }

        return view('staff/index', [
            'shipments'   => $model->orderBy('created_at', 'DESC')->findAll(),
            'filters'     => $filters,
            'searchError' => $searchError,
            'showDeleted' => $filters['deleted'] === '1',
            'shipmentStats' => $shipmentStats,
        ]);
    }

    public function deleteShipment(int $id): RedirectResponse
    {
        $shipmentModel = new ShipmentModel();
        $shipment      = $shipmentModel->find($id);

        if ($shipment === null) {
            throw PageNotFoundException::forPageNotFound('Pengiriman tidak ditemukan.');
        }

        if ($shipmentModel->delete($id) === false) {
            log_message('error', 'Gagal mengarsipkan shipment ID ' . $id . '.');
            throw new RuntimeException('Pengiriman gagal diarsipkan. Silakan periksa log aplikasi.');
        }

        return redirect()->to(site_url('staff'))->with('success', 'Pengiriman berhasil dipindahkan ke arsip.');
    }

    public function restoreShipment(int $id): RedirectResponse
    {
        $shipmentModel = new ShipmentModel();
        $shipment      = $shipmentModel->onlyDeleted()->find($id);

        if ($shipment === null) {
            throw PageNotFoundException::forPageNotFound('Pengiriman di arsip tidak ditemukan.');
        }

        if ($shipmentModel->withDeleted()->update($id, ['deleted_at' => null]) === false) {
            log_message('error', 'Gagal memulihkan shipment ID ' . $id . ': ' . json_encode($shipmentModel->errors()));
            throw new RuntimeException('Pengiriman gagal dipulihkan. Silakan periksa log aplikasi.');
        }

        return redirect()->to(site_url('staff?deleted=1'))->with('success', 'Pengiriman berhasil dipulihkan.');
    }

    public function deleteShipmentPermanently(int $id): RedirectResponse
    {
        $shipmentModel = new ShipmentModel();
        $shipment      = $shipmentModel->onlyDeleted()->find($id);

        if ($shipment === null) {
            throw PageNotFoundException::forPageNotFound('Pengiriman di arsip tidak ditemukan.');
        }

        if ($shipmentModel->delete($id, true) === false) {
            log_message('error', 'Gagal menghapus permanen shipment ID ' . $id . '.');
            throw new RuntimeException('Pengiriman gagal dihapus permanen. Silakan periksa log aplikasi.');
        }

        return redirect()->to(site_url('staff?deleted=1'))->with('success', 'Pengiriman dihapus permanen.');
    }

    public function guide(): string
    {
        return view('staff/guide', [
            'isFirstLogin' => (bool) session()->get('staff_guide_pending'),
        ]);
    }

    public function completeGuide(): RedirectResponse
    {
        $userId = (int) session()->get('staff_user_id');
        $model  = new StaffUserModel();

        if ($model->update($userId, ['guide_seen_at' => date('Y-m-d H:i:s')]) === false) {
            log_message('error', 'Gagal menyimpan status panduan staff: ' . json_encode($model->errors()));
            throw new RuntimeException('Status panduan gagal disimpan. Silakan periksa log aplikasi.');
        }

        session()->set('staff_guide_pending', false);

        return redirect()->to(site_url('staff'));
    }

    public function create(): string|RedirectResponse
    {
        if ($this->request->getMethod() !== 'POST') {
            return view('staff/create');
        }

        if (! $this->validate([
            'item_name'   => 'required|max_length[150]',
            'origin'      => 'required|max_length[150]',
            'destination' => 'required|max_length[150]',
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());

            return redirect()->back()->withInput();
        }

        $shipmentModel = new ShipmentModel();
        $logModel      = new ShipmentLogModel();
        $database      = db_connect();
        $origin        = trim((string) $this->request->getPost('origin'));

        $database->transStart();

        $inserted = $shipmentModel->insert([
            'resi_number'     => 'RESI-' . strtoupper(bin2hex(random_bytes(6))),
            'item_name'       => trim((string) $this->request->getPost('item_name')),
            'origin'          => $origin,
            'destination'     => trim((string) $this->request->getPost('destination')),
            'current_location' => $origin,
            'status'          => 'DIKIRIM',
        ]);

        if ($inserted === false) {
            $errors = json_encode($shipmentModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal membuat shipment: ' . $errors);
            throw new RuntimeException('Pengiriman gagal disimpan. Silakan periksa log aplikasi.');
        }

        $shipmentId = $shipmentModel->getInsertID();
        if ($logModel->insert([
            'shipment_id' => $shipmentId,
            'location'    => $origin,
            'notes'       => 'Paket diterima dan mulai dikirim dari kota asal.',
        ]) === false) {
            $errors = json_encode($logModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal membuat log awal shipment: ' . $errors);
            throw new RuntimeException('Histori awal pengiriman gagal disimpan. Silakan periksa log aplikasi.');
        }

        $database->transComplete();

        if (! $database->transStatus()) {
            throw new RuntimeException('Transaksi pengiriman gagal diselesaikan.');
        }

        return redirect()->to(site_url('staff'))->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function updateLocation(int $id): RedirectResponse
    {
        $shipmentModel = new ShipmentModel();
        $logModel      = new ShipmentLogModel();
        $shipment      = $shipmentModel->find($id);

        if ($shipment === null) {
            throw PageNotFoundException::forPageNotFound('Pengiriman tidak ditemukan.');
        }

        if ($shipment['status'] === 'SAMPAI_DI_TUJUAN') {
            return redirect()->to(site_url('staff'))->with('error', 'Pengiriman yang sudah selesai tidak dapat diubah.');
        }

        if (! $this->validate([
            'location' => 'required|max_length[255]',
            'notes'    => 'permit_empty|max_length[1000]',
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());

            return redirect()->back()->withInput();
        }

        $location = trim((string) $this->request->getPost('location'));
        $notes    = trim((string) $this->request->getPost('notes'));
        $database = db_connect();
        $database->transStart();

        if ($shipmentModel->update($id, ['current_location' => $location]) === false) {
            $errors = json_encode($shipmentModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal memperbarui lokasi shipment: ' . $errors);
            throw new RuntimeException('Lokasi pengiriman gagal diperbarui. Silakan periksa log aplikasi.');
        }

        if ($logModel->insert([
            'shipment_id' => $id,
            'location'    => $location,
            'notes'       => $notes !== '' ? $notes : null,
        ]) === false) {
            $errors = json_encode($logModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal menyimpan histori lokasi shipment: ' . $errors);
            throw new RuntimeException('Histori lokasi gagal disimpan. Silakan periksa log aplikasi.');
        }

        $database->transComplete();

        if (! $database->transStatus()) {
            throw new RuntimeException('Transaksi perubahan lokasi gagal diselesaikan.');
        }

        return redirect()->to(site_url('staff'))->with('success', 'Checkpoint lokasi berhasil ditambahkan.');
    }

    public function completeShipment(int $id): RedirectResponse
    {
        $shipmentModel = new ShipmentModel();
        $shipment      = $shipmentModel->find($id);

        if ($shipment === null) {
            throw PageNotFoundException::forPageNotFound('Pengiriman tidak ditemukan.');
        }

        if ($shipment['status'] === 'SAMPAI_DI_TUJUAN') {
            return redirect()->to(site_url('staff'))->with('error', 'Pengiriman ini sudah selesai.');
        }

        $database = db_connect();
        $database->transStart();

        if ($shipmentModel->update($id, [
            'status'           => 'SAMPAI_DI_TUJUAN',
            'current_location' => $shipment['destination'],
        ]) === false) {
            $errors = json_encode($shipmentModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal menyelesaikan shipment: ' . $errors);
            throw new RuntimeException('Status pengiriman gagal diperbarui. Silakan periksa log aplikasi.');
        }

        $logModel = new ShipmentLogModel();
        if ($logModel->insert([
            'shipment_id' => $id,
            'location'    => $shipment['destination'],
            'notes'       => 'Pengiriman telah diterima dan disetujui di lokasi tujuan.',
        ]) === false) {
            $errors = json_encode($logModel->errors());
            $database->transComplete();
            log_message('error', 'Gagal menyimpan log penyelesaian shipment: ' . $errors);
            throw new RuntimeException('Histori penyelesaian gagal disimpan. Silakan periksa log aplikasi.');
        }

        $database->transComplete();

        if (! $database->transStatus()) {
            throw new RuntimeException('Transaksi penyelesaian pengiriman gagal.');
        }

        return redirect()->to(site_url('staff'))->with('success', 'Pengiriman telah ditandai sampai di tujuan.');
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
