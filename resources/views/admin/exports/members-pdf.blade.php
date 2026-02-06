<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Angolan Community Members Report') }}</title>
    <style>
        @page {
            margin: 50px;
        }
        
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #008751;
        }
        
        .header h1 {
            color: #008751;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            color: #666;
            margin: 5px 0;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #008751;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        
        th {
            background-color: #008751;
            color: white;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 6px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 9px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .skills-list {
            font-size: 9px;
            color: #666;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo-text {
            font-size: 18px;
            font-weight: bold;
            color: #008751;
        }
    </style>
</head>
<body>
    <div class="logo">
        <div class="logo-text">{{ config('app.name') }}</div>
        <div style="font-size: 10px; color: #666;">{{ __('Angolan Community in South Africa') }}</div>
    </div>
    
    <div class="header">
        <h1>{{ __('Community Members Report') }}</h1>
        <p>{{ __('Generated on') }}: {{ $date }}</p>
        <p>{{ __('Report ID') }}: REP-{{ date('Ymd-His') }}</p>
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">{{ __('Total Members') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['provinces'] }}</div>
            <div class="stat-label">{{ __('Provinces') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['willing_to_help'] }}</div>
            <div class="stat-label">{{ __('Volunteers') }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="80">{{ __('ID') }}</th>
                <th width="120">{{ __('Name') }}</th>
                <th width="100">{{ __('Contact') }}</th>
                <th width="100">{{ __('Location') }}</th>
                <th width="80">{{ __('Status') }}</th>
                <th width="150">{{ __('Skills') }}</th>
                <th width="100">{{ __('Registration') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{ $member->registration_number }}</td>
                <td>
                    <strong>{{ $member->first_name }} {{ $member->last_name }}</strong><br>
                    <small>{{ ucfirst(str_replace('_', ' ', $member->employment_status)) }}</small>
                </td>
                <td>
                    {{ $member->mobile_number }}<br>
                    <small>{{ $member->email ?? 'N/A' }}</small>
                </td>
                <td>
                    {{ $member->city }}<br>
                    <small>{{ $member->province }}</small>
                </td>
                <td>
                    @if($member->willing_to_help)
                    <span style="color: green;">● {{ __('Volunteer') }}</span>
                    @else
                    <span style="color: #666;">● {{ __('Member') }}</span>
                    @endif
                </td>
                <td class="skills-list">
                    @foreach($member->skills->take(3) as $skill)
                    • {{ $skill->name_en }}<br>
                    @endforeach
                    @if($member->skills->count() > 3)
                    <small>+{{ $member->skills->count() - 3 }} more</small>
                    @endif
                </td>
                <td>
                    {{ $member->registered_at->format('d/m/Y') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>{{ config('app.name') }} | {{ __('Confidential Report - For Community Leadership Use Only') }}</p>
        <p>{{ __('Generated by Admin System') }} | {{ __('Page') }} 1 of 1</p>
    </div>
</body>
</html>