<h1>Orders List</h1>
<table border="1" style="width:100%">
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Customer</th>
            <th>Group Customer</th>
            <th>Order Type</th>
            <th>Payment Method</th>
            <th>Channel</th>
            <th>Status</th>
            <th>Invoice</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->customer }}</td>
            <td>{{ $order->group_customer }}</td>
            <td>{{ $order->order_type }}</td>
            <td>{{ $order->payment_method }}</td>
            <td>{{ $order->channel }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->invoice }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
