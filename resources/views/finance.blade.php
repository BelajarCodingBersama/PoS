<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
</head>
<body>
	<div class="container">
		<h3 style="text-align:center">Finance Report</h3>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Role</th>
					<th>Payment Date</th>
					<th>Basic Salary</th>
					<th>Allowances</th>
					<th>Tax</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($payrolls as $payroll)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $payroll->user->username }}</td>
					<td>{{ $payroll->user->role->name }}</td>
					<td>{{ Carbon\Carbon::parse($payroll->payment_date)->format('d-m-Y') }}</td>
					<td>Rp.{{ number_format($payroll->basic_salary,2,",",".") }}</td>
					<td>Rp.{{ number_format($payroll->allowances,2,",",".") }}</td>
					<td>Rp.{{ number_format($payroll->tax,2,",",".") }}</td>
					<td>{{ $payroll->status }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
 
	</div>
 
</body>
</html>