<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizManager extends Component
{
    public $quizzes, $currentQuiz, $questions = [], $responses = [];

    public function mount()
    {
        $this->quizzes = Quiz::all();
    }

    public function loadQuiz($quizId)
    {
        $this->currentQuiz = Quiz::with('questions.answers')->find($quizId);
        $this->questions = $this->currentQuiz->questions;
        $this->responses = array_fill(0, count($this->questions), null);
    }

    public function submit()
    {
        $score = 0;

        foreach ($this->questions as $index => $question) {
            if ($question->type === 'multiple_choice' || $question->type === 'checkboxes') {
                $correctAnswers = $question->answers->where('is_correct', true)->pluck('id')->toArray();
                $userAnswers = $this->responses[$index];

                if (is_array($userAnswers) && !array_diff($correctAnswers, $userAnswers)) {
                    $score++;
                }
            }
        }

        auth()->user()->responses()->create([
            'quiz_id' => $this->currentQuiz->id,
            'responses' => json_encode($this->responses),
            'score' => $score,
        ]);

        $this->emit('quizSubmitted', $score);
    }

    public function render()
    {
        return view('livewire.quiz-manager');
    }

    public function generateLink($quizId)
    {
        return route('quizzes.show', ['id' => $quizId]);
    }
}
