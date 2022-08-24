@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body d-flex justify-content-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <iframe src="" allow = "autoplay" width="640" height="480" srcdoc="
                    <!DOCTYPE html>
                        <script src='js/wasm_exec.js'></script>
                        <script>
                            // Polyfill
                            if (!WebAssembly.instantiateStreaming) {
                                WebAssembly.instantiateStreaming = async (resp, importObject) => {
                                    const source = await (await resp).arrayBuffer();
                                    return await WebAssembly.instantiate(source, importObject);
                                };
                            }

                            const go = new Go();
                            WebAssembly.instantiateStreaming(fetch('wasm/main.wasm'), go.importObject).then(result => {
                                go.run(result.instance);
                                setUserName('{{ $user->name  }}', function(userName){
                                    console.log('ebitengine set userName :', userName)
                                });
                            });
                    </script>">
                </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
