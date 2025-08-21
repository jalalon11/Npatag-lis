<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Sales Report - {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            color: #333;
            line-height: 1.3;
            font-size: 12px;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }
        .report-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .report-date {
            font-size: 12px;
            margin: 3px 0 0;
        }
        .report-view-options {
            margin-top: 5px;
            border-bottom: 1px solid #ddd;
            padding: 5px 10px;
        }
        .report-view-options a {
            margin-right: 15px;
            text-decoration: none;
            color: #0066cc;
            font-size: 12px;
        }
        .report-view-options a.active {
            font-weight: bold;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 5px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 4px 5px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .summary-section {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .summary-table {
            width: auto;
            margin-left: auto;
        }
        .summary-table td {
            padding: 3px 10px;
            border: none;
        }
        .summary-table tr:last-child {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .print-controls {
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 8px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .print-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            color: #666;
            padding-bottom: 10px;
        }
        .compact-table th, .compact-table td {
            padding: 3px 5px;
            font-size: 11px;
        }
        .compact-summary {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 3px;
        }
        .compact-summary h3 {
            margin-top: 0;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }
        .compact-summary h4 {
            margin-top: 10px;
            font-size: 12px;
            color: #333;
            margin-bottom: 5px;
        }
        .compact-summary table {
            margin-bottom: 10px;
        }
        .compact-summary td {
            padding: 3px 0;
            font-size: 11px;
        }
        @media print {
            .print-controls {
                display: none;
            }
            body {
                padding: 0;
            }
            .report-container {
                border: none;
            }
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <a href="{{ route('admin.reports.sales', ['year' => $year, 'school_id' => $school ? $school->id : null]) }}" class="btn btn-secondary">Back to Reports</a>
        <button onclick="window.print()" class="btn">Print Report</button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title">Yearly Sales Report</h1>
            <p class="report-date">January 1, {{ $year }} - December 31, {{ $year }} {{ $school ? '- ' . $school->name : '' }}</p>
        </div>

        <div class="report-view-options">
            <a href="#" class="view-option active" data-view="detailed">Detailed View</a>
            <a href="#" class="view-option" data-view="summary">Summary View</a>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get all view option links
                const viewOptions = document.querySelectorAll('.view-option');
                const detailedView = document.getElementById('detailed-view');
                const summaryView = document.getElementById('summary-view');

                // Add click event to each option
                viewOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Remove active class from all options
                        viewOptions.forEach(opt => opt.classList.remove('active'));

                        // Add active class to clicked option
                        this.classList.add('active');

                        // Show/hide appropriate view
                        const viewType = this.getAttribute('data-view');
                        if (viewType === 'detailed') {
                            detailedView.style.display = 'block';
                            summaryView.style.display = 'none';
                        } else {
                            detailedView.style.display = 'none';
                            summaryView.style.display = 'block';
                        }
                    });
                });
            });
        </script>

        <!-- Detailed View -->
        <div id="detailed-view" style="display: block;">
            <!-- Monthly Sales Summary -->
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Transactions</th>
                        <th class="text-right">Sales Amount</th>
                        <th class="text-right">Average</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $months = [
                            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                        ];

                        // Calculate monthly transaction counts
                        $monthlyTransactions = [];
                        foreach($payments as $payment) {
                            $paymentMonth = $payment->payment_date->month;
                            if (!isset($monthlyTransactions[$paymentMonth])) {
                                $monthlyTransactions[$paymentMonth] = 0;
                            }
                            $monthlyTransactions[$paymentMonth]++;
                        }

                        $totalTransactions = array_sum($monthlyTransactions);
                    @endphp
                    @foreach($monthlySales as $month => $amount)
                        <tr>
                            <td>{{ $months[$month] }}</td>
                            <td class="text-center">{{ $monthlyTransactions[$month] ?? 0 }}</td>
                            <td class="text-right">₱{{ number_format($amount, 2) }}</td>
                            <td class="text-right">
                                @if(isset($monthlyTransactions[$month]) && $monthlyTransactions[$month] > 0)
                                    ₱{{ number_format($amount / $monthlyTransactions[$month], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="summary-row">
                        <td>Total</td>
                        <td class="text-center">{{ $totalTransactions }}</td>
                        <td class="text-right">₱{{ number_format(array_sum($monthlySales), 2) }}</td>
                        <td class="text-right">
                            @if($totalTransactions > 0)
                                ₱{{ number_format(array_sum($monthlySales) / $totalTransactions, 2) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>



            <!-- Top Schools Section -->
            <table class="compact-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Division</th>
                        <th class="text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSchools as $school)
                        <tr>
                            <td>{{ $school->school->name }}</td>
                            <td>{{ $school->school->school_division->name ?? 'No Division' }}</td>
                            <td class="text-right">₱{{ number_format($school->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">No schools found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>

        <!-- Summary View -->
        <div id="summary-view" style="display: none;">
            <div class="compact-summary">
                <h3>Yearly Sales Summary - {{ $year }}</h3>
                <table class="compact-table">
                    <tr>
                        <td style="width: 50%; font-weight: bold;">Total Revenue:</td>
                        <td style="text-align: right; font-weight: bold;">₱{{ number_format($totalSales, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Transactions:</td>
                        <td style="text-align: right;">{{ $payments->count() }}</td>
                    </tr>
                    <tr>
                        <td>Average Transaction:</td>
                        <td style="text-align: right;">₱{{ $payments->count() > 0 ? number_format($totalSales / $payments->count(), 2) : '0.00' }}</td>
                    </tr>
                </table>

                <!-- Monthly Breakdown -->
                <div style="margin-top: 10px;">
                    <h4>Monthly Breakdown</h4>
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Txns</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Avg</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $months = [
                                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                                ];

                                // Calculate monthly transaction counts if not already done
                                if (!isset($monthlyTransactions)) {
                                    $monthlyTransactions = [];
                                    foreach($payments as $payment) {
                                        $paymentMonth = $payment->payment_date->month;
                                        if (!isset($monthlyTransactions[$paymentMonth])) {
                                            $monthlyTransactions[$paymentMonth] = 0;
                                        }
                                        $monthlyTransactions[$paymentMonth]++;
                                    }
                                    $totalTransactions = array_sum($monthlyTransactions);
                                }
                            @endphp
                            @foreach($monthlySales as $month => $amount)
                                @if($amount > 0)
                                <tr>
                                    <td>{{ $months[$month] }}</td>
                                    <td class="text-center">{{ $monthlyTransactions[$month] ?? 0 }}</td>
                                    <td class="text-right">₱{{ number_format($amount, 2) }}</td>
                                    <td class="text-right">
                                        @if(isset($monthlyTransactions[$month]) && $monthlyTransactions[$month] > 0)
                                            ₱{{ number_format($amount / $monthlyTransactions[$month], 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tr style="border-top: 1px solid #ddd; font-weight: bold;">
                                <td>Total</td>
                                <td class="text-center">{{ $totalTransactions }}</td>
                                <td class="text-right">₱{{ number_format(array_sum($monthlySales), 2) }}</td>
                                <td class="text-right">
                                    @if($totalTransactions > 0)
                                        ₱{{ number_format(array_sum($monthlySales) / $totalTransactions, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <!-- Top Schools -->
                <div style="margin-top: 15px;">
                    <h4>Top 5 Schools</h4>
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Division</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSchools->take(5) as $school)
                                <tr>
                                    <td>{{ $school->school->name }}</td>
                                    <td>{{ $school->school->school_division->name ?? 'No Division' }}</td>
                                    <td class="text-right">₱{{ number_format($school->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center;">No schools found</td>
                                </tr>
                            @endforelse
                            @if($topSchools->count() > 0)
                                <tr style="border-top: 1px solid #ddd; font-weight: bold;">
                                    <td colspan="2">Top 5 Total</td>
                                    <td class="text-right">₱{{ number_format($topSchools->take(5)->sum('total'), 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
         <div class="summary-section">
             <p style="text-align: center; padding: 20px; color: #666;">Payment data has been removed from this report view.</p>
         </div>

        <div class="print-footer">
            <p>Generated from Grading System | {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
