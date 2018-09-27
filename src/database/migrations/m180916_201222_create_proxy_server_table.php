<?php

use app\modules\base\db\Migration;

/**
 * Handles the creation of table `proxy_server`.
 */
class m180916_201222_create_proxy_server_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('proxy_server', [
			'id' => $this->primaryKey(),
			'ip' => $this->string()->notNull(),
			'port' => $this->string()->notNull(),
			'password' => $this->string(),
			'country' => $this->string(),
			'status' => $this->integer()->defaultValue(0),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
		], $this->tableOptions());
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('proxy_server');
	}
}
