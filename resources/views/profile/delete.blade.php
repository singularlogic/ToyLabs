@extends('layouts.default', ['class' => ''])

@section('title', 'Delete Profile?')

@section('content')
        <div class="ui main container">
            <!-- Profile / General -->
            <h1 class="ui header">Delete ToyLabs Profile</h1>
            <div class="ui divider"></div>

            <p>To delete your profile, your personal work (products, designs, prototypes) must not be publicly available. If you have an organization, then you need to either let us know who will take over the organization ownership (they will be contacted to verify this), or select to delete your organisation.</p>

            <p>If you decide to delete your organisation, then any work done under the organisation should be made private as well, and the organisation should have no active collaborations (we might contact your recent collaborators to verify that you have no pending obligations).</p>

            <p>A quick check on your profile revealed that:</p>

            <div class="ui list" style="margin-left: 20px;">
                <div class="item">
@if($public_products == 0)
                    <i class="green check icon"></i>
                    <div class="content">
                        You have no public products
                    </div>
@else
                    <i class="red remove icon"></i>
                    <div class="content">
                        You have <strong>{{ $public_products }}</strong> public product(s)
                    </div>
@endif
                </div>

                <div class="item">
@if($public_designs == 0)
                    <i class="green check icon"></i>
                    <div class="content">
                        You have no public designs
                    </div>
@else
                    <i class="red remove icon"></i>
                    <div class="content">
                        You have <strong>{{ $public_designs }}</strong> public design(s)
                    </div>
@endif
                </div>

                <div class="item">
@if($public_prototypes == 0)
                    <i class="green check icon"></i>
                    <div class="content">
                        You have no public prototypes
                    </div>
@else
                    <i class="red remove icon"></i>
                    <div class="content">
                        You have <strong>{{ $public_prototypes }}</strong> public prototype(s)
                    </div>
@endif
                </div>

                <div class="item">
@if(count($organizations) == 0)
                    <i class="green check icon"></i>
                    <div class="content">
                        You are not the owner of any organization.
                    </div>
@else
                    <i class="red remove icon"></i>
                    <div class="content">
                        You are the owner of the following organizations:
                        <div class="ui list" style="padding-left: 10px;">
                            @foreach($organizations as $org)
                                <i class="exclamation icon"></i> {{ $org->name }}
                            @endforeach
                        </div>
                    </div>
@endif
                </div>
            </div>

@if($hasErrors)
            <p>Please fix the issues above, and try again. To transfer the ownership or delete organization(s), please contact the administrators at: {{ Html::mailto('admin@toylabs.eu') }}.</p>
@else
            <p>By deleting your profile, you are logged out of the platform and your account is suspended. ToyLabs administrators will verify that you have no unfinished tasks regarding other members of ToyLabs before <strong>permanently</strong> deleting your profile.</p>

            <div class="ui divider"></div>

            <delete-account />
@endif
        </div>
@endsection
