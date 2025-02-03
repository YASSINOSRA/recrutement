@extends('layouts.app')

@section('title', 'User Scores')

@section('content')
    <div class="container">
        <h1>User Scores</h1>
        @if ($scores->isEmpty())
            <p>No scores available.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Quiz Name</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $score)
                        <tr>
                            <td>{{ $score->user_name }}</td>  <!-- Display user name -->
                            <td>{{ $score->quiz_name }}</td>  <!-- Display quiz name -->
                            <td>{{ $score->score }}</td>      <!-- Display individual score -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
