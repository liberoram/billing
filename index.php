<?php include('header.php'); ?> 
<section id="featured-products" class="product-store"> 
  <div class="container-md"> 
     <input type="hidden" id="CurrentBill" value="" name="">
     <input type="hidden" id="discount" value="0" name="">
     <input type="hidden" id="ACCharge" value="0" name="">

     <div class="product-content">
      <div class="col-lg-12 col-md-12 mb-6">
         <div class="table-responsive" id="print_Area">
            <div id="printheader">
                <div><b>New Nagalakshmi</b></div>
                <div>No,252,Kamaraj Salai</div>
                <div>Madurai - 625 009</div>
                <div>Ph :  02452-2329090</div>
                <div>GSTIN :33AIBPK6468M2ZW</div>
                <div>FASSAI LC NO : 12418012002012</div>
                <div>Cash bill</div>
            </div>
            <div style="font-size: 12px;">Bill No : <span class="PrintBill"></span> </div>
            <div style="font-size: 12px;">Date : <?php echo date('d/m/Y - h:i' ); ?></div>
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0" style="line-height:20px;">
              <thead>
                <tr style="border-bottom:3px dotted #000;">
                  <th>SNo</th>
                  <th>Item</th>
                  <th>Rate</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th class="item-remove">Action</th> 
              </tr>
          </thead>
          <tbody class ="item-list-result"></tbody>
          <tfoot class="summary">
            <tr style="border-top:3px dotted #000;">
              <td colspan="4" class="summary-item">Subtotal</td>
              <td> <span id="subtotal">0.00</span></td> 
              <td class="item-remove"></td>
          </tr>  
          <tr> 
              <span id="tax" style="display:none;">0.00</span>
              <td  colspan="4"   class="summary-item">SGST (2.5%)</td>
              <td><span id="Ctax">0.00</span></td> 
              <td class="item-remove"></td>
          </tr>
          <tr>
           <input type="hidden" id="gst" placeholder="Enter GST %" min="0" max="100" value="5">
           <td  colspan="4"   class="summary-item">CGST (2.5%)</td>
           <td><span id="Gtax">0.00</span></td> 
           <td class="item-remove"></td>
       </tr>
       <tr id="showAcDiv">
        <td colspan="4" class="summary-item">AC (15%)</td>
        <td><span id="BillAc">0.00</span></td> 
        <td class="item-remove"></td>
    </tr>
    <tr style="border-bottom:3px dotted #000;">
     <td colspan="4" class="summary-item">Round Amount <span id="ExcessAmount"></span></td>
     <td style="border-top:3px dotted #000;"><span id="total">0.00</span></td> 
     <td class="item-remove"></td>
 </tr>
           <!-- <tr>
            <td colspan="4" class="summary-item">Round Amount <span id="ExcessAmount"></span></td>
            <td><span id="totalwithAc">0.00</span></td> 
            <td class="item-remove"></td>
        </tr> -->
    </tfoot>
</table> 
<div class="container">
    <div class="row " style="text-align:center;">
     <div style="font-size: 20px;"><span>RS : <span class="printtotal"></span></span><br>
     Thank you... visit again...</div>
 </div>
</div>
</div>
<div class="container">

    <div class="row g-3 padding-small" >
      <div class="col-auto"> 
        <input type="text" class="form-control" id="shortcode" placeholder="Short code">
    </div>
    <div class="col-auto"> 
        <input type="text" class="form-control" id="quantity" placeholder="Qty">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-success mb-3" onclick="newbill()"> <i class="fas fa-plus"></i> New</button>
    </div>
    <div class="col-auto">
        <select class="form-select" id="pendingBillsSelect" onchange="updatebillno(this.value)">
        </select> 
    </div>
    <div class="col-auto"> 
        <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check-input" type="checkbox" value=""  id="isACCharge" onclick="isACCharge(this.id)">
            <label class="form-check-label" for="isACCharge">A/C</label> 
        </div>
        <select class="form-select" id="waiterlist" aria-label="Example select with button addon" onchange="waiterroll(this.id)">
        </select>
        <button class="btn btn-success btn-icon-split"  onclick="payment()">
            <span class="icon text-white-50"><i class="fas fa-check"></i></span>
            <span class="text">Pay now</span>
        </button>
    </div>
    </div>
