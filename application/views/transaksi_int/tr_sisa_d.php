    <script type="text/javascript">
    
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 400,
            width: 800,
            modal: true,
            autoOpen:false,
        });
        });    
     
     $(function(){
        $('#dgkibd').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_d_sisa',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
	    {field:'no_reg',
    		title:'no reg',
    		width:8,
            align:"center"},
            {field:'id_barang',
    		title:'id barang',
    		width:40,
            align:"center"},
			{field:'nm_brg',
    		title:'nm barang',
    		width:30,
            align:"left"},
			{field:'tahun',
    		title:'tahun',
    		width:10,
            align:"center"},
			{field:'nilai',
    		title:'nilai',
    		width:30,
            align:"right"},
			{field:'kd_skpd',
    		title:'kode',
    		width:0,
            align:"right"},
			
	
	
			{field:'hapus',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
        ]],
        onSelect:function(rowIndex,rowData){
          lcidx   = rowIndex;
          noregis = rowData.no_reg;
          idbrang = rowData.id_barang;
          nmbrang = rowData.nama_barang; 
          thn	  = rowData.tahun;
		  nilaix  = rowData.nilai;
          get(noregis,idbrang,nmbrang,thn,nilaix);   
          kosong();                             
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Input Akum.Penysutan dan Sisa Umur'; 
          lcstatus = 'edit';
          noregis = rowData.no_reg;
          idbrang = rowData.id_barang;
          nmbrang = rowData.nama_barang; 
          thn	  = rowData.tahun;
		  nilaix  = rowData.nilai;
		  unit    = rowData.kd_skpd;
          get(noregis,idbrang,nmbrang,thn,nilaix,unit);   
		  edit_data();   
        }
        
        });

		
		$('#dgsisad').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_d_sisah',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
	    {field:'no_reg',
    		title:'no reg',
    		width:8,
            align:"center"},
            {field:'id_barang',
    		title:'id barang',
    		width:40,
            align:"center"},
			{field:'nm_brg',
    		title:'nm barang',
    		width:30,
            align:"left"},
			{field:'tahun',
    		title:'tahun',
    		width:10,
            align:"center"},
			{field:'nilai',
    		title:'nilai',
    		width:30,
            align:"right"},
			{field:'akum_penyu',
    		title:'akum.penyu',
    		width:20,
            align:"right"},
			{field:'sisa_umur',
    		title:'umur',
    		width:10,
            align:"right"},
		
			{field:'hapus',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/close.png' onclick='javascript:hilangkan();'' />";}}
        ]],
        onSelect:function(rowIndex,rowData){
          lcidx   = rowIndex;
          noregis = rowData.no_reg;
          idbrang = rowData.id_barang;
          nmbrang = rowData.nama_barang; 
          thn	  = rowData.tahun;
		  nilaix  = rowData.nilai;
          get(noregis,idbrang,nmbrang,thn,nilaix);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Input Akum.Penysutan dan Sisa Umur'; 
          lcstatus = 'edit';
          noregis = rowData.no_reg;
          idbrang = rowData.id_barang;
          nmbrang = rowData.nama_barang; 
          thn	  = rowData.tahun;
		  nilaix  = rowData.nilai;
		  unit    = rowData.kd_skpd;
          get(noregis,idbrang,nmbrang,thn,nilaix,unit);   
		  edit_data();   
        }
        
        });
	
    
    });
	


	   function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Akum Penyu dan Sisa Umur';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');

        }  

		

    function get(noregis,idbrang,nmbrang,thn,nilaix,unit){
		$("#nilaix").attr("value",nilaix);
		$("#unit").attr("value",unit); 
		$("#noregis").attr("value",noregis);
        $("#idbrang").attr("value",idbrang);
        $("#nmbrang").attr("value",nmbrang); 
        $("#thn").attr("value",thn);
    }
    
       
    function kosong(){
        $("#noregis").attr("value",'');
        $("#idbrang").attr("value",'');
        $("#nmbrang").attr("value",''); 
        $("#thn").attr("value",'');
		$("#nilaix").attr("value",'');		
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
     $('#dgsisad').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_d_sisah',
        queryParams:({cari:kriteria})
        });        
     });
    }

    function cari2(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
     $('#dgsisad').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_d_sisa',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
	function tab1(){
    $('#tabs1').click();
	$('#dgsisad').edatagrid('reload');
}
function tab2(){
   $('#tabs2').click()
$('#dgkibd').edatagrid('reload');
   cekdouble=1;
}

    function simpan(){
        var simkd	= document.getElementById('unit').value;
		//var simkib	= document.getElementById('kode').value;
		var simid 	= document.getElementById('idbrang').value;
		var simnm 	= document.getElementById('nmbrang').value;	
        var simthn	= document.getElementById('thn').value;                
        var simreg  = document.getElementById('noregis').value;
		var simpenyu= document.getElementById('akum_penyu').value;
		var simsisa = document.getElementById('sisaumur').value;
		//var simnilai= document.getElementById('nilaix').value;
		//var ckib    = $('#pilkib').combogrid('getValue'); 

		if (simkd==''){
            alert('Pilih SKPD Terlebih dahulu');
            exit();
        } 
        
        
		if (simid==''){
            alert('Pilih Barang dahulu');
            exit();
        } 
		
		if (simpenyu==''){
            alert('Isi Nilai Penyusutan');
            exit();
        } 
		
		if (simsisa==''){
            alert('Isi Sisa Umur');
            exit();
        }
		
                     if(lcstatus=='tambah'){
            
            lcinsert = "(no_reg,id_barang,nm_brg,nilai,kd_skpd,tahun,akum_penyu,nilai_sisa)";
            lcvalues = "('"+simreg+"','"+simid+"','"+simnm+"','"+simnilai+"','"+simkd+"','"+simthn+"','"+simpenyu+"','"+simsisa+"')";
            
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/master/simpan_master',
                    data: ({tabel:'trkib_d',kolom:lcinsert,nilai:lcvalues,cid:'id_barang',lcid:simid}),
                    dataType:"json"
                });
            });    
            
            
        $('#dgkibd').datagrid('appendRow',{no_reg:simreg,id_barang:simid,nm_brg:simnm,kd_skpd:simkd,tahun:simthn,akum_penyu:simpenyu,nilai_sisa:simsisa});
					}else {
                    
                    lcquery = "UPDATE trkib_d SET akum_penyu='"+simpenyu+"',sisa_umur='"+simsisa+"' where id_barang='"+simid+"'";
                    
        
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/master/update_master',
                        data: ({st_query:lcquery}),
                        dataType:"json"
                    });
                    });
                    
						$('#dgsisad').edatagrid('reload');
						$('#dgkibd').edatagrid('reload');
						                    
                    
                        $('#dgkibd').datagrid('updateRow',{
                    	index: lcidx,
                    	row: {
							
							akum_penyu : simpenyu,
							sisa_umur  : simsisa
						}
                    });
                }
               
                
                alert("Data Berhasil disimpan");
                $("#dialog-modal").dialog('close');
              

    } 
  
   function segarkan(){

        $('#dgkibd').edatagrid('reload');
        $('#dgsisad').edatagrid('reload');
		
		}   
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Wilayah';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        $('#dgkibd').edatagrid('reload');
		
		} 
		
