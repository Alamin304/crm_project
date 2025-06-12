<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('messages.bills_of_materials.bills_of_materials') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>{{ __('messages.bills_of_materials.bills_of_materials') }}</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.bills_of_materials.BOM_code') }}</th>
                <th>{{ __('messages.bills_of_materials.product') }}</th>
                <th>{{ __('messages.bills_of_materials.quantity') }}</th>
                <th>{{ __('messages.bills_of_materials.unit_of_measure') }}</th>
                <th>{{ __('messages.bills_of_materials.bom_type') }}</th>
                <th>{{ __('messages.common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boms as $bom)
                <tr>
                    <td>{{ $bom->BOM_code }}</td>
                    <td>{{ $bom->product }}</td>
                    <td>{{ $bom->quantity }}</td>
                    <td>{{ $bom->unit_of_measure }}</td>
                    <td>{{ $bom->bom_type == 'manufacture' ? 'Manufacture' : 'Kit' }}</td>
                    <td>{{ $bom->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
