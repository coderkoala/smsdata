@extends('backend.layouts.app')

@section('title', __('Contact Category Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <h5 class="d-flex justify-content-between align-items-center">
                All Categories
            </h5>
        </x-slot>

        <x-slot name="body">
            <form action="{{ route('admin.category-post') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-4">
                        <input id="CategoryName" name="CategoryName" type="text" class="form-control" required="required"
                            placeholder="Enter new Category">
                    </div>
                    <div class="col-2">
                        <button name="submit" type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-4 col-8">
                    </div>
                </div>
            </form>

            <table id="datatable">
                <tr>
                    <th>Category Number</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
                @if ([] !== App\Models\models\ContactCategory::all()->toArray())
                    @foreach (App\Models\models\ContactCategory::all()->toArray() as $tuple)
                        <tr>
                            <td>{{ '' === $tuple['CategoryID'] ? '-' : str_pad((string) $tuple['CategoryID'], 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>{{ '' === $tuple['CategoryName'] ? '-' : $tuple['CategoryName'] }}</td>
                            <td>
                                <button
                                    onclick="sendDeleteRequest('{{str_pad((string) $tuple["CategoryID"], 2, "0", STR_PAD_LEFT)}}', '{{$tuple["CategoryName"]}}' );"
                                    class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @else
                @endif
            </table>
        </x-slot>
    </x-backend.card>
    <script>
        function sendDeleteRequest(id, name) {
            var url = '{{ route('admin.delete-category', '') }}' + '/' + id;
            var form = jQuery('<form action="' + url + '" method="post">' +
                '<input type="text" hidden name="_token" value="{{ csrf_token() }}" />' +
                '</form>');

            if (confirm('Are you sure you wish to delete the category ' + name + '?')) {
                jQuery('body').append(form);
                form.submit();
            }
        }
    </script>
@endsection
