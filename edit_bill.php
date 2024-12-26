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
                        <div style="font-size: 12px;">Bill No : <?php echo $_GET['bill']; ?></div>
                        <div style="font-size: 12px;">Date : <?php echo date('d/m/Y - h:i' ); ?></div>
                        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
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
                            </tfoot>
                        </table>
                        <div class="container">
                            <div class="row " style="text-align:center;">
                                <div style="font-size: 20px;"><span>RS : <span class="printtotal"></span></span><br>
                                Thank you... visit again...</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 padding-small" >
                        <div class="col-auto"> 
                            <input type="text" class="form-control" id="shortcode" placeholder="Short code">
                        </div>
                        <div class="col-auto"> 
                            <input type="text" class="form-control" id="quantity" placeholder="Qty">
                        </div> 
                        <div class="col-auto"> 
                           <div class="input-group mb-3">
                              <div class="input-group-text">
                                <input class="form-check-input" type="checkbox" value=""  id="isACCharge" onclick="isACCharge(this.id)">
                                <label class="form-check-label" for="isACCharge">A/C</label> 
                            </div>
                            <button class="btn btn-success btn-icon-split"  onclick="payment()">
                                <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                <span class="text">Pay now</span>
                            </button>
                        </div>
                    </div> 
                </div> 

                </div>
            </div>
        </section> 
        <?php include('footer.php'); ?> 

        <script type="text/javascript">
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
                            const total = product.price * product.qty;  
                            var updatedetails = {};  
                            updatedetails.uniqueId = product.uniqueId;
                            updatedetails.price = product.price; 
                            updatedetails.preqty = quantity; 
                            var jsonString = JSON.stringify(updatedetails);  
                            var base64Encoded = btoa(jsonString);
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
                const discountRate = parseFloat($('#discount').val()) || 0;
                const gstRate = parseFloat($('#gst').val()) || 0;

                const taxableAmount = subtotal - discountRate;
                const tax = taxableAmount * (gstRate / 100);
                const grandTotal = taxableAmount + tax;

                const Ctax = tax / 2;
                const Gtax = tax / 2;

                $('#subtotal').text(`${subtotal.toFixed(2)}`);
                $('#Ctax').text(`${Ctax.toFixed(2)}`);
                $('#Gtax').text(`${Gtax.toFixed(2)}`);
                $('#tax').text(`${tax.toFixed(2)}`);

                var additionalCharge =0;
                if(document.getElementById('isACCharge').checked==true)
                {
                    additionalCharge = grandTotal * 0.15;
                }
                const totalWithAC = grandTotal + additionalCharge;

                let excessAmount = totalWithAC - Math.floor(totalWithAC); 
                let roundedTotal = excessAmount >= 0.50 ? Math.ceil(totalWithAC) : Math.floor(totalWithAC);
                excessAmount = (roundedTotal - totalWithAC) * 100;

                $('#total').text(`${roundedTotal.toFixed(2)}`);
                $('.printtotal').text(`${roundedTotal.toFixed(2)}`);
                $('#ExcessAmount').html(`(${excessAmount > 0 ? '+' : ''}${excessAmount.toFixed(0)})`);

                let roundedTotalWithAC = Math.round(totalWithAC);

                if(document.getElementById('isACCharge').checked==true)
                {
                    if ($('#ACCharge').length) {
                        $('#ACCharge').val(`${additionalCharge.toFixed(2)}`);
                    }
                    if ($('#BillAc').length) {
                        $('#BillAc').text(`${additionalCharge.toFixed(2)}`); 
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

            function checkItemes()
            {
                var Billno ="<?php echo $_GET['bill'] ?>";
                var discount ="<?php echo $_GET['discount'] ?? '0.00' ?>"; 

                $.ajax({
                    url: 'fetch_product.php',
                    type: 'POST',
                    data: { editbill: 'editbill', Billno:Billno },
                    success: function(response) {
                        try {

                            $('#shortcode').focus(); 
                            const result = JSON.parse(response);  
                            const Billno = result.Billno; 
                            $("#CurrentBill").val(Billno);
                            if(result.PendingBill)
                            {
                                const products = result.PendingBill;
                                products.forEach((product, i) => {
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
            checkItemes();

            function payment()
            { 
                const CurrentBill = $('#CurrentBill').val();
                const subtotal = $('#subtotal').text();
                const discount = $('#discount').val();
                const gst = $('#gst').val();
                const tax = $('#tax').text();
                const discountamt = $('#discount').val();
                const total = $('#total').text();
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
                    ac:ac
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
                            printDiv('print_Area');
                        } 
                    }
                });
            }

            function printDiv(divId) {
                $(".item-remove").hide();
                $("#printheader").show();
                var content = document.getElementById(divId).innerHTML; 
                var originalContent = document.body.innerHTML;
                document.body.innerHTML = content;
                window.print();
                document.body.innerHTML = originalContent;
                window.location.reload();
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
    if (event.key === "Enter") {
        event.preventDefault(); 
        if (!allowNumbers(element)) {
           updateqty(element);
       }
   }
}

            function updateqty(element) {
                const newQuantity = element.innerText.trim();
                if (isNaN(newQuantity) || newQuantity <= 0) {
                    alert('Invalid quantity!');
                    element.innerText = 1;
                    return;
                }
                var decodedString = atob(element.id);
                var decodedDetails = JSON.parse(decodedString);
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

        </style>
        <?php
        if(isset($_GET['ac']) && $_GET['ac']!="" && $_GET['ac']!=0)
        {
            ?>
            <script type="text/javascript">
                document.getElementById('isACCharge').checked=true;
                isACCharge();
            </script>
            <?php
        }
        ?>
    </body>

    </html>