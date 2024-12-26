<?php include('header.php'); ?>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Waiter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addProductForm" method="post">
            <div id="message"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-8 col-sm-6">
                                <label for="price">Name</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" id="isacpersion" value="" aria-label="Checkbox for following text input">
                                    </div>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" type="text" id="name" name="name" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="col-8 col-sm-6">
                                <label for="price">Mobile</label>
                                <input class="form-control" type="number" id="Mobile" name="Mobile" step="0.01" placeholder="Enter Mobile No" required>
                                <span class="error" id="Mobile-error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="row"> 
                            <div class="col-8 col-sm-6">
                                <label for="ac">Status</label>
                                <select  class="form-control"  id="Status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </form>
    </div>
      <div class="modal-footer">
         <input type="hidden" id="EditProID" value="" name="">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveitems();">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="d-grid gap-2 d-md-flex justify-content-md-end p-3">
<button type="button" class="me-md-2 btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
  <i class="fa fa-plus"></i> Add Waiter
</button>
</div>

<section id="featured-products" class="product-store"> 
  <div class="container-md">  
    <div class="product-content padding-small">
      <div class="col-lg-12 col-md-12 mb-6">
       <div class="table-responsive" id="print_Area">
        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>SNo</th>
              <th>Name</th>
               <th>Mobile</th>
              <th>A/C</th>
              <th>Status</th>
              <th>Action</th> 
            </tr>
          </thead>
          <tbody id="product-list"></tbody>
        </table> 
         <div id="pagination"></div> 
      </div>
    </div>
  </div>
</section> 
<?php include('footer.php'); ?> 

<script type="text/javascript">
    function loadProducts(page,obj) {
        $.ajax({
            url: 'fetch_product.php',
            type: 'POST',
            data: { page: page,isWaiter:"isWaiter",'sdata':obj },
            success: function(response) {
                let productsHtml = '';
                var i=1;
                response.products.forEach(function(product) { 
                    productsHtml += `<tr>
                    <td>${i}</td>
                    <td>${product.name}</td> 
                    <td>${product.Mobile}</td>
                    <td>${product.isacpersion}</td>
                    <td>${product.Status }</td>
                    <td>
                    <button class="btn btn-edit" onclick="editpro('${product.id}')"><i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-delete" onclick="deletepro('${product.id}')"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                    </tr>`;
                    i++;
                });
                $('#product-list').html(productsHtml);

                let paginationHtml = '';
                const totalPages = response.total_pages;
                const currentPage = response.current_page;

                if (currentPage > 1) {
                    paginationHtml += `<a href="#" class="page-link" data-page="1">First</a> `;
                    paginationHtml += `<a href="#" class="page-link" data-page="${currentPage - 1}">« Previous</a> `;
                } else {
                    paginationHtml += `<span class="page-link disabled">First</span> `;
                    paginationHtml += `<span class="page-link disabled">« Previous</span> `;
                }

                for (let i = 1; i <= totalPages; i++) {
                    paginationHtml += `<a href="#" class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a> `;
                }

                if (currentPage < totalPages) {
                    paginationHtml += `<a href="#" class="page-link" data-page="${currentPage + 1}">Next »</a> `;
                    paginationHtml += `<a href="#" class="page-link" data-page="${totalPages}">Last</a>`;
                } else {
                    paginationHtml += `<span class="page-link disabled">Next »</span> `;
                    paginationHtml += `<span class="page-link disabled">Last</span>`;
                }

                $('#pagination').html(paginationHtml);
            }
        });
    }
    $(document).ready(function() {

        loadProducts(1,''); 
        $('#pagination').on('click', '.page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadProducts(page,'');
        });

        document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('exampleModalLabel').innerHTML = "Add Waiter";
                    $("#EditProID").val('');
                    $("#addProductForm").trigger("reset");
                });
      });
 
    function deletepro(id) { 
        if (confirm("Are you sure you want to delete this product?")) { 
            $.ajax({
                url: 'fetch_product.php',
                type: 'POST',
                data: {deletewaiter:'deletewaiter', productID: id },
                success: function(response) {
                    const product = JSON.parse(response);
                    if (product.error) {
                        alert(product.error);
                    } else {
                        alert(product.success);
                        window.location.reload();
                    }
                }
            });
        } else { 
            alert("Product deletion canceled.");
        }
    } 

    function editpro(id)
    {
        $("#exampleModalLabel").html('Update Product');
        $("#addProductForm")[0].reset();
        $.ajax({
            url: 'fetch_product.php',
            type: 'POST',
            data: { iseditWaiter: 'iseditWaiter', productID:id },
            success: function(response) {
                const data = JSON.parse(response);
                var name =data[0].name ?? "";
                var Mobile  =data[0].Mobile  ?? "";
                var isacpersion    =data[0].isacpersion  ?? "";
                var id    =data[0].id  ?? "";
                var Status    =data[0].Status  ?? "";
                $("#name").val(name);
                $("#Mobile").val(Mobile);

                if (isacpersion == 1) {
                    $("#isacpersion").prop("checked", true);
                } else {
                    $("#isacpersion").prop("checked", false);
                } 
                $("#Status").val(Status);
                $("#EditProID").val(id);
                var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
                modal.show(); 
            }
        });
    }

    function saveitems()
    {

      let errorMessages = [];
      if ($("#name").val().trim() === "") {
        errorMessages.push("Name is required.");
    } else if ($("#Mobile").val().trim()== "") {
        errorMessages.push("Mobile is required.");
    } 

    if (errorMessages.length > 0) {
        const errorHtml = errorMessages.map(msg => `<p class='error'>${msg}</p>`).join("");
        $("#message").html(errorHtml);
        return false;
    }
        $(".error").text("");
        var isacpersion = 0;
        if ($("#isacpersion").is(":checked")) {
            isacpersion = 1;
        }
        var formData = {
            name: $("#name").val(),
            Mobile: $("#Mobile").val(),
            isacpersion:isacpersion,
            Status: $("#Status").val(),
            EditProID: $("#EditProID").val(),
            addwaiter:'addwaiter'
        };
        $.ajax({
            url: 'fetch_product.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $("#message").html("<p class='success'>"+data.success+"</p>");
                            $("#addProductForm")[0].reset();  // Reset the form
                            window.location.reload();
                        } else if (data.error) {
                            $("#message").html("<p class='error'>"+data.error+"</p>"); 
                        } else
                        {
                           $("#message").html("<p class='error'>some issues, Please try again!</p>");
                       }
                   },
                   error: function() {
                    $("#message").html("<p class='error'>An error occurred. Please try again.</p>");
                }
            });

    }
</script>
<style type="text/css"> 
.pagination {
        text-align: center;
        margin: 20px 0;
    }
    .page-link {
        display: inline-block;
        margin: 0 5px;
        padding: 10px 15px;
        font-size: 1em;
        text-decoration: none;
        color: #3498db;
        background-color: #ecf0f1;
        border-radius: 5px;
        border: 1px solid #3498db;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .page-link.active, .page-link:hover {
        background-color: #3498db;
        color: #fff;
    }
    .page-link.disabled {
        color: #bdc3c7;
        border-color: #bdc3c7;
        cursor: not-allowed;
    }
</style>
</body>

</html>