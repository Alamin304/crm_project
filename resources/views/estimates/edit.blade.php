@extends('layouts.app')
@section('title')
    {{ __('messages.estimate.edit_estimate') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ mix('assets/css/estimates/estimates.css') }}">
    <style>
        /* For Chrome, Safari, Edge, Opera */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* For Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .fa-times {
            font-size: 15px;
            color: black;
            /* Default color */
            transition: color 0.1s ease;
            /* Smooth color transition */
        }

        .fa-times:hover {
            color: red;
            /* Color on hover */
            cursor: pointer;
            /* Changes cursor to pointer for better UX */
        }

        /* Right-align text for table headers and input fields */
        th {
            text-align: right;
            /* Align header text to the right */
        }

        #itemsTable tbody tr td {

            /* Align table cell text to the right */
        }

        /* Specific input fields alignment for amounts */
        .quantity,
        .rates,
        .discount,
        .taxable,
        .tax,
        .vat-amount,
        .total-amount {
            text-align: right !important;
            /* Ensures right-alignment for input content */
        }

        .bgColor {
            background: #8887870a !important;

        }

        .table-bordered {
            border-color: white !important;
        }

        .form-group {
            margin-bottom: 0 !important;
            /* Remove bottom margin */
            padding-bottom: 0;
            /* Ensure no padding at the bottom */
        }

        .form-control,
        label {
            margin-bottom: 0 !important;
            /* Remove margin from inputs and labels */
        }

        /* Reduce the height of the Select2 dropdown */
        input,
        select,

        .form-control {
            /* height: 38px !important; */

        }

        .section-header {
            margin-top: -30px !important;
            margin-bottom: 5px !important;
            height: 55px;
        }

        .table-wrapper {
            max-height: 400px;
            /* Set the desired height for the table */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        #itemsTable thead {
            position: sticky;
            top: 0;
            background-color: #fff;
            /* Match the table background */
            z-index: 1;
            /* Ensure it stays above other content */
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.estimate.edit_estimate') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ url()->previous() }}" class="btn btn-primary form-btn float-right-mobile">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            @include('layouts.errors')
            <div class="card">
                {{ Form::open(['route' => ['estimates.update', $estimate->id], 'validated' => false, 'method' => 'POST', 'id' => 'editEstimateForm']) }}
                @include('estimates.address_modal')
                @include('estimates.edit_fields')
                {{ Form::close() }}
            </div>
        </div>
    </section>
    @include('estimates.templates.templates')
    @include('tags.common_tag_modal')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let editData = true;
        let estimateEdit = true;
        let taxData = JSON.parse('@json($data['taxes'])');
        let productUrl = "{{ route('products.index') }}";
        let estimateEditURL = "{{ route('estimates.index') }}";
        let editEstimateAddress = true;
        let customerURL = "{{ route('get.customer.address') }}";
        let services = @JSON($services);
        let categories = @JSON($categories);
        let customers = @JSON($customers);
        $('.datepicker').datetimepicker({
            format: 'DD-MM-YYYY', // Date format (adjust as needed)
            useCurrent: false, // Prevents using the current date by default
            showClose: true, // Show a "close" button
            showClear: true, // Show a "clear" button
            showTodayButton: true // Show a "today" button
        });
    </script>
    <script src="{{ mix('assets/js/sales/sales.js') }}"></script>
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script src="{{ mix('assets/js/estimates/estimates.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#btnSaveEstimateAddress').on('click', function(e) {
                e.preventDefault();

                // Clear previous error messages
                $('#validationErrorsBox').addClass('d-none').html('');
                let estimateId = $('#estimateNumber').val(); // Assuming this is an input field
                // Prepare the form data
                let formData = {
                    street: $('textarea[name="street[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    city: $('input[name="city[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    state: $('input[name="state[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    zip_code: $('input[name="zip_code[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    country: $('input[name="country[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    estimate_id: estimateId,


                };

                var createURL = route('estimates.address');
                $.ajax({
                    url: createURL,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response) {
                            // Format the address with commas
                            let addressText =
                                `${response.street}, ${response.city}, ${response.state} - ${response.zip_code}, ${response.country}`;

                            // Update the content of #bill_to with the formatted address
                            $('#bill_to').text(addressText);
                            // Close the modal
                            $('#addModal').modal('hide');
                            displaySuccessMessage("Address Added");

                        }
                    },
                    error: function(response) {

                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#terms_dropdown').select2({
                width: '100%', // Set the width of the select element
                allowClear: true // Allow clearing the selection
            });


            // Event listener for when a customer is selected from the dropdown
            $('#customerSelectBox').on('change', function() {
                // Get the selected customer ID
                var customerId = $(this).val();

                // Get the customer name associated with the selected customer ID
                var customerName = $('#customerSelectBox option:selected').text();

                // Set the customer name input to the selected customer's name
                $('#customerNameInput').val(customerName);

                var customer = customers.find(c => c.id == customerId);
                $('#vendor_code').val(customer?.vendor_code ?? '');
            });

            adjustHeights();

            // Adjust heights on window resize
            $(window).resize(function() {
                adjustHeights();
            });


            function adjustHeights() {
                // Get the window height
                const windowHeight = $(window).height();

                // Calculate heights based on percentages
                const headerHeight = (18.5 / 100) * windowHeight;
                const contentHeight = (30 / 100) * windowHeight;
                const footerHeight = (15 / 100) * windowHeight;

                $('.page_contents').css({
                    'height': `${contentHeight}px`,
                    overflow: 'auto', // Add scrollbar if overflow
                });
            }


            $('#branchSelect').change(function() {
                var branchId = $(this).val();
                updateBranch(branchId);
            });


            function updateBranch(branchId) {
                  var nextNumber = "{{ $estimate->estimate_number }}";
                    nextNumber = nextNumber.split('/')[1];
                // if (branchId) {
                //     // Update the estimateNumber field with the format "branch_id/nextNumber"
                //     $('#estimateNumber').val(branchId + '/' + nextNumber);
                // } else {
                //     // Clear the estimateNumber field if no branch is selected
                //     $('#estimateNumber').val('');
                // }
            }



        });
    </script>
    <script>
        $(document).ready(function() {
            let termIndex = $('#terms_table tbody tr').length + 1; // Start index based on existing rows

            // Add selected term to the table
            $('#terms_dropdown').change(function() {
                let selectedValue = $(this).val();
                let selectedText = $('#terms_dropdown option:selected').text();

                if (selectedValue && selectedText) {
                    // Check if the term is already added
                    if ($('#terms_table tbody').find(`tr[data-id="${selectedValue}"]`).length === 0) {
                        $('#terms_table tbody').append(`
                        <tr data-id="${selectedValue}">
                            <td>${termIndex}</td>
                            <td class='p-0 m-0'>
                                <textarea name="description[]" class='form-control' style='height:120px;width:100%;'>${selectedText}</textarea>
                                <input type='hidden' name='terms[]' value="${selectedValue}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                        termIndex++; // Increment the index for the next term
                    } else {
                        alert('This term has already been added.');
                    }
                }
            });

            // Remove term row
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                termIndex--; // Decrement index when a row is removed
                // Re-number the serial numbers
                $('#terms_table tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            let totalNewTaxable = 0;
            let globalNetAmount = 0;
            let globaltaxable = 0;
            let globalVatAmount = 0;

            let rowIndex = $('#itemRows tr').length; // Set the row index based on the existing number of rows
            calculateTotals();
            $('.serviceSelect').select2(); // Initialize select2 for existing elements

            $('.categorySelect').select2({
                width: '100%', // Set the width of the select element
                // allowClear: true // Allow clearing the selection
            });
            // Calculate for each row on page load (edit page scenario)
            $('#itemRows tr').each(function() {
                calculateRow($(this)); // Calculate each row
            });
            calculateTotals(); // Update overall totals

            // Add new row when clicking "Add Row" button
            $('#addRow').click(function() {
                addRow();
                var tmpRowSize = 1;
                $('#itemRows tr').each(function() {
                    var firstTdValue = $(this).find('td:first').text(tmpRowSize);
                    tmpRowSize++;

                });
            });



            function addRow() {

                let row = `
                <tr data-index="${rowIndex}">
                    <td style="width: 2%;">${rowIndex + 1}</td>
                    <td class="p-1" style="width: 15%"><input type="text" class="form-control item-number" name="itemsArr[${rowIndex}]['item']"  value="" ></td>

                    <td class="p-1 m-0"  style="width:25%;">
                        <select style="width:100%;" class="form-select categorySelect" name="itemsArr[${rowIndex}]['category_id']" required>
                            <option value="" disabled selected>Select a Category</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}">
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-1 m-0"  style="width:25%;" >
                        <select class="form-select serviceSelect" name="itemsArr[${rowIndex}]['service_id']" required  style="width:100%;"  >
                            <option value="" disabled selected>Select a service</option>
                            @foreach ($services as $id => $name)
                                <option value="{{ $name->id }}" data-item-number="{{ $name['item_number'] }}">{{ $name['title'] }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td class="p-1" style="width: 100px;"><input type="number" class="form-control text-right quantity" required value="1" name="itemsArr[${rowIndex}]['quantity']" ></td>
                    <td class="p-1" ><input type="number" class="form-control p-1  text-right rates" required value="0.00" name="itemsArr[${rowIndex}]['rate']" style="width: 100px !important;" ></td>
                    <td class="p-0"><input type="number" class="form-control text-right discount p-0" required value="0.00" name="itemsArr[${rowIndex}]['discount']" style="width:90px;background:white;border:none;" readonly></td>
                    <td class="p-1 m-0" style="width: 10%;"><input type="number" class="form-control p-0 text-right taxable" value="0.00" name="itemsArr[${rowIndex}]['taxable']" readonly style="background:white;border:none;"></td>

                    <td class="p-0"><input type="number" class="form-control p-0 text-right tax" value="15.00"  name="itemsArr[${rowIndex}]['tax']" readonly style="width:90px;background:white;border:none;"></td>
                    <td class="vat-amount text-end pr-0" >0.00</td>

                    <td class="p-0 pr-2"><input type="number" readonly class="total-amount p-0 form-control text-right" name="itemsArr[${rowIndex}]['total']" value="0.00" style="background:white;border:none;width:90px;" ></td>
                    <td class="p-1 text-right"><button type="button" class="btn text-danger remove-row"><i class="fa fa-times"></i></button></td>
                </tr>
            `;
                $('#itemRows').append(row);
                rowIndex++;
                $('.serviceSelect,.categorySelect').select2();
            }



            $(document).on('change', '.serviceSelect', function() {

                var selectedValue = $(this).val(); // Get the selected value
                var selectedService = services.find(service => service.id == selectedValue);
                // var itemNumber = selectedService.item_number;
                // var descriptionText = $('<div>').html(selectedService.description || '').text();
                let row = $(this).closest('tr');
                calculateRow(row);
                calculateTotals();

                // row.find('textarea[name*="description"]').val(descriptionText ||'');
                // row.find('.item-number').val(itemNumber || '');
            });




            $(document).on('change', '.categorySelect', function() {
                var selectedCategory = $(this).val();

                // Find the closest row containing the changed categorySelect
                var row = $(this).closest('tr'); // This gets the closest <tr> parent

                // Find the serviceSelect within the same row and clear it
                var serviceSelect = row.find('.serviceSelect');
                serviceSelect.empty();
                serviceSelect.append(
                    '<option value="" disabled selected>Select a service</option>'); // Add default option

                // Iterate through services and append matching options
                @foreach ($services as $service)
                    // Assuming each service has an item_group_id or similar attribute
                    if ({{ $service->item_group_id }} == selectedCategory) {
                        serviceSelect.append(new Option("{{ $service->title }}", "{{ $service->id }}"));
                    }
                @endforeach

                // Refresh the Select2 to reflect the updated options
                serviceSelect.select2();
            });



            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                calculateTotals();


            });

            $(document).on('input', '.quantity, .rates', function() {

                let row = $(this).closest('tr');
                calculateRow(row);
                calculateTotals();

            });

            // Update overall totals when totalDiscount is manually changed
            $('#totalDiscount').on('input', function() {
                calculateTotals();
            });

            function calculateRow(row) {
                let quantity = parseFloat(row.find('.quantity').val()) || 0;
                let rate = parseFloat(row.find('.rates').val()) || 0;

                let vatAmount = (quantity * rate) * 0.15;
                let taxableAmount = quantity * rate;
                let totalAmount = taxableAmount + vatAmount;

                row.find('.taxable').val(taxableAmount.toFixed(2));
                row.find('.vat-amount').text(vatAmount.toFixed(2));
                row.find('.total-amount').val(totalAmount.toFixed(2));
            }

            $("#discount_type").on('change', function() {
                calculateTotals();
            });





            function calculateTotals() {
                let subtotal = 0;
                let vatTotal = 0;
                let taxable = 0;
                let discountType = $('#discount_type').val();
                let manualDiscount = parseFloat($('#totalDiscount').val()) || 0;
                const vatRate = 0.15; // Fixed VAT rate (15%)


                // Calculate subtotal, VAT, and taxable amount for each row
                $('#itemRows tr').each(function() {
                    let row = $(this);
                    let quantity = parseFloat(row.find('.quantity').val()) || 0;
                    let rate = parseFloat(row.find('.rates').val()) || 0;

                    let taxableAmount = quantity * rate;

                    subtotal += taxableAmount;
                    taxable += taxableAmount;


                });
                globaltaxable = taxable;
                // Apply discount to taxable or subtotal based on discount type
                //  let discountAmount = discountType === '0' ? (manualDiscount / 100) * taxable : manualDiscount;
                let discountAmount = manualDiscount;
                let discountedSubtotal = taxable - discountAmount;
                // console.log("Taxable", taxable,discountAmount);


                totalNewTaxable = taxable - discountAmount; //setting global taxable


                // Update DOM elements
                $('#taxable').val(totalNewTaxable.toFixed(2));
                $('#subtotal').val(taxable.toFixed(2));
                $('#totalVAT').val(vatTotal.toFixed(2));

                $('#includingVat').val((discountedSubtotal + vatTotal).toFixed(2));
                $('#afterDiscount').val(discountedSubtotal.toFixed(2));

                updateRowWithDiscountVat(discountAmount);

                // calculateRowDiscount(discountType, discountAmount, taxable);
            }

            function updateRowWithDiscountVat(discountAmount) {


                // Calculate selectedPercentage safely


                let selectedPercentage = discountAmount !== 0 ? (discountAmount / globaltaxable) * 100 : 0;

                // Check for Infinity or NaN
                if (!isFinite(selectedPercentage)) {
                    selectedPercentage = 0; // Handle invalid result
                }
                globalVatAmount = 0;
                $('#itemRows tr').each(function() {
                    let row = $(this);
                    let quantity = parseFloat(row.find('.quantity').val()) || 0;
                    let rate = parseFloat(row.find('.rates').val()) || 0;
                    let rowDisk = (selectedPercentage * rate / 100 * quantity);

                    let vatAmount = ((quantity * rate) - rowDisk) * 0.15;
                    let taxableAmount = (quantity * rate) - rowDisk;
                    let totalAmount = taxableAmount + vatAmount;

                    row.find('.discount').val(rowDisk.toFixed(2));

                    globalVatAmount += vatAmount;
                    row.find('.taxable').val(taxableAmount.toFixed(2));
                    row.find('.vat-amount').text(vatAmount.toFixed(2));
                    row.find('.total-amount').val(totalAmount.toFixed(2));


                });

                // Calculate net total and rounding difference
                let totalNet = globaltaxable + globalVatAmount - discountAmount;
                let roundedTotal = Math.round(totalNet);
                let roundDifference = (roundedTotal - totalNet).toFixed(2);

                $('#adjustment').val(roundDifference);
                $('#totalVAT').val(globalVatAmount.toFixed(2));

                $("#totalAmt").val(roundedTotal);
                $('#netTotal').val(roundedTotal.toFixed(2));
            }


            $('#discount_type').on('change', function() {
                if ($(this).val() == '0') { // Show percentage dropdown if % selected
                    $('#percentage_discount').show();
                    $('#totalDiscount').prop('readonly', true);
                } else { // Hide if $ selected
                    $('#percentage_discount').hide();
                    $('#totalDiscount').prop('readonly', false);
                    $('#totalDiscount').val(0); // Reset discount input


                }
                $('#percentage_discount').trigger('change');
            });

            $('#percentage_discount').on('change', function() {
                let selectedPercentage = $(this).val();
                if (selectedPercentage) {

                    selectedPercentage = parseFloat(selectedPercentage) || 0;
                    let discAmount = globaltaxable * (selectedPercentage / 100);
                    $('#totalDiscount').val(discAmount.toFixed(2));
                    calculateTotals();
                }
            });


        });
    </script>
@endsection
