
  	
<script type="text/javascript">
    
    var kdkel		= '';
    var judul		= '';
    var cid 		= 0;
    var lcidx 		= 0;
    var lcstatus 	= '';
    var lcskpd 		= '';
    var lpdok 		= '';
    var no_urut		=0;
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 530,
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
     
     $(function(){
      
      $('#tanggal').datebox({  
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
        
       $('#tglstnk').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
                //ctgl = y+'-'+m+'-'+d;
            	return y+'-'+m+'-'+d;
            }
        });
      
      $('#tglbpkb').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
                //ctgl = y+'-'+m+'-'+d;
            	return y+'-'+m+'-'+d;
            }
        });
        
      $('#nodok').combogrid({  
       panelWidth:500,  
       idField:'no_dokumen',  
       textField:'no_dokumen',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/ambil_dok_b',  
       columns:[[  
           {field:'no_dokumen',title:'NOMOR DOKUMEN',width:400},  
           {field:'nilai_kontrak',title:'NILAI KONTRAK',width:100}    
       ]],  
       onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           nodok 		= rowData.no_dokumen;
           tanggal 		= rowData.tanggal;
           milik 		= rowData.kd_milik;
           wilayah		= rowData.kd_wilayah;
           skpd 		= rowData.kd_unit;
           dsr_peroleh 	= rowData.b_dasar;
           no_oleh		= rowData.b_nomor;
           tgl_oleh 	= rowData.b_tanggal;
           th_oleh 		= rowData.b_tahun;
           jns_dana 	= rowData.s_dana;
           bkt_bayar	= rowData.s_ang;
           thn_anggar 	= rowData.tahun;
           get(tanggal,milik,wilayah,skpd,dsr_peroleh,no_oleh,tgl_oleh,th_oleh,jns_dana,bkt_bayar,thn_anggar);

            disable();
           $('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/brg_msn',queryParams:({nodok:nodok}) });
          // }                 
       }  
     });
        
     $('#kdbrg').combogrid({  
           panelWidth:600,  
           idField:'kd_brg',  
           textField:'kd_brg',  
           mode:'remote',                      
           columns:[[
               {field:'no_dokumen',title:'Dokumen',width:100},  
               {field:'kd_brg',title:'Kode Barang',width:100},  
               {field:'nm_brg',title:'Nama Barang',width:400}               
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               brg = rowData.kd_brg;
               invent=rowData.invent;
               no_urut=rowData.no_urut;
               $("#nmbrg").attr("value",rowData.nm_brg);
               $("#hrg_oleh").attr("value",number_format(rowData.total,2,',','.'));
               $("#hrg").attr("value",rowData.total);
               $("#jml").attr("value",rowData.jml);
               //$("#no_urut").attr("value",rowData.no_urut);
               //$("#keterangan").attr("value",rowData.keterangan);
               nomer_akhir();
               tombol(invent);                                                                  
           }  
        });   
     
     $('#kdtnh').combogrid({  
       panelWidth:500,  
       idField:'kd_brg',  
       textField:'kd_brg',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/mtanah',  
       columns:[[ 
           {field:'kd_brg',title:'Kode Tanah',width:100},  
           {field:'nm_brg',title:'Nama Tanah',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           kd_tanah = rowData.kd_brg;
           $("#nmtanah").attr("value",rowData.nm_brg); 
          // }                 
       }  
     });
     
      $('#gambar').combogrid({  
       panelWidth:200,  
       idField:'nm',  
       textField:'nm',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/scan',  
       columns:[[  
           {field:'nm',title:'Nama Foto',width:200}   
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
       idField:'kd_milik',  
       textField:'nm_milik',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_milik',  
       columns:[[  
           {field:'kd_milik',title:'KODE MILIK',width:100},  
           {field:'nm_milik',title:'KEPEMILIKAN',width:400}    
       ]] 
     });
     
     $('#warna').combogrid({  
       panelWidth:300,  
       idField:'kd_warna',  
       textField:'nm_warna',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_warna',  
       columns:[[  
           {field:'kd_warna',title:'KODE WARNA',width:100},  
           {field:'nm_warna',title:'WARNA',width:200}    
       ]] 
     });
     
     $('#bahan').combogrid({  
       panelWidth:300,  
       idField:'kd_bahan',  
       textField:'nm_bahan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_bahan',  
       columns:[[  
           {field:'kd_bahan',title:'KODE BAHAN',width:100},  
           {field:'nm_bahan',title:'BAHAN',width:200}    
       ]] 
     });
     
     $('#satuan').combogrid({  
       panelWidth:300,  
       idField:'kd_satuan',  
       textField:'nm_satuan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_satuan',  
       columns:[[  
           {field:'kd_satuan',title:'KODE SATUAN',width:100},  
           {field:'nm_satuan',title:'SATUAN',width:200}    
       ]] 
     });
     
      $('#lokasi').combogrid({  
       panelWidth:500,  
       idField:'kd_lokasi',  
       textField:'nm_lokasi',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_lokasi',  
       columns:[[  
           {field:'kd_lokasi',title:'KODE LOKASI',width:100},  
           {field:'nm_lokasi',title:'LOKASI',width:400}    
       ]] 
     });
     
     $('#ruang').combogrid({  
       panelWidth:500,  
       idField:'kd_ruang',  
       textField:'nm_ruang',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_ruang',  
       columns:[[  
           {field:'kd_ruang',title:'KODE RUANGAN',width:100},  
           {field:'nm_ruang',title:'RUANGAN',width:400}    
       ]] 
     });
     
     $('#wilayah').combogrid({  
       panelWidth:500,  
       idField:'kd_wilayah',  
       textField:'nm_wilayah',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_wilayah',  
       columns:[[  
           {field:'kd_wilayah',title:'KODE WILAYAH',width:100},  
           {field:'nm_wilayah',title:'WILAYAH',width:400}    
       ]] 
     });
     
     $('#skpd').combogrid({  
       panelWidth:500,  
       idField:'kd_uskpd',  
       textField:'kd_uskpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',  
       columns:[[  
           {field:'kd_uskpd',title:'KODE SKPD',width:100},  
           {field:'nm_uskpd',title:'Nama SKPD',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lcskpd = rowData.kd_uskpd;
           $("#nmskpd").attr("value",rowData.nm_uskpd.toUpperCase());
           $('#uker').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uker2',queryParams:({kduskpd:lcskpd}) });
          // }                 
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
           {field:'nm_uker',title:'NAMA UNIT KERJA',width:400}    
       ]]
     });
     
     $('#perolehan').combogrid({  
       panelWidth:150,  
       idField:'cara_perolehan',  
       textField:'cara_perolehan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/perolehan',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'cara_perolehan',title:'Cara Perolehan',width:100}
       ]]
     });
     
     $('#dsr_peroleh').combogrid({  
       panelWidth:100,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/dasar_perolehan',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:10},
           {field:'dasar_perolehan',title:'Dasar Perolehan',width:90}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckd = rowData.kode;
           $("#das").attr("value",rowData.dasar_perolehan.toUpperCase());
                         
       }  
     });
     
     $('#jns_dana').combogrid({  
       panelWidth:100,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mdana',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:10},
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
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mbukti',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'Bukti',title:'Bukti Pembayaran',width:110}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lcbukti = rowData.kode;
           $("#buk").attr("value",rowData.Bukti.toUpperCase());
                         
       }  
     });
     
     $('#kondisi').combogrid({  
       panelWidth:150,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mkondisi',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'kondisi',title:'Kondisi',width:110}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#kon").attr("value",rowData.kondisi.toUpperCase());
                         
       }  
     });
	 
	  $('#metode').combogrid({  
       panelWidth:200,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mmetode',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'metode',title:'Metode',width:200}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#metode").attr("value",rowData.metode.toUpperCase());
                         
       }  
     });
                                   //masa_manfaat,nilai_sisa

     $('#st_tanah').combogrid({  
       panelWidth:150,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/mstatus',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'kode',title:'Kode',width:40},
           {field:'status',title:'Status',width:110}
       ]],
        onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           lckondisi = rowData.kode;
           $("#sta").attr("value",rowData.status.toUpperCase());
                         
       }  
     });
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_b_kap',
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
    		title:'No Register',
    		width:15,
            align:"left"},
            {field:'no_dokumen',
    		title:'No Dokumen',
    		width:15,
            align:"left"},
            {field:'nm_brg',
    		title:'Nama Barang',
    		width:25,
            align:"left"},
            {field:'hrg_perolehan',
    		title:'Harga Peroleh',
    		width:30,
            align:"left"},
            {field:'keterangan',
    		title:'Keterangan',
    		width:40,
            align:"left"},
			{field:'hapus',width:5,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}

        ]],
        onSelect:function(rowIndex,rowData){
          lcidx = rowIndex;
          noreg = rowData.no_reg;
          no = rowData.no;
          nodok = rowData.no_dokumen;
          kdbrg  = rowData.kd_brg;
          //get1(noreg,nodok,kdbrg);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx 			= rowIndex;
          judul 			= 'Edit '; 
          lcstatus 			= 'edit';
          noreg 			= rowData.no_reg;
          no 				= rowData.no_reg;
          tglreg 			= rowData.tgl_reg;
          nodok 			= rowData.no_dokumen;
          kdbrg  			= rowData.kd_brg;
          nilai1 			= rowData.nilai;
          jumlah1 			= rowData.jumlah;
          total1 			= rowData.total
          merek				= rowData.merek;
          tipe				= rowData.tipe;
          pabrik			= rowData.pabrik;
          kd_warna			= rowData.kd_warna;
          kd_bahan 			= rowData.kd_bahan;
          kd_satuan			= rowData.kd_satuan;
          no_rangka			= rowData.no_rangka;
          no_mesin			= rowData.no_mesin;
          no_polisi			= rowData.no_polisi;
          silinder			= rowData.silinder;
          no_stnk			= rowData.no_stnk;
          tgl_stnk			= rowData.tgl_stnk;
          no_bpkb			= rowData.no_bpkb;
          tgl_bpkb			= rowData.tgl_bpkb;
          kondisi			= rowData.kondisi;
          thn_produksi		= rowData.tahun_produksi;
          pengguna			= rowData.nip;
          dsr				= rowData.dasar;
          sk				= rowData.no_sk;
          tgl_sk			= rowData.tgl_sk;
          ket				= rowData.keterangan;
          kd_ruang			= rowData.kd_ruang;
          kd_lokasi			= rowData.kd_lokasi;
          kd_unit			= rowData.kd_unit;
          foto				= rowData.foto;
		  metode			= rowData.metode;
          hrg_perolehan		= rowData.hrg_perolehan;
          nilai_sisa		= rowData.nilai_sisa;
          tmbh_manfaat		= rowData.tmbh_manfaat;
		  get1(noreg,no,tglreg,nodok,kdbrg,nilai1,jumlah1,merek,kd_ruang,kd_lokasi,kd_unit,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,thn_produksi,pengguna,dsr,sk,tgl_sk,ket,tmbh_manfaat,nilai_sisa,foto,hrg_perolehan);
		  edit_data();   
        }

        });
       
	   $('#kib').combogrid({  
            panelWidth:870,  
            panelHeight:400, 
            //width:160, 
            idField:'no_dokumen',  
            textField:'no_dokumen',              
            mode:'remote',            
			url:'<?php echo base_url(); ?>index.php/transaksi/ambil_kib_b',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'Kode Aset',width:80,align:"center"}, 
               {field:'no_reg',title:'No Register',width:100,align:"center"}, 
               {field:'nm_brg',title:'Nama Aset',width:200,align:"left"},
               {field:'nilai',title:'Harga',width:80,align:"right"},   
               {field:'merek',title:'Merek',width:80,align:"left"},  
               {field:'no_stnk',title:'No STNK',width:60,align:"center"},  
               {field:'tahun',title:'Tahun',width:50,align:"right",align:"center"},
               {field:'dasar',title:'Dasar',width:80,align:"left"},  
               {field:'keterangan',title:'Keterangan',width:150}     
            ]],  
           onSelect:function(rowIndex,rowData){
						$('#no_reg').attr('value',rowData.no_reg);
						$('#id_barang').attr('value',rowData.id_barang);
                        $('#no').attr('value',rowData.no); 
                        $('#tgl_reg').attr('value',rowData.tgl_reg);  
						$('#nm_brg').attr('value',rowData.nm_brg);
                        $('#kd_brg').attr('value',rowData.kd_brg);
                        $('#nilai').attr('value',rowData.nilai);
                        $('#jumlah').attr('value',rowData.jumlah);
                        $('#total').attr('value',rowData.total);
                        $('#merek').attr('value',rowData.merek);
                        $('#tipe').attr('value',rowData.tipe);
                        $('#pabrik').attr('value',rowData.pabrik);
                        $('#kd_warna').attr('value',rowData.kd_warna);
                        $('#kd_bahan').attr('value',rowData.kd_bahan);
                        $('#kd_satuan').attr('value',rowData.kd_satuan);
                        $('#no_rangka').attr('value',rowData.no_rangka);
                        $('#no_mesin').attr('value',rowData.no_mesin);
                        $('#no_polisi').attr('value',rowData.no_polisi);
                        $('#silinder').attr('value',rowData.silinder);
                        $('#no_stnk').attr('value',rowData.no_stnk);
                        $('#tgl_stnk').attr('value',rowData.tgl_stnk);
                        $('#no_bpkb').attr('value',rowData.no_bpkb);
                        $('#tgl_bpkb').attr('value',rowData.tgl_bpkb);
                        $('#kondisi').attr('value',rowData.kondisi);
                        $('#tahun_produksi').attr('value',rowData.tahun_produksi);
                        $('#nip').attr('value',rowData.nip);
                        $('#dasar').attr('value',rowData.dasar);
                        $('#no_sk').attr('value',rowData.no_sk);
                        $('#tgl_sk').attr('value',rowData.tgl_sk);
                        $('#keterangan').attr('value',rowData.keterangan);
                        $('#tahun').attr('value',rowData.tahun); 
                        $('#tgl_sp2d').attr('value',rowData.tgl_sp2d);
                        $('#kd_ruang').attr('value',rowData.kd_ruang);
                        $('#kd_lokasi').attr('value',rowData.kd_lokasi2);
                        $('#kd_unit').attr('value',rowData.kd_unit);
                        $('#metode').attr('value',rowData.metode);
                        $('#masa_manfaat').attr('value',rowData.masa_manfaat);
                        $('#nilai_sisa').attr('value',rowData.nilai_sisa);
                        $('#foto').attr('value',rowData.foto);
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
		$("#loading")
		/*.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});*/

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
							alert(data.msg);
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
      
    function  get(tanggal,milik,wilayah,skpd,dsr_peroleh,no_oleh,tgl_oleh,th_oleh,jns_dana,bkt_bayar,thn_anggar){
           //$("#tanggal").datebox("setValue",tanggal);
           $("#milik").combogrid("setValue",milik);
           $("#wilayah").combogrid("setValue",wilayah);
           $("#skpd").combogrid("setValue",skpd);
           $("#dsr_peroleh").combogrid("setValue",dsr_peroleh);
           $("#no_oleh").attr("value",no_oleh);
           $("#tgl_oleh").attr("value",tgl_oleh);
           $("#th_oleh").attr("value",th_oleh);
           $("#jns_dana").combogrid("setValue",jns_dana);
           $("#bkt_bayar").combogrid("setValue",bkt_bayar);
           $("#thn_anggar").attr("value",thn_anggar);
           //$(".milik").combogrid.({disabled:true});
           //document.getElementById("#milik").disabled=true;
                       
    }
	
    function  
	get1(noreg,no,tglreg,nodok,kdbrg,nilai1,jumlah1,merek,kd_ruang,kd_lokasi,kd_unit,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,thn_produksi,pengguna,dsr,sk,tgl_sk,ket,tmbh_manfaat,nilai_sisa,foto,hrg_perolehan){
	
           $("#noreg").attr("value",noreg);
           $("#no").attr("value",no);
           $("#tanggal").datebox("setValue",tglreg);
           $("#dok").attr("value",nodok);
           $("#lokasi").combogrid("setValue",kd_lokasi);
           $("#ruang").combogrid("setValue",kd_ruang);
           $("#uker").combogrid("setValue",kd_unit);
           $("#kdbrg").combogrid("setValue",kdbrg);
           $("#hrg1").attr("value",number_format(nilai1,2,',','.'));
           $("#hrg2").attr("value",nilai1);
           $("#jml1").attr("value",jumlah1);
           $("#merek").attr("value",merek);
           $("#tipe").attr("value",tipe);
           $("#pabrik").attr("value",pabrik);
           $("#warna").combogrid("setValue",kd_warna);
           $("#bahan").combogrid("setValue",kd_bahan);
           $("#satuan").combogrid("setValue",kd_satuan);
           $("#norangka").attr("value",no_rangka);
           $("#nomesin").attr("value",no_mesin);
           $("#nopolisi").attr("value",no_polisi);
           $("#silinder").attr("value",silinder);
           $("#nostnk").attr("value",no_stnk);
           $("#tglstnk").datebox("setValue",tgl_stnk);
           $("#nobpkb").attr("value",no_bpkb);
           $("#tglbpkb").datebox("setValue",tgl_bpkb);
           $("#kondisi").combogrid("setValue",kondisi);
           $("#thnbuat").attr("value",thn_produksi);
           $("#guna").attr("value",pengguna);
           $("#dsr_guna").attr("value",dsr);
           $("#no_sk").attr("value",sk);
           $("#tgl_sk").datebox("setValue",tgl_sk);
           $("#keterangan").attr("value",ket);
           $("#gambar").combogrid("setValue",foto);
	       $("#metode").combogrid("setValue",metode);
		   //$("#masa_manfaat").attr("value",masa_manfaat);
		   $("#tmbh_manfaat").attr("value",tmbh_manfaat);
           $("#peroleh").attr("value",hrg_perolehan);
           //$("#foto").attr("src","<?php echo base_url(); ?>"+foto);
                
    }
     function  cokot(foto){
           
         //  test ="<?php echo base_url(); ?>"+foto
//           alert(test);
           $("#foto").attr("src","<?php echo base_url(); ?>data/"+foto);
           $("#foto1").attr("src","<?php echo base_url(); ?>data/"+foto);
    }
    function kosong(){
	
        $("#kib").combogrid("setValue",'');
        $("#nm_brg").attr("value",'');
        $("#dok").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#peroleh").attr("value",'');
        $("#tmbh_manfaat").attr("value",'');
		/*
        $("#noreg").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#nodok").combogrid("setValue",'');
        $("#kdbrg").combogrid("setValue",'');
        $("#nmbrg").attr("value",'');
        $("#milik").combogrid("setValue",'');
        $("#wilayah").combogrid("setValue",'');
        $("#skpd").combogrid("setValue",'');
        $("#nmskpd").attr("value",'');
        $("#lokasi").combogrid("setValue",'');
        $("#ruang").combogrid("setValue",'');
        $("#uker").combogrid("setValue",'');
        $("#dsr_peroleh").combogrid("setValue",'');
        $("#no_oleh").attr("value",'');
        $("#tgl_oleh").attr("value",'');
        $("#th_oleh").attr("value",'');
        $("#thn_anggar").attr("value",'');
        $("#sum").attr("value",'');
        $("#jns_dana").combogrid("setValue",'');
        $("#bkt_bayar").combogrid("setValue",'');
        $("#merek").attr("value",'');
        $("#tipe").attr("value",'');
        $("#pabrik").attr("value",'');
        $("#warna").combogrid("setValue",'');
        $("#bahan").combogrid("setValue",'');
        $("#satuan").combogrid("setValue",'');
        $("#norangka").attr("value",'');
        $("#nomesin").attr("value",'');
        $("#nopolisi").attr("value",'');
        $("#silinder").attr("value",'');
        $("#nostnk").attr("value",'');
        $("#tglstnk").datebox("setValue",'');
        $("#nobpkb").attr("value",'');
        $("#tglbpkb").datebox("setValue",'');
        $("#kondisi").combogrid("setValue",'');
        $("#thnbuat").attr("value",'');
        $("#guna").attr("value",'');
        $("#das").attr("value",'');
        $("#dsrguna").attr("value",'');
        $("#no_sk").attr("value",'');
        $("#tgl_sk").datebox("setValue",'');
        $("#keterangan").attr("value",'');
        $("#das").attr("value",'');
		$("#hrg_oleh").attr("value",'');
		$("#hrg").attr("value",'');
		$("#hrg1").attr("value",'');
		$("#hrg2").attr("value",'');
		$("#buk").attr("value",'');
		$("#jml").attr("value",'');
		$("#jml1").attr("value",'');
		$("#dsr_guna").attr("value",'');
		$("#no_urut").attr("value",'');
		$("#gambar").combogrid("setValue",'');
		$("#metode").combogrid("setValue",'');
		$("#masa_manfaat").attr("value",'');
		$("#nilai_sisa").attr("value",'');*/
		document.getElementById("p1").innerHTML="";
    }

    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_lokasi',
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
						var tgl_perolehan	= $('#tanggal').datebox('getValue');
						var peroleh			= document.getElementById('peroleh').value;
						var tmbh_manfaat	= document.getElementById('tmbh_manfaat').value;
						var no_reg			= document.getElementById('no_reg').value;
                        var no				= document.getElementById('no').value; 
                        var tgl_reg			= document.getElementById('tgl_reg').value;  
                        var id_barang		= document.getElementById('id_barang').value; 
                        var no_dokumen		= document.getElementById('dok').value;
                        var kd_brg			= document.getElementById('kd_brg').value;
                        var nilai			= document.getElementById('nilai').value;
                        var jumlah			= document.getElementById('jumlah').value;
                        var total			= document.getElementById('total').value;
                        var merek			= document.getElementById('merek').value;
                        var tipe			= document.getElementById('tipe').value;
                        var pabrik			= document.getElementById('pabrik').value;
                        var kd_warna		= document.getElementById('kd_warna').value;
                        var kd_bahan		= document.getElementById('kd_bahan').value;
						var kd_satuan		= document.getElementById('kd_satuan').value;
                        var no_rangka		= document.getElementById('no_rangka').value;
                        var no_mesin		= document.getElementById('no_mesin').value;
                        var no_polisi		= document.getElementById('no_polisi').value;
                        var silinder		= document.getElementById('silinder').value;
						var no_stnk			= document.getElementById('no_stnk').value;
                        var tgl_stnk		= document.getElementById('tgl_stnk').value;
                        var no_bpkb			= document.getElementById('no_bpkb').value;
                        var tgl_bpkb		= document.getElementById('tgl_bpkb').value;
                        var kondisi			= document.getElementById('kondisi').value;
                        var tahun_produksi	= document.getElementById('tahun_produksi').value;
                        var nip				= document.getElementById('nip').value;
                        var dasar			= document.getElementById('dasar').value;
                        var no_sk			= document.getElementById('no_sk').value;
                        var tgl_sk			= document.getElementById('tgl_sk').value;
                        var keterangan		= document.getElementById('keterangan').value;
                        var tahun			= document.getElementById('tahun').value; 
                        var tgl_sp2d		= document.getElementById('tgl_sp2d').value;
                        var kd_ruang		= document.getElementById('kd_ruang').value;
                        var kd_lokasi2		= document.getElementById('kd_lokasi2').value;
                        var kd_unit			= document.getElementById('kd_unit').value;
                        var metode			= document.getElementById('metode').value;
                        //var masa_manfaat	= document.getElementById('masa_manfaat').value;
                        var nilai_sisa		= document.getElementById('nilai_sisa').value;
                        var foto			= document.getElementById('foto1').value;
				
			lcinsert = '(no_reg,id_barang,no,tgl_reg,no_dokumen,kd_brg,nilai,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,nip,dasar,no_sk,tgl_sk,keterangan,kd_ruang,kd_lokasi2,kd_unit,tahun,tgl_sp2d,foto,tgl_perolehan,hrg_perolehan,tmbh_manfaat)';
   
			if (no_dokumen==''){
				alert('Nomor Dokumen Tidak Boleh Kosong');
				exit();
			} 
			
			if (kd_brg==''){
				alert('Kode Barang Tidak Boleh Kosong');
				exit();
			} 
			
		if(lcstatus=='tambah'){ 
		lcvalues = "('"+no_reg+"','"+id_barang+"','"+no+"','"+tgl_reg+"','"+no_dokumen+"','"+kd_brg+"','"+nilai+"','"+jumlah+"','"+total+"','"+merek+"','"+tipe+"','"+pabrik+"','"+kd_warna+"','"+kd_bahan+"','"+kd_satuan+"','"+no_rangka+"','"+no_mesin+"','"+no_polisi+"','"+silinder+"','"+no_stnk+"','"+tgl_stnk+"','"+no_bpkb+"','"+tgl_bpkb+"','"+kondisi+"','"+tahun_produksi+"','"+nip+"','"+dasar+"','"+no_sk+"','"+tgl_sk+"','"+keterangan+"','"+kd_ruang+"','"+kd_lokasi2+"','"+kd_unit+"','"+tahun+"','"+tgl_sp2d+"','"+foto+"','"+tgl_perolehan+"','"+peroleh+"','"+tmbh_manfaat+"')";
			
			$(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/transaksi/simpan_trkib_b_kap',
                    data: ({tabel:'trkib_b_kap',no:no_dokumen,kolom:lcinsert,lcvalues:lcvalues}),
                    dataType:"json"
                });
            });                

        }else{

            lcquery = " UPDATE trkib_b_kap SET merek='"+merek+"',tipe='"+tipe+"',pabrik='"+pabrik+"',kd_warna='"+warna+"',kd_bahan='"+bahan+"',kd_satuan='"+satuan+"',no_rangka='"+norangka+"',no_mesin='"+nomesin+"',no_polisi='"+nopolisi+"',silinder='"+silinder+"',no_stnk='"+nostnk+"',tgl_stnk='"+tglstnk+"',no_bpkb='"+nobpkb+"',tgl_bpkb='"+tglbpkb+"',kondisi='"+kondisi+"',tahun_produksi='"+thnbuat+"',nip='"+pengguna+"',dasar='"+dasar+"',no_sk='"+no_sk+"',tgl_sk='"+tgl_sk+"',keterangan='"+keterangan+"',nilai='"+hrg1+"',total='"+hrg1+"',foto='"+file_gbr+"',metode='"+metode+"',masa_manfaat='"+masa_manfaat+"',nilai_sisa='"+nilai_sisa+"' where no='"+no_key+"'";
			
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/transaksi/update_trkib_b_kap',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
			
        }

        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');
        
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data KIB B Kapitalisasi';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("noreg").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("noreg").disabled=true;
        document.getElementById("noreg").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     } 
     
      function keluar1(){
        //$("#dialog-modal").dialog('close');
        $("#dialog-modal_gambar").dialog('close');
     }    
    
     function hapus(){
      
		var del=confirm('Apakah Anda yakin ingin Menhapus data '+nodok+'?');
		if (del==true){     
        var urll = '<?php echo base_url(); ?>index.php/transaksi/hapus_trkib_b_kap';
        $(document).ready(function(){
         $.post(urll,({no:no,dok:nodok}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
        });    }
    } 
	
    function nomer_akhir(){
        var i = 0;
        var tabel ='trkib_b_kap'
        var kd = brg;
        var unit = skpd;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/transaksi/nomor',
            data: ({tabel:tabel,kd_brg:kd,kd_unit:unit}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    nom      = n['urut'];  
                    $("#no_urut").attr("value",nom);                              
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
    if(lcstatus=='tambah'){ 
        if (st=='1'){    
        document.getElementById("p1").innerHTML="Sudah di INVENTARISASI!!";
         } else {     
        document.getElementById("p1").innerHTML="";
         }
        }
   }
  
  function getkey(e){
	if (window.event)
		return window.event.keyCode;
	else if (e)
		return e.which;
	else
	return null;}
  function goodchars(e, goods, field){
	var key, keychar;
	key = getkey(e);
	if (key == null) return true;
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();
		goods = goods.toLowerCase();
	// check goodkeys
	if (goods.indexOf(keychar) != -1)
    return true;
	// control keys
	if ( key==null || key==0 || key==8 || key==9 || key==27 )
	return true;
	if (key == 13) {
    var i;
    for (i = 0; i < field.form.elements.length; i++)
        if (field == field.form.elements[i])
            break;
		i = (i + 1) % field.form.elements.length;
		field.form.elements[i].focus();
    return false;
    };
	return false;
	}

   </script>

<div id="content1"> 
<h3 align="center"><u><b><a>INPUTAN INVENTARIS PERALATAN DAN MESIN</a></b></u></h3>
    <div align="center">
    <p>     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();tambah();">Tambah</a></td>               
        <!--td width="10%"><a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a></td-->
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" align="center" title="LISTING INVENTARIS PERALATAN DAN MESIN" style="width:900px;height:365px;" >  
        </table>
        </td>
        </tr>
    </table>    
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">.: Kapitalisasi KIB B. Peralatan dan Mesin</p> 
    <fieldset>
    <p id="p1" style="font-size: x-large;color: red;"></p>
     <table align="center" style="width:100%;" border="0">
           <tr>
               <td width="50%" valign="top">
                    <table align="left" style="width:100%;" border="0">
					<tr>
                            <td width="20%">Nama Barang</td>
                            <td>: <input id="kib" name="kib" style="width: 200px;" /> <input id="nm_brg" name="nm_brg" style="width: 200px;" style="border:0;width: 400px;"/></td>
                            <td width="10%"></td>
                       </tr>
                       <tr>
                            <td>No Dokumen</td>
                            <td>: <input id="dok" name="dok" style="width: 200px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%">
                            <td>Tanggal Perolehan</td>
                            <td>: <input type="text" id="tanggal" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr>
                            <td>Harga Perolehan</td>
                            <td>: <input id="peroleh" name="peroleh" style="width: 200px;" style="text-align: right;"/></td>
                            <td></td>
                       </tr>
                       <tr>
                            <td>Tambahan Masa Manfaat</td>
                            <td>: <input id="tmbh_manfaat" name="tmbh_manfaat" style="width: 200px;" style="text-align: right;" onKeyPress="return goodchars(event,'1234567890',this);"/></td>
                            <td></td>
                       </tr>
					   <!--ini disengaja-->
                       <tr hidden="true">
                            <td>id_barang</td>
                            <td>: <input id="id_barang" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_reg</td>
                            <td>: <input id="no_reg" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no</td>
                            <td>: <input id="no" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tgl_reg</td>
                            <td>: <input id="tgl_reg" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_bahan</td>
                            <td>: <input id="kd_bahan" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_satuan</td>
                            <td>: <input id="kd_satuan" /></td>
                            <td></td>
                       </tr>
					   								
                       <tr hidden="true">
                            <td>no_dokumen</td>
                            <td>: <input id="no_dokumen" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_brg</td>
                            <td>: <input id="kd_brg" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>nilai</td>
                            <td>: <input id="nilai" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>jumlah</td>
                            <td>: <input id="jumlah" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>total</td>
                            <td>: <input id="total" /></td>
                            <td></td>
                       </tr>				
                       <tr hidden="true">
                            <td>merek</td>
                            <td>: <input id="merek" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tipe</td>
                            <td>: <input id="tipe" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>pabrik</td>
                            <td>: <input id="pabrik" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_warna</td>
                            <td>: <input id="kd_warna" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_rangka</td>
                            <td>: <input id="no_rangka" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_mesin</td>
                            <td>: <input id="no_mesin" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_polisi</td>
                            <td>: <input id="no_polisi" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>silinder</td>
                            <td>: <input id="silinder" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_stnk</td>
                            <td>: <input id="no_stnk" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tgl_stnk</td>
                            <td>: <input id="tgl_stnk" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_bpkb</td>
                            <td>: <input id="no_bpkb" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tgl_bpkb</td>
                            <td>: <input id="tgl_bpkb" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kondisi</td>
                            <td>: <input id="kondisi" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tahun_produksi</td>
                            <td>: <input id="tahun_produksi" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>nip</td>
                            <td>: <input id="nip" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>dasar</td>
                            <td>: <input id="dasar" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>no_sk</td>
                            <td>: <input id="no_sk" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tgl_sk</td>
                            <td>: <input id="tgl_sk" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>keterangan</td>
                            <td>: <input id="keterangan" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_ruang</td>
                            <td>: <input id="kd_ruang" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_lokasi2</td>
                            <td>: <input id="kd_lokasi2" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>metode</td>
                            <td>: <input id="metode" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>kd_unit</td>
                            <td>: <input id="kd_unit" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tahun</td>
                            <td>: <input id="tahun" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>nilai_sisa</td>
                            <td>: <input id="nilai_sisa" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>tgl_sp2d</td>
                            <td>: <input id="tgl_sp2d" /></td>
                            <td></td>
                       </tr>
                       <tr hidden="true">
                            <td>foto</td>
                            <td>: <input id="foto" /></td>
                            <td></td>
                       </tr>
                    </table> 
               </td>
           </tr>
          <tr>
                <td colspan="2" align="center">
                    <table  align="left" style="width:100%;" border="0">
                        <tr>
                            <td valign="top" width="12%">Gambar</td>
                            <td valign="top" width="3%">:</td>
                            <td width="85%"><input type="text" id="gambar1" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload1" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('1');"  /></td>
                           
                       </tr>
                       <tr>
                            <td valign="top" width="12%">&nbsp;</td>
                            <td valign="top" width="3%">:</td>
                            <td width="85%"><input type="text" id="gambar2" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload2" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('2');"  /></td>
                           
                       </tr>
                       <tr>
                            <td valign="top" width="12%">&nbsp;</td>
                            <td valign="top" width="3%">:</td>
                            <td width="85%"><input type="text" id="gambar3" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload3" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('3');"  /></td>
                           
                       </tr>
                       <tr>
                            <td valign="top" width="12%">&nbsp;</td>
                            <td valign="top" width="3%">:</td>
                            <td width="85%"><input type="text" id="gambar4" name="gambar" style="width:200px;" disabled="disabled" />
                                <img id="loading" src="<?php echo base_url();?>public/images/loading.gif" style="display:none;">
                            	<input type="file" id="fileToUpload4" name="fileToUpload"/>
                                <input type="button" id="btnupload" value="Upload" onclick="return ajaxFileUpload('4');"  /></td>
                           
                       </tr>
                       <tr>
                       <td colspan="3" align="center" >
                             <img style="width: 100px; height:100px;" id="foto1" alt="" onclick="javascript:gambar('1');"/>
                             <img style="width: 100px; height:100px;" id="foto2" alt="" onclick="javascript:gambar('2');"/>
                             <img style="width: 100px; height:100px;" id="foto3" alt="" onclick="javascript:gambar('3');"/>
                             <img style="width: 100px; height:100px;" id="foto4" alt="" onclick="javascript:gambar('4');"/>
                             </td>
                       </tr>
                    </table>
                </td>
           </tr>
           <tr>
                <td colspan="2" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>                
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                
                </td>                    
            </tr>
            
        </table>  
            
    </fieldset> 
</div>

<div id="dialog-modal_gambar" title="">
    <p class="validateTips">GAMBAR/FHOTO</p> 
   
					<table align="center" style="width:100%;" border="0">
                       <tr>
                           <!--<?php echo form_open_multipart('upload/do_upload');?>

                            <input type="file" id="file" name="file" size="20" />
                            <br />
                            <input type="submit" value="upload" />
                            <img id="fotoZ" alt="some_text"/>
                            
                            
                            </form>-->
                            <td>
                             <img style="width: 400px; height:400px;" id="fotoZ" alt="some_text"/>
                            </td>
                       </tr>
                    </table>
		<table>
			<tr>
                <td  align="center">
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar1();">Kembali</a>
                </td>                
            </tr>
        </table>  
</div>
