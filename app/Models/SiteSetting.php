<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SiteSetting
 * 
 * @property int $id
 * @property string $setting_name
 * @property string|null $setting_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SiteSetting extends Model
{
	protected $table = 'site_settings';

	protected $fillable = [
		'setting_name',
		'setting_value'
	];
}
