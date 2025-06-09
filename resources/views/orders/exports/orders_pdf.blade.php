<h1>Orders Export</h1>
<table border="1">
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Customer</th>
            <th>Order Type</th>
            <th>Payment Method</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->customer }}</td>
            <td>{{ $order->order_type }}</td>
            <td>{{ $order->payment_method }}</td>
            <td>{{ $order->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