</div>
</div>
</div>
</div> 

<?php include('footer.php'); ?> 

<script type="text/javascript">
    function waiterroll(id) {
        var data = $("#" + id);
        var selectedOption = data.find(":selected");
        document.getElementById('isACCharge').checked=false;
        if(selectedOption.attr("data-id")=="1")
        {
            document.getElementById('isACCharge').checked=true;
         }
          isACCharge();
    }

    function isACCharge(id)
    {
     $('#showAcDiv').hide();
     if(document.getElementById('isACCharge').checked==true)
     {
         $('#showAcDiv').show();
     }
     calculateTotals();
 }
 var i=0;
 var subtotal=0;
 $(document).ready(function() {
    $("#shortcode").on('keypress', function(event) {
        if (event.which === 13) {  
            const shortcode = $('#shortcode').val(); 
            if (!shortcode) {
                alert("Please enter a product shortcode.");
                return;
            }
            event.preventDefault(); 
            $("#quantity").focus();
        }
    });

    $("#quantity").on('keypress', function(event) {
        if (event.which === 13) { 
            const quantity = parseInt($('#quantity').val(), 10);
            if (!quantity) {
                alert("Please enter a valid quantity.");
                return;
            }
            event.preventDefault(); 
            getProductDetails();
        }
    });
    $("#shortcode").focus();


    document.addEventListener("keydown", function(event) {
        if (event.key === " ") {
            event.preventDefault();
            payment();
        }
    });

});
 function getProductDetails()
 {
    const shortcode = $('#shortcode').val();
    const CurrentBill = $('#CurrentBill').val();
    const quantity = parseInt($('#quantity').val(), 10);
    $.ajax({
        url: 'fetch_product.php',
        type: 'POST',
        data: { shortcode: shortcode,quantity:quantity,CurrentBill:CurrentBill,Purpose:"BILL"},
        success: function(response) {
            const product = JSON.parse(response); 
            if (product.error) {
                alert(product.error);
            } else {
                i++;

                var updatedetails = {};  
                updatedetails.uniqueId = product.uniqueId;
                updatedetails.price = product.price; 
                updatedetails.preqty = quantity; 
                var jsonString = JSON.stringify(updatedetails);  
                var base64Encoded = btoa(jsonString);
 
                const total = product.price * product.qty;  
                const productHtml = `
                <tr class='item' id="line_${product.uniqueId}" data-index='${i}'>
                <td class='item-number'></td>
                <td class='item-name'>${product.tamil}</td>
                <td class='item-price'>${product.price}</td>
                <td class='item-qty' contenteditable="true" onkeydown="checkEnterKey(event, this)" oninput="allowNumbers(this)" id="${base64Encoded}" onblur="updateqty(this)">${product.qty}</td>
                <td class='item-total' id="total_${product.uniqueId}">${total.toFixed(2)}</td>
                <td class='item-remove'><i class="fa fa-trash" id='${product.uniqueId}' onclick='removeItem(this.id)'></i></td>
                </tr>`;
                $('.item-list-result').append(productHtml); 
                subtotal += total;
                calculateTotals();
            } 
            $('#shortcode').val('').focus();
            $('#quantity').val('');
        }
    });
}

