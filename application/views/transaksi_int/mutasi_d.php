<script src="<?php echo base_url(); ?>lib/sweet-alert.min.js"></script>
<link   rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>lib/sweet-alert.css">
<script type="text/javascript">
       
    var kdkel= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var lcskpd = '';
    var lpdok = '';
    var no_urut=0;
      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 800,
            width: 1000,
            modal: true,
            autoOpen:false,
        });
         $( "#dialog-modal_gambar" ).dialog({
            height: 520,
            width: 500,
            modal: true,
            autoOpen:false
        });
        });    
     
       $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-riwayat" ).dialog({
            height: 350,
            width: 1000,
            modal: true,
            autoOpen:false,
        });
        }); 
     $(function(){
      
      $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
                //ctgl = y+'-'+m+'-'+d;
            	return y+'-'+m+'-'+d;
            },
			onSelect:function(){
			var tgl_reg= $('#tanggal').datebox('getValue'); 
			var bts_tgl= '2014-12-31';
			if (Date.parse(tgl_reg) <= Date.parse(bts_tgl)){swal({title: "Error!",text: "MOHON TANGGAL REGISTER TIDAK MELEBIHI ATAU SAMA TANGGAL 31 Desember 2014.!!, Karena Akan mempengaruhi Neraca Aset Per 2014.!, Silahkan Catat Tanggal Berlanjut dgn tdk merubah Tahun dan tgl Perolehan",type: "error",confirmButtonText: "OK"});
				exit();
			}}
        });
		
		$('#tgl_oleh').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
                //ctgl = y+'-'+m+'-'+d;
            	return y+'-'+m+'-'+d;
            }
        });
        
      $('#tgl_sk').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
                //ctgl = y+'-'+m+'-'+d;
            	return y+'-'+m+'-'+d;
            }
        });
        		
		$('#tgl_riwayat').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		
			 	$('#bida').combogrid({  
            panelWidth:550, 
            idField:'bidang',  
            textField:'bidang',              
            mode:'remote',            
			url:'<?php echo base_url(); ?>index.php/master/load_bidang_mutasi_d',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               
               {field:'bidang',title:'Kode Bidang',width:100},  
               {field:'nm_bidang',title:'Nama Bidang',width:500}
            ]],  
             onSelect:function(rowIndex,rowData){
				kode_rekbrg=rowData.bidang;
                //nmbidang=rowData.nm_bidang;
				get_rekskpd(kode_rekbrg);
				//$('#nmbidang_u').attr('value',nmbidang);
                //$('#kdbarang_u').combogrid('disable');
                //$('#kdbarang_u').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_brg_dh',
                //queryParams:({bidang:bidang})});            
        }  
    });
	
		$('#kd_riwayat').combogrid({  
       panelWidth:220,  
       idField:'kode',  
       textField:'riwayat',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mriwayat',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'riwayat',title:'Riwayat',width:150}
       ]],
        onSelect:function(rowIndex,rowData){
           lckondisi = rowData.kode;
           $("#kon").attr("value",rowData.riwayat.toUpperCase());
       }  
     });
      $('#nosdok').combogrid({  
       panelWidth:830,  
       idField:'no_dokumen',  
       textField:'no_dokumen',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/ambil_dok_d',  
       columns:[[  
           {field:'invent',title:'STT',width:30},
		   {field:'no_dokumen',title:'NOMOR DOKUMEN',width:160},
		   {field:'kd_kegiatan',title:'KEGIATAN',width:140},
		   {field:'kd_rek5',title:'REKENING',width:60},
		   {field:'kd_brg',title:'KODE BARANG',width:90},
           {field:'nm_brg',title:'NAMA BARANG',width:200},  
           {field:'total2',title:'NILAI',width:120,align:"right"}     
       ]],  
       onSelect:function(rowIndex,rowData){
			no_dokumen	= rowData.no_dokumen;
			kd_brg		  = rowData.kd_brg;
			kd_unit		  = rowData.kd_unit;
			kd_uskpd	  = rowData.kd_uskpd;
			nm_brg		  = rowData.nm_brg;
			jumlah		  = rowData.jumlah;
			harga		    = rowData.harga;
			total		    = rowData.total;
			keterangan	= rowData.keterangan;
			s_dana		  = rowData.s_dana;
			kd_milik	  = rowData.kd_milik;
			kd_wilayah	= rowData.kd_wilayah;
			b_dasar		  = rowData.b_dasar;
			b_nomor		  = rowData.b_nomor;
			tahun		    = rowData.tahun;
			b_tanggal	  = rowData.b_tanggal;
			kd_cr_oleh	= rowData.kd_cr_oleh;
      kdgol       = rowData.jns_barang;
      bidang      = rowData.kd_bidang;
	  kd_kegiatan = rowData.kd_kegiatan;
			nm_kegiatan = rowData.nm_kegiatan;
			kd_rek5 = rowData.kd_rek5;
			nm_rek5 = rowData.nm_rek5;
           get(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,jumlah,harga,total,keterangan,s_dana,kd_milik,kd_wilayah,b_dasar,b_nomor,tahun,b_tanggal,kd_cr_oleh,kdgol,bidang,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5);
           //lutji();
          cek_status(no_dokumen);          
       }  
     });
        
	
	/*$('#bida').combogrid({  
            panelWidth:600, 
            width:160, 
			url:'<?php echo base_url(); ?>index.php/master/ambil_bidang',queryParams:({gol:'04'}),
            idField:'bidang',  
            textField:'bidang',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'bidang',title:'Kode Barang',width:100},  
               {field:'nm_bidang',title:'Nama Barang',width:500}    
            ]],  
             onSelect:function(rowIndex,rowData){
				cbidang=rowData.bidang;
				$('#kelo').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_kelompok',queryParams:({bidang:cbidang}) });            
        }  
    });*/
        
		$('#kelo').combogrid({  
           panelWidth:600, 
            width:160, 
            idField:'kelompok',  
            textField:'kelompok',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kelompok',title:'Kode Barang',width:100},  
               {field:'nm_kelompok',title:'Nama Barang',width:500}    
            ]],  
             onSelect:function(rowIndex,rowData){
				ckelompok=rowData.kelompok;
				$('#kelo1').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_kelompok1', queryParams:({kelompok:ckelompok}) });            
        }  
    });
		
		 $('#kelo1').combogrid({    
            panelWidth:600, 
            width:160, 
            idField:'kd_kelompok',  
            textField:'kd_kelompok',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_kelompok',title:'Kode Barang',width:100},  
               {field:'nm_kelompok',title:'Nama Barang',width:500}    
            ]],  
             onSelect:function(rowIndex,rowData){
				csubkel=rowData.kd_kelompok; 
				//$('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_brg_kib',queryParams:({subkel:csubkel,sts:'mrek5'})});             
        }  
    }); 

	    function get_rekskpd(kode_rekbrg){
			var x = kode_rekbrg;
	$('#kdbrg').combogrid({  
            panelWidth:600,  
			width:300,
            idField:'kd_barang',  
            textField:'kd_barang',  
            mode:'remote',                      
            url:'<?php echo base_url(); ?>index.php/master/ambil_rekbrg?kode='+x,  
            columns:[[  
               {field:'kd_barang',title:'Kode Barang',width:100},  
               {field:'nm_barang',title:'Nama Barang',width:370}     
            ]],  
            onSelect:function(rowIndex,rowData){
               $('#nmbrg').attr('value',rowData.nm_barang);  				
            } 
         });  	 
	}
	
    function cek_status(no_dokumen){
    //var no_dokumen    = $('#nodok').combogrid('getValue');
    var kdbrg   = document.getElementById('kdbrg').value;
    $(document).ready(function(){
    $.ajax({
      type:'post',
      data:({nodok:no_dokumen,kdbrg:kdbrg}),
      url :"<?php echo base_url(); ?>/index.php/transaksi/brg_jln_dh",
      dataType:"json",
      success:function(data){
        $.each(data,function(i,n){
            var st = n['invent'];
            //if(st==1){
              tombol(st);
            //}
        });
      }
    });
    });
  }	
		
	/*function lutji(){
		var doku    = $('#nodok').combogrid('getValue');
		if(doku== ''){
		 $('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_brg_kib' }); 
		 }else{
		 $('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/brg_jln',queryParams:({nodok:doku}) });     
		}
	} 	
		
     $('#kdbrg').combogrid({  
           panelWidth:600,  
           panelHeight:400,   
           idField:'kd_brg',  
           textField:'kd_brg',  
           mode:'remote',              
		   loadMsg:"Sedang Mencari Barang....",    
		   url:'<?php echo base_url(); ?>index.php/master/ambil_brg_kib',         
           columns:[[  
               {field:'kd_brg',title:'Kode Barang',width:100},  
               {field:'nm_brg',title:'Nama Barang',width:470}               
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               brg = rowData.kd_brg;
               invent=rowData.invent;
               $("#nmbrg").attr("value",rowData.nm_brg);
               $("#hrg_oleh").attr("value",number_format(rowData.harga));
               $("#hrg").attr("value",rowData.harga);
               $("#jml").attr("value",rowData.jml);
               //$("#no_urut").attr("value",rowData.no_urut);
               //$("#keterangan").attr("value",rowData.keterangan);
               tombol(invent);                                                                 
           }  
        });   */
     
     $('#kdtnh').combogrid({  
           panelWidth:600,  
           idField:'kd_brg',  
           textField:'kd_brg',  
           mode:'remote',                      
		   //url:'<?php echo base_url(); ?>index.php/transaksi/brg_tnh_bangunan', 
           columns:[[  
               {field:'kd_brg',title:'Kode Tanah',width:100},  
               {field:'nm_brg',title:'Nama Tanah',width:370},  
               {field:'nilai',title:'Nilai',width:100,align:'right'}                
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat 	= rowIndex;               
               brg 		  = rowData.kd_brg;
               nmbrg    = rowData.nm_brg;
               $("#nmtanah").attr("value",nmbrg);                                       
           }  
        }); 
	 
	 $('#kontruksi').combogrid({  
       panelWidth:160,  
       idField:'nm_konstruksi',  
       textField:'nm_konstruksi',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mkonstruksi',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'nm_konstruksi',title:'Konstruksi',width:100}
       ]],
        onSelect:function(rowIndex,rowData){
           lckondisi = rowData.kode;
           $("#nm_konstruksi").attr("value",rowData.nm_konstruksi.toUpperCase());
       }  
     });
     
     $('#gambar').combogrid({  
       panelWidth:200,  
       idField:'nm',  
       textField:'nm',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/scan',  
       columns:[[  
           {field:'nm',title:'Nama Foto',width:190}   
       ]],  
       onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           fot = rowData.nm;
           cokot(fot);
          
          // }                 
       }  
     });
     
     $('#milik').combogrid({  
       panelWidth:500,  
       idField:'nm_milik',  
       textField:'nm_milik',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_milik',  
       columns:[[  
           {field:'kd_milik',title:'KODE MILIK',width:100},  
           {field:'nm_milik',title:'KEPEMILIKAN',width:390}    
       ]] 
     });
     
     $('#wilayah').combogrid({  
       panelWidth:500,  
       idField:'nm_wilayah',  
       textField:'nm_wilayah',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_wilayah',  
       columns:[[  
           {field:'kd_wilayah',title:'KODE WILAYAH',width:100},  
           {field:'nm_wilayah',title:'WILAYAH',width:390}    
       ]] 
     });
     
	$('#skp').combogrid({  
           panelWidth:500,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_msskpd',  
           columns:[[  
               {field:'kd_skpd',title:'KODE SKPD',width:100},  
               {field:'nm_skpd',title:'NAMA SKPD',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpdx 	= rowData.kd_lokasi; 
               lcskpd 	= rowData.kd_skpd; 
               //$('#skpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uskpd',queryParams:({kduskpd:lcskpdx,skpd:lcskpd}) });
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
		   //$('#kdtnh').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/brg_tnh_bangunan', queryParams:({skpd:lcskpd}) });  
           }  
         });
		 	
			$('#skpdasal').combogrid({  
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
               lcskpdx 	= rowData.kd_lokasi; 
               lcskpd 	= rowData.kd_skpd; 
             //  $('#skpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uskpd',queryParams:({kduskpd:lcskpdx,skpd:lcskpd}) });
               $("#nmskpdasal").attr("value",rowData.nm_skpd.toUpperCase());
		   //$('#kdtnh').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/brg_tnh_bangunan', queryParams:({skpd:lcskpd}) });  
           }  
         });
    $('#skpd').combogrid({  
       panelWidth:500,  
       idField:'kd_uskpd',  
       textField:'kd_uskpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',  
       queryParams:({tabel:'trkib_a'}),  
       columns:[[  
           {field:'kd_uskpd',title:'KODE SKPD',width:100},  
           {field:'nm_uskpd',title:'Nama SKPD',width:390}    
       ]],  
       onSelect:function(rowIndex,rowData){
           lcskpd = rowData.kd_uskpd; 
           lcskpdx = rowData.kd_skpd;
           $("#kd_skpdx").attr("value",lcskpdx);
           $("#nmskpd").attr("value",rowData.nm_uskpd.toUpperCase());
           $('#uker').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uker2',queryParams:({kduskpd:lcskpd}) });   
		   nomer_akhir();             
	   }  
     });
     
     $('#uker').combogrid({  
       panelWidth:500,  
       idField:'kd_uker',  
       textField:'kd_uker',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_uker2',
       queryParams:({kduskpd:''}),
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kd_uker',title:'KODE UNIT KERJA',width:100},  
           {field:'nm_uker',title:'NAMA UNIT KERJA',width:390}    
       ]]
     });
     
     $('#perolehan').combogrid({  
       panelWidth:250,  
       idField:'cara_perolehan',  
       textField:'cara_perolehan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/perolehan',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'cara_perolehan',title:'Cara Perolehan',width:220}
       ]]
     });
     
     $('#dsr_peroleh').combogrid({  
       panelWidth:180,  
       idField:'dasar_perolehan',  
       textField:'dasar_perolehan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/dasar_perolehan',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'dasar_perolehan',title:'Dasar Perolehan',width:130}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckd = rowData.kode;
           $("#das").attr("value",rowData.dasar_perolehan.toUpperCase());
                         
       }  
     });
     
     $('#jns_dana').combogrid({  
       panelWidth:150,  
       idField:'sumber_dana',  
       textField:'sumber_dana',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mdana',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:50},
           {field:'sumber_dana',title:'Jenis Dana',width:90}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lcsumber = rowData.kode;
           $("#sum").attr("value",rowData.sumber_dana.toUpperCase());
                         
       }  
     });
     
     $('#bkt_bayar').combogrid({  
       panelWidth:150,  
       idField:'bukti',  
       textField:'bukti',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mbukti',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'bukti',title:'Bukti Pembayaran',width:100}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lcbukti = rowData.kode;
           $("#buk").attr("value",rowData.Bukti.toUpperCase());
                         
       }  
     });
     
     $('#kondisi').combogrid({  
       panelWidth:150,  
       idField:'kondisi',  
       textField:'kondisi',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mkondisi',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'kondisi',title:'Kondisi',width:100}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#kon").attr("value",rowData.kondisi.toUpperCase());
                         
       }  
     });
     
     $('#st_tanah').combogrid({  
       panelWidth:200,  
       idField:'status',  
       textField:'status',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mstatus',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'status',title:'Status',width:150}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#sta").attr("value",rowData.status.toUpperCase());
                         
       }  
     });
	 
	 $('#metode').combogrid({  
       panelWidth:200,  
       idField:'metode',  
       textField:'metode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mmetode',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'metode',title:'Metode',width:150}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#metode").attr("value",rowData.metode.toUpperCase());
                         
       }  
     });
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_d_mutasi_masuk',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_brg',title:'KODE BARANG',width:25,align:"left"},
            {field:'nm_brg',title:'NAMA BARANG',width:40,align:"left"},
            {field:'no_reg',title:'NO REGISTER',width:25,align:"left"},
            {field:'alamat1',title:'ALAMAT',width:40,align:"left"},
            {field:'tahun',title:'TAHUN',width:15,align:"left"},
            {field:'nilai',title:'HARGA',width:20,align:"right"},
            {field:'keterangan',title:'KETERANGAN',width:35,align:"left"},
            {field:'id_barang',title:'id_barang',width:35,align:"left",hidden:true},
			{field:'history',title:'HIS',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/document.png' onclick='javascript:riwayat();'' />";}},
			{field:'del',title:'DEL',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}},
        ]],
        onSelect:function(rowIndex,rowData){
          lcidx 			    = rowIndex;
          noreg 			    = rowData.no_reg;
          no 				      = rowData.no;
          nodok 			    = rowData.no_dokumen;
          kdbrg  			    = rowData.kd_brg;
		      id_barang 		  = rowData.id_barang;
		      no_furut			  = rowData.no_urut;
		      kd_skpd 			  = rowData.kd_skpd;
		      kd_unit			    = rowData.kd_unit;
		      nm_brg			    = rowData.nm_brg;
		      nilai				    = rowData.nilai;
		      tahun				    = rowData.tahun;
		      tgl_riwayat		  = rowData.tgl_riwayat;
		      kd_riwayat		  = rowData.kd_riwayat;
		      detail_riwayat	= rowData.detail_riwayat;
          nm_skpd         = rowData.nm_skpd;
		 get_riwayat(kd_skpd,nm_skpd,kd_unit,kdbrg,nm_brg,id_barang,nilai,tahun,no_furut,tgl_riwayat,kd_riwayat,detail_riwayat);
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx 		  = rowIndex;
          judul 		  = 'Edit Data Lokasi'; 
          lcstatus 		= 'edit';
          noreg 		  = rowData.no_reg;
          no_furut  	= rowData.no_urut;
		      id_barang 	= rowData.id_barang;
          no 			    = rowData.no;
          no_oleh		  = rowData.no_oleh;
          tgl_reg 		= rowData.tgl_reg;
          tgl_oleh 		= rowData.tgl_oleh;
          nodok 		  = rowData.no_dokumen;
          kdbrg  		  = rowData.kd_brg;
          detail_brg	= rowData.detail_brg;
          kd_tnh		  = rowData.kd_tanah;
          kondi			  = rowData.kondisi;
          sta_thn		  = rowData.status_tanah;
          pj			    = rowData.panjang;
          ls			    = rowData.luas;
          lb			    = rowData.lebar;
          jumlah		  = rowData.jumlah;
          ket			    = rowData.keterangan;
		      nilai			  = rowData.nilai;
          nilai_baru	= rowData.total;
          kd_skpd		  = rowData.kd_skpd;
          kd_unit		  = rowData.kd_unit;
          milik			  = rowData.milik;
          wilayah		  = rowData.wilayah;
          penggunaan	= rowData.penggunaan;
          konstruksi	= rowData.konstruksi;
          alamat1		  = rowData.alamat1;
          alamat2		  = rowData.alamat2;
          alamat3		  = rowData.alamat3;
          perolehan		= rowData.perolehan;
          dsr			    = rowData.dasar;
          tahun			  = rowData.tahun;
          foto			  = rowData.foto;
          cokot(foto,'1');
          foto2			  = rowData.foto2;
          cokot(foto2,'2');
          foto3			  = rowData.foto3;
          cokot(foto3,'3');
		      metode		  = rowData.metode;
          masa_manfaat= rowData.masa_manfaat;
          nilai_sisa	= rowData.nilai_sisa;
		      lat 			  = rowData.lat;
		      lon 			  = rowData.lon;
          kdgol       = rowData.kd_golongan;
          bidang      = rowData.kd_bidang;
          nmbrg       = rowData.nm_brg;
           kd_kegiatan = rowData.kd_kegiatan;
			nm_kegiatan = rowData.nm_kegiatan;
			kd_rek5 = rowData.kd_rek5;
			nm_rek5 = rowData.nm_rek5;

          get1(id_barang,noreg,no,no_oleh,tgl_reg,tgl_oleh,nodok,kdbrg,detail_brg,kd_tnh,kondi,sta_thn,pj,ls,lb,perolehan,dsr,tahun,jumlah,ket,nilai,nilai_baru,kd_skpd,kd_unit,milik,wilayah,penggunaan,konstruksi,alamat1,alamat2,alamat3,foto,foto2,foto3,metode,masa_manfaat,nilai_sisa,lat,lon,kdgol,bidang,nmbrg,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5);
		 
		  edit_data();   cek_status();
        }
      
        });
       
    });
	
    function ajaxFileUpload(lc)
	{
        var lcno = 'gambar'+lc;
        var lcupload = 'fileToUpload'+lc;
		var cfile = document.getElementById(lcupload).files[0];
		//$("#gambar").attr("value",cfile.name);
		document.getElementById(lcno).value = cfile.name;
        cokot(cfile.name,lc);
		$("#loading").show();
		/*.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		*/
		$.ajaxFileUpload
		(
			{
				url:'<?php echo base_url();?>index.php/transaksi/uploadfile',
				secureuri:false,
				fileElementId:lcupload,
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
				$("#loading").hide();
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							//alert(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		return false;
	}
    
    function get(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,jumlah,harga,total,keterangan,s_dana,kd_milik,kd_wilayah,b_dasar,b_nomor,tahun,b_tanggal,kd_cr_oleh,kdgol,bidang,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5){
           $("#nodok").attr("value",no_dokumen);
           $("#kdbrg").attr("value",kd_brg);
           $("#nmbrg").attr("value",nm_brg);
           $("#detail_brg").attr("value",nm_brg);
           $("#milik").combogrid("setValue",kd_milik);
           $("#wilayah").combogrid("setValue",kd_wilayah);
           $("#skp").combogrid("setValue",kd_uskpd);
           $("#skpd").combogrid("setValue",kd_unit);
           $("#perolehan").combogrid("setValue",kd_cr_oleh);
           $("#dsr_peroleh").combogrid("setValue",b_dasar);
           $("#no_oleh").attr("value",b_nomor);
           $("#tgl_oleh").datebox("setValue",b_tanggal);
           $("#th_oleh").attr("value",tahun);
           $("#hrg_oleh").attr("value",number_format(harga));
           $("#jml").attr("value",jumlah);
           $("#jns_dana").combogrid("setValue",s_dana);
           $("#keterangan").attr("value",keterangan);
           $("#gol").attr("value",kdgol);
           $("#bida").attr("value",bidang);
           $('#nkd_keg').attr('value',kd_kegiatan);
		   $('#nnm_keg').attr('value',nm_kegiatan);
		   $('#nkd_rek5').attr('value',kd_rek5);
		   $('#nnm_rek5').attr('value',nm_rek5);            
    }
    
    function get1(id_barang,noreg,no,no_oleh,tgl_reg,tgl_oleh,nodok,kdbrg,detail_brg,kd_tnh,kondi,sta_thn,pj,ls,lb,perolehan,dsr,tahun,jumlah,ket,nilai,nilai_baru,kd_skpd,kd_unit,milik,wilayah,penggunaan,konstruksi,alamat1,alamat2,alamat3,foto,foto2,foto3,metode,masa_manfaat,nilai_sisa,lat,lon,kdgol,bidang,nmbrg,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5){ //get1(id_barang,noreg,no,no_oleh,tgl_reg,tgl_oleh,nodok,kdbrg,detail_brg,kd_tnh,kondi,sta_thn,pj,ls,lb,perolehan,dsr,tahun,jumlah,ket,nilai,nilai_baru,kd_unit,milik,wilayah,penggunaan,konstruksi,alamat1,alamat2,alamat3,foto,foto2,foto3,metode,masa_manfaat,nilai_sisa,lat,lon){
           $("#id_barang").attr("value",id_barang);
		       $("#noreg").attr("value",noreg);
           $("#no").attr("value",no);
           $("#no_oleh").attr("value",no_oleh);
           $("#tanggal").datebox("setValue",tgl_reg);
           $("#tgl_oleh").datebox("setValue",tgl_oleh);
           //$("#nodok").combogrid("setValue",nodok);
           $("#kdbrg").attr("value",kdbrg);
           $("#detail_brg").attr("value",detail_brg);
           $('#kdtnh').combogrid('setValue',kd_tnh);
           $('#kondisi').combogrid("setValue",kondi);
           $('#st_tanah').combogrid("setValue",sta_thn);
           $("#panjang").attr("value",pj);
           $("#luas").attr("value",ls);
           $("#lebar").attr("value",lb);
           $("#perolehan").combogrid("setValue",perolehan);
           $("#dsr_peroleh").combogrid("setValue",dsr);
           $("#jml").attr("value",jumlah);
           $("#keterangan").attr("value",ket);
           $("#kontruk").attr("value",konstruksi);
           $("#alamat1").attr("value",alamat1);
           $("#alamat2").attr("value",alamat2);
           $("#alamat3").attr("value",alamat3);
           $("#skp").combogrid("setValue",kd_skpd);
		       $("#skpd").combogrid("setValue",kd_unit);
		       $("#milik").combogrid("setValue",milik);
		       $("#wilayah").combogrid("setValue",wilayah);
           $("#guna").attr("value",penggunaan);
           $("#hrg_oleh").attr("value",number_format(nilai));
           $("#th_oleh").attr("value",tahun);
           $("#gambar1").attr("value",foto);
           $("#gambar2").attr("value",foto2);
           $("#gambar3").attr("value",foto3);
		       $("#metode").combogrid("setValue",metode);
		       $("#masa_manfaat").attr("value",masa_manfaat);
           $("#nilai_sisa").attr("value",nilai_sisa);
           $("#nilai_baru").attr("value",number_format(nilai_baru));
           $("#lat").attr("value",lat);
           $("#lon").attr("value",lon);
           $("#gol").attr("value",kdgol);
           $("#bida").attr("value",bidang);
           $("#nmbrg").attr("value",nmbrg);
			$//("#bida").attr("value",kd_bidang);
		   $('#nkd_keg').attr('value',kd_kegiatan);
		   $('#nnm_keg').attr('value',nm_kegiatan);
		   $('#nkd_rek5').attr('value',kd_rek5);
		   $('#nnm_rek5').attr('value',nm_rek5);

		   
		   if(tgl_reg<='2014-12-31'){
			document.getElementById("hrg_oleh").disabled=true;
		   }else{
			document.getElementById("hrg_oleh").disabled=false;
			}
                              
    }
    function get_riwayat(kd_skpd,nm_skpd,kd_unit,kdbrg,nm_brg,id_barang,nilai,tahun,no_furut,tgl_riwayat,kd_riwayat,detail_riwayat){
		   $("#kd_brg").attr("value",kdbrg);
		   $("#nm_brg").attr("value",nm_brg);
       $("#kd_skpd").attr("value",kd_skpd);
       $("#nm_skpd").attr("value",nm_skpd);
       $("#kd_unit").attr("value",kd_unit);
       $("#no_urut").attr("value",no_furut);
       $("#id_barang").attr("value",id_barang);
       $("#nilai").attr("value",number_format(nilai));
       $("#tahun").attr("value",tahun);
       $("#tgl_riwayat").datebox("setValue",tgl_riwayat);
       $("#kd_riwayat").combogrid("setValue",kd_riwayat); 
       $("#detail_riwayat").attr("value",detail_riwayat);
	}
   function  cokot(foto,lc){
         var lcfoto = 'foto'+lc;
         document.getElementById(lcfoto).src = "<?php echo base_url(); ?>data/"+foto;
    }
    
    function kosong(){
		var skpd = '<?php echo ($this->session->userdata('skpd')); ?>';
    var unit_skpd = '<?php echo ($this->session->userdata('unit_skpd')); ?>';
    var thn   = '<?php echo ($this->session->userdata('ta_simbakda')); ?>';
    var oto   = '<?php echo ($this->session->userdata('otori_simbakda')); ?>';
    
    if(oto=='01'){
      $('#skp').combogrid("clear");
      $('#skpd').combogrid("clear");
      $("#nmskpd").attr("value",'');
    }else{
      $('#skp').combogrid("setValue",skpd);
      $('#skpd').combogrid("setValue",unit_skpd);
    }
        $("#noreg").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#milik").combogrid("setValue",'');
        $("#wilayah").combogrid("setValue",'');
        $("#dsr_peroleh").combogrid("setValue",'');
        $("#no_oleh").attr("value",'');
        $("#tgl_oleh").datebox("setValue",'');
        $("#th_oleh").attr("value",thn);
        $("#jns_dana").combogrid("setValue",'');
        $("#bkt_bayar").combogrid("setValue",'');
        $("#thn_anggar").attr("value",'');
        //$("#nodok").combogrid("clear");
        //$("#nodok").combogrid('grid').datagrid('reload');
		//    $("#kdbrg").attr("value",'');
        $("#detail_brg").attr("value",'');
    		$('#kdtnh').combogrid("setValue",'');
    		$('#kondisi').combogrid("setValue",'');
    		$('#perolehan').combogrid("setValue",'');
    		$('#st_tanah').combogrid("setValue",'');
    		$('#kontruksi').combogrid("setValue",'');
    		$("#panjang").attr("value",'');
    		$("#luas").attr("value",'');
    		$("#lebar").attr("value",'');
    		$("#guna").attr("value",'');
    		$("#dsr_guna").attr("value",'');
    		$("#no_sk").attr("value",'');
    		$("#tgl_sk").datebox("setValue",'');
    		$("#keterangan").attr("value",'');
    		$("#kontruk").attr("value",'');
    		$("#alamat1").attr("value",'');
    		$("#alamat2").attr("value",'');
    		$("#alamat3").attr("value",'');
    		$("#nmbrg").attr("value",'');
    		$("#nmtanah").attr("value",'');
        $("#das").attr("value",'');
		    $("#hrg_oleh").attr("value",'');
		    $("#hrg").attr("value",'');
	      $("#nilai_baru").attr("value",'');
    		$("#buk").attr("value",'');
    		$("#jml").attr("value",'');
    		$("#sum").attr("value",'');
    		$("#giat").attr("value",'');
    		$("#kon").attr("value",'');
    		$("#sta").attr("value",'');
        $("#no_urut").attr("value",'');
        $("#gambar").combogrid("setValue",'');
    		$("#metode").combogrid("setValue",'');
    		$("#masa_manfaat").attr("value",'');
    		$("#nilai_sisa").attr("value",'');
        $("#lat").attr("value",'');
        $("#lon").attr("value",'');
        $("#gol").attr("value",'');
       // $("#bida").attr("value",'');
        $('#save').linkbutton('enable');
      //  $('#nodok').combogrid('enable');
        //$('#bida').combogrid('enable');
        //$('#kdbrg').combogrid('enable');
       
	   document.getElementById("p1").innerHTML="";
       $("#kd_skpd").attr("value",'');
       $("#nm_skpd").attr("value",'');
       nomer_akhir();
    }
    
   function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){
     $('#dg').edatagrid({
		url: '<?php echo base_url();?>index.php/transaksi/ambil_kib_d_mutasi_masuk',
        queryParams:({cari:kriteria})
        });        
     });
    }
        
	function gambar(lf){
        lcfoto = 'foto'+lf;
        document.getElementById("fotoZ").src =  document.getElementById(lcfoto).src;
        $("#dialog-modal_gambar").dialog('open');             
    } 
	
    function simpan(){
        var id_brg	 		= document.getElementById('id_barang').value;
		    var user  			= document.getElementById('nmskpd').value;
		    var no_urut 		= document.getElementById('no_reg').value;
		    var urutan	 		= document.getElementById('no_urut').value; 
        var no_reg 			= document.getElementById('noreg').value;
        var no 				  = document.getElementById('no').value;
        var tgl_reg 		= $('#tanggal').datebox('getValue');
        var tgl_oleh 		= $('#tgl_oleh').datebox('getValue');
        var no_dokumen 		= document.getElementById('nodok').value;//$('#nodok').combogrid('getValue');
        var kd_brg 			= $('#kdbrg').combogrid('getValue');
        var kd_thn 			= $('#kdtnh').combogrid('getValue'); 
        var kd_skpd 		= $('#skp').combogrid('getValue');
        var kd_unit 		= $('#skpd').combogrid('getValue');
        var tahun 			= document.getElementById('th_oleh').value;
        var nilai 			= angka(document.getElementById('hrg_oleh').value);
        var nilai_baru	= angka(document.getElementById('nilai_baru').value);
		    var joss 			  = document.getElementById('jml').value;
		    var fa	 			  = joss/joss;
        var no_oleh			= document.getElementById('no_oleh').value;
        var detail 			= document.getElementById('detail_brg').value;
        var kondisi 		= $('#kondisi').combogrid('getValue');
        var st_tanah 		= $('#st_tanah').combogrid('getValue');
        var perolehan		= $('#perolehan').combogrid('getValue');
        var dsr_peroleh	= $('#dsr_peroleh').combogrid('getValue');
        var panjang 		= document.getElementById('panjang').value;
        var luas 			  = document.getElementById('luas').value;
        var lebar 			= document.getElementById('lebar').value;
        var penggunaan 	= document.getElementById('guna').value;
        var dasar_pengguna 	= document.getElementById('dsr_guna').value;
        var konstruksi	= document.getElementById('kontruk').value;
        var milik 			= $('#milik').combogrid('getValue');
        var wilayah			= $('#wilayah').combogrid('getValue');
        var tgl_sk 			= $('#tgl_sk').datebox('getValue');
        var lokasi 			= document.getElementById('lokasi').value;
        var alamat1 		= document.getElementById('alamat1').value;
        var alamat2 		= document.getElementById('alamat2').value;
        var alamat3 		= document.getElementById('alamat3').value;
        var keterangan 	= document.getElementById('keterangan').value;
        //var file_gbr1 	= document.getElementById('gambar1').value;
        //var file_gbr2 	= document.getElementById('gambar2').value;
        //var file_gbr3 	= document.getElementById('gambar3').value;
		    var metode 			= $('#metode').combogrid('getValue');
		    var masa_manfaat= document.getElementById('masa_manfaat').value;
        var nilai_sisa 	= document.getElementById('nilai_sisa').value;
        //var clat 			  = document.getElementById('lat').value;
        //var clon 			  = document.getElementById('lon').value;
        var nmbrg       = document.getElementById('nmbrg').value;
        var kdgol       = document.getElementById('gol').value;
        var bidang      = $('#bida').combogrid('getValue');//document.getElementById('bida').value;
        var username      = '<?php echo ($this->session->userdata('nmuser')); ?>';
        var tgl_update    = '<?php echo date('Y-m-d H:i:s'); ?>';
		var kd_keg      = document.getElementById('nkd_keg').value;
        var nm_keg   = document.getElementById('nnm_keg').value;
		 var kd_rek      = document.getElementById('nkd_rek5').value;
        var nm_rek   = document.getElementById('nnm_rek5').value;
		var nm_brg   = document.getElementById('nmbrg').value;
		var kd_asal = $('#skpdasal').combogrid('getValue');
		
		//no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_golongan,kd_bidang,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,total,no_dok,tgl_dok,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_pemilik,kd_kegiatan,kd_rek5,nm_kegiatan,nm_rek5
        //lcinsert = "(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_golongan,kd_bidang,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,no_urut,metode,masa_manfaat,nilai_sisa,kd_pemilik,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5)";
		lcinsert = "(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_golongan,kd_bidang,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,no_urut,metode,masa_manfaat,nilai_sisa,kd_pemilik,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,mutasi_masuk,kd_skpd_asal)";
				
		       
       	if (kd_brg==''){
            alert('Kode Barang Tidak Boleh Kosong.!');
            exit();
        }if (tgl_reg==''){
            alert('Tanggal Register Tidak Boleh Kosong.!');
            exit();
        }if (milik==''){
            alert('Kepemilikan Tidak Boleh Kosong.!');
            exit();
        }if (wilayah==''){
            alert('Wilayah Tidak Boleh Kosong.!');
            exit();
        }  if (kd_skpd==''){
            alert('Kode SKPD Tidak Boleh Kosong.!');
            exit();
        }  if (kd_asal==''){
            alert('Kode Asal SKPD Tidak Boleh Kosong.!');
            exit();
        }   if (nilai==''){
            alert('Nilai Tidak Boleh Kosong.!');
            exit();
        }  if (kondisi==''){
            alert('Kondisi Tidak Boleh Kosong.!');
            exit();
        } if (tahun==''){
            alert('Tahun Perolehan Tidak Boleh Kosong.!');
            exit();
        } if (tgl_oleh==''){
            alert('Tanggal Perolehan Tidak Boleh Kosong.!');
            exit();
        }              
		if (joss==''){
            alert('Jumlah Tidak Boleh Kosong..!!');
           exit();
		}
		if (st_tanah==''){
            alert('status Tidak Boleh Kosong..!!');
           exit();
        }		
		if (panjang==''){
            alert('panjang Tidak Boleh Kosong..!!');
           exit();
        }
		if (luas==''){
            alert('luas Tidak Boleh Kosong..!!');
           exit();
        }
		if (lebar==''){
            alert('lebar Tidak Boleh Kosong..!!');
           exit();
        }
		if (alamat1==''){
            alert('alamat Tidak Boleh Kosong..!!');
           exit();
        }

        lcvalues = '';
		if (lcstatus=='tambah'){ 
        for (var j=0;j<joss;j++){
			creg   = j+1;
            no_urt = tambah_urut(no_urut,7);
            nomor = no_urt.substr(3,4);
            urutan = no_urt.substr(3,4);
			id_barang = kd_skpd.trim()+'/'+tahun+'/'+kd_brg+'/'+nomor;
            no_gabung = kd_brg+'/'+nomor+'/'+kd_skpd;
		if (j>0){
//					lcvalues = lcvalues+",('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+file_gbr1+"','"+file_gbr2+"','"+file_gbr3+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+clat+"','"+clon+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"')";
			//		lcvalues = lcvalues+",('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"')";
					lcvalues = lcvalues+",('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"','1','"+kd_asal+"')";
		
			}else{
				    //lcvalues = lcvalues+"('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+file_gbr1+"','"+file_gbr2+"','"+file_gbr3+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+clat+"','"+clon+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"')";
				//    lcvalues = lcvalues+"('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"')";
				    lcvalues = lcvalues+"('"+nomor+"','"+id_barang+"','"+no_gabung+"','"+no_oleh+"','"+tgl_reg+"','"+tgl_oleh+"','"+no_dokumen+"','"+kdgol+"','"+bidang+"','"+kd_brg+"','"+nmbrg+"','"+detail+"','"+kd_thn+"','"+nilai+"','"+nilai_baru+"','"+kondisi+"','"+st_tanah+"','"+panjang+"','"+luas+"','"+lebar+"','"+konstruksi+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+perolehan+"','"+dsr_peroleh+"','"+fa+"','"+keterangan+"','"+kd_skpd+"','"+kd_unit+"','"+milik+"','"+wilayah+"','"+penggunaan+"','"+username+"','"+tgl_update+"','"+tahun+"','"+urutan+"','"+metode+"','"+masa_manfaat+"','"+nilai_sisa+"','"+milik+"','"+kd_keg+"','"+nm_keg+"','"+kd_rek+"','"+nm_rek+"','1','"+kd_asal+"')";
				}   
          no_urut=no_urt;             
			}
	  
			$(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/transaksi/simpan_trkib_d',
                    data: ({tabel:'trkib_d',urut:urutan,reg:no_urt,unit:kd_unit,kolom:lcinsert,lcvalues:lcvalues,kd_brg:kd_brg,no:no_dokumen}),
                    dataType:"json",
                    success:function(data){                                          
                        $.each(data,function(i,n){                                    
                            pesan = n['pesan'];
                            if(pesan==1){
                              swal({
                              title: "Berhasil",
                              text: "Data telah disimpan.!!",
                              imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
                              });
                              $("#dialog-modal").dialog('close');
                              $('#dg').edatagrid('reload');
                            }else{
                              swal({
                                title: "Oooopppppsssssssss!!!!!!!!!!",
                                text: "Data Gagal disimpan.!!",
                                imageUrl:"<?php echo base_url();?>/lib/images/er.jpg"
                                });
                            }                              
                        });
                    }
                });
            });			
        } else{
		   lcquery = "UPDATE trkib_d SET no_oleh='"+no_oleh+"',tgl_reg='"+tgl_reg+"',tgl_oleh='"+tgl_oleh+"',no_dokumen='"+no_dokumen+"',kd_brg='"+kd_brg+"',nm_brg='"+nmbrg+"',detail_brg='"+detail+"',kd_tanah='"+kd_thn+"',nilai='"+nilai+"',total='"+nilai_baru+"',kondisi='"+kondisi+"',status_tanah='"+st_tanah+"',panjang='"+panjang+"',luas='"+luas+"',lebar='"+lebar+"',konstruksi='"+konstruksi+"',alamat1='"+alamat1+"',alamat2='"+alamat2+"',alamat3='"+alamat3+"',perolehan='"+perolehan+"',dasar='"+dsr_peroleh+"',jumlah='"+fa+"',keterangan='"+keterangan+"',kd_skpd='"+kd_skpd+"',kd_unit='"+kd_unit+"',milik='"+milik+"',wilayah='"+wilayah+"',penggunaan='"+penggunaan+"',username='"+username+"',tgl_update='"+tgl_update+"',tahun='"+tahun+"',foto='"+file_gbr1+"',foto2='"+file_gbr2+"',foto3='"+file_gbr3+"',metode='"+metode+"',masa_manfaat='"+masa_manfaat+"',nilai_sisa='"+nilai_sisa+"',lat='"+clat+"',lon='"+clon+"',kd_pemilik='"+milik+"' WHERE id_barang='"+id_brg+"'";// and no_urut='"+urutan+"'
			
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/transaksi/update_trkib_d',
                data: ({st_query:lcquery}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                        pesan = n['pesan'];
                        if(pesan==1){
                              swal({
                              title: "Berhasil",
                              text: "Data telah di-update.!!",
                              imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
                              });
                              $("#dialog-modal").dialog('close');
                              $('#dg').edatagrid('reload');
                            }else{
                              swal({
                                title: "Oooopppppsssssssss!!!!!!!!!!",
                                text: "Data Gagal di-Update.!!",
                                imageUrl:"<?php echo base_url();?>/lib/images/er.jpg"
                                });
                            }                              
                    });
                }
            });
            });
        }     
		/*swal({
		title: "Berhasil",
		text: "Data telah disimpan.!!",
		imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
		});*/
        /*$("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');*/
    } 
	
		function simpan_riwayat(){
        var id_barang 		= document.getElementById('id_barang').value;
		    var tgl_riwayat		= $('#tgl_riwayat').datebox('getValue');
        var kd_riwayat 		= $('#kd_riwayat').combogrid('getValue'); 
        var detail_riwayat	= document.getElementById('detail_riwayat').value;
		
        var tahun	= document.getElementById('tahun').value;
        var kd_unit	= document.getElementById('kd_unit').value;
        var no_urut	= document.getElementById('no_urut').value;
        var kd_brg	= document.getElementById('kd_brg').value;
        if (kd_riwayat==''){
            alert('Riwayat Barang Tidak Boleh Kosong');
            exit();
        } 
		
        lcinsert = "(kd_riwayat,tgl_riwayat,detail_riwayat)";
        
        if(lcstatus=='tambah'){ 
		lcvalues = "('"+kd_riwayat+"','"+tgl_riwayat+"','"+detail_riwayat+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/transaksi/simpan_trkib_d',
                    data: ({tabel:'trkib_d',no:id_barang,kolom:lcinsert,lcvalues:lcvalues}),
                    dataType:"json"
                });
            });    
        } else {
            lcquery = "update trkib_d set kd_riwayat='"+kd_riwayat+"',tgl_riwayat='"+tgl_riwayat+"',detail_riwayat='"+detail_riwayat+"' where id_barang ='"+id_barang+"' and tahun ='"+tahun+"' and kd_unit ='"+kd_unit+"' and kd_brg ='"+kd_brg+"'";// and no_urut ='"+no_urut+"'
			$(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/transaksi/update_trkib_d',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
        }                            
		swal({
		title: "Berhasil",
		text: "Data telah disimpan.!!",
		imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
		});
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');       
    }
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Kib D';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("noreg").disabled=true;
        $('#save').linkbutton('enable');
        //$('#nodok').combogrid('disable');
        $('#skp').combogrid('disable');
        $('#skpd').combogrid('disable');
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Kib D';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("noreg").disabled=false;
        document.getElementById("noreg").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     	  $("#dialog-riwayat").dialog('close');
        $('#dg').edatagrid('unselectAll');
        $('#dg').edatagrid('reload');
        kosong();
     } 
	  function riwayat(){
        $("#dialog-riwayat").dialog('open');
		
     } 
     //has modify by demansyah
     function hapus(){
      var rows = $('#dg').datagrid('getSelected');
      id_brg = rows.id_barang;
		var del = confirm("Apakah Anda Yakin ingin menghapus data   "+id_brg+"?");
		if(del==true){
        var urll = '<?php echo base_url(); ?>index.php/transaksi/hapus_trkib_d';
        $(document).ready(function(){
         $.post(urll,({no:id_barang,dok:nodok,no_urut:no_furut}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').edatagrid('reload')
                exit();
            }
         });
        });    
      }
    } 
    
 function nomer_akhir(){
        var i 		= 0; 
        var tabel 	='trkib_d'
        var urut 	='no_urut'
        var reg 	='no_reg'
        var kd_unit	= $('#skpd').combogrid('getValue'); 
		    var brg		= document.getElementById('kdbrg').value;  //$('#kdbrg').combogrid('getValue'); 
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/transaksi/mlokasi_urut',
            data: ({tabel:tabel,kd_unit:kd_unit,urut:urut,reg:reg,brg:brg}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    nom      = n['urut'];                                     
                    no_reg   = n['no_reg'];  
                    $("#no_urut").attr("value",nom);
                    $("#no_reg").attr("value",no_reg);                              
                });
            }
        });         
    }
    
    function disable(){
         $('#milik').combogrid('disable');
         $("#wilayah").combogrid('disable');
           $("#skpd").combogrid('disable');
           $("#dsr_peroleh").combogrid('disable');
           $("#jns_dana").combogrid('disable');
           $("#bkt_bayar").combogrid('disable');
      }
      
    function tambah_urut(angka,panjang){
        no=((angka)*1)+1;
        a=no.toString();
        jnol=panjang-a.length;
        nol='';
        for(i=1;i<=jnol;i++){
        nol=nol+'0';
        }
        b= nol+a;
        return b;
    }
    
    function tombol(st){
     
        if (st=='1'){  
          if(lcstatus=='tambah'){  
            document.getElementById("p1").innerHTML="Sudah di INVENTARISASI!!";
            $('#save').linkbutton('disable');
          }else{
            document.getElementById("p1").innerHTML="Sudah di INVENTARISASI!!"; 
            $('#save').linkbutton('enable');
          }
        } else { 
          document.getElementById("p1").innerHTML="";
          $('#save').linkbutton('enable');
        }
      }
  
  function getkey(e){
	if (window.event)
		return window.event.keyCode;
	else if (e)
		return e.which;
	else
    return null;
	}
