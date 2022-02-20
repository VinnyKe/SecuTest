<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\QuestionsImport;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class importQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    protected $filename = 'Questions - v7.xlsx';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        print('Resetting database'.PHP_EOL);
        $this->resetQuestions();
        print('Reset complete'.PHP_EOL);

        print('Import start'.PHP_EOL);
        Excel::import(new QuestionsImport, app_path().'/Imports/files/'.$this->filename);
        print('Import complete'.PHP_EOL);
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
}
