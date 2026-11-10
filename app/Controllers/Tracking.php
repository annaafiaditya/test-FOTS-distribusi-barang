<?php

namespace App\Controllers;

use App\Models\ShipmentLogModel;
use App\Models\ShipmentModel;

class Tracking extends BaseController
{
    public function index(): string
    {
        $resiNumber = trim((string) $this->request->getGet('resi_number'));
        $shipment   = null;
        $logs       = [];

        if ($resiNumber !== '') {
            $shipment = (new ShipmentModel())
                ->where('resi_number', $resiNumber)
                ->first();

            if ($shipment !== null) {
                $logs = (new ShipmentLogModel())
                    ->where('shipment_id', $shipment['id'])
                    ->orderBy('created_at', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
            }
        }

        return view('tracking_view', [
            'resiNumber' => $resiNumber,
            'shipment'   => $shipment,
            'logs'       => $logs,
        ]);
    }
}
