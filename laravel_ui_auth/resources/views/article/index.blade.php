@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Artikel</div>

                <div class="card-body">
                    <a href="{{ route('article.create') }}" class="btn btn-success">Tambah Data</a>
                    <table class="table caption-top table-hover">
                        <caption>List of Article</caption>
                        <thead>
                          <tr>
                            <th scope="col" style="width: 10%">#</th>
                            <th scope="col" style="width: 30%">Title</th>
                            <th scope="col" style="width: 40%">Content</th>
                            <th scope="col" style="width: 20%">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                            <tr>
                              <th scope="row">{{ $key+1 }}</th>
                              <td>{{ $item->title }}</td>
                              <td>{{ $item->content }}</td>
                              <td class="d-flex">
                                <a href="{{ route('article.show', $item->id) }}"
                                  class="btn btn-info m-1">Show</a>
                                <a href="{{ route('article.edit', $item->id) }}"
                                    class="btn btn-primary m-1">Edit</a>
                                <form action="{{ route('article.destroy', $item->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger m-1"
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
