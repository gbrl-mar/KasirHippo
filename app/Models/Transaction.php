<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $user_id
 * @property string $transaction_code
 * @property string|null $customer_name
 * @property float $total_price
 * @property string $payment_method
 * @property float|null $amount_paid
 * @property float|null $change_due
 * @property string $payment_status
 * @property string|null $payment_gateway_id
 * @property string|null $payment_response
 * @property Carbon $transaction_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|TransactionDetail[] $transaction_details
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'user_id' => 'int',
		'total_price' => 'float',
		'amount_paid' => 'float',
		'change_due' => 'float',
		'transaction_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'transaction_code',
		'customer_name',
		'total_price',
		'payment_method',
		'amount_paid',
		'change_due',
		'payment_status',
		'payment_gateway_id',
		'payment_response',
		'transaction_date'
	];

	public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}
	public function adjustments()
{
    return $this->hasMany(TransactionAdjustment::class);
}
}
