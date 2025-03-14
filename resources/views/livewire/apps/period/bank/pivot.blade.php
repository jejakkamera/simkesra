<div>
    <livewire:Apps.Period.Bank.Dashboard />
    <hr>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/gchart_renderers.min.js'></script>
        <script src='https://cdn.plot.ly/plotly-basic-latest.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.22.0/plotly_renderers.min.js'></script>
        
      

        <br>
        <div class="row">
            <div >
                <div class="card">

                     <button class="btn btn-warning" onclick="exportTableToExcel('pvtTable')">Export Table Data To Excel File</button>
        
                    <div style="margin: 1em;" id="output"></div>
                </div>
            </div>
        </div>

    <script>
        var salesPivotData = <?php echo json_encode($pivotData); ?> ;
        google.load("visualization", "1", {packages:["corechart", "charteditor"]});
        var derivers = $.pivotUtilities.derivers;
        var renderers = $.extend($.pivotUtilities.renderers,
        $.pivotUtilities.gchart_renderers);
    
        $("#output").pivotUI(
            salesPivotData,{
                renderers: renderers,
                vals: ["nominal"],
                aggregatorName: "Count",
                rows: ['judul','nm_wil'],
                cols: ['verif_teller'],
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
