@extends('layouts.app')

@section('title')
    {{ __('messages.form_builder') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/header-padding.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="col-sm-2 col-2">
        <a href="{{ route('formbuilder.create') }}" class="btn btn-success d-block">
            {{__('Create')}}
        </a>
    </div>
    
    <div class="d-flex flex-column">
        @include('flash::message')

        <livewire:form-builder-table />

        <!-- Score Table -->
    
    </div>
</div>
@endsection

@push('scripts')
    <script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this form?")) {
            Livewire.emit('delete', id);
        }
    }
    </script>
@endpush
