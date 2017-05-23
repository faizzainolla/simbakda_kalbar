<script type="text/javascript" src="<?php echo base_url(); ?>easyui/autoCurrency.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/currencyFields.js"></script>
<script src="<?php echo base_url(); ?>lib/sweet-alert.min.js"></script>
<link   rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>lib/sweet-alert.css">
<script type="text/javascript">
    
    var updt = 'f';
    var idx2 = '';
    var total2 = 0;
     $(document).ready(function() {
          $("#tabs").tabs();
          $("#dialog-modal").dialog({
            height: 680,
            width: 1000,
            modal: true, 
            background:'#2da305',           
            autoOpen:false                
          });                       
     });    
     
    $(function(){         
         $('#trh').edatagrid({
    		url: "<?php echo base_url(); ?>index.php/transaksi/trh_pelihara_barang",
            idField:"no_dokumen",            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
        	    {field:'no_dokumen',title:'Nomor Dokumen',width:40},
                {field:'tgl_dokumen',title:'Tanggal',width:20},
                {field:'nm_uskpd',title:'Unit SKPD',width:100},
                {field:'tahun',title:'Tahun',width:20,align:"center"},
				{field:'hapus',width:5,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
            ]],
            onSelect:function(rowIndex,rowData){ 
                nomor   = rowData.no_dokumen;
                no_reg  = rowData.no_reg;
                tgl     = rowData.tgl_dokumen;
                unit    = rowData.kd_unit;
                kode    = rowData.kd_uskpd;
                nmkode  = rowData.nm_uskpd;
                tahun   = rowData.tahun;
                total   = rowData.total;
                get(nomor,no_reg,tgl,unit,kode,nmkode,tahun,total);
               
            }
        });
               
         $('#trd2').edatagrid({    		
            idField:"no_dokumen",            
            rownumbers:"true",            
            singleSelect:"true",
            autoRowHeight:"false",             
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){
                idx2 = rowIndex;
                updt = 't';  
				kd_brg 			= rowData.kd_brg;
				kd_uskpd 		= rowData.kd_uskpd;
				nm_brg 			= rowData.nm_brg;
				merek 			= rowData.merek;
				jumlah 			= rowData.jumlah;
				kd_rek 			= rowData.kd_rek;
				harga 			= rowData.harga;
				biaya_pelihara 	= rowData.biaya_pelihara;
				uraian_pelihara = rowData.uraian_pelihara;
				total 			= rowData.total;
				ket 			= rowData.ket
                get2(kd_brg,kd_uskpd,nm_brg,merek,jumlah,kd_rek,harga,biaya_pelihara,uraian_pelihara,total,ket); 
            }          
        });
		
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
          	var y = date.getFullYear();
           	var m = date.getMonth()+1;
           	var d = date.getDate();    
           	return y+'-'+m+'-'+d;
            }
         });
     
         $('#uskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',                      
            url:'<?php echo base_url(); ?>index.php/master/ambil_msskpd2',  
            columns:[[  
               {field:'kd_skpd',title:'Kode Unit',width:100},  
               {field:'nm_skpd',title:'Nama Unit',width:700}    
            ]],  
            onSelect:function(rowIndex,rowData){
               cuskpd = rowData.kd_skpd;            
               ckd_lokasi = rowData.kd_lokasi;    
               $('#nmuskpd').attr('value',rowData.nm_skpd);    
               $('#mlokasi').attr('value',rowData.ckd_lokasi);                               
            } 
         });  

        $('#tahun').combobox({           
            valueField:'tahun',  
            textField:'tahun',
            url:'<?php echo base_url(); ?>index.php/master/tahun'
        });
        
        /*$('#jenis').combogrid({  
            panelWidth:400,  
            width:160,
            idField:'golongan',  
            textField:'golongan',  
            mode:'remote',                                  
            url:'<?php echo base_url(); ?>index.php/master/ambil_gol',  
            columns:[[  
               {field:'golongan',title:'Kode Golongan',width:100},  
               {field:'nm_golongan',title:'Nama Golongan',width:300}    
            ]],  
            onSelect:function(rowIndex,rowData){  
                cgol = rowData.golongan;
				coba();
			} 
        }); 
		   
		   function coba(){
			//var cthnn    = $('#tahun').combobox('getValue');
			if (cgol == '01'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kiba'});
            } if (cgol == '02'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kibb'});
			} if (cgol == '03'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kibc'});
			} if (cgol == '04'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kibd'});
			} if (cgol == '05'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kibe'});
			} if (cgol == '06'){
                $('#kib').combogrid({url:'<?php echo base_url(); ?>index.php/master/load_kibf'});
			} 
		}*/
		
	$('#kib').combogrid({  
            panelWidth:900,  
            panelHeight:400, 
            width:160, 
            idField:'no_dokumen',  
            textField:'no_dokumen',              
            mode:'remote',            
			url:'<?php echo base_url(); ?>index.php/transaksi/ambil_trd_treatbrg',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'no_dokumen',title:'No Dokumen',width:120},
               {field:'kd_brg',title:'Kode Aset',width:100},  
               {field:'nm_brg',title:'Nama Aset',width:200},
               {field:'harga',title:'Harga',width:80,align:"right"},   
               {field:'uraian_pelihara',title:'Uraian',width:150},  
               {field:'biaya_pelihara',title:'Biaya Pelihara',width:85,align:"right"},
               {field:'ket',title:'Keterangan',width:150}     
            ]],  
             onSelect:function(rowIndex,rowData){
				cbidang=rowData.no_dokumen;
                $('#nm_kib').attr('value',rowData.nm_brg);
                $('#nilai').attr('value',rowData.harga); 
                $('#kdkib').attr('value',rowData.kd_brg);
                $('#kd_rek').attr('value',rowData.kd_rek);
                $('#uraian').attr('value',rowData.uraian_pelihara);
                $('#hrg').attr('value',number_format(rowData.biaya_pelihara));
                $('#ket').attr('value',rowData.ket);
    		}  
			/* kd_rek
			uraian
			hrg
			ket */
    });
		
    });
    
    function section1(){        
        $('#tabs1').click();  
        $('#trh').edatagrid('reload'); 
        set_grid();                                                     
    }
    function section2(){            
        $('#tabs2').click();
        load_detail();
        set_grid();                                                        
    }
	
   function get(nomor,no_reg,tgl,unit,kode,nmkode,tahun,total){
        $('#nomor').attr('value',nomor);
        $('#tanggal').datebox('setValue',tgl);
        $('#uskpd').combogrid('setValue',kode);
        $('#nmuskpd').attr('value',nmkode);
        $('#mlokasi').attr('value',unit);
       // $('#tahun').combobox('setValue',tahun);
        $('#total').attr('value',number_format(total,2,'.',','));
        $('#nomor').attr('disabled',true);
    }
    function kosong(){
		var skpd = '<?php echo ($this->session->userdata('skpd'));?>';
		var unit = '<?php echo ($this->session->userdata('unit_skpd'));?>';
        cdate = '<?php echo date("Y-m-d"); ?>';
        $('#nomor').attr('value','');
        $('#tanggal').datebox('setValue',cdate);
        $('#uskpd').combogrid('setValue',skpd);
        $('#mlokasi').attr('value',unit);
        $('#nomor').attr('disabled',false);
        $('#total').attr('value','0');
		max_rinci();
    }
    function kosong2(){ 
        updt = 'f';
        cthn = '<?php echo date("Y"); ?>'; 
        $('#tahun').combobox('setValue',cthn);
        $('#jenis').combogrid('setValue','');
		$('#kib').combogrid('setValue','');
        //$('#kd').combogrid('setValue','');
        $('#nilai').attr('value','');
        $('#kdkib').attr('value','');
        $('#nm_kib').attr('value','');
        $('#kd_rek').attr('value','');
        $('#uraian').attr('value','');
        $('#hrg').attr('value','');
        $('#ket').attr('value',''); 
        //$('#merek').attr('value','');
        //$('#jml').attr('value','');
        $('#tot').attr('value','');
        $('#total2').attr('value','0');       
    }
	
    function load_detail(){
        var i = 0;
        var nomor = document.getElementById('nomor').value;
        var tgl   = $('#tanggal').datebox('getValue');
        var kode  = $('#uskpd').combogrid('getValue');  
         $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/transaksi/trd_trpelihara',
                data: ({no:nomor}),
                dataType:"json",
				success:function(data){                                          
                    $.each(data,function(i,n){                                    
                        no      = n['no_dokumen'];                                                                                        
                        kd      = n['kd_brg'];                                                                                        
                        kds     = n['kd_uskpd'];
                        nm      = n['nm_brg'];
                        mrk     = n['merek'];                        
                        jml     = n['jumlah'];                         
                        kd_rek  = n['kd_rek'];                       
                        hrg     = n['harga'];
                        biaya_pelihara     = number_format(n['biaya_pelihara'],2,'.',',');                      
                        uraian_pelihara    = n['uraian_pelihara'];
                        tot     = number_format(n['total'],2,'.',',');
                        ket     = n['ket'];                                
                        $('#trd').edatagrid('appendRow',{no_dokumen:no,kd_brg:kd,kd_uskpd:kds,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:biaya_pelihara,uraian_pelihara:uraian_pelihara,total:tot,ket:ket});                                
                    });
                }
         });         
          set_grid();
    }
    function load_detail2(){                     
       $('#trd').datagrid('selectAll');
       var rows = $('#trd').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
		for(var p=0;p<rows.length;p++){
            no 		= rows[p].no_dokumen;                    
            kd 		= rows[p].kd_brg;                   
            kds 	= rows[p].kd_uskpd;
            nm 		= rows[p].nm_brg;
            mrk 	= rows[p].merek;
            jml 	= rows[p].jumlah;
            kd_rek 	= rows[p].kd_rek;
            hrg 	= rows[p].harga;
            biaya_pelihara 	= rows[p].biaya_pelihara;
            uraian_pelihara	= rows[p].uraian_pelihara;
            tot 	= rows[p].total;
            ket 	= rows[p].ket;                                                                                                             
            $('#trd2').edatagrid('appendRow',{no_dokumen:no,kd_brg:kd,kd_uskpd:kds,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:biaya_pelihara,uraian_pelihara:uraian_pelihara,total:tot,ket:ket});            
        }
        tot = document.getElementById('total').value;        
        $('#total2').attr('value',tot);
        $('#trd').edatagrid('unselectAll');    
    }
	
 function set_grid(){
         $('#trd').edatagrid({                                                                   
              columns:[[
            	    {field:'no_dokumen',title:'Nomor Dokumen',width:100,hidden:true},
                    {field:'kd_brg',title:'Kode Barang',width:100},
                    {field:'kd_uskpd',title:'Kode Skpd',width:80},
                    {field:'nm_brg',title:'Nama Barang',width:220},
                    {field:'harga',title:'Harga',width:115,align:"right"},
                    {field:'kd_rek',title:'Kode Rekening',width:100,align:"right"},
                    {field:'biaya_pelihara',title:'Pemeliharaan',width:115,align:"right"}, 
                    {field:'total',title:'Total',width:150,align:"right",hidden:true},                           
                    {field:'ket',title:'Keterangan',width:200}
                ]]
        });          
    }
    function set_grid2(){
         $('#trd2').edatagrid({                                                                   
              columns:[[
                    {field:'hapus',title:'Hapus',width:40,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();'' />";}},
					{field:'no_dokumen',title:'Nomor Dokumen',width:100,hidden:true},
                    {field:'kd_brg',title:'Kode Barang',width:80},
                    {field:'kd_uskpd',title:'Kode Skpd',width:80},
                    {field:'nm_brg',title:'Nama Barang',width:200},
                    {field:'harga',title:'Harga',width:100,align:"right"},
                    {field:'kd_rek',title:'Kode Rekening',width:100,align:"right"},
                    {field:'biaya_pelihara',title:'Pemeliharaan',width:100,align:"right"}, 
                    {field:'total',title:'Total',width:150,align:"right",hidden:true},                           
                    {field:'ket',title:'Keterangan',width:200}
                ]]
        });          
    }
	
    function tambah_detail(){
        var no = document.getElementById('nomor').value;
        var tgl = $('#tanggal').datebox('getValue');
        var kd  = $('#uskpd').combogrid('getValue');
        //var thn = $('#tahun').combobox('getValue');
        $('#trd2').edatagrid('reload');
        if (no!='' && tgl!='' && kd!=''){
            $("#dialog-modal").dialog('open');  
            kosong2();   
            set_grid2();
            load_detail2();        
                  
        } else {
            alert('Nomor/Tanggal/Unit Kerja/Tahun masih kosong, harap isi terlebih dahulu');
        }        
    }
    
    function hitung(){
        var a = angka(document.getElementById('jml').value);        
        var b = angka(document.getElementById('hrg').value);        
        var tot = a*b;        
            tot = number_format(tot,2,'.',',');
            $('#tot').attr('value',tot);
    }
   
    function append_save(){
		var no      = document.getElementById('nomor').value;        
        //var jns    	= $('#jenis').combogrid('getValue');  
        var kd      = document.getElementById('kdkib').value; 
		var skp	   	= $('#uskpd').combogrid('getValue');
        var mlokasi = document.getElementById('mlokasi').value;
        var nm      = document.getElementById('nm_kib').value;
        var mrk     = document.getElementById('nilai').value;
        var jml     = 1;
        var kd_rek  = document.getElementById('kd_rek').value;
        var nilai   = angka(document.getElementById('nilai').value);
        var hrg     = angka(document.getElementById('hrg').value); 
        var uraian  = document.getElementById('uraian').value;
       // var tot 	= angka(document.getElementById('tot').value);
		var tot 	= jml*hrg;
        var ket     = document.getElementById('ket').value;
		
        csql = " values('"+no+"','"+kd+"','"+mlokasi+"','"+skp+"','"+nm+"','"+nilai+"','"+jml+"','"+kd_rek+"','"+hrg+"','"+uraian+"','"+tot+"','"+ket+"')";
				$.ajax({
                    type: 'POST',
                    data: ({sql:csql,nodok:no,lokasi:mlokasi}),
                     url:"<?php echo base_url(); ?>index.php/transaksi/save_peliharabrg",
        			success:function(data){
                     var lctot = data;
                     $('#total').attr('value',lctot);
                     $('#total2').attr('value',lctot);
        			}
                  });
				  
        if (kd != '' && kd_rek != '' && uraian != '' && hrg != ''){  
            if (updt == 'f') {
                //for(var lpb=0; lpb<jml; lpb++){
				/* no_dokumen:no,kd_brg:kd,kd_uskpd:kds,nm_brg:nm,merek:mrk,
						jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:biaya_pelihara,uraian_pelihara:uraian_pelihara,
						total:tot,ket:ket */
$('#trd').edatagrid('appendRow',{no_dokumen:no,kd_brg:kd,kd_uskpd:skp,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:hrg,uraian_pelihara:uraian,total:tot,ket:ket});
$('#trd2').edatagrid('appendRow',{no_dokumen:no,kd_brg:kd,kd_uskpd:skp,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:hrg,uraian_pelihara:uraian,total:tot,ket:ket});
               //  }
                  a = total + angka(tot); 
                               
            } else {
                $('#trd').edatagrid('updateRow',{index:idx2,row:{no_dokumen:no,kd_brg:kd,kd_uskpd:skp,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:hrg,uraian_pelihara:uraian,total:tot,ket:ket}});
                $('#trd2').edatagrid('updateRow',{index:idx2,row:{no_dokumen:no,kd_brg:kd,kd_uskpd:skp,nm_brg:nm,merek:mrk,jumlah:jml,kd_rek:kd_rek,harga:hrg,biaya_pelihara:hrg,uraian_pelihara:uraian,total:tot,ket:ket}});                                
                s = total - angka(total2);
                a = s + angka(tot);
            }
            updt = 'f';
            total = number_format(a,2,'.',',');
            $('#total').attr('value',total);
            $('#total2').attr('value',total);                                    
            kosong2();
        }else {
                alert('Apakah semua inputan sudah lengkap??. Mohon diperiksa kembali KIB, PILIHAN ASET, KODE REKENING, URAIAN, dan BIAYA PEMELIHARAAN.!! ');
                exit();
			}
		}
    
    function keluar(){
		 swal({
					title: "Jangan lupa disimpan.!!",
					type:"warning"
					});
        $("#dialog-modal").dialog('close');
        $('#trd2').edatagrid('reload');                              
    }   
    
    function get2(kd_brg,kd_uskpd,nm_brg,merek,jumlah,kd_rek,harga,biaya_pelihara,uraian_pelihara,total,ket){
        //$('#jenis').combogrid('setValue',jns);
		//$('#bida').combogrid('setValue',bdg);
		//$('#kelo').combogrid('setValue',klpk);
		//$('#kelo1').combogrid('setValue',klpk1);
        //$('#kdkib').combogrid('setValue',kdkib);
        //$('#nm_kib').attr('value',nm_kib);                                  
        $('#merek').attr('value',merek);
        $('#jml').attr('value',jumlah);
        $('#kd_rek').attr('value',kd_rek);
        $('#hrg').attr('value',biaya_pelihara);
        $('#uraian').attr('value',uraian_pelihara);
        $('#tot').attr('value',total);
        $('#ket').attr('value',ket);
        total2 = total;
		}
     function simpan(){
        var cno     = document.getElementById('nomor').value;
        var ctgl    = $('#tanggal').datebox('getValue');
        var cuskpd  = $('#uskpd').combogrid('getValue');
        var cmlokasi = document.getElementById('mlokasi').value; 
        var cnmuskpd = document.getElementById('nmuskpd').value;
		var cthn    = '<?php echo ($this->session->userdata('ta_simbakda')); ?>';
        var ctotal  = angka(document.getElementById('total').value);  
        if (cno==''){
		sweetAlert("MAAF..!!", "Nomor Dokumen mohon diisi", "error");
            exit();
        } 
        if (ctgl==''){
		sweetAlert("MAAF..!!", "Nomor Tanggal Dokumen mohon diisi", "error");
            exit();
        }
        if (cuskpd==''){
            alert('Kode Unit Tidak Boleh Kosong');
            exit();
        }                 
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trh_trpelihara',no:cno,tgl:ctgl,lokasi:cmlokasi,uskpd:cuskpd,nmuskpd:cnmuskpd,tahun:cthn,total:ctotal}),
                url: '<?php echo base_url(); ?>index.php/transaksi/simpan_peliharabrg',
                success:function(data){
                   status = data.pesan;                    
                   if (status == '0'){
                       alert('Gagal Simpan...!!');
                       exit();
                   } else {                                 
				 swal({
					title: "Berhasil",
					text: "Data telah disimpan.!!",
					imageUrl:"<?php echo base_url();?>/lib/images/bantaeng.png"
					});                               
                    }                                                                                                         
                }
				});
				});                                 
			}
    
    function hapus(){
        var cnomor = document.getElementById('nomor').value;
		var cmlokasi = document.getElementById('mlokasi').value;
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Dokumen : '+cnomor);        
        if (tny==true){
        var urll = '<?php echo base_url(); ?>index.php/transaksi/hapus_peliharabrg';
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor,skpd:cmlokasi}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus');         
                        } else {
                            alert('Gagal Hapus');
                        }        
                 }
                 
                });           
        });
        }
        $('#trh').edatagrid('reload');  
     
    }
   
   function hapus_detail(){
    
        var cnomor = document.getElementById('nomor').value; 
        var rows = $('#trd2').edatagrid('getSelected');
		var cmlokasi = document.getElementById('mlokasi').value;
        ckd =   rows.kd_brg;
        cnm =   rows.nm_brg;
        ctot =   rows.total;  
		
        var idx = $('#trd2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Kode Barang : '+ckd+' Nama Barang : '+cnm+' Nilai : '+ctot);
        if (tny==true){
            $('#trd2').edatagrid('deleteRow',idx);
            $('#trd').edatagrid('deleteRow',idx);            
            total = angka(document.getElementById('total2').value) - angka(ctot);
            
            $.ajax({
                type: 'POST',
                data: ({nomor:cnomor,kd:ckd,ctotal:total,kd_unit:cmlokasi}),
                url:"<?php echo base_url(); ?>index.php/transaksi/hps_trd_peliharabrg"
            });
            
            $('#total2').attr('value',number_format(total,2,'.',','));
            $('#total').attr('value',number_format(total,2,'.',','));                                
            kosong2();
        }                     
    }
	
	function max_rinci(){  
	var organisasi = $('#uskpd').combogrid('getValue');
		$.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/transaksi/load_idmax',
            data: ({skpd:organisasi,table:'trh_trpelihara',kolom:'no_dokumen',kolom_skpd:'kd_uskpd'}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    no		      = n['kode'];
					no_urut		  = tambah_urut(no,4); 
					$("#nomor").attr("value",no_urut); 
                });
            }
        }); 
	 }
	 
	  function tambah_urut(angka,panjang){
        no=((angka)*1);
        a=no.toString();
        jnol=panjang-a.length;
        nol='';
        for(i=1;i<=jnol;i++){
        nol=nol+'0';
        }
        b= nol+a;
        return b;
    }
   </script>


