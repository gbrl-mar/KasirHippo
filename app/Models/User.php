<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use \Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id_user
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Role $role
 * @property Collection|IngredientPurchase[] $ingredient_purchases
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	 use HasApiTokens, Notifiable;
	protected $table = 'users';
	protected $primaryKey = 'id_user';

	protected $casts = [
		'role_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'role_id',
		'name',
		'email',
		'password'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function ingredient_purchases()
	{
		return $this->hasMany(IngredientPurchase::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
