@if (session('error'))
            <div class="ui error message">
                <i class="close icon"></i>
                <div class="content">
                    <p>{!! session('error') !!}</p>
                </div>
            </div>
@endif
@if (session('warning'))
            <div class="ui warning message">
                <i class="close icon"></i>
                <div class="content">
                    <p>{!! session('warning') !!}</p>
                </div>
            </div>
@endif
@if (session('info'))
            <div class="ui info message">
                <i class="close icon"></i>
                <div class="content">
                    <p>{!! session('info') !!}</p>
                </div>
            </div>
@endif
@if (session('success'))
            <div class="ui success message">
                <i class="close icon"></i>
                <div class="content">
                    <p>{!! session('success') !!}</p>
                </div>
            </div>
@endif
