@extends('backend.layouts.main')
@section('title','User')
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
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-square"></i> Tambah User</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Hak User</th>
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
                    url: "{{ route('users.datatable') }}",
                },  
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                    },
                    { 
                        data: 'name', 
                        name: 'name',
                    },
                    { 
                        data: 'email', 
                        name: 'email',
                    },
                    { 
                        data: 'roles', 
                        name: 'roles',
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
{{-- Delete pos --}}
<script>
    $(document).ready(function(){
        var id = null; // Variabel untuk menyimpan ID post yang akan dihapus
        
        // Saat tombol hapus diklik, tampilkan modal konfirmasi
        $(document).on('click', '.btn-delete', function(){
            id = $(this).data('id'); // Ambil ID dari atribut data-id
            $('#confirmModalDelete').modal('show'); // Tampilkan modal
        });
        
        // Saat tombol "Hapus" pada modal diklik, kirimkan AJAX request untuk menghapus data
        $('#btnDestroy').on('click', function(){
            if(id) {
                $.ajax({
                    url: `{{ route('users.destroy','') }}/${id}`, // Sesuaikan dengan route delete Anda
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
                         toastr.success('User berhasil dihapus');
                    },
                    error: function(xhr) {
                        $('#confirmModalDelete').modal('hide');
                        toastr.error('Gagal menghapus User.');
                    }
                });
            }
        });
    });
</script>
@endpush
