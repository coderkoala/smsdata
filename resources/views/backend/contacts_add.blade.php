@extends('backend.layouts.app')

@section('title', __('Editting Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                {{isset($contact['ContactName'])? 'Editing ' . $contact['ContactName']:'Creating New Contact'}}
              </h5>
        </x-slot>
        <?php if ( ! isset($contact) ) $contact = null?>
        <?php if ( ! isset($categories) ) $categories = []?>


        <x-slot name="body">
            <form action="{{route('admin.store-contact')}}" method="POST">
                @csrf
                <div class="form-group row">
                  <label for="CategoryID" class="col-4 col-form-label">Contact Category</label>
                  <div class="col-8">
                    <select id="CategoryID" name="CategoryID" class="custom-select" required="required">
                      <option selected disabled value="">Select Contact Category</option>
                      @foreach($categories as $tuple)
                      <option value="{{str_pad((string) $tuple['CategoryID'], 2, "0", STR_PAD_LEFT)}}">{{$tuple['CategoryName']}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="ContactID" class="col-4 col-form-label">Contact Identification</label>
                  <div class="col-8">
                    <input disabled id="ContactID" value="{{isset($contact['ContactID'])?str_pad((string)$contact['ContactID'], 5, "0", STR_PAD_LEFT):''}}" name="ContactID" type="text" required="required" class="form-control">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="ContactName" class="col-4 col-form-label">Contact Name</label>
                  <div class="col-8">
                    <input id="ContactName" name="ContactName" value="{{isset($contact['ContactName'])?$contact['ContactName']:''}}" type="text" class="form-control" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="ContactMobile" class="col-4 col-form-label">Contact Mobile</label>
                  <div class="col-8">
                    <input id="ContactMobile" name="ContactMobile" value="{{isset($contact['ContactMobile'])?$contact['ContactMobile']:''}}" type="text" class="form-control" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="ContactEmail" class="col-4 col-form-label">Contact Email</label>
                  <div class="col-8">
                    <input id="ContactEmail" name="ContactEmail" value="{{isset($contact['ContactEmail'])?$contact['ContactEmail']:''}}" type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="IsLive" class="col-4 col-form-label">Contact Status</label>
                  <div class="col-8">
                    <select id="IsLive" name="IsLive" required="required" class="custom-select">
                      <option {{isset($contact['IsLive']) && 'N' === $contact['IsLive']?'selected':''}} value="N">Inactive</option>
                      <option {{isset($contact['IsLive']) && 'Y' === $contact['IsLive']?'selected':''}} value="Y">Active</option>
                    </select>
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
