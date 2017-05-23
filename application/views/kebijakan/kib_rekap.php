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

	
	function openWindow($cek){
		var cpilih		= $cek; 
        var cskpd 	= $('#kdubidskpd').combogrid('getValue');
        var cnmskpd = document.getElementById('nmskpd').value;
        var ctahu 	= document.getElementById('nip_tahu').value;
        //var cbend = document.getElementById('nip_bend').value; 
		var tahun_ini	= '<?php echo date('Y'); ?>'; 
		var oto  = '<?php echo ($this->session->userdata('otori'));?>';
        var ctgl_awal 	= $('#tgl_cetak').datebox('getValue');
        var ctgl_akhir 	= $('#tgl_cetak1').datebox('getValue');
        var ctgl 	= $('#tgl_cetak2').datebox('getValue');
        var ctahun 		= $('#tahun').combobox('getValue'); 
        var kib 	= $('#kib').combogrid('getValue');
        var cza 		= $('#msusut').combobox('getValue'); 
        var tgl_reg		= $('#tgl_reg').datebox('getValue');
		var pnilai		= $('#pilih_nilai').combobox('getValue');
		//var url	  		= "<?php echo site_url(); ?>/laporan_kebijakan/rekap_kib_penyusutan";
		if(cza=='1'){
		var url	  		= "<?php echo site_url(); ?>/laporan_kebijakan/rekap_kib_penyusutan";
		}else{
		if(tgl_reg==''){
            alert('Belum Pilih KIB PER TANGGAL');
			exit();
        }
		var url	  		= "<?php echo site_url(); ?>/laporan_kebijakan/rekap_kib_penyusutan_bln";
		}
		if(kib=='02'){
		var trkib	= 'trkib_b';	
		}else if(kib=='03'){
		var trkib	= 'trkib_c';	
		}else if(kib=='04'){
		var trkib	= 'trkib_d';	
		}else{
		var trkib	= 'trkib_e';	
		}
        var nmkib 	= document.getElementById('nmkib').value;
        var jenis 	= $('#jenis').combogrid('getValue');
        var nmjenis	= document.getElementById('nmjenis').value;
		if(oto!='01'){
        if(cskpd==''){
            alert('Belum Pilih SKPD');
        }else if(ctgl==''){
            alert('Belum pilih Tanggal Cetak')
        }else if(kib==''){
            alert('Belum pilih KIB Yang Akan Dicetak')
        }else if(pnilai == ''){
            alert('Belum Pilih Nilai');
        }else{
        lc = '?kd_skpd='+cskpd+'&tahun_ini='+tahun_ini+'&nm_skpd='+cnmskpd+'&tahu='+ctahu+'&kib='+kib+'&nmkib='+nmkib+'&jenis='+jenis+'&nmjenis='+nmjenis+'&tgl='+ctgl+'&tgl_akhir='+ctgl_akhir+'&ctahun='+ctahun+'&trkib='+trkib+'&tgl_reg='+tgl_reg+'&za='+cza+'&pnilai='+pnilai+'&fa='+cpilih;
        window.open(url+lc,'_blank');
        window.focus();
        }
		}else{
		if(ctgl==''){
            alert('Belum pilih Tanggal Cetak')
        }else if(kib==''){
            alert('Belum pilih KIB Yang Akan Dicetak')
        }else if(pnilai == ''){
            alert('Belum Pilih Nilai');
        }else{
        lc = '?kd_skpd='+cskpd+'&tahun_ini='+tahun_ini+'&nm_skpd='+cnmskpd+'&tahu='+ctahu+'&kib='+kib+'&nmkib='+nmkib+'&jenis='+jenis+'&nmjenis='+nmjenis+'&tgl='+ctgl+'&tgl_akhir='+ctgl_akhir+'&ctahun='+ctahun+'&trkib='+trkib+'&tgl_reg='+tgl_reg+'&za='+cza+'&pnilai='+pnilai+'&fa='+cpilih;
        window.open(url+lc,'_blank');
        window.focus();
        }
		}
    } 
    
    function keluar(){
        
    }
    
    $(function(){
    $('#kdubidskpd').combogrid({  
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
               lcskpd = rowData.kd_lokasi;
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa2',queryParams:({kduskpd:lcskpd}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pb2',queryParams:({kduskpd:lcskpd}) });
                                
           }  
         });
         
         $('#tgl_reg').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
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
        
		$('#tgl_cetak1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		$('#tgl_cetak2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
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
		 
		 $('#kib').combogrid({  
           panelWidth:500,  
           idField:'gol',  
           textField:'gol',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_mrekap_penyusutan',
           columns:[[  
               {field:'gol',title:'KODE',width:100},  
               {field:'nama',title:'GOLONGAN',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               jenis = rowData.nama;
               gol 	 = rowData.gol;
               $("#nmkib").attr("value",rowData.nama.toUpperCase()); 
			   $('#jenis').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_jenis_kib',queryParams:({kode:gol}) });
           }  
         });
        
		$('#jenis').combogrid({  
           panelWidth:500,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/master/ambil_jenis_kib',queryParams:({kode:'02'}), 
           columns:[[  
               {field:'kode',title:'KODE',width:100},  
               {field:'jenis',title:'JENIS',width:300}, 
               {field:'umur',title:'UMUR(th)',width:100,align:'center'}    
           ]],  
           onSelect:function(rowIndex,rowData){
               jenis = rowData.jenis;
               $("#nmjenis").attr("value",rowData.jenis.toUpperCase()); 
			   
           }  
         });   
        $('#tahun').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:50,
        data:[{kode:'0',nama:'2012'},{kode:'1',nama:'2013'},{kode:'2',nama:'2014'},{kode:'3',nama:'2015'},{kode:'4',nama:'2016'},
        {kode:'5',nama:'2017'}]
    });  
    
	$('#msusut').combobox({           
        valueField:'kode',  
        textField:'nama',
        width:100,
        data:[{kode:'1',nama:'Tahunan'},{kode:'2',nama:'Bulanan'}],
		onSelect:function(rowIndex,rowData){
        var kode 		= $('#msusut').combobox('getValue'); 
			if(kode=='2'){
            $("#div_per").show();
			}else{
            $("#div_per").hide();
			}
		}
    });
	$('#pilih_nilai').combobox({
		valueField:'kode',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'NILAI LAMA'},{kode:'2',nama:'NILAI BARU'}]
	 });
            $("#div_per").hide(); 
       		 
    }); 
   </script>



