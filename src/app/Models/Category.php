<?php

namespace GBarak\ViewCrudGenerator\Models;

/**
 * GBarak\ViewCrudGenerator\Models\Category
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $category_name
 * @property string $lang_code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCategoryName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereLangCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends TranslatableModel
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

	protected $primaryKey = 'id';

	protected $modelKey = 'category_id';

	protected $fillable = ['category_id', 'category_name', 'lang_code'];
}
