<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,800|great-vibes:400" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .cert-container { background: white; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 8px solid #1e3a8a; max-width: 800px; text-align: center; position: relative; }
        .cert-title { font-size: 3rem; color: #1e3a8a; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; }
        .cert-subtitle { font-size: 1.25rem; color: #4b5563; margin-top: 10px; }
        .trainee-name { font-family: 'Great Vibes', cursive; font-size: 4rem; color: #0f172a; margin: 20px 0; border-bottom: 2px solid #cbd5e1; display: inline-block; padding: 0 40px; line-height: 1.2; }
        .program-name { font-size: 1.5rem; font-weight: 600; color: #1e3a8a; margin: 20px 0; }
        .cert-footer { margin-top: 50px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature { border-top: 1px solid #4b5563; padding-top: 10px; width: 200px; font-weight: 600; color: #334155; }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; font-family: 'Inter', sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background 0.2s; }
        .print-btn:hover { background: #1d4ed8; }
        @media print { .print-btn { display: none !important; } body { background: white; } .cert-container { box-shadow: none; border: none; max-width: 100%; border: 4px solid #1e3a8a;} }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>
    <div class="cert-container">
        <div class="cert-title">Certificate of Completion</div>
        <div class="cert-subtitle">This proudly certifies that</div>
        
        <div class="trainee-name">{{ $trainee->name }}</div>
        
        <div class="cert-subtitle">has successfully completed the training program</div>
        <div class="program-name">{{ $program->title }}</div>
        
        <div style="color: #64748b; margin-top: 10px;">
            Held at {{ $program->venue }} on {{ $program->schedule_datetime->format('F jS, Y') }}<br/>
            Instructor: {{ $program->trainer->name ?? 'Amex Faculty' }}
        </div>

        <div class="cert-footer">
            <div class="signature" style="text-align:center;">Amex Training Director</div>
            <div style="color: #cbd5e1; font-weight: bold; font-size:1.5rem;">Amex Corp</div>
            <div class="signature" style="text-align:center;">{{ $program->trainer->name ?? 'Instructor' }}</div>
        </div>
    </div>
</body>
</html>