function angkadanhuruf(e, goods, field){
	var angka, karakterangka;
	angka = getkey(e);
	if (angka == null) return true;
	karakterangka = String.fromCharCode(angka);
	karakterangka = karakterangka.toLowerCase();
	goods = goods.toLowerCase();
	// check goodkeys
	if (goods.indexOf(karakterangka) != -1)
    return true;
	// control angka
	if ( angka==null || angka==0 || angka==8 || angka==9 || angka==27 )
   return true;
	if (angka == 13) {
    var i;
    for (i = 0; i < field.form.elements.length; i++)
        if (field == field.form.elements[i])
            break;
    i = (i + 1) % field.form.elements.length;
    field.form.elements[i].focus();
    return false;
    };
	// else return false
	return false;
	} 
 
 function isNumberKey(evt)
	{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))

	return false;
	return true;
	}
  
  function kib_kib(cek){
		var link = cek;
		var kib = "";
		
		if(link==1){kib = "mutasi_a";}else
		if(link==2){kib = "mutasi_b";}else
		if(link==3){kib = "mutasi_c";}else
		if(link==4){kib = "mutasi_e";}else
		if(link==5){kib = "mutasi_f";}
		
		
		var url    = "<?php echo site_url('transaksi');?>/"+kib;	  												
		window.open(url, '_self');						
	}
  
   </script>



