<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
    <div class="row col-md-12">
        <div class="col-md-3"> </div>
        <div class="col-md-6 mt-4">
            <div class="invoice-form" id="invoice-form">
                <div class="form-group row">
                    <label for="customer_name" class="col-sm-3 col-form-label">Customer Name</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                            placeholder="customer name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="customer_name" class="col-sm-3 col-form-label">Product</label>
                    <div class="col-sm-7">
                        <select class="form-control" id="product" class="select-product">
                            <option value=""> Please select </option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}"> {{ $product->product_name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="rate" class="col-sm-3 col-form-label">Rate</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="rate" name="rate" placeholder="Rate"
                            value="" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="unit" class="col-sm-3 col-form-label">Unit</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Unit"
                            readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="qty" name="qty" placeholder="qty" oninput="updateQty()">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="discount" class="col-sm-3 col-form-label">Discount (%)</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="discount" name="discount"
                            placeholder="discount" oninput="updateQty()">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="net_amount" class="col-sm-3 col-form-label">Net Amount</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="net_amount" name="net_amount"
                            placeholder="net amount" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_amount" class="col-sm-3 col-form-label">Total Amount</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="total_amount" name="total_amount"
                            placeholder="total amount" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3"> </div>
                    <div class="col-sm-7">
                        <button id="add-invoice" class="btn btn-success col-sm-12">+ ADD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <table class="table table-hover table-responsive">
            <thead>
              <tr>
                <th scope="col">Product</th>
                <th scope="col">Rate</th>
                <th scope="col">Unit</th>
                <th scope="col">Qty</th>
                <th scope="col">Disc(%)</th>
                <th scope="col">Net Amt.</th>
                <th scope="col">Total Amt.</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#product').on('change', function() {
            let productId = $(this).val();
            if (productId != '') {
                let ajaxurl = 'product/' + productId
                $.ajax({
                    type: "GET",
                    url: ajaxurl,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $('#rate').val(data.data.rate);
                            $('#unit').val(data.data.unit);
                            setAmount(data.data.rate, data.data.unit);
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            } else {
                $('#rate').val('');
                $('#unit').val('');
                $('#net_amount').val('');
                $('#total_amount').val('');
            }
        });

        var i = 0;
        $('#add-invoice').click(function() {
            $('tbody').append(`
                <tr>
                    <td><select class="form-control" id="invoice[`+ i +`][product_id]" name="invoice[`+ i +`][product_id]" class="select-product">
                            <option value=""> Please select </option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}"> {{ $product->product_name }} </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input  type="hidden" id="invoice[`+ i +`][customer_name]" name="invoice[`+ i +`][customer_name]" />
                        <input class="form-control" id="invoice[`+ i +`][rate]" name="invoice[`+ i +`][rate]" readonly /></td>
                    <td><input class="form-control"  id="invoice[`+ i +`][unit]" name="invoice[`+ i +`][unit]" readonly /></td>
                    <td><input class="form-control" id="invoice[`+ i +`][qty]" name="invoice[`+ i +`][qty]" /></td>
                    <td><input class="form-control"  id="invoice[`+ i +`][disc]" name="invoice[`+ i +`][disc]" /></td>
                    <td><input class="form-control"  id="invoice[`+ i +`][net]" name="invoice[`+ i +`][net]" readonly /></td>
                    <td><input class="form-control" id="invoice[`+ i +`][total]" name="invoice[`+ i +`][total]" readonly /></td>
                    <td><button class="btn btn-danger form-control" > REMOVE </button> </td>
                </tr>
            `);
        });



    });

    function updateQty()
    {
        let rate = $('#rate').val();
        let unit = $('#unit').val();
        setAmount(rate,unit);
    }
    function setAmount(rate, unit) {
        let qty = $('#qty').val();
        let discount = $('#discount').val();
        let unitPerRate = parseFloat(rate) / parseFloat(unit);
        if (qty != '') {
            let netAmount = parseFloat(qty) * parseFloat(unitPerRate);
            $('#net_amount').val(netAmount);
            if (discount != '') {
                let totalAmount = netAmount - (netAmount * parseFloat(discount) / 100);
                $('#total_amount').val(totalAmount);
            }
        }

    }
</script>

</html>
