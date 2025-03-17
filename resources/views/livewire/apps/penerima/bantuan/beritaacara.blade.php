<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TANDA TERIMA UANG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css" />
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10px; /* Ukuran font kecil */
        }

        h2, h3, h4, p {
            margin: 2px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px; /* Ukuran font di tabel */
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px; /* Kurangi padding */
        }

        th {
            background-color: #f0f0f0;
        }

        .berkas th {
            background-color: lightgreen;
        }

        .rekap th {
            background-color: #d0d0d0;
        }

        .footer-table td {
            border: none;
            padding: 2px 4px;
        }

        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }

        section.sheet {
        break-after: page;
        }
        section.sheet {
        overflow: visible;
        }


    </style>
    <style>@page { size: A4 }</style>
</head>

<body class="A4">
    @foreach ($pivotQuery as $desa => $items)
        <section class="sheet padding-10mm">

            <div style="text-align:center;">
                <h2>TANDA TERIMA UANG</h2>
                <p>BANTUAN INSENTIF KEPADA GURU NGAJI, AMIL, MARBOT, GURU DTA, RA, MI, MTs, Dan TPQ</p>
                <p>KECAMATAN {{ $items->first()->nm_wil ?? 'N/A' }} - DESA / KELURAHAN {{ strtoupper($desa) }}</p>
                <p>TAHUN 2024</p>
            </div>

            <table class="berkas">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama</th>
                        <th style="width: 30%;">Alamat</th>
                        <th style="width: 20%;">Besaran uang</th>
                        <th style="width: 20%;">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $hitung=1; @endphp
                    @foreach ($items as $key => $item)
                        <tr>
                            <td style="text-align: center;">{{ $key + 1 }}</td>
                            <td>{{ $item->nama_lengkap }} <br> {{ $item->nik }} <br> {{ $item->skema->judul }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>Rp {{ number_format($item->skema->nominal) }}</td>
                            <td></td>
                        </tr>
                        @php
                            if($hitung > 22){
                        @endphp
                                    </tbody>
                                </table>
                            </section>
                            <section class="sheet padding-10mm">
                                <table class="rekap" style="margin-top: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 25%;">Nama</th>
                                            <th style="width: 30%;">Alamat</th>
                                            <th style="width: 20%;">Besaran uang</th>
                                            <th style="width: 20%;">Tanda Tangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        @php
                        $hitung=0;
                            }
                            $hitung++;
                        @endphp
                    @endforeach
                </tbody>
            </table>

            @php
                $rekapDesa = [];
                $totalPenerimaDesa = 0;
                $totalNominalDesa = 0;

                foreach ($items as $item) {
                    $judul = $item->skema->judul;
                    $nominal = $item->skema->nominal;

                    if (!isset($rekapDesa[$judul])) {
                        $rekapDesa[$judul] = [
                            'jumlah_penerima' => 0,
                            'total_nominal' => 0
                        ];
                    }

                    $rekapDesa[$judul]['jumlah_penerima'] += 1;
                    $rekapDesa[$judul]['total_nominal'] += $nominal;

                    $totalPenerimaDesa += 1;
                    $totalNominalDesa += $nominal;
                }
            @endphp
             @php
                if($hitung > 12){
            @endphp
                </section>
                <section class="sheet padding-10mm">
            @php
                }
            @endphp
            <h4>Rekapitulasi Bantuan di Desa {{ $desa }}</h4>
            <table class="rekap" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Judul Skema</th>
                        <th style="width: 15%;">Jumlah Penerima</th>
                        <th style="width: 25%;">Total Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($rekapDesa as $judul => $data)
                        <tr>
                            <td style="text-align: center;">{{ $no++ }}</td>
                            <td>{{ $judul }}</td>
                            <td style="text-align: center;">{{ $data['jumlah_penerima'] }}</td>
                            <td>Rp {{ number_format($data['total_nominal']) }}</td>
                        </tr>
                        
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td colspan="2" style="text-align: center;">TOTAL</td>
                        <td style="text-align: center;">{{ $totalPenerimaDesa }}</td>
                        <td>Rp {{ number_format($totalNominalDesa) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" style="padding-bottom: 5px;">Mengetahui</td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">
                            PIC Bank BJB <br><br><br>
                            <div style="border-bottom: 1px solid #000; width: 80%;"></div>
                        </td>
                        <td style="width: 40%;">
                            <table class="footer-table">
                                <tr>
                                    <td>Sisa Amplop Guru Ngaji</td>
                                    <td>:</td>
                                    <td>..............</td>
                                </tr>
                                <tr>
                                    <td>Sisa Amplop Guru/Amil</td>
                                    <td>:</td>
                                    <td>..............</td>
                                </tr>
                                <tr>
                                    <td>Sisa Amplop Marbot</td>
                                    <td>:</td>
                                    <td>..............</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Sisa Uang</strong></td>
                                    <td>:</td>
                                    <td><strong>..............</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

        </section>
    @endforeach

    <script>
        window.print();
    </script>
</body>

</html>
