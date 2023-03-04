@extends('layouts.app')

@push('styles')

@endpush

@section('title')
  post
@endsection


@section('page_header')

    <!-- Basic button -->
    <div class="page-header page-header-light border rounded mb-3">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h5 class="mb-0">Section post</h5>
                <div class="text-muted mt-1">All Information About post Section</div>
            </div>

            <div class="my-auto ms-auto">
                <button type="button" class="btn btn-dark" id="create_form_vertical">Add post <i
                        class="fa fa-plus ms-2"></i></button>
            </div>
        </div>

        <div class="page-header-content border-top">
            <div class="breadcrumb">
                <a href="/" class="breadcrumb-item py-2">Home</a>
                <span class="breadcrumb-item active py-2">post</span>

            </div>
        </div>
    </div>
    <!-- /basic button -->
@endsection
@section('content')
    @include('post.create')

    <!-- Content area -->
    <div class="content">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">post Table</h5>
            </div>

            <div class="card-body">
            </div>
            <table class="table info_table" id="info_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Postal Code</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


    </div>
    <!-- /content area -->
@endsection

@push('scripts')
    @include('post.script')
@endpush