<div id="content1"> 
<div><h3 align="center"><b>.:INPUTAN MUTASI MASUK:.</BR>.:JALAN,IRIGASI DAN JARINGAN:.</b></h3></div>
<div align="center">
    <p>     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        <!--td width="10%"><a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a></td-->
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:250px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" align="center" title="LISTING MUTASI MASUK JALAN,IRIGASI dan JARINGAN" style="width:900px;height:310px;" >  
        </table>
		<table align="center" border="0" style="width:900px;height:35px; border-radius:5px 5px 5px 5px; background-color:#D0D0D0;">  
			<tr>
				<td width="100px" align="center"><b style="color:red">Navigasi Kib :</b></td>
				<td width="800px">				
				<a class="easyui-linkbutton" plain="true" onclick="javascript:kib_kib(1);"><b>[1] Tanah</b></a> | 
				&nbsp;<a class="easyui-linkbutton" plain="true" onclick="javascript:kib_kib(2);"><b>[2] Peralatan Dan Mesin</b></a> | 
				&nbsp;<a class="easyui-linkbutton" plain="true" onclick="javascript:kib_kib(3);"><b>[3] Gedung Dan Bangungan</b></a> | 
				&nbsp;<a class="easyui-linkbutton" plain="true" onclick="javascript:kib_kib(4);"><b>[4] Aset Tetap Lainnya</b></a> | 
				&nbsp;<a class="easyui-linkbutton" plain="true" onclick="javascript:kib_kib(5);"><b>[5] Konstruksi Dlm Pengerjaan</b></a>							
				</td>
			</tr>
        </table>
        </td>
        </tr>
    </table>    
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">.:Mutasi Masuk Jalan,Irigasi dan Jaringan  </p> 
    <fieldset>
    <p id="p1" style="font-size: x-large;color: red;"></p>
     <table align="center" style="width:100%;" border="0">
           <tr>
               <td width="50%" valign="top">
                    <table align="left" style="width:100%;" border="0">
                       <tr hidden="true">
                            <td>ID Barang</td>
                            <td>:</td>
                            <td><input type="text" id="id_barang" name="id_barang" style="width: 250px;" />
							<input type="hidden" id="nkd_rek5" name="nkd_rek5" />
							<input type="hidden" id="nnm_rek5" name="nnm_rek5" />
							<input type="hidden" id="nkd_keg" name="nkd_keg" />
							<input type="hidden" id="nnm_keg" name="nnm_keg" /></td>
                       </tr>
                       <tr>
                            <td width="25%">No Register</td>
                            <td width="5%">:</td>
                            <td width="70%"><input readonly="true" type="text" id="noreg" name="noreg" style="width:150px; " placeholder="AutoNumber"/>
                            <input type="hidden" id="no" name="no"   style="width:150px;"/><input type="text" id="tanggal" style="width: 140px;" /></td>
                       </tr>
                       <tr>
                            <td>No Dokumen</td>
                            <td>:</td>
                            <td><input id="nodok" name="nodok" style="width:250px;" /></td>
                       </tr>
						<tr>
              <td>Golongan Barang</td>
              <td>:</td>
              <td><input id="gol" name="gol"  disabled="true"/>  </td>                            
            </tr> 
            <!-- <tr>
              <td>Kelompok barang</td>
              <td>:</td>
              <td><input id="kelo" name="kelo" value=""/>  </td>                            
            </tr>  -->
            <tr>
              <td>Bidang Barang</td>
              <td>:</td>
              <td><input id="bida" name="bida"/>  </td>                            
            </tr> 
                       <tr>
                            <td>Kode Barang </td>
                            <td>:</td>
                            <td><input id="kdbrg" name="kdbrg" style="width: 250px;" />
                             <input type="hidden" id="no_urut" name="no_urut" style="width: 200px;" disabled="true"/></td>
                       </tr>
                       <tr>
                         <td colspan="2">&nbsp;</td>
                         <td><span style="font-size:12px;">
                           <input name="nmbrg" type="text" id="nmbrg" style="width:250px; border:none;"  disabled="true"/>
                           <input type="hidden" id="no_reg" name="no_reg" style="width: 50px;"/>
                         </span></td>
                       </tr>
                       <tr>
                            <td>Kode Tanah </td>
                            <td>:</td>
                            <td><input type="text" name="kdtnh" type="text" id="kdtnh"  style="width:250px;"/></td>
                       </tr>
                       <tr>
                         <td colspan="2">&nbsp;</td>
                         <td><span style="font-size:12px;">
                           <input name="nmtanah" type="text" id="nmtanah" style="width:250px; border:none;"  disabled="true"/>
                         </span></td>
                       </tr>
                       <tr>
                            <td>Detail Barang</td>
                            <td>:</td>
                            <td><input id="detail_brg" name="detail_brg" style="width: 250px;" placeholder="*Diisi jika kib ada nama barang." /></td>
                       </tr>
                       <tr>
                            <td colspan="3" align="left"><u><b>Pengurus Barang</b></u></td>
                       </tr>
                       <tr>
                            <td>Kepemilikan</td>
                            <td>:</td>
                            <td><input id="milik" name="milik" style="width: 150px;" /></td>
                       </tr>
                       <tr>
                            <td>Wilayah</td>
                            <td>:</td>
                            <td><input id="wilayah" name="wilayah" style="width: 150px;"  /></td>
                       </tr>
                       <tr>
                            <td>SKPD</td>
                            <td>:</td>
                            <td><input type="text" id="skp" name="skp" style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td>UNIT</td>
                            <td>:</td>
                            <td><input type="text" id="skpd" style="width:150px;" disabled ="true"/></td>
                       </tr>
                       <tr>
                            <td colspan="2">&nbsp;<input type="text" id="kd_skpdx" style="border:none;" hidden="true"/></td>
                            <td style="font-size:12px;"><input type="text" id="nmskpd" style="width:250px; border:none;" disabled="true"/></td>
                       </tr>
                       <tr>
                            <td colspan="3" align="left"><u><b>Asal Usul Barang</b></u></td>
                       </tr>
                       <tr>
                            <td>SKPD Asal</td>
                            <td>:</td>
                            <td><input type="text" id="skpdasal" name='skpdasal' style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td>Nama SKPD</td>
                            <td>:</td>
                            <td><input type="text" id="nmskpdasal" name='nmskpdasal' style="width:150px; border:none;" disabled ="true"/></td>
                       </tr>
                       <tr>
                            <td>Cara Perolehan</td>
                            <td>:</td>
                            <td><input type="text" id="perolehan" name='perolehan' style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td>Dasar Perolehan</td>
                            <td>:</td>
                            <td><input type="text" id="dsr_peroleh" name="dsr_peroleh" style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td>&ensp;&ensp;a)&nbsp;Nomor</td>
                            <td>:</td>
                            <td><input type="text" id="no_oleh" name="no_oleh" style="width:250px;" /></td>
                       </tr>
                       <tr>
                            <td>&ensp;&ensp;b)&nbsp;Tanggal</td>
                            <td>:</td>
                            <td><input type="text" id="tgl_oleh" name="tgl_oleh" style="width:150px;" /></td>
                       </tr>
                       <tr>
                            <td>Tahun Perolehan</td>
                            <td>:</td>
                            <td><input type="text" id="th_oleh" name="th_oleh" style="width:150px;" /></td>
                       </tr>
                       <tr>
                         <td>Harga Perolehan</td>
                         <td>:</td>
                         <td><input id="hrg_oleh" name="hrg_oleh" value="" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event));"  style="width:150px;" />
                         <input name="hrg" type="hidden" id="hrg" style="width:150px;" /></td>
                       </tr>
                       <tr>
                         <td>Harga Penilaian</td>
                         <td>:</td>
                         <td><input id="nilai_baru" name="nilai_baru" value="" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event));"  style="width:150px;" />
                         </td>
                       </tr>
                       <tr>
                         <td>Jumlah</td>
                         <td>:</td>
                         <td><input name="jml" type="text" id="jml" style="width:150px;" /></td>
                       </tr>
                       <tr>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                       </tr>
                      <tr hidden="true">
                            <td colspan="3" align="left"><u><b>Penyusutan Aset</b></u></td>
                       </tr>
					   <tr hidden="true">
                         <td>Metode </td>
                         <td>:</td>
                         <td><input name="metode" type="text" id="metode" style="width:150px;"/></td>
                      </tr>
					  <tr hidden="true">
                         <td>Masa Manfaat</td>
                         <td>:</td>
                         <td><input name="masa_manfaat" type="text" id="masa_manfaat" style="width:150px;" onkeypress="return isNumberKey(event)"/></td>
                      </tr>
                      <tr hidden="true">
                         <td>Nilai Sisa</td>
                         <td>:</td>
                         <td><input name="nilai_sisa" type="text" id="nilai_sisa" style="width:150px;" onkeypress="return isNumberKey(event)"/></td>
                      </tr>
                    </table> 
               </td>
               <td width="50%" valign="top">
                    <table  align="left" style="width:100%;" border="0">
                       <tr hidden="true">
                            <td colspan="3" align="left"><u><b>Sumber Pembiayaan</b></u></td>
                       </tr >
                       <tr hidden="true">
                            <td width="30%">Jenis Dana</td>
                            <td width="5%">:</td>
                            <td width="65%"><input type="text" id="jns_dana" name="jns_dana" style="width:150px;"/>
                            <input type="text" id="sum" name="sum" style="width:100px;  border:none;"  /></td>
                       </tr>
                       <tr hidden="true">
                            <td>Tahun Anggaran</td>
                            <td>:</td>
                            <td><input type="text" id="thn_anggar" name="thn_anggar" style="width:150px;" /></td>
                       </tr>
                       <tr hidden="true">
                            <td>Bukti Pembayaran</td>
                            <td>:</td>
                            <td><input type="text" id="bkt_bayar" name="bkt_bayar" style="width:150px;"/>
                            <input type="text" id="buk" name="buk" style="width:100px;  border:none;"  /></td>
                       </tr>
					   <tr>
                            <td colspan="3" align="left"><u><b>Spesifikasi Barang</b></u></td>
                       </tr>
                       <tr>
                            <td>Kondisi</td>
                            <td>:</td>
                            <td><input type="text" id="kondisi" name="kondisi" style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td><input type="text" id="st_tanah" name="st_tanah" style="width:150px;"/></td>
                       </tr>
                       <tr>
                         <td>Konstruksi</td>
                         <td valign="top">:</td>
                         <td><input type="text" id="kontruk" name="kontruk" style="width:250px;"  placeholder="exp:aspal,beton,dll"/></td>
                       </tr>
                       <tr>
                            <td>Panjang</td>
                            <td>:</td>
                            <td><input type="text" id="panjang" style="width:100px;" onkeypress="return isNumberKey(event)"/>
							M</td>
                       </tr>
                       <tr>
                            <td>Luas</td>
                            <td>:</td>
                            <td><input type="text" id="luas" style="width:100px;" onkeypress="return isNumberKey(event)"/>
                            M2</td>
                       </tr>
                       <tr>
                            <td>Lebar </td>
                            <td>:</td>
                            <td><input type="text" id="lebar" style="width:100px;" onkeypress="return isNumberKey(event)"/>
							M</td>
                       </tr>
                       <tr>
                         <td>Penggunaan</td>
                         <td>&nbsp;</td>
                         <td><input name="guna" type="text" id="guna" style="width:250px;"/></td>
                       </tr>
                       <tr hidden="true">
                         <td>Dasar Penggunaan </td>
                         <td>&nbsp;</td>
                         <td><input name="dsr_guna" type="text" id="dsr_guna" style="width:150px;"/></td>
                       </tr>
                       <tr hidden="true">
                         <td>Nomor SK </td>
                         <td>&nbsp;</td>
                         <td><input name="no_sk" type="text" id="no_sk" style="width:150px;"/></td>
                       </tr>
                       <tr hidden="true">
                         <td>Tgl SK </td>
                         <td>&nbsp;</td>
                         <td><input name="tgl_sk" type="text" id="tgl_sk" style="width:150px;"/></td>
                       </tr>
                       <tr hidden="true">
                            <td>Lokasi</td>
                            <td>:</td>
                            <td><input name="lokasi" type="text" id="lokasi" style="width:150px;"/></td>
                       </tr>
                       <tr>
                            <td rowspan="3" valign="top">Letak/Alamat</td>
                            <td>:</td>
                            <td><input type="text" id="alamat1" style="width:250px;"/></td>
                       </tr>
                       <tr>
                            <td>:</td>
                            <td><input type="text" id="alamat2" style="width:250px;"/></td>
                       </tr>
                       <tr>
                            <td>:</td>
                            <td><input type="text" id="alamat3" style="width:250px;"/></td>
                       </tr>
                       <tr>
                            <td valign="top">Keterangan</td>
                            <td valign="top">:</td>
                            <td><textarea rows="2" cols="50" id="keterangan" name="keterangan" style="width:250px;"></textarea></td>
                       </tr>
                       <!--<tr>
                            <td>Latitude</td>
                            <td>:</td>
                            <td><input type="lat" id="lat" style="width:100px;" placeholder="exp:-11.1111"/></td>
                       </tr>
                       <tr>
                            <td>Longtitude</td>
                            <td>:</td>
                            <td><input type="lon" id="lon" style="width:100px;" placeholder="exp:11.1111"/></td>
                       </tr>-->
                        <tr>
                <td colspan="2" hidden align="center">
                    <table  align="left" style="width:100%;" border="1">
                        <tr>
                            <td valign="top" width="14%" height="12.5px">Gambar</td>
                            <td valign="top" width="3%">:</td>
                            <td valign="top" width="58%"><input type="text" id="gambar1" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload1" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('1');"  /></td>
                            <td  width="25%" rowspan="5">
                                <table  align="left" style="width:100%;height: 100%;" border="0">
                                    <tr>
                                        <td width="50%" height="50px" align="center"><img style="width: 100px; height:100px;" id="foto1" alt="" onclick="javascript:gambar('1');"/>
                                        </td>                                        
                                        <td width="50%" align="center">
                                         <img style="width: 100px; height:100px;" id="foto2" alt="" onclick="javascript:gambar('2');"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="50px" align="center"><img style="width: 100px; height:100px;" id="foto3" alt="" onclick="javascript:gambar('3');"/></td>
                                        <td align="center"><img style="width: 100px; height:100px;" id="foto4" alt="" onclick="javascript:gambar('4');"/></td>
                                    </tr>
                                </table>
                            </td>   
                            
                           
                       </tr>
                       <tr>
                            <td valign="top" height="12.5px">&nbsp;</td>
                            <td valign="top">:</td>
                            <td valign="top"><input type="text" id="gambar2" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload2" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('2');"  /></td>
                           
                       </tr>
                       <tr>
                            <td valign="top" height="12.5px">&nbsp;</td>
                            <td valign="top" >:</td>
                            <td valign="top"><input type="text" id="gambar3" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload3" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('3');"  /></td>
                           
                       </tr>
                       <tr>
                            <td valign="top" height="12.5px">&nbsp;</td>
                            <td valign="top">:</td>
                            <td valign="top"><input type="text" id="gambar4" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload4" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('4');"  /></td>
                       </tr>
                    </table>
                </td>
           </tr>
           <tr>
                <td colspan="2">&nbsp;</td>
           </tr>

                    </table>
               
               </td>
           </tr>
           <tr>
                <td colspan="2" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                
                </td>                
            </tr>
        </table>  
            
    </fieldset> 
