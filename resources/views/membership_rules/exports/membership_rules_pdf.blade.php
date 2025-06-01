<!DOCTYPE html>
<html>
<head>
      <title>Membership Rules Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
   <h1>Membership Rules</h1>
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
</body>
</html>
