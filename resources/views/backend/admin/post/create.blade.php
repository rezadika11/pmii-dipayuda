@extends('backend.layouts.main')
@section('title','Tambah Postingan Baru')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2-bootstrap-5-theme.min.css') }}" />
     <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">@yield('title')</h3> 
        </div>
      </div>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Judul -->
                            <div class="form-group">
                                <label for="title">Judul <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title')
                                    is-invalid
                                @enderror" id="title" name="title" autofocus>
                                  @error('title')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- Isi -->
                            <div class="form-group">
                                <label for="summernote">Isi <span class="text-danger">*</span></label>
                                <textarea id="summernote" name="content" class="form-control @error('content')
                                    is-invalid
                                @enderror"></textarea>
                                @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- Deskripsi -->
                            <div class="form-group">
                                <label for="excerpt">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="excerpt" class="form-control @error('excerpt')
                                    is-invalid
                                @enderror" id="excerpt" cols="4" rows="4"></textarea>
                                 @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- SEO Meta Deskripsi -->
                            <div class="form-group">
                                <label for="meta_description">SEO Meta Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="meta_description" class="form-control @error('meta_description')
                                    is-invalid
                                @enderror" id="meta_description" cols="4" rows="4"></textarea>
                                  @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <!-- Kategori -->
                            <div class="form-group">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="select2 form-select @error('category')
                                    is-invalid
                                @enderror">
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- Tag -->
                            <div class="form-group">
                                <label for="tag">Tag <span class="text-danger">*</span></label>
                                <select class="form-select select2-multiple @error('tag')
                                    is-invalid
                                @enderror" name="tag[]" id="tag" placeholder="Pilih Tag" multiple="multiple">
                                    @foreach ($tags as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                 @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- Penulis -->
                            <div class="form-group">
                                <label for="author">Penulis <span class="text-danger">*</span></label>
                                <select name="author" id="author" class="select2 form-select @error('author')
                                    is-invalid
                                @enderror">
                                    <option value="" selected disabled>Pilih Penulis</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}" {{ auth()->user()->id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                               @error('content')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                            </div>
                            <!-- Tanggal Publish dan Status Publish -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="published">Tanggal Publish</label>
                                        <input type="date" name="published" id="published" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <!-- Hidden input untuk mengirimkan nilai 0 saat tidak dicentang -->
                                        <input type="hidden" name="is_published" value="0">
                                        <!-- Checkbox, jika dicentang akan mengirimkan nilai 1 -->
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" name="is_published" value="1">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Publish</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Gambar -->
                            <div class="form-group">
                                <label for="gambar">Pilih Gambar <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control @error('image')
                                    is-invalid
                                @enderror" id="gambar">
                                 @error('image')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                                 @enderror
                                <div id="previewImage" style="margin-top: 10px;"></div>
                            </div>
                            <!-- Tombol Submit -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/plugin/select2/select2.full.min.js') }}"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $('.select2').select2( {
            theme: 'bootstrap-5'
        } );

         $('.select2-multiple').select2( {
            theme: 'bootstrap-5',
            placeholder: "Pilih Tag"
        } );


        $('#gambar').on('change', function(){
            var file = this.files[0];
            
            if (file) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    // Menampilkan preview gambar
                    $('#previewImage').html('<img src="' + e.target.result + '" alt="Preview Gambar" class="img-thumbnail" style="max-width:200px;">');
                }
                
                reader.readAsDataURL(file);
            } else {
                $('#previewImage').html('');
            }
        });
    </script>
    <script>
    // Frontend validation and configuration
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis konten di sini...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['mybutton', ['quote']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Frontend validation
                    const file = files[0];
                    
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!allowedTypes.includes(file.type)) {
                        toastr.error('Hanya gambar PNG, JPG, atau JPEG yang diperbolehkan.');
                        return;
                    }
                    
                    // Validate file size (max 2MB = 2 * 1024 * 1024 bytes)
                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        toastr.error('Ukuran gambar tidak boleh lebih dari 2MB.');
                        return;
                    }

                    // If validation passes, proceed with upload
                    uploadImage(file);
                }
            }
        });

        function uploadImage(file) {
            const data = new FormData();
            data.append("file", file);
            data.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: '{{ route('posts.upload') }}', // Sesuaikan dengan route Anda
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                success: function(response) {
                    $('#summernote').summernote('insertImage', response);
                },
                error: function(xhr) {
                    // Handle different types of errors
                    let errorMessage = 'Terjadi kesalahan saat mengunggah gambar.';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    
                    toastr.error(errorMessage);
                }
            });
        }
    });
    </script>
@endpush