function kosong(){
	$('#akum_penyu').attr('value','');
	$('#sisaumur').attr('value','');	
}
		
		function hilangkan(){
                    
        var simid = document.getElementById('idbrang').value;
               if (simid !=''){
		var del=confirm('Apakah Anda yakin ingin Menhapus data '+simid+'?');
		if (del==true){
		lcquery = "UPDATE trkib_d SET akum_penyu=null ,sisa_umur=null where id_barang='"+simid+"'";
                    
        
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/master/update_master',
                        data: ({st_query:lcquery}),
                        dataType:"json"
                    });
                    });
						$('#dgsisad').edatagrid('reload');
						$('#dgkibd').edatagrid('reload');
						
                        $('#dgsisad').datagrid('updateRow',{
                    	index: lcidx,
                    	row: {            		
							akum_penyu : simpenyu,
							sisa_umur  : simsisa
						}
                    });                    
                    
                        $('#dgkibd').datagrid('updateRow',{
                    	index: lcidx,
                    	row: {
                    		
							akum_penyu : simpenyu,
							sisa_umur  : simsisa
						}
                    });
                }
}
		}
		
		function keluar(){
			
        $("#dialog-modal").dialog('close');
     }      
   </script>

<div id="tabs" class="easyui-tabs">

    <ul style="background-color:#2da305;">
        <li><a href="#tabs-1" style="width: 453px;" id="tabs1" onclick="javascript:segarkan()">List View</a></li>
        <li><a href="#tabs-2" style="width: 453px;" id="tabs2" onclick="javascript:segarkan()">Form Input</a></li>        
    </ul>
    <div id="tabs-1">
