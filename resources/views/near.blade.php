@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">Favorite locations API</div>
                    <div class="panel-body">
                        <div style="width: 900px; height: 600px;">
                            {!! Mapper::render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
