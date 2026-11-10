<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShipmentsAndShipmentLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'resi_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'origin' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'destination' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'current_location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => "'DRAFT','DIKIRIM','SAMPAI_DI_TUJUAN'",
                'default'    => 'DRAFT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('resi_number');
        $this->forge->createTable('shipments');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'shipment_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('shipment_id');
        $this->forge->addForeignKey('shipment_id', 'shipments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('shipment_logs');
    }

    public function down()
    {
        $this->forge->dropTable('shipment_logs', true);
        $this->forge->dropTable('shipments', true);
    }
}
