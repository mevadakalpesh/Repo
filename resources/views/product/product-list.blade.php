@extends('layouts.app')

@section('content')
<div class="container">
  <div class="title d-flex mb-3">
    <h3>Products</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addproduct">
      Add New Product
    </button>
  </div>
  <div class="row">
    <div style="display:none" class="ajax-success-alert alert alert-success"></div>
    <div class="table-responsive">
      <table id="product-table" class="table table-bordered data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>SKU</th>
            <th width="105px">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- add new product modle  -->
    <div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="addproductLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addproductLabel">Add Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="add-product-form" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">

            <div class="modal-body">

              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
              </div>

              <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" class="form-control">
              </div>

              <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control">
              </div>

              <div class="form-group">
                <label>Description</label>
                <textarea name="description" cols="30" rows="10" class="form-control"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="error-message text-danger">
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- edit product modle  -->
    <div class="modal fade" id="editproduct" tabindex="-1" aria-labelledby="editproductLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addproductLabel">Edit Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="edit-product-form" action="{{ route('product.update','%ID%') }}" method="POST" enctype="multipart/form-data">
              @method('PUT')
            <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
              </div>

              <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" class="form-control">
              </div>

              <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control">
              </div>

              <div class="form-group">
                <label>Description</label>
                <textarea name="description" cols="30" rows="10" class="form-control"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="error-message text-danger">
            </div>
          </form>
        </div>
      </div>
    </div>


  </div>
</div>


@endsection

@push('js')
<script type="text/javascript">
  $(function () {
    
    var dataTable = $('#product-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: "{{ route('product.index') }}",
      columns: [{
        data: 'DT_RowIndex', name: 'DT_RowIndex'
      },
        {
          data: 'name', name: 'name'
        },
        {
          data: 'price', name: 'price'
        },
        {
          data: 'description', name: 'description'
        },
        {
          data: 'sku', name: 'sku'
        },
        {
          data: 'action', name: 'action', orderable: false, searchable: false
        },
      ]
    });


    $(document).on('submit', '#add-product-form', function(e) {
      e.preventDefault();
      storeOrUpdate($(this),"POST","addproduct",new FormData(this));
    });
    
    $(document).on('submit', '#edit-product-form', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      formData.append('_method', 'PUT');
      storeOrUpdate($(this),"POST","editproduct",formData);
    });
    
    function storeOrUpdate(currenSelector,methodType,modalId,formData){
      $(`#${modalId} .error-message`).html(" ");
      $.ajax({
        url: currenSelector.attr('action'),
        type: methodType,
        data: formData,
        processData: false, 
        contentType: false, 
        headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if(response.error){
            var htmldata = "<ul>";
             $.each(response.data,function(key,value){
               htmldata += "<li>"+value+"</li>";
             });
             htmldata  += "</ul>";
             $(`#${modalId} .error-message`).html(htmldata);
          } else {
            $('.ajax-success-alert').show().text(response.message);
            $(`#${modalId} .modal-footer button[data-bs-dismiss]`).trigger('click');
            currenSelector.trigger('reset');
            dataTable.clear().draw();
          }
        },
        error: function(xhr, status, error) {
          console.error("Request failed with status: " + status);
        }
      });
    }
   
     $(document).on('click','.edit-product',function(){
       $.ajax({
        url: $(this).attr('data-editurl'),
        type: "GET",
        success: function(response) {
          $('#edit-product-form input, #edit-product-form textarea , #edit-product-form select').each(function(key,value ){
            var inputName = $(value).attr('name');
            $(value).val(response.data[inputName]);
          });
          var editUrl = $('#edit-product-form').attr('action');
          var newUrl = editUrl.replace('%ID%',response.data.id);
          $('#edit-product-form').attr('action',newUrl);
          console.log('response',response);
        },
        error: function(xhr, status, error) {
          console.error("Request failed with status: " + status);
        }
      });
     });

    $(document).on('click', '.delete-product', function() {
      if (confirm('are you sure delete this product?')) {
        $.ajax({
          url: $(this).attr('data-deleteurl'),
          type: 'DELETE',
          dataType: 'json',
          headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if(!response.error){
              dataTable.clear().draw();
              $('.ajax-success-alert').show().text(response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        });
      }
    });
  });
</script>

@endpush