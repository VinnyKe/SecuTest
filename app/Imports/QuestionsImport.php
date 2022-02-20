<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToArray;

class QuestionsImport implements ToArray
{

    // HEADERS
    protected $headers = [
        '#',
        'status',
        'Illustrat.',
        'Niveeau',
        'category1',
        'category2',
        'typ1',
        'typ2',
        'question',
        'Bulle',
        'Contenu bulle',
        '1',
        '2',
        '3',
        '4',
        'correct 1',
        'correct 2',
        'correct 3',
        'correct 4',
        'answer description',
    ];

    protected $extensions = 'jpeg|jpg|png|JPEG|JPG|PNG';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function array($rows) {
        $files = preg_grep('~\.('.$this->extensions.')$~', scandir(app_path().'/Imports/images'));
        $questionsCount = 0;

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
                $questionsCount++;
                $answers = array_filter(array_slice($row, $this->headerPos('1'), 4));
                $correctAnswers = array_slice($row, $this->headerPos('correct 1'), 4);

                $group = explode('.', $row[$this->headerPos('#')])[0];

                if ($index > 40) break;
                $question = Question::create([
                    'text' => $row[$this->headerPos('question')],
                    'group' => $group,
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
                        ->addMedia(app_path().'/Imports/images/'.$image)
                        ->preservingOriginal()
                        ->toMediaCollection();
                }
            }
        }
        print('Successfully imported '.$questionsCount.' questions'.PHP_EOL);
    }

    /**
     * Get the specified header's position
     *
     * @param String $header
     *
     * @return int The header position in the header array
     */
    private function headerPos(String $header): int
    {
        return array_search($header, $this->headers);
    }
}
