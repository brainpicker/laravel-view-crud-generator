<?php

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * GBarak\ViewCrudGenerator\Models\User
 *
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property integer $facebook_user_id
 * @property boolean $verified
 * @property boolean $is_admin
 * @property string $token
 * @property string $activation_code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \GBarak\ViewCrudGenerator\Models\FacebookUser $facebookUser
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFacebookUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = ['name', 'email', 'facebook_user_id', 'verified'];

    protected $hidden = ['remember_token'];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->token = str_random(30);
        });
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function facebookUser()
	{
		return $this->hasOne(FacebookUser::class, 'facebook_user_id', 'facebook_user_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function admin()
	{
		return $this->hasOne(AdminUser::class, 'user_id', 'user_id');
	}

    /**
     * Confirm the user.
     *
     * @return void
     */
    public function confirmEmail()
    {
        $this->verified = true;
        $this->activation_code = strtoupper(str_random(6));
        $this->save();
    }

	public function validate(array $data)
	{
		$v = \Validator::make($data, [
			'name'			   => 'required|string|max:255',
			'email'            => 'required|string|max:255|email',
			'facebook_user_id' => 'integer|unique:users,facebook_user_id',
		]);

		$v->sometimes('email', 'unique:users,email', function ($input) {
			return empty($input->facebook_user_id);
		});

		if ($v->fails()) {
			return response()->json($v->messages(), 424);
		}

		return true;
	}
}
