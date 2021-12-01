@extends('backend.layouts.app')

@section('title', __('Editting Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                {{ isset($contact['ContactName']) ? 'Viewing ' . $contact['ContactName'] : 'Creating New Contact' }}
                <a type="button" href="{{route('admin.edit-contact', isset($contact['ContactID']) ? str_pad((string) $contact['ContactID'], 5, '0', STR_PAD_LEFT) : 'new')}}" class="btn btn-sm btn-primary">Edit Contact</a>
            </h5>
        </x-slot>

        <x-slot name="body">
            <div class="form-group row">
                <label for="CategoryID" class="col-4 col-form-label">Contact Category</label>
                <div class="col-8">
                    <select disabled class="custom-select" required="required">
                        <option {{ null === $contact ? 'selected' : '' }} disabled value="">Select Contact Category</option>
                        @foreach ($categories as $tuple)
                            <option
                                {{ null !== $contact && (int) $contact['CategoryID'] === (int) $tuple['CategoryID'] ? 'selected' : '' }}
                                value="{{ str_pad((string) $tuple['CategoryID'], 2, '0', STR_PAD_LEFT) }}">
                                {{ $tuple['CategoryName'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="ContactID" class="col-4 col-form-label">Contact Identification</label>
                <div class="col-8">
                    <input disabled id="ContactID"
                        value="{{ isset($contact['ContactID']) ? str_pad((string) $contact['ContactID'], 5, '0', STR_PAD_LEFT) : '' }}"
                        name="ContactID" type="text" required="required" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="ContactName" class="col-4 col-form-label">Contact Name</label>
                <div class="col-8">
                    <input id="ContactName" disabled name="ContactName"
                        value="{{ isset($contact['ContactName']) ? $contact['ContactName'] : '' }}" type="text"
                        class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="ContactMobile" class="col-4 col-form-label">Contact Mobile</label>
                <div class="col-8">
                    <input id="ContactMobile" disabled name="ContactMobile"
                        value="{{ isset($contact['ContactMobile']) ? $contact['ContactMobile'] : '' }}" type="text"
                        class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="ContactEmail" class="col-4 col-form-label">Contact Email</label>
                <div class="col-8">
                    <input id="ContactEmail" disabled name="ContactEmail"
                        value="{{ isset($contact['ContactEmail']) ? $contact['ContactEmail'] : '' }}" type="text"
                        class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="IsLive" class="col-4 col-form-label">Contact Status</label>
                <div class="col-8">
                    <select disabled id="IsLive" name="IsLive" required="required" class="custom-select">
                        <option {{ isset($contact['IsLive']) && 'N' === $contact['IsLive'] ? 'selected' : '' }} value="N">
                            Inactive</option>
                        <option {{ isset($contact['IsLive']) && 'Y' === $contact['IsLive'] ? 'selected' : '' }} value="Y">
                            Active</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <a name="cancel" href="{{route('admin.contact')}}" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
