<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $transaction_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price_at_transaction
 * @property float $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class TransactionDetail extends Model
{
	protected $table = 'transaction_details';

	protected $casts = [
		'transaction_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'price_at_transaction' => 'float',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'transaction_id',
		'product_id',
		'quantity',
		'price_at_transaction',
		'subtotal'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
