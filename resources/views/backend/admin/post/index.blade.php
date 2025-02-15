@extends('backend.layouts.main')
@section('title','Postingan')
@section('content')
<div class="container">
    <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">@yield('title')</h3> 
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <a href="{{ route('posts.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-square"></i> Tambah Pos</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Publish</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@include('backend.components.modal-delete')
@include('backend.admin.tag.edit')
@endsection
@push('js')
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
@endpush
