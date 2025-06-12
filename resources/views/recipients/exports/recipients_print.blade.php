@extends('layouts.print')

@section('content')
    <h1 class="text-center">Recipients List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Recipient</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipients as $recipient)
            <tr>
                <td>{{ $recipient->customer }}</td>
                <td>{{ $recipient->recipient }}</td>
                <td>{{ $recipient->email }}</td>
                <td>{{ $recipient->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
