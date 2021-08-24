@extends('backend.layouts.app')

@section('title', __('Contacts Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                Contacts Management
                <a href="{{route('admin.add-contact')}}" type="button" class="btn btn-sm btn-primary">Add new Contact</a>
            </h5>
        </x-slot>

        <x-slot name="body">
            <table id="datatable">
                <tr>
                    <th>Contact Name</th>
                    <th>Contact Category</th>
                    <th>Contact Mobile</th>
                    <th>Contact Email</th>
                    <th>Contact Status</th>
                    <th>Actions</th>
                </tr>
                @if ([] !== App\Models\models\Contacts::all()->toArray())
                    @foreach (App\Models\models\Contacts::all()->toArray() as $tuple)
                        <tr>
                            <td>{{ '' === $tuple['ContactName'] ? '-' : $tuple['ContactName'] }}</td>
                            <td>{{ null === $tuple['category'] ? 'None' : $tuple['category']['CategoryName'] }}</td>
                            <td>{{ '' === $tuple['ContactMobile'] ? '-' : $tuple['ContactMobile'] }}</td>
                            <td>{{ '' === $tuple['ContactEmail'] ? '-' : $tuple['ContactEmail'] }}</td>
                            <td>{{ 'Y' === $tuple['IsLive'] ? 'Y' : 'N' }}</td>
                            <td>
                                <a href="{{ route('admin.view-contact', $tuple['ContactID']) }}"
                                    class="btn btn-info btn-sm"><i class="fas fa-search"></i></a>
                                <a href="{{ route('admin.edit-contact', $tuple['ContactID']) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-pencil-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                @endif
            </table>
        </x-slot>
    </x-backend.card>
@endsection
