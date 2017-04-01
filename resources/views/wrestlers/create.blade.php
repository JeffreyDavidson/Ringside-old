@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Wrestler</h1>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-md-6">
                    <!-- Example Basic Form -->
                    <div class="example-wrap">
                        <h4 class="example-title">Basic Form</h4>
                        <div class="example">
                            <form method="POST" action="/wrestlers">
                                @include('wrestlers.form', compact('wrestler'))
                            </form>
                        </div>
                    </div>
                    <!-- End Example Basic Form -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
@endsection
