<?php include('header.php'); ?> 

 
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span class="billtxt"></span> - Bill Details </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"> 
        <form id="addProductForm" method="post">
         <div id="printheader" style="text-align: center;">
          <div><b>New Nagalakshmi</b></div>
          <div>No,252,Kamaraj Salai</div>
          <div>Madurai - 625 009</div>
          <div>Ph :  02452-2329090</div>
          <div>GSTIN :123</div>
          <div>GSTIN :123</div>
          <div>Cash bill</div>
        </div>
        <div>Bill No : <span class="billtxt"></span> </div>
        <div>Date : <span class="Htmlbilldate"></span></div>

        <table id="product-table"  class="table table-hover">
          <thead>
            <tr> 
              <th>S#</th>
              <th>Item</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody id="listofsales"></tbody>
          <tbody id="otherTotal"></tbody>             
        </table> 
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
      </div>
    </div>
  </div>
</div>
 

<section id="featured-products" class="product-store"> 
  <div class="container-md">  
    <div class="product-content padding-small">
      <div class="col-lg-12 col-md-12 mb-6"> 
         <table>
            <tr>
                <td><input type="number"  class="form-control bg-light border-1 small" id="bill-search" onblur="resetdata(this.value)" placeholder="Search by Bill Number"></td>
                <td><input type="date"  class="form-control bg-light border-1 small" id="from_date"> </td>
                <td><input type="date"   class="form-control bg-light border-1 small"id="to_date"></td>
                <td><button  class="btn btn-success" onclick="searchdata();"><i class="fa fa-search"></i> Search</button></td>
                <td><button  class="btn btn-primary" onclick="printDiv('printReport');"><i class="fa fa-print"></i> Print</button></td> 
                <td><button  class="btn btn-primary" onclick="exportexcel();"><i class="fa fa-file-excel"></i> Export</button></td> 
            </tr>
        </table>
    </div>

    <div class="col-lg-12 col-md-12 mb-6"> 
      <div class="table-responsive">
       <div id="printReport"> 
        <table id="product-table"  class="table table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr> 
            <th>S No</th>
              <th>Bill No</th>
              <th>Date</th>
                <th>Amount</th>
                <th>GST 5%</th> 
                <th>Net Amount</th> 
              <th class="item-remove">Actions</th>
            </tr>
          </thead>
          <tbody id="product-list"></tbody>
        </table>
      </div>
      <nav aria-label="Page navigation example">
      <ul id="pagination" class="pagination"></ul>
    </nav>
    </div>
  </div>

  </div>
</section> 
<?php include('footer.php'); ?> 
 <script type="text/javascript">
    function searchdata() {
      var from_date = $("#from_date").val();
      var to_date = $("#to_date").val();
      var bill_search = '';

      if(from_date === "" || to_date === "") {
        alert("Please enter select date.");
        return false;  
    }
    var obj = {from_date:from_date,to_date:to_date,bill_search:bill_search};
    obj =JSON.stringify(obj); 
    loadProducts(1,obj);
}
    function exportexcel() {
      var from_date = $("#from_date").val();
      var to_date = $("#to_date").val();
      var bill_search = '';

      if(from_date === "" || to_date === "") {
        alert("Please enter select date.");
        return false;  
    }
    var obj = "from_date="+from_date+"&to_date="+to_date+"&bill_search="+bill_search;

    window.location.href="./excel.php?"+obj;
}


function resetdata(val)
{
    if(val=="")
    {
      loadProducts(1,'');
  }
}
 

