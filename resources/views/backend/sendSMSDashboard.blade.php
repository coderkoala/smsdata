@extends('backend.layouts.app')

@section('title', __('SMS Dashboard'))

@section('content')
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-primary">
            <div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-value-lg">{{App\Models\models\SMSData::count()}}</div>
                    <div>Total Messages</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height: 70px">
                <canvas class="chart" id="card-chart1" height="70"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-success">
            <div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-value-lg">{{App\Models\models\SMSData::where('isSent', 'Y')->count()}}</div>
                    <div>Sent Messages</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height: 70px">
                <canvas class="chart" id="card-chart2" height="70"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-info">
            <div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-value-lg">{{App\Models\models\Contacts::where('IsLive', 'Y')->count()}}</div>
                    <div>Total Contacts</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3" style="height: 70px">
                <canvas class="chart" id="card-chart3" height="70"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-danger">
            <div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-value-lg">{{App\Models\models\SMSData::where('isSent', '!=' , 'Y')->orWhereNull('isSent')->count()}}</div>
                    <div>Unsent Messages</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height: 70px">
                <canvas class="chart" id="card-chart4" height="70"></canvas>
            </div>
        </div>
    </div>
</div>

    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                SMS Management Service Dashboard
                <a href="{{ route('admin.new-sms') }}" type="button" class="btn btn-sm btn-primary">New SMS Dispatch</a>
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
                            <td>{{ '' === $tuple['SmsText'] ? '-' : substr($tuple['SmsText'], 0, 30) . ' [...]' }}</td>
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
