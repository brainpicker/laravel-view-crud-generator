<?php

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GBarak\ViewCrudGenerator\Models\FacebookUser
 *
 * @property integer $facebook_user_id
 * @property string $access_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \GBarak\ViewCrudGenerator\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\FacebookUser whereFacebookUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\FacebookUser whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\FacebookUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\FacebookUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookUser extends Model
{
    public $table = 'users_facebook';

	public $primaryKey = 'facebook_user_id';

	protected $fillable = ['facebook_user_id', 'access_token'];

	public $incrementing = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'facebook_user_id', 'facebook_user_id');
	}
}
