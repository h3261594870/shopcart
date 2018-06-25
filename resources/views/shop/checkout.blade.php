@extends('layouts.main')

@section('title')
    Laravel Shopping Cart
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <h1>訂購人資訊</h1>
            <h4>Your Total: ${{ $total }}</h4>
            <div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'hidden' : ''  }}">
                {{ Session::get('error') }}
            </div>
            <form name='Spgateway' method='post' action='https://core.spgateway.com/MPG/mpg_gateway'>
                <input type='hidden' id="MerchantID" name='MerchantID' value='MS155127302'>
                <input type='hidden' id="TradeInfo" name='TradeInfo' value='{{$AES['TradeInfo']}}'>
                <input type='hidden' id="TradeSha" name='TradeSha' value='{{$AES['sha256']}}'>
                <input type='hidden' id="Version" name='Version' value='1.4'>
                <input type='hidden' id="RespondType" name='RespondType' value='JSON'>
                <input type='hidden' id="TimeStamp" name='TimeStamp' value='{{$info['TimeStamp']}}'>
                <input type='hidden' id="MerchantOrderNo" name='MerchantOrderNo' value="{{$info['MerchantOrderNo']}}">
                <input type='hidden' id="Amt" name='Amt' value='{{ $total }}'>
                <input type='hidden' id="ItemDesc" name='ItemDesc' value="{{$info['ItemDesc']}}">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="Name">Name</label>
                            <input type="text" id="name" class="form-control" required name="Name">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" class="form-control" required name="email">
                        </div>
                    </div>

                    <input type='hidden' id="LoginType" name='LoginType' value='0'>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" class="form-control" required name="address">
                        </div>
                    </div>
                    <hr>
                </div>
                {{ csrf_field() }}
                <button type="submit" class="btn btn-success" value="submit">Buy now</button>
            </form>
        </div>
    </div>
@endsection