<div id="content1"> 
    <h3 align="center"><b>CETAK REKAP PENYUSUTAN ASET TETAP</b></h3>
    <fieldset>
     <table align="center" style="width:100%;" border="0">
	 
			<tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="kdubidskpd" name="kdubidskpd" style="width: 100px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 250px;border:0" /><font color="red"><i>*khusus admin bisa kosong u/ keseluruhan skpd</i><font> 
                </td>
            </tr>
            <tr>
                <td width="20%">KEPALA SKPD</td>
                <td width="1%">:</td>
                <td><input id="tahu" name="tahu" style="width: 300px;" />
                <input type="hidden" id="nip_tahu"/>
                </td> 
            </tr>
            <tr hidden="true">
                <td width="20%">PERODE</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_cetak" style="width: 140px;" /> S/D <input type="text" id="tgl_cetak1" style="width: 140px;" /></td>  
            </tr>
            <tr>
                <td width="20%">SAMPAI TAHUN</td>
                <td width="1%">:</td>
                <td><input type="text" id="tahun" style="width: 150px;" /></td>  
            </tr>
            <tr>
                <td width="20%">PILIH KIB</td>
                <td width="1%">:</td>
                <td><input type="text" id="kib" style="width: 140px;" />
				<input type="text" id="nmkib" style="width: 240px; border:0;" readonly="true" /></td>  
            </tr>
            <tr>
                <td width="20%">CETAK PER JENIS</td>
                <td width="1%">:</td>
                <td><input type="text" id="jenis" style="width: 140px;" />
				<input type="text" id="nmjenis" style="width: 240px; border:0;" readonly="true" /><font color="red"><i>*isi jika ingin lap per jenis</i><font>
				</td>  
            </tr>
            <tr>
                <td width="20%">METODE SUSUT</td>
                <td width="1%">:</td>
                <td><input type="text" id="msusut" style="width: 100;" /></td>  
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_nilai">
                    <table style="width:100%;" border="0">
                        <td width="20%">PILIH NILAI</td>
                        <td width="1%">:</td>
                        <td><input type="text" id="pilih_nilai" style="width: 150px;" /><font color="red"><i>*WAJIB DIISI</i><font></td>  
                    </table>
                </div>
                </td> 
            </tr> 
            <tr>
                <td colspan="3">
                <div id="div_per">
                        <table style="width:100%;" border="0">
                            <td width="20%">KIB PER TANGGAL</td>
                            <td width="1%">:</td>
                            <td><input id="tgl_reg" name="tgl_reg" style="width: 140px;" />
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td width="20%">TANGGAL CETAK</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_cetak2" style="width: 140px;" /></td>  
            </tr>
            <td colspan="3">&nbsp;</td>			
            <tr>
                <td colspan="3" align="center">
                <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(1);">PDF</a>
                <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:openWindow(2);">EXCEL</a>
			    <a href="<?php echo base_url();?>" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Keluar</a>
                </td>                
            </tr>
        </table>  
            
    </fieldset>  
</div>



