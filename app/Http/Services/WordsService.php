<?php
namespace App\Http\Services;

use App\Models\Word;

class WordsService{
    public static function getWords(){
      return Word::all();
    }

    public static function getWordById($word_id){
      return Word::find($word_id);
    }

    public static function getPaginatedWords($records){
      return Word::paginate($records);
    }

    public static function getWordsFromCourse($course_id){
      return Word::where('course_id',$course_id)->get();
    }
}
?>