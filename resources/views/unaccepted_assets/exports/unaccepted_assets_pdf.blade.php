<h1>Unaccepted Assets Export</h1>
<table border="1">
    <thead>
        <tr>
            <th>Title</th>
            <th>Asset</th>
            <th>Serial Number</th>
            <th>Checkout For</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
        <tr>
            <td>{{ $asset->title }}</td>
            <td>{{ $asset->asset }}</td>
            <td>{{ $asset->serial_number }}</td>
            <td>{{ $asset->checkout_for }}</td>
            <td>{{ $asset->notes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
