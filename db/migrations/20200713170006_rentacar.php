<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Rentacar extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8';");
        $this->execute("ALTER DATABASE COLLATE='utf8_general_ci';");
        $this->table('activity', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('ip_adress', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'limit' => 150,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'ip_adress',
            ])
            ->create();
        $this->table('brands', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('brand_name', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['brand_name'], [
                'name' => 'brand_name_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['brand_name'], [
                'name' => 'brand_name',
                'unique' => true,
            ])
            ->create();
        $this->table('car_body', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('car_body_name', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['car_body_name'], [
                'name' => 'car_body_name_UNIQUE',
                'unique' => true,
            ])
            ->create();
        $this->table('cars', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('model', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('available', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'model',
            ])
            ->addColumn('image', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'available',
            ])
            ->addColumn('rent_reserved', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'image',
            ])
            ->addColumn('rented', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'rent_reserved',
            ])
            ->addColumn('brands_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'rented',
            ])
            ->addColumn('class_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'brands_id',
            ])
            ->addColumn('car_body_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'class_id',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'after' => 'car_body_id',
            ])
            ->addColumn('updated_at', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'created_at',
            ])
            ->addIndex(['model'], [
                'name' => 'model',
                'unique' => true,
            ])
            ->addIndex(['brands_id'], [
                'name' => 'fk_cars_brands1_idx',
                'unique' => false,
            ])
            ->addIndex(['class_id'], [
                'name' => 'fk_cars_class1_idx',
                'unique' => false,
            ])
            ->addIndex(['car_body_id'], [
                'name' => 'fk_cars_car_body1_idx',
                'unique' => false,
            ])
            ->create();
        $this->table('class', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('class_name', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('class_color', 'string', [
                'null' => false,
                'limit' => 7,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'class_name',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['class_name'], [
                'name' => 'class_name_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['class_name'], [
                'name' => 'class_name',
                'unique' => true,
            ])
            ->addIndex(['class_name'], [
                'name' => 'class_name_2',
                'unique' => true,
            ])
            ->create();
        $this->table('comments', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 70,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('created_at', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'comment',
            ])
            ->addColumn('allowed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'created_at',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'allowed',
            ])
            ->addColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'deleted',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['cars_id'], [
                'name' => 'fk_comments_cars1_idx',
                'unique' => false,
            ])
            ->create();
        $this->table('contact_info', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('mobile_number', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('fix_number', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'mobile_number',
            ])
            ->addColumn('adress', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'fix_number',
            ])
            ->addColumn('facebook_url', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'adress',
            ])
            ->addColumn('instagram_url', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'facebook_url',
            ])
            ->addColumn('time_last_change', 'timestamp', [
                'null' => true,
                'after' => 'instagram_url',
            ])
            ->create();
        $this->table('fuels', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('fuel', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['fuel'], [
                'name' => 'fuel_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['fuel'], [
                'name' => 'fuel',
                'unique' => true,
            ])
            ->create();
        $this->table('fuels_cars', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('fuels_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'id',
            ])
            ->addColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'fuels_id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['fuels_id'], [
                'name' => 'fk_fuels_cars_fuels_idx',
                'unique' => false,
            ])
            ->addIndex(['cars_id'], [
                'name' => 'fk_fuels_cars_cars1_idx',
                'unique' => false,
            ])
            ->create();
        $this->table('informations', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('informations', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('time_last_change', 'timestamp', [
                'null' => true,
                'after' => 'informations',
            ])
            ->create();
        $this->table('logs', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('log', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->create();
        $this->table('messages', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('lastname', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'lastname',
            ])
            ->addColumn('message', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'message',
            ])
            ->create();
        $this->table('monitoring_cars', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('x_position', 'double', [
                'null' => false,
                'after' => 'id',
            ])
            ->addColumn('y_position', 'double', [
                'null' => false,
                'after' => 'x_position',
            ])
            ->addColumn('time', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'y_position',
            ])
            ->addColumn('rented_cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'time',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['rented_cars_id'], [
                'name' => 'fk_monitoring_cars_rented_cars1_idx',
                'unique' => false,
            ])
            ->create();
        $this->table('password_resets', [
                'id' => false,
                'primary_key' => ['id', 'token'],
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
            ])
            ->addColumn('email', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('token', 'string', [
                'null' => false,
                'limit' => 256,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['token'], [
                'name' => 'token_UNIQUE',
                'unique' => true,
            ])
            ->create();
        $this->table('price', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('one_day', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('two_days', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'one_day',
            ])
            ->addColumn('three_days', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'two_days',
            ])
            ->addColumn('seven_days', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'three_days',
            ])
            ->addColumn('fourteen_days', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'seven_days',
            ])
            ->addColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'fourteen_days',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['cars_id'], [
                'name' => 'fk_price_cars1_idx',
                'unique' => false,
            ])
            ->create();
        $this->table('ratings', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 70,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('rating', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rating',
            ])
            ->addColumn('allowed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'comment',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'allowed',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->create();
        $this->table('rented_cars', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('lastname', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'lastname',
            ])
            ->addColumn('phone_number', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('JMBG', 'biginteger', [
                'null' => false,
                'limit' => '13',
                'after' => 'phone_number',
            ])
            ->addColumn('adress', 'string', [
                'null' => true,
                'limit' => 120,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'JMBG',
            ])
            ->addColumn('allowed', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'adress',
            ])
            ->addColumn('rented_time', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'allowed',
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
            ->addColumn('period_of_rent', 'string', [
                'null' => false,
                'limit' => 7,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'end_renting_date',
            ])
            ->addColumn('price', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'period_of_rent',
            ])
            ->addColumn('time_added', 'timestamp', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'after' => 'price',
            ])
            ->addColumn('cars_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'time_added',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cars_id',
            ])
            ->addColumn('time_deleted', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'deleted',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['cars_id'], [
                'name' => 'fk_rented_cars_cars1_idx',
                'unique' => false,
            ])
            ->create();
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
        $this->table('reviews', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'limit' => 150,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('reviews', 'biginteger', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'path',
            ])
            ->create();
        $this->table('reviews_cars', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('ip_adress', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('car_model', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'ip_adress',
            ])
            ->addColumn('car_brand', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'car_model',
            ])
            ->addColumn('car_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'car_brand',
            ])
            ->addIndex(['car_id'], [
                'name' => 'car_id',
                'unique' => false,
            ])
            ->create();
        $this->table('roles', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('role_name', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->create();
        $this->table('users', [
                'id' => false,
                'primary_key' => ['id'],
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
            ->addColumn('username', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'username',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'password',
            ])
            ->addColumn('lastname', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('phone_number', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'lastname',
            ])
            ->addColumn('adress', 'string', [
                'null' => true,
                'limit' => 70,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'phone_number',
            ])
            ->addColumn('avatar_name', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'adress',
            ])
            ->addColumn('roles_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'after' => 'avatar_name',
            ])
            ->addIndex(['id'], [
                'name' => 'id_UNIQUE',
                'unique' => true,
            ])
            ->addIndex(['username', 'email'], [
                'name' => 'username',
                'unique' => true,
            ])
            ->addIndex(['roles_id'], [
                'name' => 'fk_users_roles1_idx',
                'unique' => false,
            ])
            ->create();
    }
}
