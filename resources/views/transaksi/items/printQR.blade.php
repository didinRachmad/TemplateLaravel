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

        .print-container {
            width: 80mm;
            padding: 25px;
            border: 2px solid black;
            background: white;
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
            font-size: 12px;
        }

        .row>* {
            padding: 0 !important;
        }
    </style>
</head>

<body>
    <div class="print-container text-center">
        <!-- Header -->
        {{-- <div class="header-title fw-bold">
            <p>Inventory System</p>
        </div> --}}

        <!-- QR Code -->
        <div class="qr-container my-3">
            {!! QrCode::size(200)->generate($item->id) !!}
        </div>

        <!-- Detail Informasi -->
        {{-- <div class="details container">
            <div class="row">
                <div class="col-3 d-flex justify-content-between fw-bold">
                    <span class="text-start">Item</span>
                    <span class="text-end pe-1">:</span>
                </div>
                <div class="col-9 text-start"> {{ $item->kode_item }} | {{ $item->nama_item }}</div>
            </div>
            <div class="row">
                <div class="col-3 d-flex justify-content-between fw-bold">
                    <span class="text-start">Jenis</span>
                    <span class="text-end pe-1">:</span>
                </div>
                <div class="col-9 text-start"> {{ $item->jenis }}</div>
            </div>
            <div class="row">
                <div class="col-3 d-flex justify-content-between fw-bold">
                    <span class="text-start">Kondisi</span>
                    <span class="text-end pe-1">:</span>
                </div>
                <div class="col-9 text-start"> {{ $item->kondisi }}</div>
            </div>
            <div class="row">
                <div class="col-3 d-flex justify-content-between fw-bold">
                    <span class="text-start">Lokasi</span>
                    <span class="text-end pe-1">:</span>
                </div>
                <div class="col-9 text-start"> {{ $item->nama_lokasi }} | {{ $item->detail_lokasi }}</div>
            </div>
        </div> --}}
    </div>
</body>

</html>
