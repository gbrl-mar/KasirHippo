<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IngredientPurchase
 * 
 * @property int $id
 * @property int $ingredient_id
 * @property int $user_id
 * @property float $quantity_purchased
 * @property float $cost
 * @property Carbon $purchase_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ingredient $ingredient
 * @property User $user
 *
 * @package App\Models
 */
class IngredientPurchase extends Model
{
	protected $table = 'ingredient_purchases';

	protected $casts = [
		'ingredient_id' => 'int',
		'user_id' => 'int',
		'quantity_purchased' => 'float',
		'cost' => 'float',
		'purchase_date' => 'datetime'
	];

	protected $fillable = [
		'ingredient_id',
		'user_id',
		'quantity_purchased',
		'cost',
		'purchase_date'
	];

	public function ingredient()
	{
		return $this->belongsTo(Ingredient::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
