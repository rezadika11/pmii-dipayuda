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
                                    <th width="30%">Judul</th>
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
    <script>
        $(document).ready(()=>{
            let datatables = $("#dataTables").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    type: "GET",
                    url: "{{ route('posts.datatable') }}",
                },  
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                    },
                    { 
                        data: 'judul', 
                        name: 'title',
                    },
                    { 
                        data: 'penulis', 
                        name: 'users.name',
                    },
                    { 
                        data: 'kategori', 
                        name: 'category.name',
                    },
                    { 
                        data: 'tanggal', 
                        name: 'published',
                        searchable: false,
                    },
                    { 
                        data: 'publish', 
                        name: 'is_published',
                    },
                    {
                        data: 'aksi', 
                        name: 'aksi', 
                        orderable: false, 
                        searchable: false,
                    }
                ],
            });
        });
    </script>

    {{-- Toogle fungsi publish --}}
    <script>
    $(document).on('click', '.btn-toggle-publish', function() {
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        // Toggle status: jika published (1) maka jadikan draft (0), dan sebaliknya.
        var newStatus = currentStatus == 1 ? 0 : 1;

        $.ajax({
            url: '{{ route("posts.toggle-publish") }}',
            type: 'POST',
            data: {
                id: id,
                is_published: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Opsional: tampilkan notifikasi sukses
                // console.log(response.message);
                // Reload DataTable tanpa mereset paging
                $('#dataTables').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                // Opsional: tampilkan error
                toastr.error(xhr.responseText);
            }
        });
    });
</script>
{{-- Delete pos --}}
<script>
    $(document).ready(function(){
        var deletePostId = null; // Variabel untuk menyimpan ID post yang akan dihapus
        
        // Saat tombol hapus diklik, tampilkan modal konfirmasi
        $(document).on('click', '.btn-delete', function(){
            deletePostId = $(this).data('id'); // Ambil ID dari atribut data-id
            $('#confirmModalDelete').modal('show'); // Tampilkan modal
        });
        
        // Saat tombol "Hapus" pada modal diklik, kirimkan AJAX request untuk menghapus data
        $('#btnDestroy').on('click', function(){
            if(deletePostId) {
                $.ajax({
                    url: `{{ route('posts.destroy','') }}/${deletePostId}`, // Sesuaikan dengan route delete Anda
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Sertakan CSRF    token
                    },
                    success: function(response) {
                        // Sembunyikan modal
                        $('#confirmModalDelete').modal('hide');
                        // Reload DataTable tanpa mereset paging (jika menggunakan DataTables)
                        $('#dataTables').DataTable().ajax.reload(null, false);
                        // Tampilkan notifikasi sukses (bisa menggunakan alert atau notifikasi lainnya)
                         toastr.success('Pos berhasil dihapus');
                    },
                    error: function(xhr) {
                        $('#confirmModalDelete').modal('hide');
                        toastr.error('Gagal menghapus post.');
                    }
                });
            }
        });
    });
</script>


@endpush
