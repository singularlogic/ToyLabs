@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <div class="ui main container" id="app">
            <!-- Profile / General -->
            <div class="ui clearing">
                <h1 class="ui left floated header">{{ $title }}</h1>
                <div class="ui right floated image" style="padding-top: 8px;">
                    <div class="ui red label">
                        <i class="heart icon"></i> 13
                    </div>
                </div>
            </div>
            <div class="ui clearing divider"></div>

            <div class="ui basic segment" style="text-align: justify;">
                <img class="ui rounded large left floated image" src="http://lorempixel.com/400/200/" />
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>

            <div class="ui comments">
                <h3 class="ui dividing header">Comments (4)</h3>
                <div class="comment">
                    <a class="avatar"><img src="/images/avatar/small/matt.jpg" /></a>
                    <div class="content">
                        <a class="author">Matt</a>
                        <div class="metadata">
                            <span class="date">Today at 5:42PM</span>
                        </div>
                        <div class="text">How artistic!</div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <a class="avatar"><img src="/images/avatar/small/elliot.jpg" /></a>
                    <div class="content">
                        <a class="author">Elliot Fu</a>
                        <div class="metadata">
                            <span class="date">Yesterday at 12:30AM</span>
                        </div>
                        <div class="text">
                            <p>This has been very useful for my research. Thanks as well!</p>
                        </div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                    <div class="comments">
                        <div class="comment">
                            <a class="avatar"><img src="/images/avatar/small/jenny.jpg" /></a>
                            <div class="content">
                                <a class="author">Jenny Hess</a>
                                <div class="metadata">
                                    <span class="date">Just now</span>
                                </div>
                                <div class="text">Elliot you are always so right :)</div>
                                <div class="actions">
                                    <a class="reply">Reply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <a class="avatar"><img src="/images/avatar/small/joe.jpg" /></a>
                    <div class="content">
                        <a class="author">Joe Henderson</a>
                        <div class="metadata">
                            <span class="date">5 days ago</span>
                        </div>
                        <div class="text">Dude, this is awesome. Thanks so much</div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>

@if (Auth::check())
                <form class="ui reply form">
                    <div class="field">
                        <textarea rows="2"></textarea>
                    </div>
                    <div class="ui orange labeled right floated submit icon button">
                        <i class="icon edit"></i> Add Reply
                    </div>
                </form>
@endif
            </div>
        </div>
@endsection