</div>

<div id="dialog-modal_gambar" title="">
    <p class="validateTips">GAMBAR/FOTO</p> 
     <table align="center" style="width:100%;" border="0">
        <tr>
			<td>
			 <img style="width: 450px; height:350px;" id="fotoZ" alt="some_text"/>
			</td>
		</tr>       
     </table>
           <tr>
                <td  align="center">
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar1();">Kembali</a>
                </td>                
           </tr>
</div>

<div id="dialog-riwayat" title="">
    <fieldset>
		<p id="p1" style="font-size: x-large;color: red;"></p>
     <table align="center" style="width:100%;" border="0">
               <td width="50%" valign="top">
                    <table align="left" style="width:100%;" border="0">
                       <tr hidden="true">
                            <td>ID Barang</td>
                            <td>:</td>
                            <td><input type="text" id="id_barang" name="id_barang" style="width: 250px;" /></td>
                       </tr>
					   <tr hidden="true">
                            <td>ID tahun</td>
                            <td>:</td>
                            <td><input type="text" id="tahun" name="tahun" style="width: 250px;" /></td>
                       </tr> <tr hidden="true">
                            <td>ID kd_unit</td>
                            <td>:</td>
                            <td><input type="text" id="kd_unit" name="kd_unit" style="width: 250px;" /></td>
                       </tr> <tr hidden="true">
                            <td>ID no_urut</td>
                            <td>:</td>
                            <td><input type="text" id="no_urut" name="no_urut" style="width: 250px;" /></td>
                       </tr>
					    <tr hidden="true">
                            <td>ID kd_brg</td>
                            <td>:</td>
                            <td><input readonly="true" border="0" type="text" id="kd_brg" name="kd_brg" style="width: 350px;" /><input type="text" id="kd_brg" name="kd_brg" style="width: 250px;" /></td>
                       </tr>
                       <tr>
                            <td>.:TAMBAH RIWAYAT</td>
                            <td>:</td>
                            <td><b>
							<input readonly="true" type="text" id="nm_brg" name="nm_brg" style="width:400px;  border:none;"  />
							</b></td>
                       </tr>
                       <tr disabled="true">
                            <td>SKPD</td>
                            <td>:</td>
                            <td><input readonly="true" type="text" id="kd_skpd" name="kd_skpd" style="width: 100px;" />
							<input readonly="true" type="text" id="nm_skpd" name="nm_skpd" style="width: 350px;" /></td>
                       </tr>
                       <tr disabled="true">
                            <td>Nilai</td>
                            <td>:</td>
                            <td><input disabled="true" type="text" id="nilai" name="nilai" style="width: 150px;" /></td>
                       </tr>
                       <tr>
                            <td>Tanggal Riwayat</td>
                            <td>:</td>
                            <td><input type="text" id="tgl_riwayat" name="tgl_riwayat" style="width: 100px;" /></td>
                       </tr>
                       <tr>
                            <td>Riwayat</td>
                            <td>:</td>
                            <td><input type="text" id="kd_riwayat" name="kd_riwayat" style="width: 250px;" /></td>
                       </tr>
                       <tr>
                            <td>Detail Riwayat</td>
                            <td>:</td>
                            <td><textarea placeholder="*silahkan input detail riwayat barang" type="text" id="detail_riwayat" name="detail_riwayat" style="width: 500px; height:70px;"></textarea></td>
                       </tr>
                    </table> 
               </td>
		   <tr>
                <td colspan="2" align="center">
				<a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_riwayat();">Simpan</a>                
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
				</td>                
           </tr>
        </table>  
    </fieldset> 
</div>