<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Award Lists') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header .subtitle { color: #7f8c8d; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #3498db; color: white; text-align: left; padding: 10px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Award Lists') }}</h1>
        <div class="subtitle">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.award_lists.id') }}</th>
                <th>{{ __('messages.award_lists.award_name') }}</th>
                <th>{{ __('messages.award_lists.award_description') }}</th>
                <th>{{ __('messages.award_lists.gift_item') }}</th>
                <th>{{ __('messages.award_lists.date') }}</th>
                <th>{{ __('messages.award_lists.employee_name') }}</th>
                <th>{{ __('messages.award_lists.award_by') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($awardLists as $index => $award)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $award->award_name }}</td>
                <td>{{ $award->award_description }}</td>
                <td>{{ $award->gift_item }}</td>
                <td>{{ $award->date }}</td>
                <td>{{ $award->employee_name }}</td>
                <td>{{ $award->award_by }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Awards') }}: {{ count($awardLists) }}
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
