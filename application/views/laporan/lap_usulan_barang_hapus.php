    <script type="text/javascript">
    
    var kdwil= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
    
    function kosong(){
        $("#kdmilik").attr("value",'');
        $("#nmmilik").attr("value",''); 
    }
    
    function openWindow( url ){
        var cskpd = $('#kdskpd').combogrid('getValue');
        var no_uhapus = $('#no_uhapus').combogrid('getValue');
        var cnmskpd = document.getElementById('nmskpd').value;
        var ctahu = document.getElementById('nip_tahu').value;
        var cbend = document.getElementById('nip_bend').value;
        var ctgl  = $('#tgl_cetak').datebox('getValue');
        var cthn  = '<?php echo ($this->session->userdata('skpd')); ?>'; 
        if(cskpd==''){
            alert('Belum Pilih SKPD');
        }else if(no_uhapus == ''){
            alert('Belum Pilih Nomor Usulan Penghapusan.!')
        }else if(ctgl == ''){
            alert('Belum Pilih Tanggal')
        }else{
        lc = '?kd_skpd='+cskpd+'&nm_skpd='+cnmskpd+'&tahu='+ctahu+'&bend='+cbend+'&tgl='+ctgl+'&no='+no_uhapus+'&tahun='+cthn;
        window.open(url+lc,'_blank');
        window.focus();
        }
    } 
    
    function keluar(){
        
    }
    
    $(function(){
        $('#kdskpd').combogrid({  
           panelWidth:500,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_msskpd2',  
           columns:[[  
               {field:'kd_skpd',title:'KODE SKPD',width:100},  
               {field:'nm_skpd',title:'NAMA SKPD',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpd = rowData.kd_skpd;
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa',queryParams:({kduskpd:lcskpd}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pb',queryParams:({kduskpd:lcskpd}) });
               $('#no_uhapus').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/ambil_no_uhapus',queryParams:({kduskpd:lcskpd}) });
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
        
	    $('#no_uhapus').combogrid({  
           panelWidth:300,  
           idField:'no',  
           textField:'no',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/transaksi/ambil_no_uhapus',
           queryParams:({kduskpd:''}),
           loadMsg:"Tunggu Sebentar....!!",  
           columns:[[  
               {field:'no',title:'Nomor Usulan',width:200},
               {field:'tgl',title:'Tanggal Usulan',width:100}    
           ]] 
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
			   lcnama = rowData.nama;
               $("#nip_tahu").attr("value",lcnip);
			   $("#nama_tahu").attr("value",lcnama);
           } 
         });
         
         $('#bend').combogrid({  
           panelWidth:300,  
           idField:'nama',  
           textField:'nama',  
           mode:'remote',
          // url:'<?php echo base_url(); ?>index.php/master/ambil_pb',
           queryParams:({kduskpd:''}),
           loadMsg:"Tunggu Sebentar....!!",  
           columns:[[  
               {field:'nama',title:'Nama Pengurus',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcnip_bend = rowData.nip;
			   lcnama_bend = rowData.nama;
               $("#nip_bend").attr("value",lcnip_bend);                              
			   $("#nama_bend").attr('value',lcnama_bend);
		   }		   
         });
         
         $('#tahun').combobox({           
            valueField:'tahun',  
            textField:'tahun',
            url:'<?php echo base_url(); ?>index.php/master/tahun',
            
        });
        
		$('#tgl_cetak').datebox('setValue','<?php echo date('y-m-d')?>');
    }); 
  
  
   </script>



<div id="content1"> 
    <h3 align="center"><b>CETAK USULAN BARANG YANG AKAN DIHAPUS</b></h3>
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="kdskpd" name="kdskpd" style="width: 100px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 500px;border:0" />
                </td>
            </tr>
            <tr>
                <td>NO. USULAN</td>
                <td>:</td>
                <td><input id="no_uhapus" name="no_uhapus" style="width: 140px;" />
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
                <td width="20%">PENGURUS</td>
                <td width="1%">:</td>
                <td><input id="bend" name="bend" style="width: 300px;" />
                <input type="hidden" id="nip_bend"/> 
                </td> 
            </tr>
            <tr>
                <td width="20%">TANGGAL CETAK</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_cetak" style="width: 140px;" /></td>  
            </tr>
            
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center">
                <a  href="<?php echo base_url(); ?>index.php/laporan/lap_usulan_barang_hapus" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false">Cetak</a>
		        <a href="<?php echo base_url();?>" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Keluar</a>
                </td>                
            </tr>
        </table>  
            
    </fieldset>  
</div>



