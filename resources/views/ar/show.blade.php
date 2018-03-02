@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
    <div class="ui main container">
        <!-- AR / Show -->
        <h1 class="ui left floated header">
            {{ $title }}
            <span class="sub header">Feedback for {{ $back['type'] }}: {{ $model->parent->title }}</span>
        </h1>
        <a href="/{{ $back['type'] }}/{{ $back['id'] }}/ar-models" class="ui right floated basic button">
            <i class="chevron left icon"></i>Back to AR Models
        </a>
        <div class="ui clearing divider"></div>

        <div class="ui basic segment">
            {!! nl2br(e($model['description'])) !!}
        </div>

        <h3 class="ui dividing header">Average Ratings</h3>

        <div class="ui horizontal small statistics">
@foreach ($model['questions'] as $question)
@if ($question['averageRating'] > 4)
            <div class="green statistic">
@elseif ($question['averageRating'] > 3)
            <div class="olive statistic">
@elseif ($question['averageRating'] > 2)
            <div class="yellow statistic">
@elseif ($question['averageRating'] > 1)
            <div class="orange statistic">
@elseif ($question['averageRating'] > 0)
            <div class="red statistic">
@else
            <div class="statistic">
@endif
                <div class="value">{{ number_format($question['averageRating'], 2) }}</div>
                <div class="label">{{ $question['text'] }}</div>
            </div>
@endforeach
        </div>

        <h3 class="ui dividing header">Analysis of Responses</h3>

        <table class="ui very basic celled table">
            <thead>
                <tr>
                    <th class="right aligned">Question</th>
                    <th class="center aligned">
                        <div class="ui star rating" data-rating="1" data-max-rating="5"></div>
                    </th>
                    <th class="center aligned">
                        <div class="ui star rating" data-rating="2" data-max-rating="5"></div>
                    </th>
                    <th class="center aligned">
                        <div class="ui star rating" data-rating="3" data-max-rating="5"></div>
                    </th>
                    <th class="center aligned">
                        <div class="ui star rating" data-rating="4" data-max-rating="5"></div>
                    </th>
                    <th class="center aligned">
                        <div class="ui star rating" data-rating="5" data-max-rating="5"></div>
                    </th>
                </tr>
            </thead>
            <tbody>
@foreach ($model['questions'] as $question)
                <tr>
                    <td class="right aligned">{{ $question['text'] }}</td>
@foreach ($question['detailedRating'] as $rating)
                    <td class="center aligned"><strong>{{ $rating }}</strong></td>
@endforeach
                </tr>
@endforeach
            </tbody>
        </table>

        <comments
            :comments="{{ json_encode($model['comments']) }}"
            :model="{{ json_encode($_model) }}"
        ></comments>
    </div>
@endsection
