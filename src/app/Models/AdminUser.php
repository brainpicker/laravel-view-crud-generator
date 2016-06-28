<?php

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\SupportsBasicAuth;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model implements Authenticatable
{
	protected $table = 'users_admins';

	protected $fillable = [];

	/**
	 * Get the name of the unique identifier for the user.
	 *
	 * @return string
	 */
	public function getAuthIdentifierName()
	{
		return 'id';
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		$name = $this->getAuthIdentifierName();

		return $this->attributes[$name];
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->attributes['password'];
	}

	/**
	 * Get the "remember me" token value.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->attributes[$this->getRememberTokenName()];
	}

	/**
	 * Set the "remember me" token value.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->attributes[$this->getRememberTokenName()] = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

}
