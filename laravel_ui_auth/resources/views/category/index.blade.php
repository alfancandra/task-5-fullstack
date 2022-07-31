@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Category</div>

                    <div class="card-body">
                        <a href="{{ route('category.create') }}" class="btn btn-success">Tambah Data</a>
                        <table class="table caption-top table-hover">
                            <caption>List of Category</caption>
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%">#</th>
                                    <th scope="col" style="width: 60%">Category</th>
                                    <th scope="col" style="width: 30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td class="d-flex">
                                            <a href="{{ route('category.edit', $item->id) }}"
                                                class="btn btn-primary m-2">Edit</a>
                                            <form action="{{ route('category.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger m-2"
                                                    onclick="return confirm('Are you sure to delete this user?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
