@extends('backend.layouts.app')

@section('title', __('Create new SMS'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                Create new SMS Dispatch Request
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{asset('/import/BulkSMS.xlsx')}}" type="button" class="btn btn-sm btn-warning" download>Download Excel File for Bulk SMS Dispatch</a>
                    &nbsp;
                    <a href="{{ route('admin.sms') }}" class="btn btn-sm btn-secondary">
                        <span class="ml-1">Back</span>
                    </a>
                </div>
            </h5>
        </x-slot>

        <x-slot name="body">
            <form method="POST" action="{{route('admin.post-sms-bulk')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-4"><b>Please note</b></label>
                  <div class="col-8">
                        This feature lets you multiple SMS with different numbers with different contents in messages. <br>Please download the template Excel file to continue.
                  </div>
                </div>
                <div class="form-group row">
                  <label for="SmsText" class="col-4 col-form-label">SMS Dispatch File</label>
                  <div class="col-8">
                    <input type="file" id="smsFile" name="smsFile" accept=".xls,.xlsx" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    <button name="cancel" onclick="window.history.back();" class="btn btn-secondary">Back</button>
                  </div>
                </div>
              </form>
        </x-slot>
    </x-backend.card>
@endsection
