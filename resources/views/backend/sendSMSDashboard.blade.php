@extends('backend.layouts.app')

@section('title', __('SMS Management Console'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                SMS Management Console
                <a href="{{route('admin.new-sms')}}" type="button" class="btn btn-sm btn-primary">New SMS Dispatch</a>
            </h5>
        </x-slot>

        <x-slot name="body">
            <table id="datatable">
                <tr>
                    <th>#</th>
                    <th>Dispatched To</th>
                    <th>SMS Text</th>
                    <th>Sent Date</th>
                    <th>Sent Status</th>
                    <th>Dispatcher</th>
                </tr>
                @if ([] !== App\Models\models\SMSData::all()->toArray())
                    @foreach (App\Models\models\SMSData::all()->toArray() as $tuple)
                        <tr>
                            <td>{{ '' === $tuple['ID'] ? '-' : $tuple['ID'] }}</td>
                            <td>{{ null === $tuple['PhoneNo'] ? 'N/A' : $tuple['PhoneNo'] }}</td>
                            <td>{{ '' === $tuple['SmsText'] ? '-' : substr($tuple['SmsText'], 0 , 30) . ' [...]' }}</td>
                            <td>{{ null === $tuple['SentDate'] ? 'N/A' : $tuple['SentDate'] }}</td>
                            <td>{{ 'Y' === $tuple['isSent'] ? 'S' : 'U' }}</td>
                            <td>{{ '' === $tuple['CreatedBy'] ? '-' : $tuple['CreatedBy'] }}</td>
                        </tr>
                    @endforeach
                @else
                @endif
            </table>
        </x-slot>
    </x-backend.card>
@endsection
