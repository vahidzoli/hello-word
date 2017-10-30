@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Favorite locations API</div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel-body">
                    <form method="post" action="/api/loc">
                        {{Csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE" >

                        <label>Title :</label>
                        <br>
                        <input class="form-control" type="text" name="title" placeholder="title">
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Lat :</label>
                                <br>
                                <input class="form-control" type="number" name="lat" step="0.01" placeholder="ex 0.01">
                                <br>
                            </div>
                            <div class="col-md-6">
                                <label>Ing :</label>
                                <br>
                                <input class="form-control" type="number" name="ing" step="0.01" placeholder="ex 0.01">
                            </div>
                        </div>

                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                    <hr>
                    <div style="width: 700px; height: 600px;">
                        {!! Mapper::render() !!}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
