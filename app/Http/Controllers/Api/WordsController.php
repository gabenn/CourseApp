<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Http\Requests\WordRequest;
use App\Http\Services\WordsService;
use App\Models\Word;
use Illuminate\Http\Request;
use App\Models\Course;

class WordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WordResource::collection(Word::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WordRequest $request)
    {
        // Gdy pisałem ten kod tylko ja i Bóg 
        // wiedział co i dlaczego on robi 
        // Teraz tylko Bóg
        // Powodzenia :D
        // liczba prób: 2
        $course = $request->course_id==0 ? Course::latest()->first() : Course::find($request->course_id);       

        return $course->words()->createMany($request->words);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function show(Word $word)
    {
        return new WordResource($word);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function update(WordRequest $request, Word $word)
    {
        WordsService::updateWord($request, $word);
        
        return new WordResource($word);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function destroy(Word $word)
    {
        $word->delete();
        
        return response()->noContent();
    }
}
