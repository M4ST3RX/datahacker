@extends('layouts.pre')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="computers">
                    <div class="card flex-row flex-wr computer">
                        @foreach($computers as $computer)
                            <div class="card-header border-0">
                                <img src="//placehold.it/120" alt="">
                            </div>
                            <div class="card-body">
                                <div class="card-top">
                                    {{ $computer->name }}
                                </div>
                                <div class="card-bottom">
                                    <a href="{{ route('computer.configure') }}" role="button" class="btn btn-primary">Configure</a>
                                    <a href="{{ route('computer.play') }}" role="button" class="btn btn-success">Play</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection