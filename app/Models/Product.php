<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string|null $image_url
 * @property bool $is_available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category $category
 * @property Collection|TransactionDetail[] $transaction_details
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'category_id' => 'int',
		'price' => 'float',
		'is_available' => 'bool'
	];

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price',
		'image_url',
		'is_available'
	];

	public function category()
{
    return $this->belongsTo(Category::class, 'category_id', 'id_categories');
}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}
}
