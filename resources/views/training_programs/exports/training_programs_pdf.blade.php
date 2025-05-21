<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Training Programs List') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .date {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('Training Programs List') }}</h1>
        <div class="date">{{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.training_programs.name') }}</th>
                <th>{{ __('messages.training_programs.training_type') }}</th>
                <th>{{ __('messages.training_programs.description') }}</th>
                <th>{{ __('messages.training_programs.point') }}</th>
                <th>{{ __('messages.training_programs.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trainingPrograms as $program)
                <tr>
                    <td>{{ $program->program_name }}</td>
                    <td>{{ $program->training_type }}</td>
                    <td>{{ strip_tags($program->description) }}</td>
                    <td>{{ $program->point }}</td>
                    <td>{{ \Carbon\Carbon::parse($program->created_at)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
