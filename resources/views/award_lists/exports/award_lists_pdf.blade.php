<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.award_lists.award_lists') }} Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2 { color: #333; text-align: center; margin-bottom: 15px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>{{ __('messages.award_lists.award_lists') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.award_lists.id') }}</th>
                <th>{{ __('messages.award_lists.award_name') }}</th>
                <th>{{ __('messages.award_lists.employee_name') }}</th>
                <th>{{ __('messages.award_lists.award_by') }}</th>
                <th>{{ __('messages.award_lists.date') }}</th>
                <th>{{ __('messages.award_lists.gift_item') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($awardLists as $award)
            <tr>
                <td>{{ $award->id }}</td>
                <td>{{ $award->award_name }}</td>
                <td>{{ $award->employee_name }}</td>
                <td>{{ $award->award_by }}</td>
                <td>{{ \Carbon\Carbon::parse($award->date)->format('m/d/Y') }}</td>
                <td>{{ $award->gift_item }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
