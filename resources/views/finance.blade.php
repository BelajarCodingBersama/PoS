<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}

        table {
            width: 100%
        }

        table, thead, tbody, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th {
            padding: 8px 5px;
            background-color: #04AA6D;
            color: white;
            font-style: normal;
        }

        td {
            padding: 5px;
        }

        tr:nth-child(even) {background-color: #f2f2f2;}

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .title {
            margin-bottom: 30px;
        }
	</style>
</head>
<body>
	<div class="container">
        @if ($month || $year != null)
		    <h3 class="text-center title">Employee Salary Report for {{ $month }} {{ $year }}</h3>
        @else
		    <h3 class="text-center title">Employee Salary Report</h3>
        @endif
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Role</th>
					<th>Basic Salary (Rp)</th>
					<th>Allowances (Rp)</th>
					<th>Tax (Rp)</th>
					<th>Payment Date</th>
					<th>Status</th>
					<th>Net Pay (Rp)</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($payrolls as $payroll)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $payroll->user->username }}</td>
					<td class="text-center">{{ $payroll->role }}</td>
					<td class="text-right">{{ $payroll->format_basic_salary }}</td>
					<td class="text-right">{{ $payroll->format_allowances }}</td>
					<td class="text-right">{{ $payroll->format_tax }}</td>
					<td class="text-center">{{ $payroll->format_payment_date ?? "-" }}</td>
					<td class="text-center">{{ $payroll->status }}</td>
					<td class="text-right">{{ $payroll->format_net_pay }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>

</body>
</html>
