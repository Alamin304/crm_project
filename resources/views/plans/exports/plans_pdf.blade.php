<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.plans.plans') }} {{ __('messages.common.export') }}</title>
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
    <h2>{{ __('messages.plans.plans') }} – {{ __('messages.common.list') }}</h2>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.plans.plan_name') }}</th>
                <th>{{ __('messages.plans.position') }}</th>
                <th>{{ __('messages.plans.working_form') }}</th>
                <th>{{ __('messages.plans.department') }}</th>
                <th>{{ __('messages.plans.recruited_quantity') }}</th>
                <th>{{ __('messages.plans.is_active') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $plan)
            <tr>
                <td>{{ $plan->plan_name }}</td>
                <td>{{ $plan->position }}</td>
                <td>{{ $plan->working_form }}</td>
                <td>{{ $plan->department }}</td>
                <td class="text-center">{{ $plan->recruited_quantity }}</td>
                <td class="text-center">
                    @if($plan->is_active)
                        {{ __('messages.plans.active') }}
                    @else
                        {{ __('messages.plans.inactive') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print text-right">
        <button onclick="window.print();" class="btn btn-primary">{{ __('messages.common.print') }}</button>
    </div>
</body>
</html>
