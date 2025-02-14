<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEdit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalEdit">Edit kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditCategory">
            @csrf
            @method('PUT')
            <input type="hidden" id="editCategoryId" name="id">
            <div class="form-group">
                <label for="editName">Nama</label>
                <input type="text" class="form-control" id="editName" name="name" required>
                <div class="invalid-feedback name-error"></div>
            </div>
            <div class="form-group">
                <label for="editSlug">Slug</label>
                <input type="text" class="form-control" id="editSlug" name="slug" readonly>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-success" id="btnUpdate">Update</button>
      </div>
    </div>
  </div>
</div>