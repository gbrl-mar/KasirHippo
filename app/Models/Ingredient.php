<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 * 
 * @property int $id
 * @property string $name
 * @property string $unit
 * @property float $current_stock
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|IngredientPurchase[] $ingredient_purchases
 *
 * @package App\Models
 */
class Ingredient extends Model
{
	protected $table = 'ingredients';

	protected $casts = [
		'current_stock' => 'float'
	];

	protected $fillable = [
		'name',
		'unit',
		'current_stock'
	];

	public function ingredient_purchases()
	{
		return $this->hasMany(IngredientPurchase::class);
	}
}
