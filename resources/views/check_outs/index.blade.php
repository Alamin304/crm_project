@extends('layouts.app')
@section('title')
{{ __('Check Out ') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .select2-container {
            width: 100% !important;
        }
        .room-select-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .room-select-wrapper {
            display: flex;
            align-items: flex-end; /* Align items to bottom */
            max-width: 600px;
            width: 100%;
            gap: 10px; /* Space between elements */
        }
        .select2-wrapper {
            flex: 1; /* Take remaining space */
        }
        .go-button-wrapper {
            margin-bottom: 16px; /* Match label height */
        }
        .go-button {
            height: 38px; /* Match select2 height */
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('Check Out ') }}</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="room-select-container">
                        <div class="room-select-wrapper">
                            <div class="select2-wrapper">
                                <label for="multiSelectBox">Room No:</label>
                                <select class="form-control select2" id="multiSelectBox" multiple="multiple">
                                    <option value="101">101-test</option>
                                    <option value="102">102-test2</option>
                                    <option value="103">103-test3</option>
                                    <option value="201">201-test4</option>
                                    <option value="202">202-test5</option>
                                    <option value="203">203-test6</option>
                                    <option value="301">301-test7</option>
                                    <option value="302">302-test8</option>
                                </select>
                            </div>
                            <div class="go-button-wrapper">
                                <button class="btn btn-primary go-button">Go</button>
                            </div>
                        </div>
                    </div>
                    <!-- You can add your table or other content here -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#multiSelectBox').select2({
                placeholder: "Select room numbers",
                allowClear: true
            });

            // Go button click handler
            $('.go-button').click(function() {
                var selectedRooms = $('#multiSelectBox').val();
                if (selectedRooms && selectedRooms.length > 0) {
                    // Perform action with selected rooms
                    alert('Selected rooms: ' + selectedRooms.join(', '));
                    // You can replace this with your actual logic, like:
                    // window.location.href = '/your-route?rooms=' + selectedRooms.join(',');
                } else {
                    alert('Please select at least one room');
                }
            });
        });
    </script>
@endsection
