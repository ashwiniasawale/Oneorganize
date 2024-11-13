@extends('layouts.admin')

@section('page-title')
    {{ __('Payslip') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('payslip') }}</li>
@endsection

@section('content')
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['payslip.store'], 'method' => 'POST', 'id' => 'payslip_form']) }}
                <div class="d-flex align-items-center justify-content-end">
                    <div class="col-xl-2 col-lg-3 col-md-3">
                        <div class="btn-box ms-2">
                            {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
                            {{ Form::select('month', $month, date('m'), ['class' => 'form-control select', 'id' => 'month']) }}
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-3">
                        <div class="btn-box ms-2">
                            {{ Form::label('year', __('Select Year'), ['class' => 'form-label']) }}
                            {{ Form::select('year', $year, date('Y'), ['class' => 'form-control select']) }}
                        </div>
                    </div>
                    <div class="col-auto float-end ms-2 mt-4">
                        <button href="#" class="btn  btn-primary"
                            
                            data-bs-toggle="tooltip" title="{{ __('payslip') }}"
                            data-original-title="{{ __('payslip') }}">{{ __('Generate Payslip') }}
</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-start mt-2">
                            <h5>{{ __('Find Employee Payslip') }}</h5>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center justify-content-end ">
                            <div class="col-xl-2 col-lg-3 col-md-4">
                                <div class="btn-box ms-2">
                                    <select class="form-control month_date " name="year" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="--">--</option>
                                        @foreach ($month as $k => $mon)
                                            @php
                                                $selected = date('m') == $k ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $k }}" {{ $selected }}>{{ $mon }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4">
                                <div class="btn-box ms-2 me-2">
                                    {{ Form::select('year', $year, date('Y'), ['class' => 'form-control year_date ']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end me-2">
                                {{ Form::open(['route' => ['payslip.export'], 'method' => 'POST', 'id' => 'payslip_form']) }}
                                <input type="hidden" name="filter_month" class="filter_month">
                                <input type="hidden" name="filter_year" class="filter_year">
                                <input type="submit" value="{{ __('Export') }}" class="btn btn-primary">
                                {{ Form::close() }}
                            </div>
                            <!-- <div class="col-auto float-end me-0">
                                @can('create pay slip')
                                    <input type="button" value="{{ __('Bulk Payment') }}" class="btn btn-primary"
                                        id="bulk_payment">
                                @endcan
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-render-column-cells">
                        <thead>
                            <tr>
                                <th>{{ __('Employee Id') }}</th>
                                <th>{{ __('Name') }}</th>
                                <!-- <th>{{ __('Payroll Type') }}</th>
                                <th>{{ __('Salary') }}</th>
                                <th>{{ __('Net Salary') }}</th> -->
                                <th>{{__('Month')}}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            callback();

            function callback() {
                var month = $(".month_date").val();
                var year = $(".year_date").val();

                $('.filter_month').val(month);
                $('.filter_year').val(year);

                if (month == '') {
                    month = '{{ date('m', strtotime('last month')) }}';
                    year = '{{ date('Y') }}';

                    $('.filter_month').val(month);
                    $('.filter_year').val(year);
                }

                var datePicker = year + '-' + month;

                $.ajax({
                    url: '{{ route('payslip.search_json') }}',
                    type: 'POST',
                    data: {
                        "datePicker": datePicker,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
console.log(data);
                        function renderstatus(data, cell, row) {
                            if (data == 'Paid')
                                return '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                            else
                                return '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                        }

                        function renderButton(data, cell, row) {

                            var $div = $(row);
                            employee_id = $div.find('td:eq(0)').text();
                            status = $div.find('td:eq(6)').text();

                            var month = $(".month_date").val();
                            var year = $(".year_date").val();
                            var id = employee_id;
                            var payslip_id = data;

                            var clickToPaid = '';
                            var payslip = '';
                            var view = '';
                            var edit = '';
                            var deleted = '';
                            var form = '';

                            if (data != 0) {
                                var payslip =
                                    '<a href="#" data-url="{{ url('payslip/pdf/') }}/' + id +
                                    '/' + datePicker +
                                    '" data-size="md-pdf"  data-ajax-popup="true" class="btn btn-primary" data-title="{{ __('Employee Payslip') }}">' +
                                    '{{ __('Payslip') }}' + '</a> ';
                            }

                          

                        }
                        var tr = '';
                        if (data.length > 0) {
                            $.each(data, function(indexInArray, valueOfElement) {



                                var status =
                                    '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    valueOfElement[6] + '</a></div>';
                                if (valueOfElement[6] == 'Paid') {
                                    var status =
                                        '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                        valueOfElement[6] + '</a></div>';
                                }

                                var id = valueOfElement[0];
                                var employee_id = valueOfElement[1];
                                var payslip_id = valueOfElement[7];

                                if (valueOfElement[7] != 0) {
                                    var payslip =
                                        '<a href="#" data-url="{{ url('payslip/pdf/') }}/' +
                                        id +
                                        '/' + datePicker +
                                        '" data-size="lg"  data-ajax-popup="true" class=" btn-sm btn btn-warning" data-title="{{ __('Employee Payslip') }}">' +
                                        '{{ __('Payslip') }}' + '</a> ';
                                }
                                
                                    var edit =
                                        '<a href="#" data-url="{{ url('payslip/editemployee/') }}/' +
                                        payslip_id +
                                        '"  data-ajax-popup="true" class="btn-sm btn btn-info" data-title="{{ __('Edit Employee salary') }}">' +
                                        '{{ __('Edit') }}' + '</a>';
                                
                                var url_employee = valueOfElement['url'];

                                tr +=
                                    '<tr> ' +
                                    '<td> <a class="btn btn-outline-primary" href="' +
                                    url_employee + '">' +
                                    valueOfElement[1] + '</a></td> ' +
                                    '<td>' + valueOfElement[2] + '</td> ' +
                                    '<td>' + valueOfElement[8] + '</td>' +
                                   
                                    '<td>' + status + '</td>' +
                                    '<td>' + payslip + '</td>' +
                                    '</tr>';
                            });
                        } else {
                           
                        }

                        $('#pc-dt-render-column-cells tbody').html(tr);
                      
                        new simpleDatatables.DataTable('#pc-dt-render-column-cells');
                        

                    },
                    error: function(data) {

                    }

                });

            }

            $(document).on("change", ".month_date,.year_date", function() {
              
                callback();
            });

           
          
        });
    </script>
@endpush
