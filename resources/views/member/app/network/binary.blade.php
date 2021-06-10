@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        {{-- search downline by username --}}
        <div class="nm-flat-gray-200 rounded-2xl p-3">
            <div class="flex justify-end">
                <button id="open-search-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Cek
                    posisi downline</button>
            </div>
            <div id="search-div" class="hidden">
                <div class="my-2 text-sm text-gray-600">Cari downline (by username):</div>
                <div id="instruction" style="font-size: 10px" class="text-gray-400 font-light">Ketikkan
                    3-4 huruf awal
                    username pembeli, akan tampil list username, silakan klik username yang diinginkan.
                </div>
                <div class="mt-2 nm-inset-gray-200 p-2 rounded-lg">
                    <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" id="get_id" name="username"
                        placeholder="cari username..." autocomplete="off">
                </div>
                <div class="px-2">
                    <form action="" method="get">
                        @csrf
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="mt-3 flex justify-end">
                            <button
                                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Cari</button>
                        </div>
                    </form>

                    <ul class="text-sm font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                        id="get_id-box"></ul>
                </div>
            </div>

        </div>

        <div class="mt-4 p-1 flex justify-start space-x-1">
            @if ($back)
            <a href="{{ route('member.network.binaryTree') }}">
                <button
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-red-300 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Back</button>
            </a>
            @endif
            @if ($user->id != $binary[0]->id)
            <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[0]->upline_id}}">
                <button
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-blue-300 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Back
                    1 Level Up</button>
            </a>
            @endif
        </div>

        {{-- binary tree --}}
        <div class="mt-4 p-1 object-contain">
            <div class="block clearfix">
                <div class="binary-genealogy-tree binary_tree_extended">
                    <div class="binary-genealogy-level-0 clearfix">
                        <div class="no_padding parent-wrapper clearfix">
                            <div class="node-centere-item binary-level-width-100">
                                <div class="node-item-root">
                                    <div class="binary-node-single-item user-0">
                                        <div class="images_wrapper" style="font-size: 40px;margin: 11px 0;">
                                            <a href="#" class="object-contain flex justify-center -mb-2">
                                                <div>
                                                    @include('member.components.user-icon-svg')
                                                </div>
                                            </a>
                                        </div>
                                        <div class="wrap_content">
                                            {{$binary[0]->username}}</div>

                                    </div>
                                </div>
                                <div class="parent-wrapper clearfix">
                                    <div class="node-left-item binary-level-width-50">
                                        @php
                                        $root = '';
                                        if($binary[1]){
                                        $root = 'node-item-root';
                                        }
                                        @endphp
                                        <div class="{{$root}}">
                                            <span
                                                class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                            <div class="node-item-1-child-left">
                                                @if($binary[1])
                                                <div class="binary-node-single-item user-block user-9">
                                                    <div class="images_wrapper" style="font-size: 40px;margin: 11px 0;">
                                                        <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[1]->id}}"
                                                            class="object-contain flex justify-center -mb-2">
                                                            <div>
                                                                @include('member.components.user-icon-svg')
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <span class="wrap_content ">{{$binary[1]->username}}</span>

                                                </div>
                                                @else
                                                <div class="binary-node-single-item user-block user-13">

                                                </div>

                                                @endif
                                            </div>
                                        </div>
                                        @if($binary[1])
                                        <div class="parent-wrapper clearfix">
                                            <div class="node-left-item binary-level-width-50">
                                                <span
                                                    class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                                <div class="node-item-1-child-left">
                                                    @if($binary[3])
                                                    <div class="binary-node-single-item user-block user-9">
                                                        <div class="images_wrapper"
                                                            style="font-size: 40px;margin: 11px 0;">
                                                            <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[3]->id}}"
                                                                class="object-contain flex justify-center -mb-2">
                                                                <div>
                                                                    @include('member.components.user-icon-svg')
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <span class="wrap_content ">{{$binary[3]->username}}</span>

                                                    </div>
                                                    @else
                                                    <div class="binary-node-single-item user-block user-13">

                                                    </div>
                                                    <div class="last_level_user"></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="node-right-item binary-level-width-50">
                                                <span
                                                    class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                                <div class="node-item-1-child-right">
                                                    @if($binary[4])
                                                    <div class="binary-node-single-item user-block user-10">
                                                        <div class="images_wrapper"
                                                            style="font-size: 40px;margin: 11px 0;">
                                                            <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[4]->id}}"
                                                                class="object-contain flex justify-center -mb-2">
                                                                <div>
                                                                    @include('member.components.user-icon-svg')
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <span class="wrap_content ">{{$binary[4]->username}}</span>

                                                    </div>
                                                    @else
                                                    <div class="binary-node-single-item user-block user-13">

                                                    </div>
                                                    <div class="last_level_user"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="node-right-item binary-level-width-50">
                                        @php
                                        $root1 = '';
                                        if($binary[2]){
                                        $root1 = 'node-item-root';
                                        }
                                        @endphp
                                        <div class="{{$root1}}">
                                            <span
                                                class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                            <div class="node-item-1-child-right">
                                                @if($binary[2])
                                                <div class="binary-node-single-item user-block user-10">
                                                    <div class="images_wrapper" style="font-size: 40px;margin: 11px 0;">
                                                        <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[2]->id}}"
                                                            class="object-contain flex justify-center -mb-2">
                                                            <div>
                                                                @include('member.components.user-icon-svg')
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <span class="wrap_content ">{{$binary[2]->username}}</span>

                                                </div>
                                                @else
                                                <div class="binary-node-single-item user-block user-13">

                                                </div>

                                                @endif
                                            </div>
                                        </div>
                                        @if($binary[2])
                                        <div class="parent-wrapper clearfix">
                                            <div class="node-left-item binary-level-width-50">
                                                <span
                                                    class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                                <div class="node-item-1-child-left">
                                                    @if($binary[5])
                                                    <div class="binary-node-single-item user-block user-9">
                                                        <div class="images_wrapper"
                                                            style="font-size: 40px;margin: 11px 0;">
                                                            <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[5]->id}}"
                                                                class="object-contain flex justify-center -mb-2">
                                                                <div>
                                                                    @include('member.components.user-icon-svg')
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <span class="wrap_content ">{{$binary[5]->username}}</span>

                                                    </div>
                                                    @else
                                                    <div class="binary-node-single-item user-block user-13">

                                                    </div>
                                                    <div class="last_level_user"></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="node-right-item binary-level-width-50">
                                                <span
                                                    class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                                <div class="node-item-1-child-right">
                                                    @if($binary[6])
                                                    <div class="binary-node-single-item user-block user-10">
                                                        <div class="images_wrapper"
                                                            style="font-size: 40px;margin: 11px 0;">
                                                            <a href="{{ route('member.network.binaryTree') }}?user_id={{$binary[6]->id}}"
                                                                class="object-contain flex justify-center -mb-2">
                                                                <div>
                                                                    @include('member.components.user-icon-svg')
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <span class="wrap_content ">{{$binary[6]->username}}</span>

                                                    </div>
                                                    @else
                                                    <div class="binary-node-single-item user-block user-13">

                                                    </div>
                                                    <div class="last_level_user"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<link href="{{ asset('css/binary.css') }}" rel="stylesheet" />
<link href="{{ asset('css/bin-developer.css') }}" rel="stylesheet" />
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $("#get_id").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.network.getDownlineUsername') }}" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").removeClass('hidden');
                    $("#get_id-box").html(data);
                }
            });
        });
    });

    function selectUsername(val) {
        var valNew = val.split("___");
        $("#get_id").val(valNew[1]);
        $("#user_id").val(valNew[0]);
        $("#get_id-box").hide();
        $('#instruction').remove();
    }

    $('#open-search-btn').click( function () {
        $('#search-div').removeClass('hidden');
        $(this).remove();
    })
</script>
@endsection