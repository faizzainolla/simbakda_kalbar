    <script type="text/javascript">
    
    var kdwil= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
                    
    
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
		var cpilih		= $cek; 
        var cskpd 		= $('#skpd').combogrid('getValue');
        var cunit 		= $('#kdubidskpd').combogrid('getValue');
        var cnmunit 	= document.getElementById('nmbidskpd').value;
        var cnmskpd 	= document.getElementById('nmskpd').value;
        var cgiat 		= $('#giat').combogrid('getValue');
        var cnmgiat 	= document.getElementById('nm_giat').value;
        var ctahu 		= document.getElementById('nip_tahu').value;
        var cbend 		= document.getElementById('nip_bend').value;
        var tgl_satu  	= $('#tgl_satu').datebox('getValue');
        var tgl_dua 	= $('#tgl_dua').datebox('getValue');
        var ctgl  		= $('#tgl_cetak').datebox('getValue');
		var url	  		= "<?php echo site_url(); ?>/laporan_bhp/lap_kartubhp";
		var cbidang 	= '';
		var cnmbid 		= '';
        if(cskpd==''){
            alert('Belum Pilih SKPD');
        }else if(ctgl==''){
            alert('Belum pilih Tanggal Cetak')
        }else{
                lc = '?kd_skpd='+cskpd+'&kd_bid='+cbidang+'&nm_skpd='+cnmskpd+'&unit='+cunit+'&nm_unit='+cnmunit+'&nm_bid='+cnmbid+'&tahu='+ctahu+'&bend='+cbend+'&tgl='+ctgl+'&giat='+cgiat+'&nmgiat='+cnmgiat+'&tgl_satu='+tgl_satu+'&tgl_dua='+tgl_dua+'&fa='+cpilih;
                window.open(url+lc,'_blank');
                window.focus();
            }

    } 
    
    $(function(){
        $("#div_skpd").hide();
        $("#div_bidang").hide();
   	});
    
    $(function(){
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
               lcskpd = rowData.kd_skpd;
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#kdubidskpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',queryParams:({skpd:lcskpd}) });
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',queryParams:({skpd:lcskpd}) });
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
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',queryParams:({uskpd:lcskpdx,skpd:lcskpd}) });
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa2',queryParams:({kduskpd:lcskpdx}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pb2',queryParams:({kduskpd:lcskpdx}) });
           }  
         });
         $('#kdskpd').combogrid({  
           panelWidth:500,  
           idField:'kd_uskpd',  
           textField:'kd_uskpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',  
           columns:[[  
               {field:'kd_uskpd',title:'KODE SKPD',width:100},  
               {field:'nm_uskpd',title:'NAMA SKPD',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpd = rowData.kd_uskpd;
               
               $("#nmskpd").attr("value",rowData.nm_uskpd.toUpperCase());
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa',queryParams:({kduskpd:lcskpd }) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_bb',queryParams:({kduskpd:lcskpd }) });
                                
           }  
         });
         
         $('#tgl_cetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		$("#tgl_cetak").datebox("setValue",'<?php echo date("Y-m-d"); ?>');
         $('#tgl_satu').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        });  
		$('#tgl_dua').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        }); 
        
          $('#tahu').combogrid({  
           panelWidth:300,  
           idField:'nama',  
           textField:'nama',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/master/ambil_pa',
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
           //url:'<?php echo base_url(); ?>index.php/master/ambil_bb',
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
    <h3 align="center"><b>PERSEDIAAN BARANG HABIS PAKAI</b></h3>
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <!--tr>
                <td><input type="radio" name="cetak" value="1" onclick="opt(this.value)" />SKPD &ensp;</td>
                <!--td><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />Per BIDANG &ensp;</td-->
                <!--td><input type="radio" name="cetak" value="3" id="status1" onclick="opt(this.value)" checked="true" />Keseluruhan</td>
                
            </tr-->
		   <tr>
		       <td>
				<input type="radio" name="cetak" value="1" onclick="opt(this.value)" checked />SKPD &ensp;
				<input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />UNIT&ensp;
				</td>
		   </tr>
            <tr>
                <td colspan="3">
                <div>
                        <table style="width:100%;" border="0">
                    
                            <td width="20%">SKPD</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="skpd" name="skpd" style="width: 100px;" />
                            <input type="text" id="nmskpd" readonly="true" style="width: 500px;border:0" />
                            </td>
                        </table>
                </div>
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
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">KEPALA SKPD</td>
                            <td width="1%">:</td>
                            <td><input id="tahu" name="tahu" style="width: 300px;" />
                            <input type="hidden" id="nip_tahu"/> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">PENYIMPAN</td>
                            <td width="1%">:</td>
                            <td><input id="bend" name="bend" style="width: 300px;" />
                            <input type="hidden" id="nip_bend"/> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_keg">
                        <table style="width:100%;" border="0">
                            <td width="20%">KEGIATAN</td>
                            <td width="1%">:</td>
                            <td><input id="giat" name="giat" style="width: 300px;" /><font color="red">
								<input type="text" id="nm_giat" readonly="true" style="width: 200px;border:0" /><font><i>*isi jika ingin lap per kegiatan</i></font>
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td width="20%">PERIODE</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_satu" style="width: 140px;" />S/D<input type="text" id="tgl_dua" style="width: 140px;" /><font color="red"><i>*isi jika ingin lap per periode</i></font></td>  
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_tgl">
                    <table style="width:100%;" border="0">
                        <td width="20%">TANGGAL CETAK</td>
                        <td width="1%">:</td>
                        <td><input type="text" id="tgl_cetak" style="width: 140px;" /></td>  
                    </table>
                </div>
                </td> 
            </tr>
            <tr>
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



