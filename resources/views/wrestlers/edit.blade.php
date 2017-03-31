@extends('layouts.app')

@section('header')
    <h1 class="page-title">Edit Wrestler</h1>
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
                            <form autocomplete="off">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Name" autocomplete="off" value="{{ $wrestler->name }}"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                           placeholder="Slug" autocomplete="off" value="{{ $wrestler->slug }}"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="status_id">Status</label>
                                    <select class="form-control" id="status_id" name="status_id">
                                        <option value="0">Choose One</option>
                                        @foreach(App\WrestlerStatus::all() as $status)
                                            <option value="{{ $status->id }}" {{ $wrestler->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary">Update</button>
                                </div>
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