function loadProducts(page,obj) {$.ajax({
    url: 'fetch_product.php',
    type: 'POST',
    data: { page: page, isSalseReport: "isSalseReport",'sdata':obj},
    success: function(response) {
        let productsHtml = '';
        let totalPrice = 0;
         let totalGST = 0;
          let totalSUB = 0;
        let i = 1;

        response.products.forEach(function(product) {
          var discountAmt=0;
            var param = JSON.stringify(product);
            var b64encoded = btoa(param);  
            var types = product.accharge>0 ? "fa-home" : "fa-university";
            productsHtml += `<tr> 
             <td>${i} </td>
            <td>${product.billno}</td>
            <td>${product.billdate}</td>
            <td>${product.Subtotal}</td>
            <td>${product.Tax}</td> 
            <td>${product.total }</td>
            <td class="item-remove">
            <button class="btn btn-edit" onclick="viewbill('${b64encoded}','')"><i class="fa ${types}"></i> Details</button>
            <button class="btn btn-edit" onclick="viewbill('${b64encoded}','print')"><i class="fa fa-print"></i> print</button>
            <button class="btn btn-edit" onclick="editbill('${product.billno}','${discountAmt}','${product.accharge}')"><i class="fa fa-edit"></i>  Edit</button> Name :  ${product.waitername}
            </td>
            </tr>`;
            totalGST += parseFloat(product.Tax ?? 0) / 2;// Accumulate the total price
            totalPrice += parseFloat(product.total ?? 0);// Accumulate the total price
            totalSUB += parseFloat(product.Subtotal ?? 0);// Accumulate the total price
           i++;
       });

        <?php
        if(isset($_SESSION['username']) && $_SESSION['username']!="")
        {
            ?>
        productsHtml += `<tr>
         <td colspan='4'></td> 
        <td>Subtotal</td> 
        <td><strong>${totalSUB.toFixed(2)}</strong></td> 
        <td></td>
        </tr>`;

        productsHtml += `<tr>
        <td colspan='4'></td> 
        <td>CGST</td> 
        <td><strong>${totalGST.toFixed(2)}</strong></td> 
        <td></td>
        </tr>`;
         productsHtml += `<tr>
         <td colspan='4'></td> 
        <td>SGST</td> 
        <td><strong>${totalGST.toFixed(2)}</strong></td> 
        <td></td>
        </tr>`;

         productsHtml += `<tr>
         <td colspan='4'></td> 
        <td>Total</td> 
        <td><strong>${totalPrice.toFixed(2)}</strong></td> 
        <td></td>
        </tr>`;
<?php } ?>
        $('#product-list').html(productsHtml);

        let paginationHtml = '';
        const totalPages = response.total_pages;
        const currentPage = response.current_page;

        if (currentPage > 1) {
            paginationHtml += `<li class="page-item"><a href="#" class="page-link" data-page="1">First</a></li>`;
            paginationHtml += `<li class="page-item"><a href="#" class="page-link" data-page="${currentPage - 1}">« Previous</a></li>`;
        } else {
            paginationHtml += `<li class="page-item"><span class="page-link disabled">First</span></li>`;
            paginationHtml += `<li class="page-item"><span class="page-link disabled">« Previous</span></li>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<li class="page-item"><a href="#" class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a></li>`;
        }

        if (currentPage < totalPages) {
            paginationHtml += `<li class="page-item"><a href="#" class="page-link" data-page="${currentPage + 1}">Next »</a> </li>`;
            paginationHtml += `<li class="page-item"><a href="#" class="page-link" data-page="${totalPages}">Last</a></li>`;
        } else {
            paginationHtml += `<li class="page-item"><span class="page-link disabled">Next »</span> </li>`;
            paginationHtml += `<li class="page-item"><span class="page-link disabled">Last</span></li>`;
        }

        $('#pagination').html(paginationHtml);
    }
});
}
function printDiv(divId) {
    $(".item-remove").hide();
    var content = document.getElementById(divId).innerHTML; 
    var originalContent = document.body.innerHTML;

    // Replace the body content with the specific div content
    document.body.innerHTML = content;
    
    // Trigger the print dialog
    window.print();

    // Restore the original page content after printing
    document.body.innerHTML = originalContent;
    $(".item-remove").show();
    setTimeout(function()
    {
       $(".modal-backdrop,#exampleModal").hide();
    },100);    
}
$(document).ready(function() {

    loadProducts(1,''); 
    $('#pagination').on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadProducts(page,'');
    });

    $('#bill-search').on('keypress', function(e) {
        if (e.which === 13) { // Enter key pressed
            const bill_search = $(this).val().trim();
            var obj = {bill_search:bill_search};
            obj =JSON.stringify(obj); 
            loadProducts(1, obj);
        }
    });

});
function editbill(val,discount,ac)
{
    window.location="edit_bill.php?bill="+val+"&discount="+discount+"&ac="+ac;
}

