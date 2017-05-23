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
            height: 500,
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
        
      $('#nodok').combogrid({  
       panelWidth:500,  
       idField:'no_dokumen',  
       textField:'no_dokumen',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/transaksi/ambil_dok_a',  
       columns:[[  
           {field:'no_dokumen',title:'NOMOR DOKUMEN',width:400},  
           {field:'nilai_kontrak',title:'NILAI KONTRAK',width:100}    
       ]],  
       onSelect:function(rowIndex,rowData){
          // if (lcstatus == 'tambah'){
           nodok = rowData.no_dokumen;
           tanggal = rowData.tanggal;
           milik = rowData.kd_milik;
           wilayah= rowData.kd_wilayah;
           skpd =rowData.kd_unit;
           dsr_peroleh = rowData.b_dasar;
           no_oleh= rowData.b_nomor;
           tgl_oleh =rowData.b_tanggal;
           th_oleh =rowData.b_tahun;
           jns_dana =rowData.s_dana;
           bkt_bayar= rowData.s_ang;
           thn_anggar =rowData.tahun;
           get(tanggal,milik,wilayah,skpd,dsr_peroleh,no_oleh,tgl_oleh,th_oleh,jns_dana,bkt_bayar,thn_anggar);
           //$("#tanggal").datebox("setValue",rowData.tanggal);
//           $("#milik").combogrid("setValue",rowData.kd_milik);
//           $("#wilayah").combogrid("setValue",rowData.kd_wilayah);
//           $("#skpd").combogrid("setValue",rowData.kd_unit);
//           $("#dsr_peroleh").combogrid("setValue",rowData.b_dasar);
//           $("#no_oleh").attr("value",rowData.b_nomor);
//           $("#tgl_oleh").attr("value",rowData.b_tanggal);
//           $("#th_oleh").attr("value",rowData.b_tahun);
//           $("#jns_dana").combogrid("setValue",rowData.s_dana);
//           $("#bkt_bayar").combogrid("setValue",rowData.s_ang);
//           $("#thn_anggar").attr("value",rowData.tahun);
            disable();
           $('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/transaksi/brg_tnh',queryParams:({nodok:nodok}) });
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
               no_urut=rowData.no_urut;
               invent=rowData.invent;
               $("#nmbrg").attr("value",rowData.nm_brg);
               $("#hrg_oleh").attr("value",number_format(rowData.harga,2,',','.'));
               $("#hrg").attr("value",rowData.harga);
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
       panelWidth:200,  
       idField:'cara_perolehan',  
       textField:'cara_perolehan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/perolehan',
       loadMsg:"Tunggu Sebentar....!!",  
       columns:[[  
           {field:'cara_perolehan',title:'Cara Perolehan',width:200}
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
		url: '<?php echo base_url();?>index.php/transaksi/ambil_kib_a_kap',
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
          lcidx 	= rowIndex;
          noreg 	= rowData.no_reg;
          no 		= rowData.no;
          nodok 	= rowData.no_dokumen;
          kdbrg  	= rowData.kd_brg;
          id_barang	= rowData.id_barang;   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx 	= rowIndex;
          judul 	= 'Edit Data Lokasi'; 
          lcstatus 	= 'edit';
          id_barang	= rowData.id_barang; 
          noreg 	= rowData.no_reg;
          no 		= rowData.no;
          tgl_reg 	= rowData.tgl_reg;
          nodok 	= rowData.no_dokumen;
          kdbrg  	= rowData.kd_brg;
          sta_thn	= rowData.status_tanah;;
          ls		= rowData.luas;
          lokasi	= rowData.kd_lokasi;
          pengguna	= rowData.nip;
          sk		= rowData.no_sertifikat;
          tgl_sk 	= rowData.tgl_sertifikat;
          ket		= rowData.keterangan;
          alamat1	= rowData.alamat1;
          alamat2	= rowData.alamat3;
          alamat3	= rowData.alamat3;
          lat 		= rowData.lat;
          lon 		= rowData.lon;
          foto1		= rowData.foto1;
          hrg_perolehan		= rowData.hrg_perolehan;
          cokot(foto1,'1');
          foto2=rowData.foto2;
          cokot(foto2,'2');
          foto3=rowData.foto3;
          cokot(foto3,'3');
          foto4=rowData.foto4;
          cokot(foto4,'4');
          get1(id_barang,noreg,no,tgl_reg,nodok,kdbrg,hrg_perolehan,sta_thn,ls,pengguna,sk,tgl_sk,ket,alamat1,alamat2,alamat3,lokasi,lat,lon,foto1,foto2,foto3,foto4);
          edit_data();   
        }
        
        });
		
	$('#kib').combogrid({  
            panelWidth:870,  
            panelHeight:400, 
            //width:160, 
            idField:'kd_brg',  
            textField:'kd_brg',              
            mode:'remote',            
			url:'<?php echo base_url(); ?>index.php/transaksi/ambil_kib_a',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'Kode Aset',width:80,align:"center"},  
               {field:'nm_brg',title:'Nama Aset',width:200,align:"left"},
               {field:'no_reg',title:'No Register',width:100,align:"center"},
               {field:'tahun',title:'Tahun',width:50,align:"right",align:"center"},
               {field:'alamat1',title:'Alamat',width:50,align:"right",align:"center"},
               {field:'nilai',title:'Harga',width:80,align:"right"},   
               {field:'penggunaan',title:'Penggunaan',width:80,align:"left"},
               {field:'luas',title:'Luas(M2)',width:60,align:"center"},    
               {field:'keterangan',title:'Keterangan',width:150}     
            ]],  
             onSelect:function(rowIndex,rowData){
						$('#no_reg').attr('value',rowData.no_reg);
                        $('#no').attr('value',rowData.no);
                        $('#tgl_reg').attr('value',rowData.tgl_reg); 
                        $('#id_barang').attr('value',rowData.id_barang);  
                        $('#kd_brg').attr('value',rowData.kd_brg);
						$('#nm_brg').attr('value',rowData.nm_brg);
                        $('#status_tanah').attr('value',rowData.status_tanah);
                        $('#no_sertifikat').attr('value',rowData.no_sertifikat);
                        $('#tgl_sertifikat').attr('value',rowData.tgl_sertifikat);
                        $('#luas').attr('value',rowData.luas);
                        $('#nilai').attr('value',rowData.nilai);
                        $('#penggunaan').attr('value',rowData.penggunaan);
                        $('#alamat1').attr('value',rowData.alamat1);
                        $('#alamat2').attr('value',rowData.alamat2);
                        $('#alamat3').attr('value',rowData.alamat3);
                        $('#no_mutasi').attr('value',rowData.no_mutasi);
                        $('#no_pindah').attr('value',rowData.no_pindah);
                        $('#no_hapus').attr('value',rowData.no_hapus);
                        $('#keterangan').attr('value',rowData.keterangan);
                        $('#kd_lokasi').attr('value',rowData.kd_lokasi2);
                        $('#kd_skpd').attr('value',rowData.kd_skpd); 
                        $('#kd_unit').attr('value',rowData.kd_unit);
                        $('#tahun').attr('value',rowData.tahun); 
                        $('#tgl_sp2d').attr('value',rowData.tgl_sp2d);
                        $('#nm_uskpd').attr('value',rowData.nm_uskpd);
                        $('#lat').attr('value',rowData.lat);
                        $('#lon').attr('value',rowData.lon);
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
    
    function  get1(id_barang,noreg,no,tgl_reg,nodok,hrg_perolehan,kdbrg,sta_thn,ls,pengguna,sk,tgl_sk,ket,alamat1,alamat2,alamat3,lokasi,foto){
           $("#noreg").attr("value",noreg);
           $("#id_barang").attr("value",id_barang);
		   $("#no").attr("value",no);
           $("#tanggal").datebox("setValue",tgl_reg);
           $("#dok").attr("value",nodok);
           $("#kdbrg").combogrid("setValue",kdbrg);
           $('#st_tanah').combogrid("setValue",sta_thn);
           $("#luas").attr("value",ls);
           $("#guna").attr("value",pengguna);
           $("#no_sk").attr("value",sk);
           $("#tgl_sk").datebox("setValue",tgl_sk);
           $("#lokasi").combogrid("setValue",lokasi);
           $("#keterangan").attr("value",ket);
           $("#lat").attr("value",lat);
           $("#lon").attr("value",lon);
           $("#alamat1").attr("value",alamat1);
           $("#alamat2").attr("value",alamat2);
           $("#alamat3").attr("value",alamat3);
           $("#gambar1").attr("value",foto1);
           $("#gambar2").attr("value",foto2);
           $("#gambar3").attr("value",foto3);
           $("#gambar4").attr("value",foto4);
           //$("#gambar").combogrid("setValue",foto);
           //$("#foto").attr("src","<?php echo base_url(); ?>"+foto);
           
                              
    }
    
    function  cokot(foto,lc){
           
         //  test ="<?php echo base_url(); ?>"+foto
//           alert(test);
         var lcfoto = 'foto'+lc;
         //document.getElementById("img1").src="new url";
         //alert(lcfoto+'/'+foto);
         document.getElementById(lcfoto).src = "<?php echo base_url(); ?>data/"+foto;
          // $("#foto").attr("src","<?php echo base_url(); ?>data/"+foto);
          // $("#foto1").attr("src","<?php echo base_url(); ?>data/"+foto);
           
                              
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
        $("#milik").combogrid("setValue",'');
        $("#wilayah").combogrid("setValue",'');
        $("#skpd").combogrid("setValue",'');
        $("#dsr_peroleh").combogrid("setValue",'');
        $("#no_oleh").attr("value",'');
        $("#tgl_oleh").attr("value",'');
        $("#th_oleh").attr("value",'');
        $("#jns_dana").combogrid("setValue",'');
        $("#bkt_bayar").combogrid("setValue",'');
        $("#thn_anggar").attr("value",'');
        $("#nodok").combogrid("setValue",'');
       $("#kdbrg").combogrid("setValue",'');
       $('#kdtnh').combogrid("setValue",'');
       $('#kondisi').combogrid("setValue",'');
       $('#st_tanah').combogrid("setValue",'');
       $("#kontruksi").attr("value",'');
       $("#panjang").attr("value",'');
       $("#luas").attr("value",'');
       $("#lebar").attr("value",'');
       $("#guna").attr("value",'');
       $("#dsr_guna").attr("value",'');
       $("#no_sk").attr("value",'');
       $("#tgl_sk").datebox("setValue",'');
       $("#keterangan").attr("value",'');
       $("#alamat1").attr("value",'');
       $("#alamat2").attr("value",'');
       $("#alamat3").attr("value",'');
       $("#nmbrg").attr("value",'');
       $("#nmtanah").attr("value",'');
       $("#nmskpd").attr("value",'');
       $("#das").attr("value",'');
       $("#hrg_oleh").attr("value",'');
       $("#hrg").attr("value",'');
       $("#buk").attr("value",'');
       $("#jml").attr("value",'');
       $("#sum").attr("value",'');
       $("#giat").attr("value",'');
       $("#kon").attr("value",'');
       $("#sta").attr("value",'');
       $("#no_urut").attr("value",'');
       $("#skpd").combogrid("setValue",'');
       $("#lat").attr("value",'');
       $("#lon").attr("value",'');
       $("#gambar1").attr("value",'');
       $("#gambar2").attr("value",'');
       $("#gambar3").attr("value",'');
       $("#gambar4").attr("value",'');
       $("#fileToUpload1").attr("value",'');
       $("#fileToUpload2").attr("value",'');
       $("#fileToUpload3").attr("value",'');
       $("#fileToUpload4").attr("value",'');
       cokot('','1');
       cokot('','2');
       cokot('','3');
       cokot('','4');
       // $("#gambar").combogrid("setValue",'');*/
       document.getElementById("p1").innerHTML="";
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
     $('#dg').edatagrid({
		url: '<?php echo base_url();?>index.php/master/load_lokasi',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function gambar(lf){
        
        lcfoto = 'foto'+lf;
        //alert('sdsdsd');
        //var a = document.getElementById(lcfoto).src;
        //alert(a);
        document.getElementById("fotoZ").src =  document.getElementById(lcfoto).src;
        $("#dialog-modal_gambar").dialog('open');             
    }
    
    function simpan(){
		var no_dokumen		= document.getElementById('dok').value;
        var tgl_perolehan	= $('#tanggal').datebox('getValue');
		var hrg_perolehan	= document.getElementById('peroleh').value;
		var no_reg			= document.getElementById('no_reg').value;
		var id_barang		= document.getElementById('id_barang').value;
		var no				= document.getElementById('no').value;
		var tgl_reg			= document.getElementById('tgl_reg').value;  
		var kd_brg			= document.getElementById('kd_brg').value;
		var status_tanah	= document.getElementById('status_tanah').value;
		var no_sertifikat	= document.getElementById('no_sertifikat').value;
		var tgl_sertifikat	= document.getElementById('tgl_sertifikat').value;
		var luas			= document.getElementById('luas').value;
		var nilai			= document.getElementById('nilai').value;
		var penggunaan		= document.getElementById('penggunaan').value;
		var alamat1			= document.getElementById('alamat1').value;
		var alamat2			= document.getElementById('alamat2').value;
		var alamat3			= document.getElementById('alamat3').value;
		var no_mutasi		= document.getElementById('no_mutasi').value;
		var no_pindah		= document.getElementById('no_pindah').value;
		var no_hapus		= document.getElementById('no_hapus').value;
		var keterangan		= document.getElementById('keterangan').value;
		var kd_lokasi2		= document.getElementById('kd_lokasi2').value;
		var kd_skpd			= document.getElementById('kd_skpd').value; 
		var kd_unit			= document.getElementById('kd_unit').value;
		var tahun			= document.getElementById('tahun').value; 
		var tgl_sp2d		= document.getElementById('tgl_sp2d').value;
		var lat				= document.getElementById('lat').value;
		var lon				= document.getElementById('lon').value;
		var foto1			= document.getElementById('foto1').value;
		var foto2			= document.getElementById('foto2').value;
		var foto3			= document.getElementById('foto3').value;
		var foto4			= document.getElementById('foto4').value;
		var no_urut			= document.getElementById('no_urut').value;

		lcinsert = "(no_reg,id_barang,no,tgl_reg,no_dokumen,kd_brg,status_tanah,no_sertifikat,tgl_sertifikat,luas,nilai,penggunaan,alamat1,alamat2,alamat3,no_mutasi,no_pindah,no_hapus,keterangan,kd_unit,tgl_update,tahun,foto1,foto2,foto3,foto4,kd_lokasi2,no_urut,lat,lon,tgl_perolehan,hrg_perolehan)";
       // if (no_dokumen==''){
         //   alert('Nomor Dokumen Tidak Boleh Kosong');
         //   exit();
        //} 
        
        if (hrg_perolehan==''){
            alert('Harga Tidak Boleh Kosong');
            exit();
        } 
        
        if(lcstatus=='tambah'){ 
        //lcvalues = '';
				lcvalues = "('"+no_reg+"','"+id_barang+"','"+no+"','"+tgl_reg+"','"+no_dokumen+"','"+kd_brg+"','"+status_tanah+"','"+no_sertifikat+"','"+tgl_sertifikat+"','"+luas+"','"+nilai+"','"+penggunaan+"','"+alamat1+"','"+alamat2+"','"+alamat3+"','"+no_mutasi+"','"+no_pindah+"','"+no_hapus+"','"+keterangan+"','"+kd_unit+"','"+tgl_update+"','"+tahun+"','"+foto1+"','"+foto2+"','"+foto3+"','"+foto4+"','"+kd_lokasi2+"','"+no_urut+"','"+lat+"','"+lon+"','"+tgl_perolehan+"','"+hrg_perolehan+"')";
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/transaksi/simpan_trkib_a_kap',
                    data: ({tabel:'trkib_a_kap',no:no_dokumen,lkd_brg:kd_brg,kolom:lcinsert,lcvalues:lcvalues}),
                    dataType:"json"
                });
            });    
        }
		else {
            lcquery ="update trkib_a_kap set status_tanah='"+st_tanah+"',no_sertifikat='"+no_sertifikat+"',tgl_sertifikat='"+tgl_sertifikat+"',luas='"+luas+"',nilai='"+nilai+"',penggunaan='"+penguna+"',alamat1='"+alamat1+"',alamat2='"+alamat2+"',alamat3='"+alamat3+"',keterangan='"+keterangan+"',kd_unit='"+kd_unit+"',tgl_update='<?php echo date('y-m-d H:i:s'); ?>',tahun='"+tahun+"',foto1='"+file_gbr1+"',foto2='"+file_gbr2+"',foto3='"+file_gbr3+"',foto4='"+file_gbr4+"',kd_lokasi2='"+lokasi+"',lat='"+clat+"',lon='"+clon+"' where no ='"+no+"'";
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/transaksi/update_trkib_a_kap',
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
        judul = 'Edit Data Lokasi';
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
        document.getElementById("noreg").disabled=false;
        document.getElementById("noreg").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        $("#dialog-modal_gambar").dialog('close');
     }
     function keluar1(){
        //$("#dialog-modal").dialog('close');
        $("#dialog-modal_gambar").dialog('close');
     } 
     
     function hapus(){
		var del=confirm('Apakah Anda yakin ingin Menhapus data '+id_barang+'?');
		if (del==true){
        var urll = '<?php echo base_url(); ?>index.php/transaksi/hapus_trkib_a_kap';
        $(document).ready(function(){
         $.post(urll,({no:id_barang,dok:nodok}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                //$('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                $('#dg').edatagrid('reload')
                //$('#trh').edatagrid('reload');   
                exit();
            }
         });
        });    }
    } 
    
    function nomer_akhir(){
        var i = 0;
        var tabel ='trkib_a_kap'
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
    
    function upload(){
        //alert(noreg);
        //var file_gbr = document.getElementById('userfile').value;
       //alert(file_gbr);       
        var urll = '<?php echo base_url(); ?>index.php/upload/do_upload';
        $(document).ready(function(){
         $.post(urll,function(data){
            status = data;            
         });
        });    
    } 
    
    function tambah_urut(angka,panjang){
        no=((angka)*1)+1;
        a=no.toString();
        jnol=panjang-a.length;
        //alert(a.length);
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
   
   </script>
    
<div id="content1"> 
<h3 align="center"><u><b><a>INPUTAN INVENTARIS TANAH</a></b></u></h3>
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
        <table id="dg" align="center" title="LISTING INVENTARIS TANAH" style="width:900px;height:365px;" >  
        </table>
        </td>
        </tr>
    </table>    
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">.: Kapitalisasi KIB A. Tanah</p>
     
    <fieldset>
    <p id="p1" style="font-size: x-large;color: red;"></p>
     <table align="center" style="width:100%;" border="0">
           <tr>
               <td width="50%" valign="top">
                    <table align="left" style="width:100%;" border="0">
                       <tr>
                            <td width="20%">Nama Barang</td>
                            <td>: <input id="kib" name="kib" style="width: 200px;" /> <input disabled="true" id="nm_brg" name="nm_brg" style="width: 200px;" style="border:0;width: 400px;"/></td>
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
					   <!--INI SENGAJA DI HIDE-->
                       <tr width="10%" hidden="true">
                            <td>ID Barang</td>
                            <td>: <input type="text" id="id_barang" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_reg</td>
                            <td>: <input type="text" id="no_reg" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>id_barang</td>
                            <td>: <input type="text" id="id_barang" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no</td>
                            <td>: <input type="text" id="no" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>tgl_reg</td>
                            <td>: <input type="text" id="tgl_reg" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>kd_brg</td>
                            <td>: <input type="text" id="kd_brg" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>status_tanah</td>
                            <td>: <input type="text" id="status_tanah" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_sertifikat</td>
                            <td>: <input type="text" id="no_sertifikat" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>tgl_sertifikat</td>
                            <td>: <input type="text" id="tgl_sertifikat" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>luas</td>
                            <td>: <input type="text" id="luas" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>nilai</td>
                            <td>: <input type="text" id="nilai" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>total</td>
                            <td>: <input type="text" id="total" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>penggunaan</td>
                            <td>: <input type="text" id="penggunaan" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>alamat1</td>
                            <td>: <input type="text" id="alamat1" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>alamat2</td>
                            <td>: <input type="text" id="alamat2" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>alamat3</td>
                            <td>: <input type="text" id="alamat3" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_mutasi</td>
                            <td>: <input type="text" id="no_mutasi" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_pindah</td>
                            <td>: <input type="text" id="no_pindah" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_hapus</td>
                            <td>: <input type="text" id="no_hapus" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%">
                            <td>Keterangan</td>
                            <td>: <textarea type="text" id="keterangan" style="width: 200px;"> </textarea></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>kd_lokasi2</td>
                            <td>: <input type="text" id="kd_lokasi2" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>kd_skpd</td>
                            <td>: <input type="text" id="kd_skpd" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>kd_unit</td>
                            <td>: <input type="text" id="kd_unit" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>username</td>
                            <td>: <input type="text" id="username" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>tgl_update</td>
                            <td>: <input type="text" id="tgl_update" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>tahun</td>
                            <td>: <input type="text" id="tahun" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>tgl_sp2d</td>
                            <td>: <input type="text" id="tgl_sp2d" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>no_urut</td>
                            <td>: <input type="text" id="no_urut" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>lat</td>
                            <td>: <input type="text" id="lat" style="width: 140px;" /></td>
                            <td></td>
                       </tr>
                       <tr width="10%" hidden="true">
                            <td>lon</td>
                            <td>: <input type="text" id="lon" style="width: 140px;" /></td>
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
     <table align="center" style="width:100%;" border="0">
           
                        
                       <tr>
                           <!--<?php echo form_open_multipart('upload/do_upload');?>

                            <input type="file" id="file" name="file" size="20" />
                            <br />
                            <input type="submit" value="upload" />
                            <img id="foto" alt="some_text"/>
                            
                            
                            </form>-->
                            <td align="center">
                             <img style="width: 400px; height:400px;" id="fotoZ" alt="some_text"/>
                            </td>
                       </tr>
                       
                   
           <tr>
            <td>&nbsp;
            </td>
           </tr> 
            
           <tr>
                <td  align="center">
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar1();">Kembali</a>
                </td>                
            </tr>
        </table>  
            
    
</div>


