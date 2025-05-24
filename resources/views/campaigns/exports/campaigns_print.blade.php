<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.campaigns.campaigns') }} {{ __('messages.common.export') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h2>{{ __('messages.campaigns.campaigns') }} – {{ __('messages.common.list') }}</h2>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.campaigns.campaign_name') }}</th>
                <th>{{ __('messages.campaigns.company') }}</th>
                <th>{{ __('messages.campaigns.position') }}</th>
                <th>{{ __('messages.campaigns.working_form') }}</th>
                <th>{{ __('messages.campaigns.department') }}</th>
                <th>{{ __('messages.campaigns.recruitment_plan') }}</th>
                <th>{{ __('messages.campaigns.recruited_quantity') }}</th>
                <th>{{ __('messages.campaigns.recruitment_channel_from') }}</th>
                <th>{{ __('messages.campaigns.managers') }}</th>
                <th>{{ __('messages.campaigns.is_active') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $campaign)
                <tr>
                    <td>{{ $campaign->campaign_name }}</td>
                    <td>{{ $campaign->company }}</td>
                    <td>{{ $campaign->position }}</td>
                    <td>{{ $campaign->working_form }}</td>
                    <td>{{ $campaign->department }}</td>
                    <td>{{ $campaign->recruitment_plan }}</td>
                    <td class="text-center">{{ $campaign->recruited_quantity }}</td>
                    <td>{{ $campaign->recruitment_channel_from }}</td>
                    <td>{{ is_array($campaign->managers) ? implode(', ', $campaign->managers) : $campaign->managers }}</td>
                    <td class="text-center">
                        {{ $campaign->is_active ? __('messages.campaigns.active') : __('messages.campaigns.inactive') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print text-right">
        <button onclick="window.print();" class="btn btn-primary">{{ __('messages.common.print') }}</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
