@extends('layouts.app_wasm')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const go = new Go();
    WebAssembly.instantiateStreaming(fetch("wasm/main.wasm"), go.importObject).then((result) => {
        go.run(result.instance);

        goStrLength("{{ $user->name }}", function(strLen){
            console.log("strLen:", strLen);
            console.log("typeof strLen:", typeof strLen);
        });
    });

    </script>
@endsection