function calculateTotals()
{
    // Fetch input values or set defaults
    const discountRate = parseFloat($('#discount').val()) || 0;
    const gstRate = parseFloat($('#gst').val()) || 0;

// Calculate taxable amount and tax
    const taxableAmount = subtotal - discountRate;
    const tax = taxableAmount * (gstRate / 100);
    const grandTotal = taxableAmount + tax;

// Split tax into Central (Ctax) and General (Gtax)
    const Ctax = tax / 2;
    const Gtax = tax / 2;

// Update the DOM for subtotal and taxes
    $('#subtotal').text(`${subtotal.toFixed(2)}`);
    $('#Ctax').text(`${Ctax.toFixed(2)}`);
    $('#Gtax').text(`${Gtax.toFixed(2)}`);
    $('#tax').text(`${tax.toFixed(2)}`);

    var additionalCharge =0;
    if(document.getElementById('isACCharge').checked==true)
    {
// Calculate the AC charge (15% of grandTotal)
      additionalCharge = grandTotal * 0.15;
  }

// Calculate new total including AC charge
  const totalWithAC = grandTotal + additionalCharge;

// Calculate the excess amount based on totalWithAC
  let excessAmount = totalWithAC - Math.floor(totalWithAC); 
// Round up if excess is 50 paise or more, otherwise round down
  let roundedTotal = excessAmount >= 0.50 ? Math.ceil(totalWithAC) : Math.floor(totalWithAC);

// Recalculate excess amount in paisa for display
  excessAmount = (roundedTotal - totalWithAC) * 100;

// Update the DOM for rounded total, AC charge, and excess amount
  $('#total').text(`${roundedTotal.toFixed(2)}`);
  $('.printtotal').text(`${roundedTotal.toFixed(2)}`);
  $('#ExcessAmount').html(`(${excessAmount > 0 ? '+' : ''}${excessAmount.toFixed(0)})`);

// Round totalWithAC to nearest integer (to show as whole currency value)
let roundedTotalWithAC = Math.round(totalWithAC); // Round to nearest integer

if(document.getElementById('isACCharge').checked==true)
{
    if ($('#ACCharge').length) {
    $('#ACCharge').val(`${additionalCharge.toFixed(2)}`); // Hidden input value
}
if ($('#BillAc').length) {
    $('#BillAc').text(`${additionalCharge.toFixed(2)}`);  // Show AC on bill
}
}
else
{
  $('#ACCharge').val(0);
  $('#BillAc').text('0.00');
} 

}

function removeItem(id)
{ 
  $.ajax({
    url: 'fetch_product.php',
    type: 'POST',
    data: { deleteid: id },
    success: function(response) {
        var itemTotal =  $('#total_'+id).text();
        subtotal -= itemTotal;
        $('#line_'+id).remove();
        calculateTotals();
    }
});
}

function newbill() {

    if($("#pendingBillsSelect").children('option').length > 2)
    {
        return false;
    }
    $.ajax({
        url: 'fetch_product.php',
        type: 'POST',
        data: { Purpose: 'NEWBILL' },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    $("#CurrentBill").val(data.new_billno); 
                    $(".PrintBill").html(data.new_billno); 
                    var selectElement = document.getElementById('pendingBillsSelect'); 
                    var newOption = document.createElement('option'); 
                    newOption.text = 'Bill No: ' + (data.new_billno); 
                    newOption.value = data.new_billno;  
                    selectElement.appendChild(newOption);
                    $("#pendingBillsSelect").val(data.new_billno)
                    updatebillno(data.new_billno);
                } else if (data.error) {
                    alert("Error: " + data.error);
                }
            } catch (e) {
                alert("Error processing response");
            }
        },
        error: function() {
            alert("AJAX request failed. Please try again.");
        }
    });
}

