<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable QR Code - {{ $group->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-12 rounded-3xl border-2 border-sky-100 shadow-2xl max-w-md w-full text-center space-y-6">
        <div class="w-16 h-16 bg-sky-600 text-white rounded-2xl font-bold text-2xl flex items-center justify-center mx-auto shadow-md">
            {{ substr($group->name, 0, 2) }}
        </div>

        <div>
            <h1 class="text-3xl font-bold text-black">{{ $group->name }}</h1>
            <p class="text-sm font-semibold text-sky-600 mt-1">Connect. Network. Support. Grow Together.</p>
        </div>

        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 inline-block shadow-inner">
            {!! $qrCodeSvg !!}
        </div>

        <div class="space-y-1">
            <p class="text-lg font-bold text-black">Scan QR Code to Join Community</p>
            <p class="text-xs text-black font-mono">{{ $group->join_url }}</p>
        </div>

        <div class="no-print pt-4 space-y-2">
            <button onclick="window.print()" class="w-full py-3 bg-sky-600 text-white font-bold rounded-xl shadow hover:bg-sky-700 transition flex items-center justify-center space-x-2">
                <span><i class="fa-solid fa-print mr-2"></i> Print Poster / Save as PDF</span>
            </button>
            <button onclick="window.close()" class="text-xs text-black hover:underline">Close Window</button>
        </div>
    </div>
</body>
</html>
