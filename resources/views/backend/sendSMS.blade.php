@extends('backend.layouts.app')

@section('title', __('Create new SMS'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                Create new SMS Dispatch Request
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.new-sms-bulk') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-envelope"></i>
                        <span class="ml-1">Bulk Dispatch</span>
                    </a>
                </div>
            </h5>
        </x-slot>

        <x-slot name="body">
            <form method="POST" action="{{route('admin.post-sms')}}">
                @csrf
                <div class="form-group row">
                  <label class="col-4">Single/Individual SMS</label>
                  <div class="col-8">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input name="dispatchType" id="dispatchType_0" type="radio" class="custom-control-input" value="single" required="required">
                      <label for="dispatchType_0" class="custom-control-label">Send SMS Individually</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input name="dispatchType" id="dispatchType_1" type="radio" class="custom-control-input" value="bulk" required="required">
                      <label for="dispatchType_1" class="custom-control-label">Send SMS to group</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row toHide group">
                  <label class="col-4">Contact Group to SMS</label>
                  <div class="col-8">
                    @if ([] !== App\Models\models\ContactCategory::all()->toArray())
                        <select id="GroupToText" name="GroupToText[]" class="select-boot" multiple="multiple" style="min-width:100%">
                            @foreach (App\Models\models\ContactCategory::all()->toArray() as $tuple)
                                <option value="{{str_pad((string) $tuple['CategoryID'], 2, '0', STR_PAD_LEFT)}}">{{str_pad((string) $tuple['CategoryID'], 2, '0', STR_PAD_LEFT)}} : {{$tuple["CategoryName"]}}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="alert alert-success header-message" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                                </button>
                            No categories were found. Please try individual SMS instead.
                        </div>
                    @endif
                  </div>
                </div>
                <div class="form-group row toHide individual">
                  <label for="IndividualToText" class="col-4 col-form-label">Individual to SMS</label>
                  <div class="col-8">
                    @if ([] !== App\Models\models\Contacts::all()->toArray())
                        <select id="IndividualToText" name="IndividualToText[]" class="select-boot" multiple="multiple" style="min-width:100%!important">
                            @foreach (App\Models\models\Contacts::all()->toArray() as $tuple)
                                <option value="{{str_pad((string) $tuple['ContactID'], 2, '0', STR_PAD_LEFT)}}">{{str_pad((string) $tuple['ContactID'], 2, '0', STR_PAD_LEFT)}} : {{$tuple["ContactName"]}}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="alert alert-success header-message" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            No contacts were found. Please try to first setup contacts.
                        </div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label for="SmsText" class="col-4 col-form-label">SMS Content</label>
                  <div class="col-8">
                    <textarea id="SmsText" maxlength="160" name="SmsText" cols="40" rows="5" required="required" class="form-control"></textarea>
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
