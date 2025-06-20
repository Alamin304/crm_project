<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Membership Rules') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header .date { color: #7f8c8d; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #3498db; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Membership Rules List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Customer Group</th>
                <th>Customer</th>
                <th>Card</th>
                <th>Point From</th>
                <th>Point To</th>
            </tr>
        </thead>
        <tbody>
             @foreach($membershipRules as $rule)
                <tr>
                    <td>{{ $rule->name }}</td>
                    <td>{{ $rule->customer_group }}</td>
                    <td>{{ $rule->customer }}</td>
                    <td>{{ $rule->card }}</td>
                    <td>{{ $rule->point_from }}</td>
                    <td>{{ $rule->point_to }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Membership rules') }}: {{ count($membershipRules) }}
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        }
    </script>
</body>
</html>