<div id="tabs" >
   <p><h3 align="center">PEMELIHARAAN BARANG</h3></p>
	<ul style="background-color:#2da305;">
        <li><a href="#tabs-1" style="width: 452px;" id="tabs1">List View</a></li>
        <li><a href="#tabs-2" style="width: 452px;" id="tabs2">Form Input</a></li>        
    </ul>
    <div id="tabs-1">
        <div>
            <p align="right">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();section2();">Tambah</a> 
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();">Detail Barang</a>               
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" >Cari</a>
                <input type="text" value="" id="txtcari"/>              
                <table  id="trh" title="List Dokumen" style="width:940px;height:400px;" >  
                </table>                
            </p>
        </div>
    </div>
    <div id="tabs-2">  
        <br /><br />
        <table>
            <tr>
                <td width="100px">No. Dokumen</td>
                <td>:</td>
                <td><input type="text" id="nomor" style="width: 200px;" onclick="javascript:select();" /></td>
                <td width="70px"></td>
                <td width="150px">Tanggal Dokumen</td>
                <td>:</td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>
            <tr>
                <td height="30px">Unit Kerja</td>
                <td>:</td>
                <td><input id="uskpd" name="uskpd" style="width: 140px;" /></td>
                <td></td>
                <td>Nama Unit Kerja</td> 
                <td>:</td>
                <td><input type="text" id="nmuskpd" style="border:0;width: 400px;" readonly="true"/>
					<input hidden="true" type="text" id="mlokasi" style="border:0;width: 400px;" readonly="true"/>				
				</td>                                
            </tr>       
            <!--tr>
                <td>Tahun Aset</td>
                <td>:</td>
                <td><input id="tahun" name="tahun" style="width: 140px;" value=""/>  </td>            
            </tr-->                            
        </table>  
        <fieldset >
        <div id="toolbar" align="center" >
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_detail();"><b>Tambah/Edit Barang</b></a>   		                            		
        </div>
        </fieldset>
        <!--br />
        <div align="center">
        	<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a>
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>   		  
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>          
        </div-->
        <br /> 
        <table  id="trd" title="Detail Barang" style="width:940px;height:300px;" >  
        </table>       
        <div align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>        
        <br />
        <div align="center">
		<fieldset>
		<INPUT TYPE="button" VALUE="SIMPAN" style="height:40px;width:100px" onclick="javascript:simpan();" >
		<INPUT TYPE="button" VALUE="KEMBALI" style="height:40px;width:100px" onclick="javascript:section1();" >
        </fieldset>
		</div> 
    </div>  
