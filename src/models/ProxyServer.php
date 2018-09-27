<?php

namespace shintio\yii2\proxy\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "proxy_server".
 *
 * @property int $id
 * @property string $ip
 * @property string $port
 * @property string $password
 * @property string $country
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class ProxyServer extends \yii\db\ActiveRecord implements ProxyServerInterface
{
	use ProxyServerTrait;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return ['timestamp' => TimestampBehavior::class];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{proxy_server}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[
				[
					'ip',
					'port'
				],
				'required'
			],
			[
				[
					'status',
					'created_at',
					'updated_at'
				],
				'integer'
			],
			[
				[
					'ip',
					'port',
					'password',
					'country'
				],
				'string',
				'max' => 255
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'ip' => Yii::t('app', 'Ip'),
			'port' => Yii::t('app', 'Port'),
			'password' => Yii::t('app', 'Password'),
			'country' => Yii::t('app', 'Country'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}
