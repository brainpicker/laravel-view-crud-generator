<?php
/**
 * User: Gady Barak
 * Date: 2016-04-16
 */

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GBarak\ViewCrudGenerator\Models\TranslatableModel
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TranslatableModel lang()
 * @mixin \Eloquent
 */
class TranslatableModel extends Model
{
	protected $modelKey;

	public function getModelKey()
	{
		return $this->modelKey;
	}

	public function getExistsRule()
	{
		$personTable = $this->getTable();
		$personTableKey = $this->getKeyName();

		return "exists:{$personTable},{$personTableKey}";
	}

	public static function boot()
	{
		parent::boot();

		static::creating(function (TranslatableModel $model) {
			$model->{$model->getModelKey()} =
				\DB::select(sprintf("SELECT IFNULL(MAX(%s) + 1, 1) as next_person_id FROM %s",
					$model->getModelKey(), $model->getTable()))[0]->next_person_id;
		});
	}

	/**
	 * Scope a query to only include active users.
	 *
	 * @param $query \Illuminate\Database\Eloquent\Builder
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeLang($query)
	{
		return $query->where('lang_code', \App::getLocale());
	}
}