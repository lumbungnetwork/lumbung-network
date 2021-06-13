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
            @if ($user->id != $node1->id)
            <a href="{{ route('member.network.sponsorTree') }}">
                <button
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-red-300 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Back</button>
            </a>
            <a href="{{ route('member.network.sponsorTree') }}?user_id={{$node1->sponsor_id}}">
                <button
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-blue-300 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Back
                    1 Level Up</button>
            </a>
            @endif
        </div>

        {{-- sponsor tree --}}
        <div class="mt-4 p-1 object-contain">
            <div id="block-system-main" class="block block-system clearfix">
                <div class="binary-genealogy-tree binary_tree_extended">
                    <div class="sponsor-tree-wrapper">
                        <div class="eps-sponsor-tree eps-tree" style="max-width: 270px;">
                            <ul>
                                <li>
                                    <div class="eps-nc" nid="12">
                                        <div class="user-pic">
                                            <div class="images_wrapper" style="font-size: 40px;margin: 10px 0;">
                                                <a href="#" class="object-contain flex justify-center -mb-2">
                                                    <div>
                                                        @include('member.components.user-icon-svg')
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="user-name">
                                            {{$node1->username}}
                                        </div>
                                        <div class="user-name" style="background-color: #e0dfdf;color: #444;">
                                            Sponsoring: {{$node1->total_sponsor}}
                                        </div>
                                    </div>
                                    @if(count($directs) > 0)
                                    <ul>
                                        @foreach($directs as $direct)
                                        <li>
                                            <div class="eps-nc" nid="13">
                                                <div class="user-pic">
                                                    <div class="images_wrapper text-success"
                                                        style="font-size: 40px;margin: 10px 0;">
                                                        <a href="{{ route('member.network.sponsorTree') }}?user_id={{$direct->id}}"
                                                            class="object-contain flex justify-center -mb-2">
                                                            <div>
                                                                @include('member.components.user-icon-svg')
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="user-name">
                                                    {{$direct->username}}
                                                </div>
                                                <div class="user-name" style="background-color: #e0dfdf;color: #444;">
                                                    Sponsoring: {{$direct->total_sponsor}}
                                                </div>

                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            </ul>
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
<link href="{{ asset('css/sponsor-tree.css') }}" rel="stylesheet" />
<link href="{{ asset('css/bin-developer.css') }}" rel="stylesheet" />
@endsection

@section('scripts')
<script>
    // Check/Search/Jump to downline's node using username
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