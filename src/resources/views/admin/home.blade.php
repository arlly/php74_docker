@extends('admin.layouts.layout')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                </div>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                </div>
            </div>

            <div id="app">
                <router-view></router-view>
            </div>
        </div>
    </div>
@endsection
