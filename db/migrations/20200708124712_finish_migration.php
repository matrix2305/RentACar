<?php

use Phinx\Db\Adapter\MysqlAdapter;

class FinishMigration extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('class', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->changeColumn('class_color', 'string', [
                'null' => false,
                'limit' => 7,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'class_name',
            ])
            ->save();
        $this->table('comments', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('created_at', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'comment',
            ])
            ->changeColumn('allowed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'created_at',
            ])
            ->changeColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'allowed',
            ])
            ->changeColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'deleted',
            ])
            ->save();
        $this->table('rented_cars', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('start_renting_date', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rented_time',
            ])
            ->addColumn('end_renting_date', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'start_renting_date',
            ])
            ->changeColumn('period_of_rent', 'string', [
                'null' => false,
                'limit' => 7,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'end_renting_date',
            ])
            ->changeColumn('price', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'period_of_rent',
            ])
            ->changeColumn('time_added', 'timestamp', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'after' => 'price',
            ])
            ->changeColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'time_added',
            ])
            ->changeColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cars_id',
            ])
            ->changeColumn('time_deleted', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'deleted',
            ])
            ->removeColumn('end_renting_time')
            ->removeColumn('reviews_cars_id')
            ->removeColumn('users_id')
            ->removeIndexByName("fk_rented_cars_reviews_cars1_idx")
            ->removeIndexByName("fk_rented_cars_users1_idx")
            ->save();
        $this->table('rented_cars_has_users', [
                'id' => false,
                'primary_key' => ['id', 'rented_cars_id', 'users_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('rented_cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'id',
            ])
            ->addColumn('users_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'rented_cars_id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['users_id'], [
                'name' => 'fk_rented_cars_has_users_users1_idx',
                'unique' => false,
            ])
            ->addIndex(['rented_cars_id'], [
                'name' => 'fk_rented_cars_has_users_rented_cars1_idx',
                'unique' => false,
            ])
            ->create();
    }
}
