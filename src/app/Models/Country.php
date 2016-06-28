<?php

namespace GBarak\ViewCrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GBarak\ViewCrudGenerator\Models\Country
 *
 * @property integer $country_id
 * @property integer $geoname_id
 * @property string $lang_code
 * @property string $continent_code
 * @property string $continent_name
 * @property string $country_iso_code
 * @property string $country_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereGeonameId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereLangCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereContinentCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereContinentName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereCountryIsoCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereCountryName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Country extends Model
{

    protected $table = 'countries';

	protected $fillable = [];

}