function updatebillno(val)
{
    subtotal=0;
    $("#CurrentBill").val(val);
    $(".PrintBill").html(val);
    $(".item-list-result").html('');

    document.getElementById('isACCharge').checked=false;
    isACCharge();
    $.ajax({
        url: 'fetch_product.php',
        type: 'POST',
        data: { Purpose: 'GETPENDINGBILLS',billno:val},
        success: function(response) {
            try {

                $('#shortcode').focus(); 
                const result = JSON.parse(response); 
                if(result.PendingBill)
                {
                 const products = result.PendingBill;
                 products.forEach((product, i) => {
                    i = i+1;
                     var updatedetails = {};  
                     updatedetails.uniqueId = product.uniqueId;
                     updatedetails.price = product.amt; 
                     updatedetails.preqty = product.quantity; 
                     var jsonString = JSON.stringify(updatedetails);  
                     var base64Encoded = btoa(jsonString);

                    const total = product.amt * product.quantity; 
                    const productHtml = `
                    <tr class='item' id="line_${product.uniqueId}" data-index='${i}'>
                    <td class='item-number'></td>
                    <td class='item-name'>${product.tamil}</td>
                    <td class='item-price'>${product.amt}</td>
                    <td class='item-qty' contenteditable="true" onkeydown="checkEnterKey(event, this)" oninput="allowNumbers(this)" id="${base64Encoded}" onblur="updateqty(this)">${product.quantity}</td>

                    <td class='item-total' id="total_${product.uniqueId}">${total.toFixed(2)}</td>
                    <td class='item-remove'><i class="fa fa-trash" id='${product.uniqueId}' onclick='removeItem(this.id)'></i></td>
                    </tr>`;
                    subtotal += total;
                    $('.item-list-result').append(productHtml);
                });
                 calculateTotals();
             }

         } catch (e) {
            console.error('Failed to parse response:', e);
        }

    }
});
}
function checkItemes()
{ 
  $.ajax({
    url: 'fetch_product.php',
    type: 'POST',
    data: { checkItemes: 'checkItemes'},
    success: function(response) {
        try {

            $('#shortcode').focus(); 
                const result = JSON.parse(response); // Parse the full JSON response
                const Billno = result.Billno; 
                $("#CurrentBill").val(Billno);
                $(".PrintBill").html(Billno);

                if (result.listofpendingbills) { 
                    var selectElement = document.getElementById('pendingBillsSelect'); 

                    selectElement.innerHTML = '<option value="">pending bill</option>'; 
                    var pendingBills = result.listofpendingbills; 

                    for (var i = 0; i < pendingBills.length; i++) {
                        var option = document.createElement('option');
                        option.text = 'Bill No: ' + pendingBills[i].billno;
                        option.value = pendingBills[i].billno;
                        if (pendingBills[i].billno == Billno) {
                            option.selected = true;  
                        }
                        selectElement.appendChild(option); 
                    }
                }

                if (result.waiterslist) { 
                    var selectwaiter = document.getElementById('waiterlist'); 
                    selectwaiter.innerHTML = '<option value="">Choose...</option>'; 
                    var pendingBills = result.waiterslist; 

                    for (var i = 0; i < pendingBills.length; i++) {
                        var option = document.createElement('option');
                        option.text = pendingBills[i].name;
                        option.value = pendingBills[i].name; 
                        option.setAttribute('data-id', pendingBills[i].isacpersion );
                        selectwaiter.appendChild(option); 
                    }
                }

                updatebillno(Billno);
                if(result.PendingBill)
                {
                   const products = result.PendingBill;

                   products.forEach((product, i) => {
                    const total = product.amt * product.quantity; 
                    var updatedetails = {};  
                    updatedetails.uniqueId = product.uniqueId;
                    updatedetails.price = product.amt; 
                    updatedetails.preqty = product.quantity; 
                    var jsonString = JSON.stringify(updatedetails);  
                    var base64Encoded = btoa(jsonString);
                    const productHtml = `
                    <tr class='item' id="line_${product.uniqueId}" data-index='${i}'>
                    <td class='item-number'></td>
                    <td class='item-name'>${product.tamil}</td>
                    <td class='item-price'>${product.amt}</td>
                    <td class='item-qty' contenteditable="true" onkeydown="checkEnterKey(event, this)" oninput="allowNumbers(this)" id="${base64Encoded}" onblur="updateqty(this)">${product.quantity}</td>

                    <td class='item-total' id="total_${product.uniqueId}">${total.toFixed(2)}</td>
                    <td class='item-remove'><i class="fa fa-trash" id='${product.uniqueId}' onclick='removeItem(this.id)'></i></td>
                    </tr>`;
                    subtotal += total;
                    $('.item-list-result').append(productHtml);
                });
                   calculateTotals();
               }

           } catch (e) {
            console.error('Failed to parse response:', e);
        }

    }
});
}
checkItemes();

