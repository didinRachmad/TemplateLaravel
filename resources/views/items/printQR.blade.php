<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $item->kode_item }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                padding: 0;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .print-container {
                border: none;
                box-shadow: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .print-container {
            width: 80mm;
            padding: 25px;
            border: 2px solid black;
            background: white;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
        }

        .qr-container img {
            width: 50mm;
            height: 50mm;
        }

        .details {
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header-title">
            <p>Inventory System</p>
            <p><strong>Kode Item: {{ $item->kode_item }}</strong></p>
        </div>

        <!-- QR Code -->
        <div class="qr-container my-3">
            {!! QrCode::size(200)->generate($item->kode_item) !!}
        </div>

        <!-- Detail Informasi -->
        <div class="details">
            <p><strong>Item:</strong> {{ $item->kode_item }} | {{ $item->nama_item }} </p>
            <p><strong>Jenis:</strong> {{ $item->jenis }}</p>
            <p><strong>Kondisi:</strong> {{ $item->kondisi }}</p>
            <p><strong>Lokasi:</strong> {{ $item->kode_lokasi }} | {{ $item->nama_lokasi }} </p>
            <p><strong></strong> {{ $item->nama_lokasi }}</p>
        </div>
    </div>
</body>

</html>
