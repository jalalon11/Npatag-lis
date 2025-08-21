<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report - {{ $monthName }} {{ $year }}</title>
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
        <a href="{{ route('admin.reports.sales', ['year' => $year, 'month' => $month, 'school_id' => $school ? $school->id : null]) }}" class="btn btn-secondary">Back to Reports</a>
        <button onclick="window.print()" class="btn">Print Report</button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title">Monthly Sales Report</h1>
            <p class="report-date">{{ $monthName }} 1, {{ $year }} - {{ $monthName }} {{ Carbon\Carbon::createFromDate($year, $month)->endOfMonth()->format('d') }}, {{ $year }} {{ $school ? '- ' . $school->name : '' }}</p>
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
            <p style="text-align: center; padding: 20px; color: #666;">Payment data has been removed from this report view.</p>
        </div>

        <!-- Summary View -->
        <div id="summary-view" style="display: none;">
            <p style="text-align: center; padding: 20px; color: #666;">Payment data has been removed from this report view.</p>
        </div>



        <div class="print-footer">
            <p>Generated from Grading System | {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
