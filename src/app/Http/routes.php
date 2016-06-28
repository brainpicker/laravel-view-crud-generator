<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use \GBarak\ViewCrudGenerator\Models\PersonSuggestion;

Route::get('/gbarak/vcm/test', function() {
    $ps = new PersonSuggestion();
    $ps->category_id = 4;
    $ps->country_id = 74;
    $ps->email = 'gadybarak@gmail.com';
    $ps->id = 1;
    $ps->lang_code = 'he';
    $ps->person_name = 'Gady Barak';
    $ps->user_id = 200;


    return view('person_suggestion.index', ['people_suggestions' => [$ps]]);
});

