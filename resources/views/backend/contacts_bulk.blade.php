@extends('backend.layouts.app')

@section('title', __('Editting Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                {{isset($contact['ContactName'])? 'Editing ' . $contact['ContactName']:'Creating New Contact'}}
                <div class="justify-content-between align-items-right">
                    <a href="{{asset('/import/Import.xlsx')}}" type="button" class="btn btn-sm btn-warning" download>Download Excel File for Bulk Imports</a>
                </div>
            </h5>
        </x-slot>
        <?php if ( ! isset($contact) ) $contact = null?>
        <?php if ( ! isset($categories) ) $categories = []?>


        <x-slot name="body">
            <form action="{{route('admin.storeBulkAdd')}}" method="POST" enctype="multipart/form-data">
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
                  <label for="ContactName" class="col-4 col-form-label">Contact Name</label>
                  <div class="col-8">
                    <input type="file" id="contactFile" name="contactFile" accept=".xls,.xlsx" required>
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