</div>

<div id="dialog-modal" title="Pilihan Kib" >
    <p class="validateTips" >Pilih Barang yang Ingin Dipelihara</p> 
	<fieldset>
	<table>
		<!--tr><td>PILIH KIB</td><td colspan="4">:<input id="jenis" name="jenis" value=""/></td></tr-->
		<tr>
			<td>PILIH ASET</td>
			<td colspan="4">:<INPUT style="width: 140px;" ID="kib" NAME="kib" VALUE=""/></td>
		</tr>
		<tr>
			<td>NAMA ASET</td>
			<TD>:<INPUT style="border:0; width:300px;" ID="nm_kib" NAME="nm_kib" VALUE=""/></td></TD><td>
			<td hidden="true">HARGA ASET</td>
			<td hidden="true">:<INPUT disabled="true" ID="nilai" NAME="nilai" VALUE=""/> NO:<INPUT disabled="true" ID="kdkib" NAME="kdkib" VALUE=""/></td>
		</tr>
		<tr>
			<td>KODE REKENING</td>
			<TD>:<INPUT ID="kd_rek" NAME="kd_rek" VALUE=""/></TD>
			<td>URAIAN PEMELIHARAAN</td>
			<TD>:</TD>		
			<td><TEXTAREA ID="uraian" NAME="uraian" VALUE="" style="width: 155px;"></TEXTAREA></td>
		</tr>
		<TR>
			<TD>BIAYA PEMELIHARAAN</TD>
			<TD>:<INPUT ID="hrg" NAME="hrg" VALUE="" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event));" onkeyup="hitung();"/></TD>
			<TD align="right">KETERANGAN</TD>
			<TD>:</TD>
			<TD><TEXTAREA ID="ket" style="width: 155px; height: 60px;"></TEXTAREA></TD>
		</TR>
	</table>
	</fieldset>
    <fieldset>
        <table align="center">
            <tr>
                <td><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong2();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>                               
                </td>
            </tr>
        </table>   
    </fieldset>
    <fieldset>      
        <table border="1">      
            <tr> 
                <td rowspan="9" width="930"  >
                    <table  id="trd2" title="Detail Barang Rencana dipelihara" style="width:940px;height:270px;" >  
                    </table>           
                    <div align="right">Total : <input type="text" id="total2" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>     
                </td>         
            </tr>  
        </table>            
    </fieldset>  
</div>

