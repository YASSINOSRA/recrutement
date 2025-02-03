<div>
    <h1>Quizzes</h1>
    <ul>
        @foreach($quizzes as $quiz)
            <li wire:click="loadQuiz({{ $quiz->id }})">{{ $quiz->title }}</li>
        @endforeach
    </ul>

    @if($currentQuiz)
        <h2>{{ $currentQuiz->title }}</h2>
        <form wire:submit.prevent="submit">
            @foreach($questions as $index => $question)
                <div>
                    <p>{{ $question->question }}</p>
                    @if($question->type === 'multiple_choice')
                        @foreach($question->answers as $answer)
                            <label>
                                <input type="radio" wire:model="responses.{{ $index }}" value="{{ $answer->id }}">
                                {{ $answer->answer_text }}
                            </label>
                        @endforeach
                    @elseif($question->type === 'checkboxes')
                        @foreach($question->answers as $answer)
                            <label>
                                <input type="checkbox" wire:model="responses.{{ $index }}" value="{{ $answer->id }}">
                                {{ $answer->answer_text }}
                            </label>
                        @endforeach
                    @elseif($question->type === 'paragraph')
                        <textarea wire:model="responses.{{ $index }}"></textarea>
                    @elseif($question->type === 'file_upload')
                        <input type="file" wire:model="responses.{{ $index }}">
                    @endif
                </div>
            @endforeach
            <button type="submit">Submit</button>
        </form>
    @endif
</div>
