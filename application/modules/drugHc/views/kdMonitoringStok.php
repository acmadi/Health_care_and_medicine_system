
<div class="container">
    <div class="row">
		<div class="col-lg-12">
            <div class="panel panel-primary">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= 'Daftar Stok Obat Sekarang' ?></h3>
                </header>
                <div class="panel-body">
				<form id="find">
					<div class="col-md-3">
						<div class="form-group">
						<select id="puskesmas" name="puskesmas" class="form-control" onchange="showUnit(this.value)">
							  <option selected value="-1">Pilih Puskesmas</option>
							  <?php foreach ($puskesmas as $row) :?>
							  <option value="<?php echo $row['ID_GEDUNG'] ?>"><?php echo $row['NAMA_GEDUNG'] ?></option>
							  <?php endforeach; ?>
						</select> 
						</div>
					</div>	
					<div class="col-md-3">
						<div class="form-group">
						<select id="unit" name="unit" class="form-control in"> <!--onchange="showAntrian(this.value)">-->
							  <option selected value="-1">Pilih Unit Pelayanan</option>
							  <?php foreach ($units as $row) :?>
							  <option value="<?php echo $row['ID_UNIT'] ?>"><?php echo $row['NAMA_UNIT'] ?></option>
							  <?php endforeach; ?>
							  <option selected value="-1">Semua</option>
						</select> 
						</div>
					</div>	
					<div class="col-md-3">
						<div class="form-group">
							<input type="text" id="inputTanggal" name="inputTanggal" class="form-control datepicker"  size="16" value="<?= date('Y-m-d') ?>" >
						</div>
					</div>	
					<input class="btn btn-primary" type="button" value="Cari" id="submit" name="submit" onclick=kirim()>
					</form>
					<div class="col-md-6">
					</div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"> Filter Fields <b class="caret"></b> </a>
                                    <ul class="dropdown-menu stop-propagation" style="overflow:auto;max-height:450px;padding:10px;">
                                        <div id="filter-list"></div>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"> Row Label Fields <b class="caret"></b> </a>
                                    <ul class="dropdown-menu stop-propagation" style="overflow:auto;max-height:450px;padding:10px;">
                                        <div id="row-label-fields"></div>
                                    </ul>
                                </li>
                            </ul>
                            <hr/>
                            <h3 class="oxigenfontblue">Hasilnya</h3>
                            <span class="hide-on-print" id="pivot-detail"></span>
                            <hr/>
                            <div style="" id="results"></div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
</div>
<script>

$('#find').submit(function(e){
    kirim();
    e.preventDefault();
})

$(function () {
	$( ".datepicker" ).datepicker({
		format: 'yyyy-mm-dd',
	});
});
					
    var fields = [{
            name : 'NOMOR BATCH',
            type : 'string',
            filterable : true
        },{
            name : 'NAMA OBAT',
            type : 'string',
            filterable : true
        },{
            name : 'STOK OBAT TERAKHIR',
            type : 'string',
            filterable : true
        },{
            name : 'SATUAN',
            type : 'string',
            filterable : true
        },{
            name : 'EXPIRED DATE',
            type : 'string',
            filterable : true
        }
    ]
    
		function kirim() {	 
			if ($( "#puskesmas" ).val() == -1 || $( "#inputTanggal" ).val() == '') return;
			
			$.ajax({
				type : "POST",
				url : '<?= base_url().$this->uri->segment(1, 0).'/'.$this->uri->segment(2, 0).'/searchMonitoringUnit/'; ?>',
				data: {unit : $( "#unit" ).val(), puskesmas :$( "#puskesmas" ).val(), tanggal : $( "#inputTanggal" ).val()},
				success : function(dataCheck) {
					jso = dataCheck;
					setupPivot({
						json : jso,
						fields : fields,
						rowLabels : ["NOMOR BATCH", "NAMA OBAT","STOK OBAT TERAKHIR","SATUAN","EXPIRED DATE"]
					})
					$('.stop-propagation').click(function(event) {
						event.stopPropagation();
					});
				},
				error: function(){						
						alert('Data Stok Obat Kosong');
				}
			});
		};
	
	function setupPivot(input) {
        input.callbacks = {
            afterUpdateResults: function () {
                $('#results > table').dataTable({
                    "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
                    "iDisplayLength": 25,
                    "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ records per page"
                    }
                });
            }
        };
        $('#pivot-demo').pivot_display('setup', input);
    };
	
	function showUnit (value){
	if (value == -1) return;
	$( "#myBody" ).html('');

	var jso;
	var id_numbers = new Array();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url() .$this->uri->segment(1, 0).'/'.$this->uri->segment(2, 0).'/AllunitInPuskesmas'; ?>",
		data: {id : value},
		success: function (dataCheck) {
			id_numbers = dataCheck;
			var i =0;
			var input = '<option selected value="-1">Pilih Unit Pelayanan</option><option selected value="-1">Semua</option>';
			if(dataCheck.units != null)
			for (i=0;i<dataCheck.units.length;i++)
			{
				input += '<option value="'+dataCheck.units[i]['ID_UNIT']+'">'+dataCheck.units[i]['NAMA_UNIT']+'</option>';
			}
			$( "#unit" ).html(input);
		}
		,error: function (xhr, ajaxOptions, thrownError) {
             alert(xhr.status);
             alert(thrownError);
             alert(xhr.responseText);
           },
		dataType:"json"
	});
}
</script>