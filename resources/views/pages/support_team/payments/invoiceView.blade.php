@extends('layouts.master')
@section('page_title', 'Print Invoice')
@section('content')
<!-- col-lg-12 start here -->
<div class="card card-default plain" id="dash_0">
    <!-- Start .card -->
    <div class="card-body p30">
        <div class="row">
            <!-- Start .row -->
            <div class="col-lg-6">
                <!-- col-lg-6 start here -->
                <div class="invoice-logo"><img width="100" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Invoice logo"></div>
            </div>
            <!-- col-lg-6 end here -->
            <div class="col-lg-6">
                <!-- col-lg-6 start here -->
                <div class="invoice-from">
                    <ul class="list-unstyled text-right">
                        <li>{{ Qs::getSystemName() }}</li>
                        <li>{{ Qs::getSystemAddress() }}</li>
                        <li>{{ Qs::getSystemPhone() }}</li>
                        <li>VAT Number EU826113958</li>
                    </ul>
                </div>
            </div>
            <!-- col-lg-6 end here -->
            <div class="col-lg-12">
                <!-- col-lg-12 start here -->
                <div class="invoice-details mt25">
                    <div class="well">
                        <ul class="list-unstyled mb0">
                            <li><strong>Invoice</strong> #936988</li>
                            <li><strong>Invoice Date:</strong> Monday, October 10th, 2015</li>
                            <li><strong>Due Date:</strong> Thursday, December 1th, 2015</li>
                            <li><strong>Status:</strong> <span class="label label-danger">UNPAID</span></li>
                        </ul>
                    </div>
                </div>
                

                <div class="invoice-to mt25">
                    <ul class="list-unstyled">
                        <li><strong>Invoiced To</strong></li>
                        <li>{{$std_info->name}}</li>
                        <!-- <li>Roupark 37</li>
                        <li>New York, NY, 2014</li>
                        <li>USA</li> -->
                    </ul>
                </div>
                <div class="invoice-items">
                    <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="per70 text-center">Description</th>
                                    <th class="per5 text-center">Qty</th>
                                    <th class="per25 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($uncleared as $uc)
                                <tr>
                                    <td>{{ $uc->payment->title }}</td>
                                    <td class="text-center"> @money($uc->payment->amount) </td>
                                    <td class="text-center">{{ $uc->amt_paid ?: '0.00' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @foreach($uncleared as $uc)
                                <tr>
                                    <th colspan="2" class="text-right">Sub Total:</th>
                                    <th class="text-center"> @money($uc->payment->amount)</th>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2" class="text-right">18% VAT:</th>
                                    <th class="text-center"> @money($uc->payment->amount *0.18)</th>
                                </tr>
                                
                                <tr>
                                    <th colspan="2" class="text-right">Credit:</th>
                                    <th class="text-center">$00.00 </th>
                                </tr>
                                
                                @foreach($uncleared as $uc)
                                <tr>
                                    <th colspan="2" class="text-right">Total:</th>
                                    <th class="text-center">@money($uc->payment->amount - $uc->payment->amount *0.18)</th>
                                </tr>
                                @endforeach
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="invoice-footer mt25">
                    <p class="text-center">Generated on Monday, October 08th, 2015 <a href="#" class="btn btn-default ml15"><i class="fa fa-print mr5"></i> Print</a></p>
                </div>
            </div>
            <!-- col-lg-12 end here -->
        </div>
        <!-- End .row -->
    </div>
</div>
<!-- End .card -->

<style>
    body{
    margin-top:10px;
    background:#eee;    
}
</style>
@endsection