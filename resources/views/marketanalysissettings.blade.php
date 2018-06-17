@extends('layouts.default', ['class' => ''])

@section('title', 'Edit Market Analysis Settings')

@section('content')
        <div class="ui main container">
            <h1 class="ui left floated header">Edit Market Analysis Settings</h1>
            <div class="ui clearing divider"></div>
            <market-analysis-settings
                    :_retriever="{{ json_encode($retriever) }}"
                    :_retriever_master="{{ json_encode($retriever_master) }}"
                    @if ($can_edit)
                    _can_edit
                    @endif
                    @if ($can_edit_master)
                    _can_edit_master
                    @endif
            ></market-analysis-settings>
        </div>
@endsection
