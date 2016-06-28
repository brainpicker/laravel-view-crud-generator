<?php

namespace GBarak\ViewCrudGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PersonSuggestion;
use Illuminate\Http\Request;

use App\Http\Requests;

class PeopleSuggestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ps = new PersonSuggestion;
        $ps->category_id = 4;
        $ps->country_id = 74;
        $ps->email = 'gadybarak@gmail.com';
        $ps->id = 1;
        $ps->lang_code = 'he';
        $ps->person_name = 'Gady Barak';
        $ps->user_id = 200;


        return view('person_suggestion.index', ['people_suggestions' => [$ps]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ps = new PersonSuggestion;
        $ps->fill($request->all());

        return response()->json($ps->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()->json(['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(['id' => $id]);
    }
}
