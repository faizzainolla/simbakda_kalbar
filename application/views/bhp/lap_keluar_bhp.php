    <script type="text/javascript">
    
    var kdwil= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
	var ctk =1;
    
    function kosong(){
        $("#kdmilik").attr("value",'');
        $("#nmmilik").attr("value",''); 
    }
	function opt(val){        
        ctk = val;
        if (ctk=='1'){
            $("#div_skpd").hide();
            $("#kdubidskpd").combogrid("setValue",''); 
			$("#nmbidskpd").attr("value",'');
            $("#giat").combogrid("setValue",''); 
			$("#nm_giat").attr("value",'');
            $("#giat").combogrid('clear');
        } else if(ctk=='2'){
            $("#div_skpd").show();
            $("#giat").combogrid("setValue",''); 
			$("#nm_giat").attr("value",'');
            $("#giat").combogrid('clear');
		}else {
            exit();
        }
	}
	function openWindow($cek){
		var cpilih	= $cek; 
        var cskpd 	= $('#skpd').combogrid('getValue');
        var cunit 	= $('#kdubidskpd').combogrid('getValue');
        var cnmskpd = document.getElementById('nmskpd').value;
        var cnmunit = document.getElementById('nmbidskpd').value;
        var cgiat 	= $('#giat').combogrid('getValue');
        var cnmgiat = document.getElementById('nm_giat').value;
        var ctahu 	= document.getElementById('nip_tahu').value;
        var cbend 	= document.getElementById('nip_bend').value;
        var ctgl  	= $('#tgl_cetak').datebox('getValue');
        var ctgl2 	= $('#tgl_cetek').datebox('getValue');
        var cetak 	= $('#cetak').datebox('getValue');
		var url	  	= "<?php echo site_url(); ?>/laporan_bhp/lap_keluarbhp";
        if(cskpd==''){
            alert('Belum Pilih SKPD');
        }else if(ctgl==''){
            alert('Belum pilih Tanggal Periode Awal')
        }else if(ctgl2==''){
            alert('Belum pilih Tanggal Periode Akhir')
        }else if(cetak==''){
            alert('Belum pilih Tanggal Cetak')
        }else{
        lc = '?kd_skpd='+cskpd+'&nm_skpd='+cnmskpd+'&unit='+cunit+'&nm_unit='+cnmunit+'&tahu='+ctahu+'&bend='+cbend+'&tgl2='+ctgl2+'&giat='+cgiat+'&nmgiat='+cnmgiat+'&tgl='+ctgl+'&cetak='+cetak+'&fa='+cpilih;
        window.open(url+lc,'_blank');
        window.focus();
        }
    } 
    
    function keluar(){
        
    }
    
    $(function(){
		$("#div_skpd").hide();
		$('#skpd').combogrid({  
           panelWidth:500,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd2',  
           columns:[[  
               {field:'kd_skpd',title:'KODE UNIT BIDANG',width:100},  
               {field:'nm_skpd',title:'NAMA UNIT BIDANG',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpdx 	= rowData.kd_lokasi; 
               lcskpd = rowData.kd_skpd;
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',queryParams:({skpd:lcskpd}) });
               $('#kdubidskpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',queryParams:({skpd:lcskpd}) });
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa',queryParams:({kduskpd:lcskpd}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pn',queryParams:({kduskpd:lcskpd}) });
           }  
         });
		$('#kdubidskpd').combogrid({
           panelWidth:500,  
           idField:'kd_uskpd',  
           textField:'kd_uskpd',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',  
           columns:[[  
               {field:'kd_uskpd',title:'KODE UNIT BIDANG',width:100},  
               {field:'nm_uskpd',title:'NAMA UNIT BIDANG',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpdx = rowData.kd_uskpd;
              // $('#kdubidskpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uskpd',queryParams:({kduskpd:lcskpd}) });
               $("#nmbidskpd").attr("value",rowData.nm_uskpd.toUpperCase());
			   if(ctk=='2'){
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',queryParams:({uskpd:lcskpdx,skpd:lcskpd}) });
			   }
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa2',queryParams:({kduskpd:lcskpdx}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pb2',queryParams:({kduskpd:lcskpdx}) });
           }  
         });
         $('#tgl_cetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        });  
		$('#tgl_cetek').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        });
		
		$('#cetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        });
		
		$("#cetak").datebox("setValue",'<?php echo date("Y-m-d"); ?>');
        $('#tahu').combogrid({  
           panelWidth:300,  
           idField:'nama',  
           textField:'nama',  
           mode:'remote',
         //  url:'<?php echo base_url(); ?>index.php/master/ambil_pa',
           queryParams:({kduskpd:''}),
           loadMsg:"Tunggu Sebentar....!!",  
           columns:[[  
               {field:'nama',title:'Nama Pengguna Anggaran',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcnip = rowData.nip;
               $("#nip_tahu").attr("value",lcnip);                              
           } 
         });
         
         $('#bend').combogrid({  
           panelWidth:300,  
           idField:'nama',  
           textField:'nama',  
           mode:'remote',
         //  url:'<?php echo base_url(); ?>index.php/master/ambil_bb',
           queryParams:({kduskpd:''}),
           loadMsg:"Tunggu Sebentar....!!",  
           columns:[[  
               {field:'nama',title:'Nama Penyimpan',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcnip_bend = rowData.nip;
               $("#nip_bend").attr("value",lcnip_bend);                              
           } 
         });	
		 
	$('#giat').combogrid({
            panelWidth:600, 
            width:150, 
            idField:'kode',  
            textField:'kode',              
            mode:'remote',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kode',title:'Kode Barang',width:100},  
               {field:'nama',title:'Nama Barang',width:500}    
            ]],  
            onSelect:function(rowIndex,rowData){                                                         
                ckd = rowData.kode;                                                           
                cnm = rowData.nama;    
                $('#nm_giat').attr('value',cnm);
            } 
        });
    }); 
  
  
   </script>



<div id="content1"> 
    <h3 align="center"><b>CETAK BARANG HABIS PAKAI KELUAR</b></h3>
    <fieldset>
     <table align="center" style="width:100%;" border="0">
		   <tr>
		       <td>
				<input type="radio" name="cetak" value="1" onclick="opt(this.value)" checked />SKPD &ensp;
				<input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />UNIT&ensp;
				</td>
		   </tr>
            <tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="skpd" name="skpd" style="width: 100px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 500px;border:0" />
                </td>
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_skpd">
                        <table style="width:100%;" border="0">
                            <td width="20%">UNIT</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="kdubidskpd" name="kdubidskpd" style="width: 100px;" />
                            <input type="text" id="nmbidskpd" readonly="true" style="width: 500px;border:0" />
                            </td>
                        </table>
                </div>
                </td>
            </tr>
            <tr>
                <td width="20%">MENGETAHUI</td>
                <td width="1%">:</td>
                <td><input id="tahu" name="tahu" style="width: 300px;" />
                <input type="hidden" id="nip_tahu"/> 
                </td> 
            </tr>
            <tr>
                <td width="20%">PENYIMPAN</td>
                <td width="1%">:</td>
                <td><input id="bend" name="bend" style="width: 300px;" />
                <input type="hidden" id="nip_bend"/> 
                </td> 
            </tr>
			<tr>                
				<td>KEGIATAN</td>
                <td>:</td>
                <td align="left"><input id="giat" name="giat" style="width: 50px;" /><font color="red">
                <input type="text" id="nm_giat" readonly="true" style="width: 200px;border:0" /><font><i>*isi jika ingin lap per kegiatan</i></font></td>  
			</tr>
            <tr>
                <td width="20%">PERIODE</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_cetak" style="width: 140px;" />S/D<input type="text" id="tgl_cetek" style="width: 140px;" /></td>  
            </tr>
            <tr>
                <td width="20%">TANGGAL CETAK</td>
                <td width="1%">:</td>
                <td><input type="text" id="cetak" style="width: 140px;" /></td>  
            </tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center">
				<a  class="easyui-linkbutton" iconCls="icon-note_book" plain="true"  onclick="javascript:openWindow(1);">Cetak Pdf 1</a>
                <a  class="easyui-linkbutton" iconCls="icon-pdf" plain="true"  onclick="javascript:openWindow(2);">Cetak Pdf 2</a>
                <a  class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:openWindow(3);">Cetak Excel</a>
				<a href="<?php echo base_url();?>" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Keluar</a>
                </td>                
            </tr>
        </table>  
            
    </fieldset>  
</div>



