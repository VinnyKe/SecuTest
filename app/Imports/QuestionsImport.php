<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToArray;

class QuestionsImport implements ToArray
{

    // HEADERS
    protected $headers = [
        'id',
        'status',
        'Illustrat.',
        'niveau',
        'cat1',
        'cat2',
        'type1',
        'type2',
        'question',
        'bulle',
        'contenu_bulle',
        'answer1',
        'answer2',
        'answer3',
        'answer4',
        'correct1',
        'correct2',
        'correct3',
        'correct4',
        'answer_description',
    ];

    protected $extensions = 'jpeg|jpg|png|JPEG|JPG|PNG';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function array($rows) {
        $files = preg_grep('~\.('.$this->extensions.')$~', scandir('../app/Imports/images'));

        /**
         * Make array of files with
         * - key -> the file name with extension
         * - value  -> the file name without extension
         */
        $imageFiles = [];
        foreach ($files as $index => $file) {
            $image = explode('.', $file);
            $imageFiles[$file] = $image[0];
        }

        foreach ($rows as $index => $row) {
            if ($row[1] == 4) {
                $answers = array_filter(array_slice($row, $this->headerPos('answer1'), 4));
                $correctAnswers = array_slice($row, $this->headerPos('correct1'), 4);

                $question = Question::create([
                    'text' => $row[$this->headerPos('question')],
                ]);

                foreach ($answers as $index => $answer) {
                    $question->answers()->create([
                        'text' => $answer,
                        'isCorrect' => !!$correctAnswers[$index],
                    ]);
                }

                // If question has an image, attach the image to that question
                $imageName = $row[$this->headerPos('Illustrat.')];
                $image = array_search($imageName, $imageFiles);
                if (!!$image) {
                    $question
                        ->addMedia('../app/Imports/images/'.$image)
                        ->preservingOriginal()
                        ->toMediaCollection();
                }
            }
        }
    }

    private function headerPos(String $header): int
    {
        return array_search($header, $this->headers);
    }
}
