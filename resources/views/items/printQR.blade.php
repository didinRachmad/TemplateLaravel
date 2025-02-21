<!DOCTYPE html>
<html>

<head>
    <title>Print QR Code</title>
    <style>
        /* CSS untuk memastikan ukuran cetak 2x2 (2 inci x 2 inci) */
        .qr-container {
            width: 2in;
            height: 2in;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media print {
            .qr-container {
                width: 2in;
                height: 2in;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="qr-container">
        {{-- Menghasilkan QR Code dari data kode_item --}}
        {!! QrCode::size(200)->generate($item->kode_item) !!}
    </div>
</body>

</html>
