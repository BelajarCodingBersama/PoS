<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="card border-0">
        <div class="card-body">
            <div class="row mb-5 mt-2">
                <div class="col-12 pb-4">
                    <div class="text-center">
                        <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                        <p class="text-muted"><strong>Transaction Receipt</strong></p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-xl-4">
                            <ul class="list-unstyled">
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="fw-bold">Invoice ID:</span># {{ $transaction->id }}
                                </li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="fw-bold">Cashier Name:</span> {{ $transaction->user->username }}
                                </li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="fw-bold">Transaction Date: </span>{{ $transaction->created_at->format('d-m-y') }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row my-2 table-responsive">
                        <table class="table table-striped table-borderless table-sm">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col" class="text-center">Product Name</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-center">Unit Price (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1 @endphp
                                @foreach ($transaction->details as $detail)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td class="text-center">{{ $detail->product->name }}</td>
                                        <td class="text-center">{{ $detail->amount }}</td>
                                        <td class="text-center">{{ $detail->price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5">
                        <div class="col-xl-8"></div>
                        <div class="col-xl-3">
                            <ul class="list-unstyled float-right mr-5">
                                <li class="text-muted ms-3"><span class="text-black me-4 me-4 mr-3">Sub Total :</span> Rp. {{ $transaction->sub_total }}</li>
                                <li class="text-muted ms-3 mt-2"><span class="text-black me-4 mr-5">Total :</span> Rp. {{ $transaction->total }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
