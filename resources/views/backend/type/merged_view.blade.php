<!-- resources/views/merged_view.blade.php -->

@extends('admin.dashboard')

@section('admin')

    <!-- Include your formHonorDosen.blade.php content -->
    @include('backend.type.formHonorDosen')

    <!-- Include your all_type.blade.php content -->
    @include('backend.type.all_type')

@endsection
