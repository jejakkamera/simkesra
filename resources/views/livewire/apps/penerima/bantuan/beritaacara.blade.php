<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TANDA TERIMA UANG </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css" />
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .berkas table,
        .berkas th,
        .berkas td {
            border: 1px solid rgb(0, 0, 0);
            border-collapse: collapse;
            padding: 5px;
        }

        .validasi table,
        .validasi th,
        .validasi td {
            border: 1px solid rgb(0, 0, 0);
            border-collapse: collapse;
            padding: 5px;
        }

    </style>
</head>

<body class="A4">
    @foreach ($pivotQuery as $desa => $items)
        <section class="sheet padding-10mm">

            <div style="text-align:center;">
                <h2 style="margin-bottom: 0;">TANDA TERIMA UANG</h2>
                <p style="margin: 0;">
                    BANTUAN INSENTIF KEPADA GURU NGAJI, AMIL, MARBOT, GURU DTA, RA, MI, MTs, Dan TPQ
                </p>
                <p style="margin: 0;">
                    KECAMATAN {{ $items->first()->nm_wil ?? 'N/A' }} - DESA / KELURAHAN {{ strtoupper($desa) }}
                </p>
                <p style="margin-top: 0;">
                    TAHUN 2024
                </p>
            </div>

            <table class="berkas" style="width: 100%">
                <thead style="background-color: lightgreen">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama </th>
                        <th style="width: 30%;">Alamat</th>
                        <th style="width: 20%;">Besaran uang</th>
                        <th style="width: 55%;">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $key => $item)
                        <tr>
                            <td style="text-align: center;">{{ $key + 1 }}</td>
                            <td>{{ $item->nama_lengkap }} <br> {{ $item->nik }} <br> {{ $item->skema->judul  }} </td>
                            <td>{{ $item->alamat }}</td>
                            <td>Rp {{ number_format($item->skema->nominal) }}</td>
                            <td></td> {{-- Tempat untuk tanda tangan --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                // Rekap berdasarkan skema dalam desa
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

                    // Hitung total penerima dan nominal seluruh skema
                    $totalPenerimaDesa += 1;
                    $totalNominalDesa += $nominal;
                }
            @endphp

            {{-- Tabel Rekap Per Skema dalam Desa --}}
            <h4 style="margin-top: 30px;">Rekapitulasi Bantuan di Desa {{ $desa }}</h4>
            <table class="berkas" style="width: 100%; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Judul Skema</th>
                        <th>Jumlah Penerima</th>
                        <th>Total Nominal</th>
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
                    {{-- Baris TOTAL --}}
                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                        <td colspan="2" style="text-align: center;">TOTAL</td>
                        <td style="text-align: center;">{{ $totalPenerimaDesa }}</td>
                        <td>Rp {{ number_format($totalNominalDesa) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 50px; font-size: 14px;">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" style="padding-bottom: 10px;">Mengetahui</td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">
                            PIC Bank BJB <br><br><br><br>
                            <div style="border-bottom: 1px solid #000; width: 80%;"></div>
                        </td>
                        <td style="width: 40%;">
                            <table style="border: none; font-size: 14px;">
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



