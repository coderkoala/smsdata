@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                Dashboard
                {{-- <a href="{{route('admin.add-contact')}}" type="button" class="btn btn-sm btn-primary">Add new Contact</a> --}}
            </h5>
        </x-slot>

        <x-slot name="body">
        </x-slot>
    </x-backend.card>
@endsection
