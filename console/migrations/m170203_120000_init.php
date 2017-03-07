<?php

use yii\db\Migration;

/**
 * Initial migration
 * Sets up authentication and authorization models
 * 
 * @author Rostislav Pleshivtsev Oparina
 * @link bytehunter.net
 *
 */
class m170203_120000_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        /*
         * Table `auth_rule`
         */
        $this->createTable('auth_rule', [
            'name'          => 'varchar(64) PRIMARY KEY',
            'data'          => 'text',
            'created_at'    => 'int(11)',
            'updated_at'    => 'int(11)',
        ], $tableOptions);
        
        /*
         * Table `auth_item`
         */
        $this->createTable('auth_item', [
            'name'          => 'varchar(64) PRIMARY KEY',
            'type'          => 'int(11) NOT NULL',
            'description'   => 'text',
            'rule_name'     => 'varchar(64)',
            'data'          => 'text',
            'created_at'    => 'int(11)',
            'updated_at'    => 'int(11) ',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-rule_name', 'auth_item', 'rule_name');
        
        /*
         * Table `auth_item_child`
         */
        $this->createTable('auth_item_child', [
            'parent'    => 'varchar(64)',
            'child'     => 'varchar(64)',
        ], $tableOptions);
        $this->addPrimaryKey('', 'auth_item_child', ['parent','child']);
        
        /*
         * Table `auth_assignment`
         */
        $this->createTable('auth_assignment', [
            'item_name'     => 'varchar(64)',
            'user_id'       => 'int(10) unsigned not null',
            'created_at'    => 'int(11) default null',
        ], $tableOptions);
        $this->addPrimaryKey('', 'auth_assignment', ['item_name','user_id']);

        /*
         * Table `user`
         */
        $this->createTable('user', [
            'id'                    => 'int(10) unsigned auto_increment PRIMARY KEY',
            'auth_key'              => 'varchar(32) not null',
            'password_reset_token'  => 'varchar(255)',
            'account_confirm_token' => 'varchar(255)',
            'type'                  => 'enum("admin","normal") not null default "normal"',
            'status'                => 'enum("deleted","suspended","active") not null default "active"',
            'created_at'            => 'datetime',
            'updated_at'            => 'datetime',
            'last_activity'         => 'datetime',
        ], $tableOptions);
        $this->createIndex('idx-user-password_reset_token', 'user', 'password_reset_token', true);
        $this->createIndex('idx-user-account_confirm_token', 'user', 'account_confirm_token', true);
        
        /*
         * Table `admin`
         */
        $this->createTable('admin', [
            'id'                    => 'int(10) unsigned auto_increment PRIMARY KEY',
            'user_id'               => 'int(10) unsigned not null',
            'username'              => 'varchar(255) not null',
            'email'                 => 'varchar(255) not null',
            'password_hash'         => 'varchar(255) not null',
            'fullname'              => 'varchar(255)',
            'phone'                 => 'varchar(32)',
            'created_at'            => 'datetime',
            'updated_at'            => 'datetime',
        ], $tableOptions);
        $this->createIndex('idx-admin-user_id', 'admin', 'user_id');
        $this->createIndex('idx-admin-username', 'admin', 'username', true);
        $this->createIndex('idx-admin-email', 'admin', 'email', true);
        
        /*
         * Table `normal_user`
         */
        $this->createTable('normal_user', [
            'id'                    => 'int(10) unsigned auto_increment PRIMARY KEY',
            'user_id'               => 'int(10) unsigned not null',
            'username'              => 'varchar(255) not null',
            'email'                 => 'varchar(255) not null',
            'password_hash'         => 'varchar(255) not null',
            'fullname'              => 'varchar(255)',
            'phone'                 => 'varchar(32)',
            'gender'                => 'enum("m","f")',
            'birth_date'            => 'datetime',
            'address'               => 'varchar(255)',
            'zip_code'              => 'varchar(10)',
            'locality'              => 'varchar(255)',
            'country'               => 'varchar(5)',
            'created_at'            => 'datetime',
            'updated_at'            => 'datetime',
        ], $tableOptions);
        $this->createIndex('idx-normal_user-user_id', 'normal_user', 'user_id');
        $this->createIndex('idx-normal_user-username', 'normal_user', 'username', true);
        $this->createIndex('idx-normal_user-email', 'normal_user', 'email', true);

        /*
         * Table `api_access`
         */
        $this->createTable('api_access', [
            'id'                    => 'int(10) unsigned auto_increment PRIMARY KEY',
            'username'              => 'varchar(255) not null',
            'email'                 => 'varchar(255) not null',
            'password_hash'         => 'varchar(255) not null',
            'access_token'          => 'varchar(32) not null',
            'password_reset_token'  => 'varchar(255)',
            'type'                  => 'enum("admin","client") not null default "client"',
            'status'                => 'enum("deleted","suspended","active") not null default "active"',
            'created_at'            => 'datetime',
            'updated_at'            => 'datetime',
            'last_activity'         => 'datetime',
        ], $tableOptions);
        $this->createIndex('idx-api_access-username', 'api_access', 'username', true);
        $this->createIndex('idx-api_access-email', 'api_access', 'email', true);
        $this->createIndex('idx-api_access-access_token', 'api_access', 'access_token', true);
        $this->createIndex('idx-api_access-password_reset_token', 'api_access', 'password_reset_token', true);
        
        /*
         * Foreign keys
         */
        $this->addForeignKey('fk-auth_item-rule_name', 'auth_item', 'rule_name', 'auth_rule', 'name', 'cascade', 'cascade');
        $this->addForeignKey('fk-auth_item_child-parent', 'auth_item_child', 'parent', 'auth_item', 'name', 'cascade', 'cascade');
        $this->addForeignKey('fk-auth_item_child-child', 'auth_item_child', 'child', 'auth_item', 'name', 'cascade', 'cascade');
        $this->addForeignKey('fk-auth_assignment-item_name', 'auth_assignment', 'item_name', 'auth_item', 'name', 'cascade', 'cascade');
        $this->addForeignKey('fk-auth_assignment-user_id', 'auth_assignment', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-admin-user_id', 'admin', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-normal_user-user_id', 'normal_user', 'user_id', 'user', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk-auth_item-rule_name', 'auth_item');
        $this->dropForeignKey('fk-auth_item_child-parent', 'auth_item_child');
        $this->dropForeignKey('fk-auth_item_child-child', 'auth_item_child');
        $this->dropForeignKey('fk-auth_assignment-item_name', 'auth_assignment');
        $this->dropForeignKey('fk-auth_assignment-user_id', 'auth_assignment');
        $this->dropForeignKey('fk-admin-user_id', 'admin');
        $this->dropForeignKey('fk-normal_user-user_id', 'normal_user');
        
        $this->dropTable('auth_rule');
        $this->dropTable('auth_item');
        $this->dropTable('auth_item_child');
        $this->dropTable('auth_assignment');
        $this->dropTable('user');
        $this->dropTable('admin');
        $this->dropTable('normal_user');
        $this->dropTable('api_access');
    }
}
