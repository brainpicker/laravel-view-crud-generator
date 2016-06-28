<?php

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * GBarak\ViewCrudGenerator\Models\PersonSuggestion
 *
 * @property integer $id
 * @property string $person_name
 * @property string $lang_code
 * @property integer $category_id
 * @property integer $country_id
 * @property string $email
 * @property string $phone
 * @property string $website_url
 * @property string $picture_url
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \GBarak\ViewCrudGenerator\Models\User $user
 * @property-read \GBarak\ViewCrudGenerator\Models\Category $category
 * @property-read \GBarak\ViewCrudGenerator\Models\Country $country
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion wherePersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereLangCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereWebsiteUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion wherePictureUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PersonSuggestion whereDeletedAt($value)
 * @mixin \Eloquent
 */
class PersonSuggestion extends Model
{
	use SoftDeletes;

	protected $table = 'people_suggestions';

	protected $fillable = ['*'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'category_id', 'category_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country()
	{
		return $this->belongsTo(\App\Models\Country::class, 'country_id', 'country_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'user_id', 'user_id');
	}

}
