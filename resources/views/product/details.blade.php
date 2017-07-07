@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <!-- Product / Details -->
        <div class="ui main container">
            <h2 class="ui left floated header">{{ $title }}</h2>
            <likes class="ui right floated"
                :likes="{{ $product['likeCount'] }}"
                :id="{{ $product['id'] }}"
                liked="{{ $product['liked'] }}"
                type="product"
            ></likes>
            <div class="ui clearing divider"></div>

            <div class="ui horizontal divided list">
                <div class="item">
                    <strong>Ages</strong>: {{ $product['ages'] }}
                </div>
                <div class="item">
                    <strong>Manufacturer</strong>: {{ $product['manufacturer']['name'] }}
                </div>
                <div class="item">
                    <strong>Status</strong>: {{ ucfirst($product['status']) }}
                </div>
            </div>

            <div class="ui basic segment">
                <img class="ui large left bordered floated image" src="/images/placeholder.jpg" />

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vitae lobortis metus. Nullam consectetur lobortis odio. Curabitur aliquam convallis justo, ut luctus tellus efficitur eget. Mauris nec velit eget nisi pretium tempor et eget magna. Cras porta, quam dapibus molestie molestie, orci arcu placerat purus, sed volutpat quam augue nec odio. Fusce consectetur magna sit amet urna porta tempor. Aliquam congue porta aliquet. Proin quis lacus ac nisl porttitor cursus at nec arcu. Aliquam erat volutpat.</p>

                <p>Donec sit amet nulla ultricies, consectetur turpis eget, feugiat dolor. Aenean lacus magna, gravida eget leo vel, aliquam varius sapien. Vivamus pharetra accumsan libero quis pulvinar. Quisque posuere dignissim sapien a euismod. Integer volutpat at magna sed hendrerit. Etiam non tempor massa. Vestibulum vel quam condimentum augue bibendum tempor in dapibus leo. Maecenas dignissim finibus est a faucibus. Sed interdum, lacus at venenatis vestibulum, est felis accumsan dui, nec molestie lacus augue sed arcu. Proin non posuere magna. Duis consequat est a massa hendrerit laoreet. Vivamus cursus luctus posuere. Aliquam pulvinar turpis lacus. Aliquam aliquet turpis at velit interdum ultricies. Aliquam tristique magna eget nulla congue, eget consequat nunc eleifend.</p>

                <p>Sed maximus condimentum feugiat. Suspendisse bibendum mauris ut quam dictum, sit amet convallis nisl pellentesque. Etiam sollicitudin laoreet erat, at finibus dui tempus vel. Maecenas eu nisl lacinia, vestibulum lorem vel, tristique dolor. Pellentesque euismod turpis molestie dictum congue. Sed diam arcu, interdum eu pulvinar at, sodales a justo. Vestibulum in elementum erat. Praesent ut efficitur dui. Suspendisse porta odio sit amet sapien porta laoreet. Sed condimentum iaculis maximus. Nam porttitor nulla tristique quam porta lacinia. Aenean cursus nisi sit amet lorem varius feugiat.</p>

                <p>Quisque id mi nibh. Ut a dapibus sem. Sed tincidunt leo ac tincidunt molestie. Vivamus pharetra tincidunt nibh at venenatis. Etiam ac malesuada lectus. Quisque sagittis mollis odio quis sodales. Aliquam eget rutrum urna. Nulla pulvinar libero eget diam ultricies iaculis. Mauris feugiat placerat risus vitae consectetur. Morbi quis metus faucibus nunc auctor accumsan scelerisque vitae felis. Nunc sit amet risus sit amet lorem fermentum mattis eget ac diam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent mollis eros vel gravida lacinia. Aliquam erat volutpat.</p>

                <p>Donec quis venenatis est. Proin ut ornare ipsum. Aliquam eu eros id lorem tristique cursus. Nulla rutrum nisi at suscipit tincidunt. Vestibulum at mollis risus. Donec aliquam sapien pretium, mollis lacus nec, malesuada sem. Donec ac luctus sem. Quisque semper pharetra pulvinar. Nam in vulputate mauris. Pellentesque vehicula cursus nibh et gravida. Nulla convallis efficitur orci, at fermentum nunc. Quisque posuere suscipit ex, eu efficitur leo. Fusce id pharetra elit. Phasellus vitae mi consectetur ligula vulputate congue. Nam justo dolor, venenatis vitae convallis quis, elementum a sem.</p>
            </div>

@if(count($product['designs']))
            <h3 class="ui dividing header">Designs ({{ count($product['designs']) }})</h3>

            <products-grid
                :products="{{ json_encode($product['designs']) }}"
                :detailed=false
                size="four"
            ></products-grid>
@endif

@if(count($product['prototypes']))
            <h3 class="ui dividing header">Prototypes ({{ count($product['prototypes']) }})</h3>

            <products-grid
                :products="{{ json_encode($product['prototypes']) }}"
                :detailed=false
                size="four"
            ></products-grid>
@endif

            <h3 class="ui dividing header">Comments ({{ count($product['totalComments']) }})</h3>

            <comments
                :comments="{{ json_encode($product['comments']) }}"
            ></comments>
        </div>
@endsection
