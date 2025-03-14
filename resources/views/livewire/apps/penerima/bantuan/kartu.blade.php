<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kartu Tanda Bukti Pengambilan </title>
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

    <section class="sheet halaman1 padding-10mm" >
        <article>
            <center>
                <table class="header">
                    <tr>
                        <td>
                            <img src="{{ asset($school_logo) }}" alt="logo Apps" width="50"
                                style="margin-top:20px;margin-left:30px">
                        </td>
                        <td>
                            <h4 style="text-align: center">
                                KARTU TANDA BUKTI <br>
                                Pengambilan Bantuan Insentif <br>
                                Tahun Anggaran {{ $profile->period->name_period }}
                            </h4>
                        </td>
                    </tr>
                </table>
            </center>
            <hr>
            <br>
            <div>
                <img src="{{ asset($school_logo) }}" alt="Foto Profil"
                    style="float: right; width: 100px; height: auto;">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>: {{ ucwords($profile->nama_lengkap) }}</td>
                    </tr>
                    <tr>
                        <td>Tempat, tanggal lahir</td>
                        <td>: {{ ucwords($profile->profile->tempat_lahir) }}, {{ date('d-m-Y', strtotime($profile->Profile->tanggal_lahir)) }}</td>
                    </tr>
                    <tr>
                        <td>Instansi</td>
                        <td>: {{ $profile->profile->tempat_mengajar }}, <br>    {{ $profile->profile->Alamat_mengajar }}</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>: {{ $profile->profile->nik }}</td>
                    </tr>
                    <tr>
                        <td>Skema</td>
                        <td>: {{ $profile->skema->judul }}</td>
                    </tr>
                    <tr>
                        <td>Nominal Skema</td>
                        <td>: Rp. {{ number_format($profile->skema->nominal) }}</td>
                    </tr>

                </table>
                <hr>
                Pengambilan Bantuan di : <b>Desa / Kel {{ $profile->desa }} - {{ $profile->nm_wil }}</b><br>
                pengabilan hari sesuai dengan nama kecamatan di atas. <br>
                Membawa KTP Asli dan Fotokopy KTP 1.
            </div>
            <br>
            <div style="float: right">
                @php
                    $data = 'pendaftar:' . $profile->uuid . ' | periode:' . $profile->periode;
                @endphp
                {!! QrCode::size(150)->generate($data) !!}
            </div>
        </article>
    </section>


</div>

<script>
    window.print()
  </script>

</html>



