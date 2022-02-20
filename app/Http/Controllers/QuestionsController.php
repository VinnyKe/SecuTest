<?php

namespace App\Http\Controllers;

use App\Imports\QuestionsImport;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;

class QuestionsController extends Controller
{
    protected $fileName = 'Questions - v6.xlsx';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Question::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // return $question;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getQuestionnaire() {
        return Question::with('answers')
            ->has('media')
            ->with('media')
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

    public function submitQuestionnaire(Request $request)
    {
        $res = 'success';
        return $res;
    }

    public function importQuestions() {
        $this->resetQuestions();
        Excel::import(new QuestionsImport, app_path().'/Imports/files/Questions - v6.xlsx');
    }

    public function resetQuestions() {
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->delete();
        }

        //Hard reset
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('questions')->truncate();
        DB::table('answers')->truncate();
        DB::table('media')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function showImage(Question $question) {
        // dd($question->hasMedia());
        if ($question->hasMedia()) {
            $media = $question->getFirstMedia();
            if (!!$media) {
                $disk = Storage::disk($media->disk);
                $urlGenerator = UrlGeneratorFactory::createForMedia($media, '');
                if ($disk->exists($urlGenerator->getPathRelativeToRoot())) {
                    return response($disk->get($urlGenerator->getPathRelativeToRoot()))->withHeaders([
                        'Content-Disposition' => 'inline',
                        'Pragma' => 'cache',
                        'Cache-Control' => 'max-age=86500',
                        'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 86500),
                        'Content-type' => $media->mime_type,
                    ]);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
    }
}
