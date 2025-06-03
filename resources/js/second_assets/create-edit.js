$(document).ready(function() {
    $('.summernote-simple').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });

    $('.select2').select2({
        width: '100%'
    });

    // Toggle selling price field
    $('#forSale').change(function() {
        if ($(this).is(':checked')) {
            $('.for-sale-field').show();
            $('#selling_price').attr('required', true);
        } else {
            $('.for-sale-field').hide();
            $('#selling_price').attr('required', false);
            $('#selling_price').val('');
        }
    });

    // Toggle rental fields
    $('#forRent').change(function() {
        if ($(this).is(':checked')) {
            $('.for-rent-field').show();
            $('#rental_price').attr('required', true);
            $('#minimum_renting_days').attr('required', true);
            $('#rental_unit').attr('required', true);
        } else {
            $('.for-rent-field').hide();
            $('#rental_price').attr('required', false).val('');
            $('#minimum_renting_days').attr('required', false).val('');
            $('#rental_unit').attr('required', false).val(null).trigger('change');
        }
    });

    // Trigger initial state
    $('#forSale').trigger('change');
    $('#forRent').trigger('change');

    // Generate serial number
    $('#generateSerialBtn').click(function() {
        $.ajax({
            url: generateSerialUrl,
            type: 'GET',
            success: function(response) {
                $('#serialNumber').val(response.data);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    });

    // Form submission
    $('#createAssetForm, #editAssetForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = form.attr('method');
        let loadingText = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        let submitBtn = form.find('button[type="submit"]');
        let originalText = submitBtn.html();

        submitBtn.html(loadingText).prop('disabled', true);

        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    displaySuccessMessage(response.message);
                    window.location.href = assetIndexUrl;
                }
            },
            error: function(xhr) {
                displayErrorMessage(xhr.responseJSON.message || 'An error occurred');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});

function displaySuccessMessage(message) {
    alert(message); // Replace with your preferred notification system
}

function displayErrorMessage(message) {
    alert(message); // Replace with your preferred notification system
}
