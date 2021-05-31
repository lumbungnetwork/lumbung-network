@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="px-2 my-1">
        {{-- Order Info --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl p-4">


            <div class="text-xs font-extralight text-gray-500">ID: {{sprintf('%06s', $data->id)}}</div>
            <div class="text-xs font-extralight text-gray-500">Date:
                {{ date('d-M-y', strtotime($data->created_at)) }}</div>


        </div>

        @php
        $total_amount = $data->amount + $data->unique_digits;
        @endphp

        <div class="mt-4 nm-inset-gray-100 rounded-2xl p-2 overflow-x-scroll">
            <div class="p-2">
                <div class="">
                    <div class="text-sm text-gray-800">
                        Beli Deposit: {{ number_format($data->amount, 0) }} eIDR
                    </div>
                    <div class="text-xs text-gray-500 font-light">
                        Kode unik: {{ $data->unique_digits }}
                    </div>
                    <div class="text-xs text-gray-500 font-light">
                        @if ($data->method == 1)
                        via Transfer Bank
                        @else
                        via TRON
                        @endif
                    </div>

                </div>
                <hr class="my-2 border-b-0 border-gray-400">
                <div class="text-sm font-medium text-right">Total:
                    <b>Rp{{number_format($total_amount, 0)}}</b></div>
            </div>

        </div>

        @if ($data->status == 0)
        {{-- Waiting for Buyer Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Proses Pembayaran</h4>
            <p class="text-xs font-light text-gray-600">Silakan pilih metode pembayaran anda:</p>
        </div>
        {{-- Payment Options --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <h4 class="mt-2 text-md font-light text-gray-600">Pilih pembayaran:</h4>
            <div class="mt-2 shadow-md rounded-2xl">
                <div onclick="setPayment(1)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-one" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600"
                        for="tab-single-one">BCA</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <div class="flex justify-start items-center">
                            <p class="p-3 text-sm font-medium" id="bca">3831784502</p>
                            <div><button onclick="copy('bca')" style="font-size: 9px"
                                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">copy</button>
                            </div>
                        </div>
                        <p class="p-3 text-xs font-light text-gray-500">a/n LUMBUNG MOMENTUM BANGSA, PT</p>

                    </div>
                </div>
                <div onclick="setPayment(2)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-two" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600"
                        for="tab-single-two">BRI</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <div class="flex justify-start items-center">
                            <p class="p-3 text-sm font-medium" id="bri">033601001791568</p>
                            <div><button onclick="copy('bri')" style="font-size: 9px"
                                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">copy</button>
                            </div>
                        </div>
                        <p class="p-3 text-xs font-light text-gray-500">a/n PT LUMBUNG MOMENTUM BANGSA</p>
                    </div>
                </div>
                <div onclick="setPayment(3)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-three" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600"
                        for="tab-single-three">Mandiri</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <div class="flex justify-start items-center">
                            <p class="p-3 text-sm font-medium" id="mandiri">1060013300309</p>
                            <div><button onclick="copy('mandiri')" style="font-size: 9px"
                                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">copy</button>
                            </div>
                        </div>
                        <p class="p-3 text-xs font-light text-gray-500">a/n PT LUMBUNG MOMENTUM BANGSA</p>
                    </div>
                </div>

            </div>
        </div>
        @endif

        @if ($data->status == 1)
        {{-- Waiting for Seller Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Sedang dalam Proses...</h4>
            <div class="flex justify-center">
                @include('member.components.ellipsis_spinner')
            </div>
            <p class="text-xs font-light text-gray-600">Transaksi sedang diverifikasi, Deposit akan masuk beberapa saat
                lagi.
            </p>
        </div>
        @endif

        @if ($data->status == 3)
        {{-- Canceled --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-red-600">Batal</h4>
        </div>
        @endif

        @if ($data->status == 2)
        {{-- Done --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-green-600">Tuntas</h4>
            @php
            $payment = 'via Bank ' . $data->tx_id;
            if ($data->buy_metode == 2) {
            $payment = 'via TRON . tx_id: ' . $data->tx_id;
            }
            @endphp
            <p class="text-xs font-light text-gray-600">{{ $payment }}</p>
        </div>

    </div>
    @endif


    @if ($data->status == 0)
    {{-- Payment Summary --}}
    <div class="mt-4 nm-convex-gray-100 rounded-2xl px-4 py-2">
        <div class="mt-3 space-y-1">
            <div class="text-xs text-gray-500 font-medium">
                Nominal yang harus ditransfer:
            </div>
            <div class="text-lg text-gray-700 font-extralight">
                Rp{{number_format($total_amount)}}
            </div>
            <div class="text-xs text-red-600 font-light">Pastikan melakukan transfer dengan nominal TEPAT HINGGA DIGIT
                TERAKHIR. <span><img src="/image/red-dot-pulse.gif" alt="red dot pulse"></span></div>
        </div>


    </div>

    {{-- Action Buttons --}}
    <div class="mt-3 flex justify-end space-x-1 items-center">
        <button id="confirm-btn"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Saya
            sudah transfer</button>
        <button onclick="cancel()" id="cancel-btn"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batalkan</button>
    </div>

    {{-- confirm form --}}
    <form action="{{ route('member.postDepositPayment') }}" method="POST" id="confirm-form">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $data->id }}">
        <input type="hidden" name="bank" id="bank">
    </form>
    @endif

</div>



</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<style>
    /* Tab content - closed */
    .tab-content {
        max-height: 0;
        -webkit-transition: max-height .35s;
        -o-transition: max-height .35s;
        transition: max-height .35s;
    }

    /* :checked - resize to full height */
    .tab input:checked~.tab-content {
        max-height: 100vh;
    }

    /* Label formatting when open */
    .tab input:checked+label {
        /*@apply text-xl p-5 border-l-2 border-indigo-500 bg-gray-100 text-indigo*/
        font-size: 1.25rem;
        /*.text-xl*/
        padding: 1.25rem;
        /*.p-5*/
        border-left-width: 2px;
        /*.border-l-2*/
        border-color: #6574cd;
        /*.border-indigo*/
        background-color: #f8fafc;
        /*.bg-gray-100 */
        color: #6574cd;
        /*.text-indigo*/
    }

    /* Icon */
    .tab label::after {
        float: right;
        right: 0;
        top: 0;
        display: block;
        width: 1.5em;
        height: 1.5em;
        line-height: 1.5;
        font-size: 1.25rem;
        text-align: center;
        -webkit-transition: all .35s;
        -o-transition: all .35s;
        transition: all .35s;
    }

    /* Icon formatting - closed */
    .tab input[type=checkbox]+label::after {
        content: "+";
        font-weight: bold;
        /*.font-bold*/
        border-width: 1px;
        /*.border*/
        border-radius: 9999px;
        /*.rounded-full */
        border-color: #b8c2cc;
        /*.border-grey*/
    }

    .tab input[type=radio]+label::after {
        content: "\25BE";
        font-weight: bold;
        /*.font-bold*/
        border-width: 1px;
        /*.border*/
        border-radius: 9999px;
        /*.rounded-full */
        border-color: #b8c2cc;
        /*.border-grey*/
    }

    /* Icon formatting - open */
    .tab input[type=checkbox]:checked+label::after {
        transform: rotate(315deg);
        background-color: #6574cd;
        /*.bg-indigo*/
        color: #f8fafc;
        /*.text-grey-lightest*/
    }

    .tab input[type=radio]:checked+label::after {
        transform: rotateX(180deg);
        background-color: #6574cd;
        /*.bg-indigo*/
        color: #f8fafc;
        /*.text-grey-lightest*/
    }
</style>
@endsection


@section('scripts')
@if ($data->status == 0)
<script>
    let payment = 0;
        let href = window.location.href;
        const transaction_id = {{ $data->id }};
        const totalAmount = {{ $total_amount }};
        let _token = "{{ csrf_token() }}";

    function setPayment(type) {
        payment = type;
    }

    
        // Cancel
        function cancel() {
            Swal.fire({
                title: 'Batalkan Transaksi',
                text: "Apakah anda yakin untuk membatalkan transaksi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Batalkan!',
                cancelButtonText: 'Jangan'
            }).then((result) => {
                if (result.isConfirmed) {
                
                    Swal.fire('Sedang Memproses...');
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('ajax.postCancelDepositTransaction') }}",
                        data: {
                            transaction_id:transaction_id,
                            _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                    'Dibatalkan',
                                    'Order belanja telah dibatalkan',
                                    'info'
                                )
                                let home = '{{ route('member.home') }}';
                                setTimeout(window.location.replace(home), 3000);
                                
                            } else {
                                Swal.fire(
                                    'Gagal',
                                    'Gagal Membatalkan',
                                    'error'
                                )
                                setTimeout(window.location.replace(href), 3000);
                            }
                        }
                    })
                }
            })
        }
    
        // Confirm btn
        $('#confirm-btn').click( function () {
            if (payment == 0) {
                Swal.fire('Pilih Pembayaran!', 'Anda belum memilih metode pembayaran', 'warning');
                return false;
            }
            
            Swal.fire({
                title: 'Konfirmasi Transfer',
                text: "Apakah anda sudah melakukan Transfer ke Bank yang anda pilih?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya sudah transfer!',
                cancelButtonText: 'Belum'
            }).then((result) => {
                if (result.isConfirmed) {
                
                    Swal.fire('Sedang Memproses...');
                    Swal.showLoading();
                    $('#bank').val(payment);
                    $('#confirm-form').submit();
                    
                }
            })
        })

    // Utility
    function copy(id) {
        var copyText = document.getElementById(id);
        var textArea = document.createElement("textarea");
        textArea.value = copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
        successToast("Berhasil di-Copy");
    }

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

    function successToast (message) {
        Toast.fire({
            icon: 'success',
            title: message
        })
    }

    
</script>
@endif



@if ($data->status == 5)
<script>
    const transaction_id = {{ $data->id }};
    let href = window.location.href;
        $( function () {
            setInterval(checkStatus, 5000);

            function checkStatus() {
                console.log('check')
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.shopping.getCheckDigitalOrderStatus') }}",
                    data: {salesID:salesID},
                    success: function(response){
                        if (response.success) {
                            window.location.replace(href);
                        }
                    }
                });
            }
        })
</script>
@endif

@endsection