<h1 align="center"><b>Inputan Akum Penyusutan dan Sisa Umur KIB D</b></h1>
	<div>
            <p align="right">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tab2()">Tambah</a>
                <!--a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a-->                           
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                <input type="text" value="" id="txtcari"/>
                <input type="hidden" value="" id="txtnodok_h"/>
                <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit">              
                <table  id="dgsisad" title="List Data Hasil Input Akumulasi Penyusutan dan Sisa Umur" style="width:940px;height:370px;" >  
                </table>                
                </div>
            </p>
        </div>
    </div>
        <div id="tabs-2">
<h1 align="center"><b>Inputan Akum Penyusutan dan Sisa Umur KIB D</b></h1>
	<div>
            <p align="right">
                <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tab2();">Tambah</a>
                <!--a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a-->                           
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari2();">Cari</a>
                <input type="text" value="" id="txtcari"/>
                <input type="hidden" value="" id="txtnodok_h"/>
                <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit">              
                <table  id="dgkibd" title="List KIB D" style="width:940px;height:370px;" >  
                </table>                
                </div>
            </p>
        </div>
    </div>
<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
          <table align="center" style="width:30%;" border="0">
           <tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="unit" disabled = true name="unit" style="width: 200px;" value=""/>
                <!--td  hidden="true"><input type="text" id="thn2" style="width: 140px;" /></td-->
            </tr>		
			<tr>
                <td>Id Barang</td>
                <td>:</td>
                <td><input  id="idbrang" name="idbrang" disabled = true style="width: 200px;" /></td>
				<td><input type="text" id="nmbrang" name="nmbrang" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
			<tr>
                <td width="20">Tahun</td>
                <td width="1%">:</td>
				<td><input  id="thn" disabled = true name="thn" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
			<tr>
                <td width="20">NILAI</td>
                <td width="1%">:</td>
				<td><input  id="nilaix" disabled = true name="nilaix" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
			<tr>
                <td width="20">No Register</td>
                <td width="1%">:</td>
				<td><input id="noregis" disabled = true name="noregis" style="width: 200px;border: 0;" readonly="true"/></td> 
			</tr>
			<tr>
                <td width="20">AKUM.PENYUSUTAN TAHUN LALU</td>
                <td width="1%">:</td>
                <td><input type="text" id="akum_penyu" style="width:200px;"/></td>  
            </tr>            
			
		   <tr>
                <td width="30%">SISA UMUR</td>
                <td width="1%">:</td>
                <td><input type="text" id="sisaumur" style="width:50px;"/></td>  
            </tr>
            <tr>
             <td colspan="4">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="4" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>  
 
    </fieldset> 
</div>

