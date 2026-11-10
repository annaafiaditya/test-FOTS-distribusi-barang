<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToShipments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('shipments', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('shipments', 'deleted_at');
    }
}
