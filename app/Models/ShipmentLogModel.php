<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentLogModel extends Model
{
    protected $table            = 'shipment_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $updatedField     = '';
    protected $allowedFields    = [
        'shipment_id',
        'location',
        'notes',
    ];
}
