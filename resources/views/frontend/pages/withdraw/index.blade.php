@extends('frontend.pages_layouts.master')
@section('title')
    Withdraw
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="section-header">
                    <h1><i class="fa fa-fw fa-hand-holding-usd"></i> Withdrawal Request</h1>
                </div>
                <div class="section-body">
                    <input type="hidden" name="hal" value="withdrawreq">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>
                                Current Balance
                                <span
                                    class=" text-info">{{ $current_balance ?? 0 }}$</span>
                            </h4>
                            <h4>
                                Hit Bonus
                                <span class=" text-info">{{ $bonus_balance }}$</span>

                            </h4>
                            <h4>
                                Total Balance
                                <span
                                    class=" text-info">{{ $current_balance + $bonus_balance }}$</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 float-md-right">
                                    <blockquote>
                                        <p>Your amount withdraw request has been sent successfuly and is under processing.
                                            Your amount will be send to your account after approval.
                                        </p>
                                    </blockquote>
                                </div>
                                <div class="col-md-6">
                                    <form
                                    {{-- onsubmit="setTimeout(function(){location.href = `{{ route('dashboard') }}`},3000);"  --}}
                                    method="POST" action="{{ route('withdraw.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend"> <span
                                                        class="input-group-text">Account</span> </div>
                                                <select name='payment_method' class="custom-select" id="inputGroupSelect05"
                                                    required="">
                                                    <option value="" disabled="" selected>Select Payment Methods
                                                    </option>
                                                    @foreach ($payment_methods as $payment_method)
                                                        <option value="{{ $payment_method->id }}"
                                                            {{ $user->payment_method == $payment_method->id ? 'selected' : '' }}>
                                                            {{ $payment_method->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend"> <span class="input-group-text">Amount to
                                                        withdraw</span> </div>
                                                @if (!empty($withdraw))
                                                    <input type="number" min='5'
                                                        max="{{ $current_balance + $bonus_balance }}"
                                                        step="any" id="txamount" name="amount" class="form-control"
                                                        placeholder="0.00" required=""
                                                        oninput="amount_to_receive(this.value)">
                                                @else
                                                    <input type="number" min='5'
                                                        max="{{ $current_balance + $bonus_balance }}"
                                                        step="any" id="txamount" name="amount" class="form-control"
                                                        placeholder="0.00" required=""
                                                        oninput="amount_to_receive(this.value)">
                                                @endif
                                            </div>
                                            <div class="float-right">
                                                <p class="badge badge-info">Amount to receive: $<span id="amount_to_receive"
                                                        style="bottom: 0px !important;">0.00</span></p>
                                                <br>
                                                <p class="badge badge-info"> Withdraw Fee: $0.00</p>

                                            </div>
                                        </div>
                                        <div class="float-md-right mt-4"> <a href="" class="btn btn-danger"><i
                                                    class="fa fa-fw fa-redo"></i> Clear</a>
                                            <button type="button" class="btn btn-primary" onclick="withdraw_amount()"><i
                                                    class="fa fa-fw fa-donate"></i> Withdraw Request...</button>
                                        </div>

                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke">
                            <div class="row">
                                <div class="col-sm-12 text-small text-danger"> The system will simply ignore the amount
                                    withdraw request if it doesn't meet our requirements. </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="dosubmit" value="1">
                </div>
            </div>
        </div>
    </div>

    

        <!-- Modal -->
        <button style="display:none" type="button" id="modal-btn" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i
            class="fa fa-fw fa-donate"></i> Withdraw Request...</button>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-3">
                            <img src="public/site-logo.png" alt="" width="100%">
                        </div>
                        <div class="col-9">
                            <h5 style="color: #1b2e60"><b>Star Multinational Services</b></h5>
                            {{-- <p style="color: #51af00">Withdraw Successful</p> --}}
                            <p class="text-secondary">Withdraw request has been sent successfuly</p>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="    line-height: 8px;">
                    <h5 style="color: #1b2e60"><b>{{ date('d/m/H:i:s') }}</b></h5>
                    <br>
                    <h5><b>Send by</b></h5>
                    <p>{{ user()->get_name() }}</p>
                    <p>Sponser I'd: {{ user()->get_sponser ? user()->get_sponser->username : 'N/A' }}</p>
                    <div class="row">
                        <div class="col-6">
                            <h5><b>Account</b></h5>
                            <p id="account_name">Jazz Cash</p>
                            <p>Sponser I'd: admin</p>
                            <h5><b>Send to</b></h5>
                            <p>Admin</p>
                            <p>Sponser I'd: admin</p>
                        </div>
                        <div class="col-6">
                            <img style="transform: rotate(30deg); width: 150px;" src="public/images/stamp.png" alt="">
                        </div>
                    </div>
                    <h5><b>Withdraw fee</b></h5>
                    <p>0$</p>
                    <h5 style="color: #51af00"><b>Withdraw amount</b></h5>
                    <p id="withdraw_amount">$</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>

    <!-- Modal -->
    <button style="display:none" type="button" id="modal-btn-err" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i
        class="fa fa-fw fa-donate"></i> Withdraw Request...</button>
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="...">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-3">
                            <img src="public/site-logo.png" alt="" width="100%">
                        </div>
                        <div class="col-9">
                            <h3 style="color: #1b2e60"><b>Star Multinational Services</b></h3>
                            {{-- <p style="color: #ef1313">Withdraw Not Successful</p> --}}
                            <p class="text-secondary" id="message">Please fill account and withdraw amount.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ele = (id) => {
            return document.getElementById(id);
        }
        const amount_to_receive = (amount) => {
            document.getElementById('amount_to_receive').innerText = amount != '' ? amount : '0.00';
        }

        const pageRefresh = () => {
            setTimeout(() => {
                location.href = "{{ route('dashboard') }}";
            }, 2000);
        }

        const withdraw_amount = () => {
            var amount = ele('txamount').value;
            var payment_method = ele('inputGroupSelect05').value;
            if(amount != '' && payment_method != ''){
                $.ajax({
                    type: 'POST',
                    url: "{{ route('withdraw.store') }}",
                    data:{
                        amount: amount,
                        payment_method: payment_method
                    },
                    success: (res) => {
                        if(res.success == true){
                            ele('withdraw_amount').innerText = `${amount}$`;
                            if(payment_method == 1){
                                ele('account_name').innerText = `Bank Account`;
                            }else{
                                ele('account_name').innerText = `Jaazcash / Easypaisa`;
                            }
                            ele('modal-btn').click();
                        }else{
                            ele('modal-btn-err').click();
                            ele('message').innerText = res.message;
                        }
                    }
                })
            }else{
                ele('modal-btn-err').click();
            }
        }
    </script>
@endsection