function updateqty(element) {
    const newQuantity = element.innerText.trim();
         var decodedString = atob(element.id);
        var decodedDetails = JSON.parse(decodedString);
        if(decodedDetails.preqty ==newQuantity )
        {
            return false;
        }
        if (isNaN(newQuantity) || newQuantity <= 0) {
            alert('Invalid quantity!');
            element.innerText = decodedDetails.preqty;
            return;
        }
        
    var total = decodedDetails.price * newQuantity;
    $("#total_"+decodedDetails.uniqueId).text(total.toFixed(2));
    $.ajax({
        url: 'fetch_product.php',
        type: 'POST',
        data: { Purpose: 'UPDATEQTY',BillDetails: element.id,newQuantity:newQuantity},
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                   window.location.reload();
                } else if (data.error) {
                    alert("Error: " + data.error);
                }
            } catch (e) {
                alert("Error processing response");
            }
        },
        error: function() {
            alert("AJAX request failed. Please try again.");
        }
    });
}

function allowNumbers(element) {
    const value = element.innerText; 
    element.innerText = value.replace(/[^0-9]/g, '');
    const range = document.createRange();
    const selection = window.getSelection();
    range.selectNodeContents(element);
    range.collapse(false); // Collapse the range to the end
    selection.removeAllRanges();
    selection.addRange(range);
}

function checkEnterKey(event, element) {
    // Check if Enter key (key code 13) was pressed
    if (event.key === "Enter") {
        event.preventDefault(); 
        if (!allowNumbers(element)) {
           updateqty(element);
        }
    }
}


function payment()
{ 
    const CurrentBill = $('#CurrentBill').val();
    const subtotal = $('#subtotal').text();
    const discount = $('#discount').val();
    const gst = $('#gst').val();
    const tax = $('#tax').text();
    const discountamt = $('#discount').val();
    const total = $('#total').text();
    const waitername = $('#waiterlist').val();
    
    if(total=='0.00')
    {
        return false;
    }
    var ac=0;
    if(document.getElementById('isACCharge').checked==true)
    {
        ac = $('#ACCharge').val();
    }

    const data = {
        CurrentBill: CurrentBill,
        subtotal: subtotal,
        discount: discount,
        gst: gst,
        tax: tax,
        discountamt: discountamt,
        total: total, 
        masterTable:"masterTable",
        ac:ac,
        waitername:waitername
    };
    $.ajax({
        url: 'fetch_product.php',
        type: 'POST',
        data: data,
        success: function(response) {
            const product = JSON.parse(response); 
            if (product.error) {
                alert(product.error);
            } else {
             var selectElement = document.getElementById('pendingBillsSelect');
             for (var i = 0; i < selectElement.options.length; i++) {
                if (selectElement.options[i].value === CurrentBill) {
                    selectElement.remove(i);
                    break;   
                }
            }
            printDiv('print_Area');
        } 
    }
});
}

function printDiv(divId) {
    document.getElementById('isACCharge').checked=false;

    $(".item-remove").hide();
    $("#printheader").show();

    var content = document.getElementById(divId).innerHTML; 
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

</script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;700&display=swap" rel="stylesheet">

<style type="text/css">
    body
    {
        counter-reset: Serial;           /* Set the Serial counter to 0 */
        font-family: 'Noto Sans Tamil', sans-serif;
    }

    .item-list-result tr td:first-child::before
    {
      counter-increment: Serial;      /* Increment the Serial counter */
      content:  counter(Serial); /* Display the counter */
  } 
  #showAcDiv,#printheader
  {
    display: none;
}
.fa-trash {
  cursor: pointer;
}
#printheader {
  text-align: center;
}
.item-qty:focus {
  border: 0 !important; /* Removes the border when focused */
  outline: none; /* Removes default focus outline */
}
</style>
</body>

</html>