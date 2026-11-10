<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffUserModel extends Model
{
    protected $table            = 'staff_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'username',
        'password_hash',
        'guide_seen_at',
    ];
}