function viewbill(b64encoded,isprint)
{
  $("#printheader").hide();
   var b64decoded = atob(b64encoded);
   var deparam = JSON.parse(b64decoded); 
   var billno = deparam.billno; 
   var billdate = deparam.billdate; 
    var isAcChange = (deparam.accharge && deparam.accharge != "" && deparam.accharge != 0) ? deparam.accharge : 0;
    $(".Htmlbilldate").html(billdate);
   var discount = 0;
   var cgst = parseFloat(deparam.cgst);
     var sgst = parseFloat(deparam.sgst);
       var gst = parseFloat(cgst) + parseFloat(sgst);
   $(".billtxt,#billnohtml").html(billno);
   $.ajax({
    url: 'fetch_product.php',
    type: 'POST',
    data: { viewbill: 'viewbill', productID: billno },
    success: function(response) {
        const datas = JSON.parse(response);
        var i = 1;
        let productsHtml = '';
        let isotherTotal = '';
        let totalSum = 0;
        let discountAmt = 0;
        let gstAmt = 0;
        let grandTotal = 0; 
        datas.forEach(function(product) {
            let productTotal = parseFloat(product.total);
            totalSum += productTotal;
            productsHtml += `<tr> 
            <td>${i}</td>
            <td>${product.tamil}</td>
            <td>${product.quantity}</td>
            <td>${product.amt}</td>  <!-- Add  symbol -->
            <td>${product.total }</td>  <!-- Add  symbol -->
            </tr>`;
            i++;
          });

        const taxableAmount = totalSum - discount;
        const tax = taxableAmount * (gst / 100);
        grandTotal = taxableAmount + tax;
        var additionalCharge =0;
        if(isAcChange>0)
        {
           additionalCharge = grandTotal * 0.15;
        }
        const totalWithAC = grandTotal + additionalCharge;
        let excessAmount = totalWithAC - Math.floor(totalWithAC);  
        let roundedTotal = excessAmount >= 0.50 ? Math.ceil(totalWithAC) : Math.floor(totalWithAC); 
        excessAmount = (roundedTotal - totalWithAC) * 100; 
        const cgst = tax / 2; 
        isotherTotal += `
        <tr> 
        <td colspan="4">Subtotal:</td> 
        <td>${totalSum.toFixed(2)}</td> 
        </tr>
        <tr> 
        <td colspan="4">SGST (2.5%)</td> 
        <td>${cgst.toFixed(2)}</td> 
        </tr>
        <tr> 
        <td colspan="4">CGST (2.5%)</td>
        <td>${cgst.toFixed(2)}</td> 
        </tr>`;
        if(isAcChange>0)
        {
        isotherTotal += `<tr id="showAcDiv">
        <td colspan="4" class="summary-item">AC (15%)</td>
        <td><span id="BillAc">${additionalCharge.toFixed(2)}</span></td> 
        </tr>`;
        }
         isotherTotal += `<tr>
        <td colspan="4" class="summary-item">Round Amount <span>${excessAmount > 0 ? '+' : ''}${excessAmount.toFixed(0)})</span></td>
        <td><span id="total">${roundedTotal.toFixed(2)}</span></td> 
        </tr>`;

        $('#otherTotal').html(isotherTotal);
        $('#listofsales').html(productsHtml);

        var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();
        $('#list_of_items').hide();
        if(isprint)
        {
           $("#printheader").show();
            printDiv('addProductForm');
        }
    }
});
}
</script>
</body>
<style type="text/css">
  #printheader
  {
    text-align: center;
  }
</style>
</html>