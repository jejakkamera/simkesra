<div>
    @livewire('Apps.Period.Bank.Dashboard')
    <hr>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js'></script>
        
        @php
            if(session('active_role') =='unit'){
        @endphp

        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">List Skema</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Skema</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filterIds as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->judul }}</td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        

        @php
            }
        @endphp

        @php
            if(session('active_role') !=='unit'){
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Print QR</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route(session('active_role').'.PenerimaBantuanKartukec') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="kecamatan_id" class="form-label">Nama Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan_id" class="form-select" required>
                                    <option value="all">-- Semua Kecamatan --</option>
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id_wil }}">{{ $item->nm_wil }}</option>
                                    @endforeach
                                </select>
                                @error('kecamatan_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status_cetak" class="form-label">Status Cetak</label>
                                <select name="status_cetak" id="status_cetak" class="form-select" required>
                                    <option value="all">-- Semua --</option>
                                    <option value="1">Sudah Cetak</option>
                                    <option value="0">Belum Cetak</option>
                                </select>
                                @error('status_cetak')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Print Tanda Terima</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route(session('active_role').'.TandaTerima') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="kecamatan_id" class="form-label">Nama Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-select" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatan as $item)
                                    <option value="{{ $item->id_wil }}">{{ $item->nm_wil }}</option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            <div >
                <div class="card">

                     <button class="btn btn-warning" onclick="exportTableToExcel('pvtTable')">Export Table Data To Excel File</button>
        
                    <div style="margin: 1em;" id="output"></div>
                </div>
            </div>
        </div>
        @php
            }
        @endphp

    <script>
        var salesPivotData = <?php echo json_encode($pivotData); ?> ;
        // google.load("visualization", "1", {packages:["corechart", "charteditor"]});
        // var derivers = $.pivotUtilities.derivers;
        // var renderers = $.extend($.pivotUtilities.renderers,
        // $.pivotUtilities.gchart_renderers);
    
        $("#output").pivotUI(
            salesPivotData,{
                // renderers: renderers,
                vals: ["nominal"],
                aggregatorName: "Count",
                rows: ['tanggal_verif_teller','nm_wil'],
                cols: ['id_verif_teller'],
        });
    
        function exportTableToExcel(tableID, filename = 'Data Siswa'){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableHTML = document.getElementsByClassName(tableID)[0].outerHTML.replace(/ /g, '%20');
    
            filename = filename?filename+'.xls':'excel_data.xls';
    
            downloadLink = document.createElement("a");
    
            document.body.appendChild(downloadLink);
    
            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
        }
    </script>
</div>
