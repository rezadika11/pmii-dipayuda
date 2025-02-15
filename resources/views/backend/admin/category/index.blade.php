@extends('backend.layouts.main')
@section('title','Kategori')
@section('content')
<div class="container">
    <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">@yield('title')</h3> 
        </div>
      </div>
        <!-- Alert Container -->
      {{-- <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif
      </div> --}}
      <div class="row">
        <div class="col-md-4">
            <span>Tambah Kategori Baru</span>
            <form id="addCategoryForm">
                @csrf
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name">
                   <div class="invalid-feedback name-error"></div>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" readonly>
                </div>
                <div class="form-group">
                    <button type="button" id="addCategoryBtn" class="btn btn-sm btn-primary"> Simpan</button>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
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
@include('backend.admin.category.edit')
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
                url: "{{ route('category.datatable') }}",
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
                    data: 'slug', 
                    name: 'slug',
                },
                {
                    data: 'aksi', 
                    name: 'aksi', 
                    orderable: false, 
                    searchable: false,
                }
            ],
        });

    function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^a-z0-9-]/g, '-')  // Ganti karakter non-alfanumerik dengan strip
        .replace(/-+/g, '-')          // Ganti multiple strip dengan satu strip
        .replace(/^-|-$/g, '');       // Hapus strip di awal dan akhir
    }

    // Event listener untuk generate slug saat mengetik name
    $('#name').on('input', function() {
    let name = $(this).val();
    let slug = generateSlug(name);
    $('#slug').val(slug);
    });

    $('#addCategoryBtn').click(function(e) {
    e.preventDefault();
    // Reset error messages
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    // Disable submit button
    $('#addCategoryBtn').prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
    );

    let data = {
        'name': $('#name').val(),
        'slug': $('#slug').val(),
    }

    $.ajax({
        type: 'POST',
        url: "{{ route('category.store') }}",
        data: data,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function(response) {
            // Reset form
            $('#addCategoryForm')[0].reset();
            toastr.success(response.message);

            // Reload DataTable
            datatables.ajax.reload(null, false);
        },
        error: function(xhr) {
            // Handle validation errors
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                
                // Display validation errors
                if (errors.name) {
                    $('#name').addClass('is-invalid');
                    $('.name-error').text(errors.name[0]);
                }
                toastr.error("Kategori gagal disimpan!");
            } else {
                    toastr.error("Kategori gagal disimpan!");
            }
        },
        complete: function() {
            // Re-enable submit button
            $('#addCategoryBtn').prop('disabled', false).html(
                '<span class="btn-label"></span> Simpan'
            );
        }
    });
    });

    $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const id = $(this).data('id');

    // Menyimpan ID yang akan dihapus ke dalam tombol di modal
    $('#btnDestroy').data('id', id);
    $('#confirmModalDelete').modal('show');
    });

    $('#btnDestroy').click(function(e){
    e.preventDefault();
    const id = $(this).data('id'); 

    $.ajax({
        type: "DELETE",
        url: `{{ route('category.destroy','') }}/${id}`,
        headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function (response) {
            toastr.success("Kategori berhasil dihapus!");

            // Reload DataTable
            datatables.ajax.reload(null, false);

            $('#confirmModalDelete').modal('hide');
        },
        error: function (xhr) {
            toastr.error("Kategori gagal dihapus!");
        }
    });
    })

    $(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();
    const id = $(this).data('id');

    // Hapus pesan error
    $('.invalid-feedback').text('');

    // Hapus class is-invalid
    $('.form-control').removeClass('is-invalid');

    // Reset form
    $('#formEditCategory')[0].reset();

    // Ambil data kategori via AJAX
    $.ajax({
        url: `{{ route('category.edit', '') }}/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Isi form modal
                $('#editCategoryId').val(response.category.id);
                $('#editName').val(response.category.name);
                $('#editSlug').val(response.category.slug);
                
                // Tampilkan modal
                $('#modalEdit').modal('show');
            }
        },
        error: function() {
            // Tampilkan pesan error
            toastr.error("Kategori gagal diupdate!");
        }
    });

    });

    $('#editName').on('input', function() {
    let name = $(this).val();
    let slug = name.toLowerCase()
        .replace(/[^a-z0-9-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
        $('#editSlug').val(slug);
    });

    // Event listener untuk tombol update
    $('#btnUpdate').on('click', function() {
    const id = $('#editCategoryId').val();

        $('#btnUpdate').prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengedit...'
    );

    $.ajax({
        url: `{{ route('category.update','') }}/${id}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        data: $('#formEditCategory').serialize(),
        success: function(response) {
            // Tutup modal
            $('#modalEdit').modal('hide');
            
            // Reload DataTable
            $('#dataTables').DataTable().ajax.reload(null, false);
            
            // Tampilkan alert sukses
           toastr.success("Kategori gagal diupdate!");
        },
        error: function(xhr) {
            // Tampilkan error validasi
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                
                if (errors.name) {
                    $('#editName').addClass('is-invalid');
                    $('.name-error').text(errors.name[0]);
                }
            }
            
            // Tampilkan alert error
          toastr.error("Kategori gagal diupdate!");
        },
        complete: function() {
            // Re-enable submit button
            $('#btnUpdate').prop('disabled', false).html(
                '<span class="btn-label"></span> Update'
            );
        }
    });
    });         

    });

   
    </script>
@endpush
