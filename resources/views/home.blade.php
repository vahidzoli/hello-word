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

                        <label>Title :</label>
                        <br>
                        <input class="form-control" type="text" name="title" placeholder="title">
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Lat :</label>
                                <br>
                                <input class="form-control" type="number" step="any" name="lat">
                                <br>
                            </div>
                            <div class="col-md-6">
                                <label>Ing :</label>
                                <br>
                                <input class="form-control" type="number" step="any" name="ing">
                            </div>
                        </div>

                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
