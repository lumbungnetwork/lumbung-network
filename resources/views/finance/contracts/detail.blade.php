@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-50 rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="flex float-right mr-6 text-xl text-red-500">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="mt-5"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </form>

            </div>

            <div class="flex items-center justify-start pt-6 pl-6">
                <a href="{{ route('finance.contracts') }}">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Contracts</span>
                </a>

            </div>

            <div class="px-4 sm:px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-3xl sm:text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <small class="float-right font-extralight">#{{sprintf('%07s', $contract->id)}}</small>
                    <div class="flex items-center py-2">
                        @if ($contract->status == 0)
                        <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Processing</p>
                        @elseif ($contract->status == 1)
                        <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Active</p>
                        @endif

                    </div>
                    @php
                    // Get days of progress and progress bar percentage
                    $diff = time() - strtotime($contract->created_at);
                    $days = floor($diff / (60*60*24));
                    $progress = 3;
                    $strategy = 'Leveraged Stable Lending (Perpetual)';

                    if ($contract->strategy == 2) {
                    $strategy = 'Liquidity Yield Farming (365 days staging)';

                    $progress = round(($days / 365) * 100, 2);

                    if ($progress < 3) { $progress=3; } } @endphp <div class="flex items-end justify-between py-2">
                        <p class="text-gray-600">Day {{$days}}</p>
                        <p class="text-xl font-extralight">
                            ${{number_format($contract->principal + $contract->compounded, 2)}}
                        </p>
                </div>


                <div class="h-3 relative max-w-xl rounded-full overflow-hidden">
                    <div class="w-full h-full nm-inset-gray-50 absolute"></div>
                    <div class="h-full bg-green-500 absolute" style="width:{{$progress}}%"></div>
                </div>

                <div class="py-2">
                    <p class=" text-gray-600 text-md font-light">Strategy: </p>
                    <span class="font-extralight text-sm text-gray-800">{{$strategy}}</span>
                </div>
                @if ($contract->strategy == 2 && $contract->grade > 0)
                @php
                switch($contract->grade) {
                case 1:
                $grade = "Grade C";
                break;
                case 2:
                $grade = "Grade B";
                break;
                case 3:
                $grade = "Grade A";
                break;
                case 4:
                $grade = "Grade S";
                break;

                }

                @endphp
                <div class="py-2">
                    <p class=" text-gray-600 text-md font-light">{{$grade}}</p>
                </div>
                @endif
                <div class="py-2">
                    <div class="space-x-0">
                        <p class=" text-gray-600 text-md font-light inline-block">Principal: </p>
                        <span
                            class="font-extralight text-sm text-gray-800 inline-block">${{number_format($contract->principal, 2)}}</span>
                    </div>
                    <div class="space-x-0">
                        <p class=" text-gray-600 text-md font-light inline-block">Compounded: </p>
                        <span
                            class="font-extralight text-sm text-gray-800 inline-block">${{number_format($contract->compounded, 2)}}</span>
                    </div>


                </div>

            </div>

            <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 text-center">
                <p>Yield Available</p>
                <h2 class="mt-3 text-black text-6xl font-extralight">${{number_format($yield->net, 2)}}</h2>
                <div class="flex justify-between">
                    <button type="button" id="compound-btn"
                        class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Compound</button>
                    <button type="button" id="withdraw-btn"
                        class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Withdraw</button>
                    <form id="compound-form"
                        action="{{ route('finance.contracts.post.compound', ['contract_id' => $contract->id]) }}"
                        method="POST">
                        @csrf

                    </form>
                    <form id="withdraw-form"
                        action="{{ route('finance.contracts.post.withdraw', ['contract_id' => $contract->id]) }}"
                        method="POST">
                        @csrf

                    </form>
                </div>

                <a href="#" class="underline mt-5 text-md font-light block">History</a>


            </div>

            <div class="mt-4 p-2 flex justify-center">
                <button
                    class="p-3 w-32 rounded-xl bg-red-800 text-white focus:outline-none focus:bg-red-800 text-xs">Break
                    Contract</button>
            </div>

            <div class="mt-4 nm-inset-gray-200 rounded-2xl p-4">
                @if ($contract->strategy == 1)
                <p class="text-xs font-light text-gray-600">All actions are subject to <b>4% fee</b>. <br> Breaking the
                    contract of
                    <b>Leveraged Stable Lending Strategy</b> will remove the principal and compounded amount to
                    <b>Availabe Yield</b> (within 48
                    hours), then it would be available for withdraw action.</p>
                @elseif ($contract->strategy == 2)
                <p class="text-xs font-light text-gray-600">All actions are subject to <b>4% fee</b>. <br> Breaking the
                    contract of
                    <b>Liquidity
                        Yield Farming Strategy</b> will remove the principal and compounded amount to <b>Availabe
                        Yield</b> (within 7 days), then it would be available for withdraw action.</p>
                @endif

            </div>


        </div>

    </div>
</div>


</div>


@endsection


@section('scripts')
<script>
    const _yield = {{$yield->net}};

       $('#compound-btn').click( function() {
            if (_yield < 1) {
                errorToast('Insufficient Yield for this action!');
                return false;
            }
            Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to compound this yield to your contract?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, compound!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Compounding...');
                        Swal.showLoading();
                        $('#compound-form').submit();
                    }
                })
       }) 

       $('#withdraw-btn').click( function() {
            if (_yield < 1) {
                errorToast('Insufficient Yield for this action!');
                return false;
            }
            Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to withdraw this yield to your credit balance?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Withdraw!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Withdrawing...');
                        Swal.showLoading();
                        $('#withdraw-form').submit();
                    }
                })
       }) 

       // Toast
    const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            width: 200,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function errorToast (message) {
            Toast.fire({
                icon: 'error',
                title: message
            })
        }
</script>
@endsection