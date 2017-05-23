<script type="text/javascript" src="<?php echo base_url(); ?>easyui/autoCurrency.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/currencyFields.js"></script>
<script src="<?php echo base_url(); ?>lib/sweet-alert.min.js"></script>
<link   rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>lib/sweet-alert.css">
<script type="text/javascript">
    
    var updt = 'f';
    var idx2 = '';
	var asal = '';
	var giat = '';
    var total2 = 0;
     $(document).ready(function() {
          $("#tabs").tabs();
          $("#dialog-modal").dialog({
            height: 700,
            width: 1000,
            modal: true, 
            background:'#2da305',           
            autoOpen:false                
          });                       
     });    
     
    $(function(){         
         $('#trh').edatagrid({
    		url: "<?php echo base_url(); ?>index.php/bhp/trh_keluarbhp",
            idField:"no_dokumen",            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
        	    {field:'no_dokumen',title:'Nomor Dokumen',width:20,align:"center"},
                {field:'tgl_dokumen',title:'Tanggal',width:30},
                {field:'nm_uskpd',title:'SKPD',width:50},
                {field:'nm_kegiatan',title:'KEGIATAN',width:80},
                {field:'penerima',title:'Penerima',width:40,align:"center"},
				{field:'hapus',width:5,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
            ]],
            onSelect:function(rowIndex,rowData){ 
                nomor   = rowData.no_dokumen;
                tgl     = rowData.tgl_dokumen;
                kode    = rowData.kd_uskpd;
                unit 	= rowData.kd_unit;
                nmkode  = rowData.nm_uskpd; 
                tahun   = rowData.tahun;
                total   = rowData.total;
                penerima = rowData.penerima;
                ruang   = rowData.ruang;
                get(nomor,tgl,kode,unit,nmkode,tahun,total,penerima,ruang);
                
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
                idx2			 	= rowIndex;
                var kode_brg 		= rowData.kode_brg;
				var detail_brg 		= rowData.detail_brg;
				var merk			= rowData.merk;
				var jumlah 			= rowData.jumlah;
				//var sisa	= rowData.sisa;
				var harga 			= rowData.harga;
				var total 			= rowData.total;
				var keterangan 		= rowData.keterangan;
				var kodegiat 		= rowData.kodegiat;
				var satuan 			= rowData.satuan;
				var sdana 			= rowData.sdana;
				var asal 			= rowData.asal;
                updt = 't';                             
				get2(kode_brg,detail_brg,merk,jumlah,harga,total,keterangan,kodegiat,satuan,sdana,asal);

            }          
        });
        
		   $('#uskpdb').combogrid({  
                    panelWidth:500,  
                    idField:'kd_skpd',  
                    textField:'kd_skpd',  
                    mode:'remote',                      
                    url:'<?php echo base_url(); ?>index.php/master/ambil_skpdsek',  
                    columns:[[  
                       {field:'kd_skpd',title:'Kode Unit',width:100},  
                       {field:'nm_skpd',title:'Nama Unit',width:400}    
                    ]],  
                    onSelect:function(rowIndex,rowData){
                       cuskpd = rowData.kd_skpd;               
                       $('#nmuskpdb').attr('value',rowData.nm_skpd);    
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
     
	 $('#kdruang').combogrid({  
           panelWidth:500,  
           idField:'kd_ruang',  
           textField:'kd_ruang',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/master/ambil_ruang',  
           columns:[[  
               {field:'kd_ruang',title:'KODE',width:70},  
               {field:'nm_ruang',title:'NAMA BIDANG/RUANGAN',width:430}    
           ]],  
           onSelect:function(rowIndex,rowData){
               nm_ruang = rowData.nm_ruang; 
               $("#nama_ruang").attr("value",rowData.nm_ruang.toUpperCase());
           }  
         });	
	 
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
               lcskpdx = rowData.kd_lokasi;  
			   $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',queryParams:({skpd:lcskpd}) });
               $('#uskpd').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uskpd',queryParams:({kduskpd:lcskpdx}) });
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#kdruang').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_ruang_bidang',queryParams:({skpd:lcskpd}) }); 
		  }  
         });
	 	 
         $('#uskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_uskpd',  
            textField:'kd_uskpd',  
            mode:'remote',                      
           // url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd',  
            columns:[[  
               {field:'kd_uskpd',title:'Kode Unit',width:100},  
               {field:'nm_uskpd',title:'Nama Unit',width:700}    
            ]],  
            onSelect:function(rowIndex,rowData){
               cuskpd = rowData.kd_uskpd;               
               $('#nmuskpd').attr('value',rowData.nm_uskpd.toUpperCase());                               
			} 
         }); 
         
        $('#tahun').combobox({           
            valueField:'tahun',  
            textField:'tahun',
            url:'<?php echo base_url(); ?>index.php/master/tahun'   
        });
		
			
	$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_giat_keluar',  
            panelWidth:600, 
            width:180, 
            idField:'kode',  
            textField:'kode',              
            mode:'remote',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kode',title:'Kode Barang',width:100},  
               {field:'nama',title:'Nama Barang',width:500}    
            ]],  
            onSelect:function(rowIndex,rowData){                                                             
                cnm = rowData.nama;                                                           
                ckd = rowData.kode;
                $('#nm_giat').attr('value',cnm);
				var cuskpd  = $('#kdskpd').combogrid('getValue'); 
				$('#kd').combogrid({url:'<?php echo base_url(); ?>index.php/bhp/ambil_brg_keluar',
				queryParams:({skpd:cuskpd,giat:ckd}) });

            } 
        });
		
		 $('#kd').combogrid({
			url:'<?php echo base_url(); ?>index.php/bhp/ambil_brg_keluar',  
            panelWidth:600, 
            width:150, 
            idField:'kd_brg',  
            textField:'kd_brg',              
            mode:'remote',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'Kode Barang',width:100},  
               {field:'nm_brg',title:'Nama Barang',width:250},      
               {field:'harga',title:'Harga',width:100,align:"right"},      
               {field:'keterangan',title:'Ket',width:150,align:"left"}    
            ]],  
            onSelect:function(rowIndex,rowData){                                                            
                ckd = rowData.kd_brg;                                                                     
                cnm = rowData.nm_brg;                                                                    
                chrg = rowData.harga;                                                            
                cst = rowData.jumlah-rowData.sisa;   
                $('#nm').attr('value',cnm);
                $('#satuan').attr('value',rowData.satuan);
                $('#spek').attr('value',rowData.spek);
                //$('#stock').attr('value',cst);
                $('#hrg').attr('value',number_format(rowData.harga));
                $('#tot').attr('value',number_format(rowData.total));
                $('#ket').attr('value',rowData.keterangan);
                $('#merek').attr('value',rowData.merk);
                //$('#sisa_stock').attr('value',rowData.sisa);
                $('#koderek').attr('value',rowData.koderek);
                $('#cad').attr('value',rowData.cad);
                $('#dana').attr('value',rowData.sdana);
                $('#asal').attr('value',rowData.asal);
                $('#tgl_dokumen').attr('value',rowData.tgl_dokumen);
                $('#jml_ambil').attr('value','');
                $('#jml_ambil').focus();
				jml_stock(ckd,chrg);
            } 
        }); 
		
	function jml_stock(ckd,chrg){  
	var kd_brg	   = ckd;
	var harga	   = chrg;
	var organisasi = $('#kdskpd').combogrid('getValue'); 
	var giat 	   = $('#giat').combogrid('getValue');
		$.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/bhp/ambil_stock_brg',
            data: ({skpd:organisasi,table:'thistory_bhp',kd_brg:kd_brg,giat:giat,harga:harga}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    stock      = n['stock']; 
					$("#stock").attr("value",stock);
                });
            }
        }); 
	 }
		
		$("#div_stock").hide();
	}); 
	//sampai sini batas system
	
	
    function section1(){        
        $('#tabs1').click(); 
        set_grid(); 
        $('#trh').datagrid('reload');                                                    
    }
    function section2(){            
        $('#tabs2').click();
        load_detail();
        set_grid();                                                        
    }
    function get(nomor,tgl,kode,unit,nmkode,tahun,total,penerima,ruang){
        $('#nomor').attr('value',nomor);
        $('#tanggal').datebox('setValue',tgl);
        $('#kdskpd').combogrid('setValue',kode);
        $('#uskpd').combogrid('setValue',unit);
        //$('#nmuskpd').attr('value',nmkode);
        $('#tahun').combobox('setValue',tahun);
        $('#total').attr('value',number_format(total,2,'.',','));
        $('#total2').attr('value',number_format(total,2,'.',','));
        $('#penerima').attr('value',penerima);
        $('#kdruang').combogrid('setValue',ruang);
        $('#nomor').attr('disabled',false);
    }
	
	function get_edit(no_dokumen,kode_brg,detail_brg,merk,jumlah,harga,total,keterangan,kodegiat){
		$('#').attr('value',no_dokumen);    
		$('#').attr('value',kode_brg);    
		$('#').attr('value',detail_brg);     
		$('#').attr('value',merk);   
		$('#').attr('value',jumlah);    
		$('#').attr('value',harga);    
		$('#').attr('value',harga);    
		$('#').attr('value',total);    
		$('#').attr('value',keterangan);  
		$('#').attr('value',kodegiat);
	}
	
    function kosong(){
		cdate 	  = '<?php echo date("Y-m-d"); ?>';
        //cthn = '<?php echo date("Y"); ?>'; 
		var skpd  = '<?php echo ($this->session->userdata('skpd')); ?>'; 
		var uskpd = '<?php echo ($this->session->userdata('unit_skpd')); ?>'; 
		var cthn  = '<?php echo ($this->session->userdata('ta_simbakda')); ?>';
        $('#nomor').attr('value','');
        $('#tanggal').datebox('setValue',cdate);
        $('#kdskpd').combogrid('setValue',skpd);
        $('#uskpd').combogrid('setValue',uskpd);
        $('#giat').combogrid('setValue','');
        $('#nm_giat').attr('value','');
        $('#penerima').attr('value','');
        $('#tahun').combobox('setValue',cthn);
        $('#total').attr('value','');
        $('#total2').attr('value','');
        $('#nomor').attr('disabled',false);
		max_rinci();
    }
    function kosong2(){
        updt = 'f';
        //$('#jenis').combogrid('setValue','');
		//$('#giat').combogrid('setValue','');
		$("#div_stock").hide();
        $('#kd').combogrid('setValue','');
        $('#nm').attr('value','');
        $('#satuan').attr('value','');
		$("#save").linkbutton('enable');
        $('#untuk').attr('value','');
        $('#stock').attr('value','');
        $('#sisa_stock').attr('value','');
        $('#jml_ambil').attr('value','');
        $('#hrg').attr('value','');
        $('#tot').attr('value','');
        $('#asal').attr('value','');
        $('#ket').attr('value','');
        $('#dana').attr('value','');
        $('#merek').attr('value','');
        $('#koderek').attr('value','');
    }
	
    function load_detail(){
        var i = 0;
        var nomor = document.getElementById('nomor').value;
        var tgl   = $('#tanggal').datebox('getValue');
        var kode  = $('#uskpd').combogrid('getValue'); 
		// ------------
         $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/bhp/trd_keluarbhp',
                data: ({no:nomor,kode:kode}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){ 
                        no      = n['no_dokumen'];                                                                    
                        kd      = n['kode_brg'];
                        nm      = n['detail_brg']; 
                        mrk     = n['merk'];                       
                        jml     = n['jumlah']; 
                        hrg     = number_format(n['harga'],2,'.',',');
                        tot     = number_format(n['total'],2,'.',',');                   
                        ket     = n['keterangan']; 
						kdgiat  = n['kodegiat'];
						satu    = n['satuan'];     
						$('#giat').combogrid('setValue',kdgiat);                    
                    $('#trd').edatagrid('appendRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:kdgiat,satuan:satu});                                
                    
                    });
                }
         });         
          set_grid();
    }
    
    function load_detail2(){
        var nomor = document.getElementById('nomor').value;
        var kode  = $('#uskpd').combogrid('getValue'); 
        var i = 0;
         $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/bhp/trd_keluarbhp',//+nomor+'/'+kode
                data: ({no:nomor,kode:kode}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){ 
					
                        no      = n['no_dokumen'];                                                                  
                        kd      = n['kode_brg'];
                        nm      = n['detail_brg']; 
                        mrk     = n['merk'];                       
                        jml     = n['jumlah']; 
						//sisa	= n['sisa'];
                        hrg     = number_format(n['harga'],2,'.',',');
                        tot     = number_format(n['total'],2,'.',',');                   
                        ket     = n['keterangan']; 
						kdgiat  = n['kodegiat'];
						satu    = n['satuan'];
						sdn     = n['sdana'];
						asl     = n['asal'];                        
                      $('#trd2').edatagrid('appendRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:kdgiat,satuan:satu});                                                  
                    });
                }
         });         
          set_grid2();
    }    
	
	function load_stok(){
        var nomor = document.getElementById('nomor').value;
        var kode  = $('#kdskpd').combogrid('getValue'); 
        var giat  = $('#giat').combogrid('getValue');  
        var i = 0; 
         $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/bhp/trd_stock',//+nomor+'/'+kode
                data: ({no:nomor,kode:kode,kodegiat:giat}),
				dataType:"json", 
                success:function(data){                                          
					$.each(data,function(i,n){ 
                        nma      = n['nama'];                    
                        ssa      = n['sisa'];                   
                        hrg      = n['harga'];      
                      $('#trd_stock').edatagrid('appendRow',{nama:nma,sisa:ssa,harga:hrg});                                                  
                    });
                }
         });         
          set_grid_stock();
    }
	
	/* function load_detail2(){  
       $('#trd').datagrid('selectAll');  
       var rows = $('#trd').datagrid('getSelections'); 
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
		for(var p=0;p<rows.length;p++){
		no      = n['no_dokumen'];                                                                    
		kd      = n['kode_brg'];
		nm      = n['detail_brg']; 
		mrk     = n['merk'];                       
		jml     = n['jumlah']; 
		//sisa	= n['sisa'];
		hrg     = number_format(n['harga'],2,'.',',');
		tot     = number_format(n['total'],2,'.',',');                   
		ket     = n['keterangan']; 
		kdgiat  = n['kodegiat'];
		satu    = n['satuan'];
		//sdn     = n['sdana'];
		//asl     = n['asal'];                                                                                                              
       	alert(no+"-"+kd);
		$('#trd2').edatagrid('appendRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:kdgiat,satuan:satu}); // ,sdana:sdn,asal:asl
        }
        tot = document.getElementById('total').value;
        $('#total2').attr('value',tot);
        $('#trd').edatagrid('unselectAll');    
    }  */
	
    function set_grid(){
         $('#trd').edatagrid({
              columns:[[
			  		{field:'no',title:'ck',width:30,checkbox:true},         
                    //{field:'hapus',title:'',width:35,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();'' />";}},
					{field:'no_dokumen',title:'Nomor Dokumen',width:100,hidden:true},
                    {field:'kode_brg',title:'Kode Barang',width:100,align:"center"},
                    {field:'detail_brg',title:'Nama Barang',width:200,align:"left"},
                    {field:'merk',title:'Merek',width:150,align:"center",hidden:true},
                    {field:'jumlah',title:'Jumlah',width:100,align:"center"},
                    {field:'harga',title:'Harga',width:150,align:"right"},
                    {field:'total',title:'Total',width:150,align:"right"},                                
                    {field:'keterangan',title:'Keterangan',width:200,align:"left"},
            	    {field:'kodegiat',title:'Nomor Dokumen',width:100,hidden:true},
            	    {field:'satuan',title:'Nomor Dokumen',width:100,hidden:true}
                ]],
            onSelect:function(rowIndex,rowData){ 
                no_dokumen   = rowData.no_dokumen;
                kode_brg     = rowData.kode_brg;
                detail_brg   = rowData.detail_brg;
                merk 		 = rowData.merk;
                jumlah  	 = rowData.jumlah; 
                harga   	 = rowData.harga;
                total   	 = rowData.total;
                keterangan   = rowData.keterangan;
                kodegiat 	 = rowData.kodegiat;
                get_edit(no_dokumen,kode_brg,detail_brg,merk,jumlah,harga,total,keterangan,kodegiat);
                
            }
        });       
    }
	
    function set_grid2(){
         $('#trd2').edatagrid({                                                                   
              columns:[[
					{field:'no_dokumen',title:'Nomor Dokumen',width:100,hidden:true},
                    {field:'kode_brg',title:'Kode Barang',width:100,align:"center"},
                    {field:'detail_brg',title:'Nama Barang',width:200,align:"center"},
                    {field:'merk',title:'Merek',width:150,align:"center"},
                    {field:'jumlah',title:'Jumlah',width:100,align:"center"},
                   //{field:'sisa',title:'Sisa',width:100,align:"center"},
                    {field:'harga',title:'Harga',width:150,align:"center"},
                    {field:'total',title:'Total',width:150,align:"center"},                                
                    {field:'keterangan',title:'Keterangan',width:200,align:"center"},
            	    {field:'kodegiat',title:'Nomor Dokumen',width:100,hidden:true},
            	    {field:'satuan',title:'Nomor Dokumen',width:100,hidden:true}
                ]]
        });          
    }
	
    function set_grid_stock(){
         $('#trd_stock').edatagrid({                                                                   
              columns:[[
                    {field:'nama',title:'BARANG',width:200,align:"left"},
                    {field:'sisa',title:'STOCK',width:50,align:"center"},
                    {field:'harga',title:'HARGA',width:100,align:"right"}
                ]]
        });          
    }
	
    function tambah_detail(){
        var no 			= document.getElementById('nomor').value;
        var tgl 		= $('#tanggal').datebox('getValue');
        var kd  		= $('#uskpd').combogrid('getValue');
        var ruangan  	= $('#kdruang').combogrid('getValue');
        var giat		= $('#giat').combogrid('getValue');
        $('#trd2').datagrid('reload');
        if (no!='' && tgl!='' && kd!='' && giat!=''){
            $("#dialog-modal").dialog('open');    
            set_grid2();       
            kosong2();    
            load_detail2();  
            load_stok();   
        } else {
            alert('Nomor/Tanggal/Unit Kerja/Ruangan/Kegiatan masih kosong, harap isi terlebih dahulu');
        }        
    }
    
    function hitung(){
        var stock = angka(document.getElementById('stock').value);   
        var jml = angka(document.getElementById('sisa_stock').value);      
        var b = angka(document.getElementById('hrg').value); 
		var c = angka(document.getElementById('jml_ambil').value);
			if(c>stock){
		$("#div_stock").show();//alert("TIDAK BOLEH MELEBIHI STOCK.!!");
		$("#save").linkbutton('disable');
             }else{
		$("#div_stock").hide();
		$("#save").linkbutton('enable');
		var sisa = stock-c;
			 } 		
        var tot = c*b;
            tot = number_format(tot,2,'.',',');
            $('#tot').attr('value',tot);
            $('#sisa_stock').attr('value',sisa);
    }
		
	  function update_masuk(){
        var no     	= document.getElementById('nomor').value; 
        var kd     	= $('#kd').combogrid('getValue');
        var sisa	= document.getElementById('sisa_stock').value;
       /***************************** SIMPAN KE TRD PLBRG ********************************************************************/ 
        $(document).ready(function(){
		csql = "UPDATE trd_masuk_bhp SET sisa ='"+sisa+"' WHERE no_dokumen='"+no+"' AND kode_brg='"+kd+"'"; 
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/bhp/rubah_jml_keluar",
            data: ({sql:csql}),
			dataType:"json"
          });
		  });
	}
	
/* 	function doble_save(){
	update_masuk();
	append_save();
	} */
    
    function append_save(){
        var no     	= document.getElementById('nomor').value; 
        var asl   	= document.getElementById('asal').value; 
        var dana   	= document.getElementById('dana').value; 
        var giat    = $('#giat').combogrid('getValue'); 
        var kd     	= $('#kd').combogrid('getValue');
        var unit    = $('#uskpd').combogrid('getValue');
		var tgl		= $('#tanggal').datebox('getValue'); 
        var skpd    = $('#kdskpd').combogrid('getValue');
        var nm     	= document.getElementById('nm').value;
		var satuan  = document.getElementById('satuan').value;
        var mrk    	= document.getElementById('merek').value;
        var stock  	= document.getElementById('stock').value;
        var sisa   	= document.getElementById('sisa_stock').value;
        var jml    	= document.getElementById('jml_ambil').value;
        var hrg    	= angka(document.getElementById('hrg').value);
        var tot   	= angka(document.getElementById('tot').value);
        var kdruang = $('#kdruang').combogrid('getValue'); 
        var ket     =  document.getElementById('ket').value;
        var tgl_masuk   =  document.getElementById('tgl_dokumen').value;
		var waktu		= '<?php echo date('y-m-d H:i:s'); ?>'; 
        var spek    = document.getElementById('spek').value;
        var untuk   = document.getElementById('untuk').value;
       /***************************** SIMPAN KE TRD PLBRG ********************************************************************/ 
    csql  = " values('"+no+"','"+giat+"','"+spek+"','"+kd+"','"+unit+"','"+skpd+"','"+nm+"','"+satuan+"','"+mrk+"','"+jml+"','"+hrg+"','"+tot+"','"+ket+"','"+kdruang+"','"+untuk+"')"; 
    csql2 = " values('"+no+"','"+kd+"','"+unit+"','"+skpd+"','"+nm+"','"+mrk+"','','"+jml+"','"+sisa+"','"+hrg+"','"+tot+"','','"+satuan+"','"+giat+"','"+dana+"','"+asl+"','"+kdruang+"','"+tgl_masuk+"','"+tgl+"','"+tgl+"','"+waktu+"','"+spek+"','"+ket+"')";   
	$.ajax({
            type: 'POST',
            data: ({sql:csql,nodok:no,sisa:jml,unit:unit,brg:kd,sql2:csql2}),
            url:"<?php echo base_url(); ?>index.php/bhp/trd_keluar_bhp",
			success:function(data){ 
             var lctot = data; 
             $('#total').attr('value',lctot);
             $('#total2').attr('value',lctot);
			 kosong2();
			 load_stok();
			}
          });
        
        /********************************************** ********************************************************************/        
        if (kd != '' && jml != '' && hrg != ''){        
            if (updt == 'f') {
                $('#trd').edatagrid('appendRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:giat,satuan:satuan});
                $('#trd2').edatagrid('appendRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:giat,satuan:satuan});
				a = total + angka(tot);                
            } else {
                $('#trd').edatagrid('updateRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:giat,satuan:satuan});
                $('#trd2').edatagrid('updateRow',{no_dokumen:no,kode_brg:kd,detail_brg:nm,merk:mrk,jumlah:jml,harga:hrg,total:tot,keterangan:ket,kodegiat:giat,satuan:satuan});                        
				s = total - angka(total2);
                a = s + angka(tot);
            }
            updt = 'f';
            total = number_format(a,2,'.',',');
            $('#total').attr('value',total);
            $('#total2').attr('value',total); 
        }else {
                alert('Jenis, Bidang, Kelompok, Sub Kelompok, Kode, Jumlah dan Harga tidak boleh kosong');
                exit();
        }
    }

    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#trd2').datagrid('reload');                            
    }   
    
	    function get2(kode_brg,detail_brg,merk,jumlah,harga,total,keterangan,kodegiat,satuan,sdana,asal){
		$('#dana').combogrid('setValue',asal);
		$('#asal').combogrid('setValue',sdana);
		$('#giat').combogrid('setValue',kodegiat);
        $('#kd').combogrid('setValue',kd);
        $('#nm').attr('value',nm);
		$('#satuan').attr('value',satuan);                                      
        $('#merek').attr('value',merk);
        $('#jml').attr('value',jumlah);
		//$('#sisa_stock').attr('value',sisa);
        $('#hrg').attr('value',harga);
        $('#tot').attr('value',total);
        $('#ket').attr('value',ket);
        total2 = total;
        
    }
	
     function simpan(){
        var cno     = document.getElementById('nomor').value;
        var ctgl    = $('#tanggal').datebox('getValue');
        var cuskpd  = $('#uskpd').combogrid('getValue');
        var ckdskpd = $('#kdskpd').combogrid('getValue');
        var cnmuskpd = document.getElementById('nmuskpd').value;
        var ctotal  = angka(document.getElementById('total').value);
        var cthn    = '<?php echo ($this->session->userdata('ta_simbakda')); ?>'; 
        var cpenerima  = document.getElementById('penerima').value;  
        var kdruang = $('#kdruang').combogrid('getValue');        
        var giat    		= $('#giat').combogrid('getValue'); 
        var nm_giat 		= document.getElementById('nm_giat').value;
        if (cno==''){
            alert('Nomor Dokumen Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal Dokumen Tidak Boleh Kosong');
            exit();
        }
        if (cuskpd==''){
            alert('Kode Unit Tidak Boleh Kosong');
            exit();
        }       
       if (ckdskpd==''){
            alert('Tahun Tidak Boleh Kosong');
            exit();
        }              
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trh_keluar_bhp',no:cno,tgl:ctgl,giat:giat,nm_giat:nm_giat,uskpd:cuskpd,kdskpd:ckdskpd,nmuskpd:cnmuskpd,tahun:cthn,total:ctotal,penerima:cpenerima,kdruang:kdruang}),
                url: '<?php echo base_url(); ?>index.php/bhp/simpan_keluar_bhp',
                success:function(data){
                   status = data.pesan;       
                              
                   if (status == '0'){
                       alert('Gagal Simpan...!!');
                       exit();
                   } else {                                      
                       swal("Data Tersimpan!", "Silahkan klik Ok!", "success");
                                      
                    }
                                                                                                                              
                }
            });
       });                                 
    }
    
     function hapus(){
        var cnomor = document.getElementById('nomor').value;
        var urll = '<?php echo base_url(); ?>index.php/bhp/hapus_keluarbhp';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Dokumen : '+cnomor);
	        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor,unit:unit}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){  
							$('#trh').datagrid('reload');
                        } else {
                            alert('Gagal Hapus');
                        }        
                 }
                 
                });           
        });
        }     
    }
   
   function hapus_detail(){
		var skpd   = $("#kdskpd").combogrid('getValue');
        var cnomor = document.getElementById('nomor').value; 
        var tbl1   = "trh_keluar_bhp"; 
        var tbl2   = "trd_keluar_bhp"; 
        var rows   = $('#trd').datagrid('getSelected');
        cno  =   rows.no_dokumen;
        ckd  =   rows.kode_brg;
        cjml =   rows.jumlah;
        ctot =   rows.total; 
        var idx = $('#trd').datagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Dokumen : '+cno+' Kode Barang : '+ckd+' Nilai : '+ctot);
        if (tny==true){
            $('#trd').datagrid('deleteRow',idx);            
            total = angka(document.getElementById('total').value) - angka(ctot);
             $.ajax({
                type: 'POST',
                data: ({tabel1:tbl1,tabel2:tbl2,nomor:cnomor,kd:ckd,total:total,skpd:skpd,kode:'kd_brg'}),
                url:"<?php echo base_url(); ?>index.php/bhp/trd_bhp_hapus"
            }); 
            //$('#total2').attr('value',number_format(total,2,'.',','));
            $('#total').attr('value',number_format(total,2,'.',','));
                       
        }                     
    }
	
	 function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){
     $('#trh').edatagrid({
		url: '<?php echo base_url(); ?>index.php/bhp/trh_keluarbhp',
        queryParams:({cari:kriteria})
        });        
     });
    }
	
	
	function max_rinci(){  
	var organisasi  = $('#uskpd').combogrid('getValue');
	var kdbrg 		= $('#kd').combogrid('getValue');
		$.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/bhp/load_idmax',
            data: ({skpd:organisasi,table:'trh_keluar_bhp',kolom:'no_dokumen'}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    no_urut      = n['kode'];
					nomorku		 = tambah_urut(no_urut,5);	
					$("#nomor").attr("value",nomorku);
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
		<p><h3 align="center">LIST BARANG KELUAR</h3></p>
    <ul style="background-color:#2da305;">
        <li><a href="#tabs-1" style="width: 452px;" id="tabs1">List View</a></li>
        <li><a href="#tabs-2" style="width: 452px;" id="tabs2">Form Input</a></li> 		
    </ul>
    <div id="tabs-1">
        <div>
            <p align="right">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();section2();">Tambah</a>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();">Detail Barang</a>                          
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();" >Cari</a>
                <input type="text" value="" id="txtcari" placeholder="*no_dok/kegiatan"/>              
                <table  id="trh" title="List Dokumen" style="width:940px;height:400px;" >  
                </table>                
            </p>
        </div>
    </div>
    <div id="tabs-2">  
        <br /><br />
        <table>
            <tr>
                <td height="30px">No. Keluar</td>
                <td>:</td>
                <td><input type="text" id="nomor" name="nomor" style="width: 200px;" onclick="javascript:select();" disabled="true" /></td>
                <td width="70px"></td>
                <td height="30px">Tanggal Keluar</td>
                <td>:</td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>
			<tr>
                <td height="30px">SKPD</td>
                <td>:</td>
                <td><input id="kdskpd" name="kdskpd" style="width: 140px;" /></td>
                <td></td>
                <td>Nama SKPD</td> 
                <td>:</td>
                <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true"/></td>                                
            </tr>   
            <tr>
                <td height="30px">Unit Kerja</td>
                <td>:</td>
                <td><input id="uskpd" name="uskpd" style="width: 140px;" /></td>
                <td></td>
                <td>Nama Unit Kerja</td> 
                <td>:</td>
                <td><input type="text" id="nmuskpd" style="border:0;width: 400px;" readonly="true"/></td>                                
            </tr>     
            <tr>
                <td height="30px">Bidang/Ruang</td>
				 <td>:</td>
				 <td><input name="kdruang" type="text" id="kdruang" style="width:140px;"/> <input type="text" readonly="true" id="nama_ruang" name="nama_ruang" style="width: 100px; border:0;"/></td>
                <td></td>
                <td>Nama Penerima</td>
                <td>:</td>
                <td><input type="text" id="penerima" name="penerima" style="width: 400px;" placeholder="*isi nama penerima jk ada"/></td>            
            </tr> 
			<tr>                
				<td>Kegiatan</td>
                <td>:</td>
                <td><input id="giat" name="giat" value="" style="width: 140px;" />  
				<input id="nm_giat" name="nm_giat" value="" style="width: 250px; border:0;" readonly="true" /> </td> 
				<td colspan="4"></td>
			</tr>
        </table>  
        <!--br />
        <fieldset>
        <div align="center">
        	<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a>
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>   		  
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>          
        </div>
        </fieldset-->
		<br/>   
        <fieldset>
        <div align="center">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_detail();"><b>Keluarkan Barang</b></a> <br/>  
        	<!--a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a-->          
        </div>
        </fieldset>  
        <br /> 
		<table>
		<tr>
			<td>
			<table id="trd" title="Detail Barang" style="width:900px;height:300px;" ></table>
			</td>
			<td>
			<a class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:hapus_detail();">
			<!--a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:edit_detail();"-->			
			</td>
		</tr>
		</table>        
        <div align="right">Jumlah : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>        
    </div>  
		<div align="center">
        <fieldset>
		<INPUT TYPE="button" VALUE="SIMPAN" style="height:40px;width:100px" onclick="javascript:simpan();" >
		<INPUT TYPE="button" VALUE="KEMBALI" style="height:40px;width:100px" onclick="javascript:section1();" >
        </fieldset>            
		</div>  

</div>

<div id="dialog-modal" title="Barang Keluar Habis Pakai" >
    <fieldset>      
        <table>      
            <!--tr>
                <td>Kegiatan</td>
                <td>:</td>
                <td width="150"><input id="giat" name="giat" value=""/> </td>
                <td rowspan="9"></td>   
                <td rowspan="9" width="660"  >
                    <table  id="trd2" title="Detail Barang" style="width:665px;height:270px;" >  
                    </table>           
                    <div align="right">Jumlah : <input type="text" id="total2" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>     
                </td>         
            </tr-->  
             <tr>
                <td>Kode Barang</td>
                <td>:</td>
                <td width="150"><input id="kd" name="kd" value=""/> </td>
                <td rowspan="9"></td>   
                <td rowspan="9" width="660"  >
                    <table  id="trd2" title="Detail Barang" style="width:665px;height:270px;" >  
                    </table>           
                    <div align="right">Jumlah : <input type="text" id="total2" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>     
                </td>         
            </tr>      
            <tr>
                <td>Nama barang</td>
                <td>:</td>
                <td><input id="nm" name="nm" value="" readonly="true" style="border:0;"/> 
					<input hidden="true" id="spek" name="spek" value="" readonly="true" style="border:0;"/> 				
				</td>            
            </tr> 
            <tr>
                <td>Satuan</td>
                <td>:</td>
                <td><input disabled="true" id="satuan" name="satuan" value=""/>  </td>            
            </tr> 
            <tr>
                <td>Peruntukan</td>
                <td>:</td>
                <td><textarea id="untuk" style="width: 155px; height: 60px;" name="untuk" value="" placeholder="*isi penjelasan peruntukan barang"></textarea></td>            
            </tr> 
			<tr>
                <td>Stock</td>
                <td>:</td>
                <td>
				<input disabled="true" id="stock" name="stock" value="" style="text-align: right;" /></td>            
            </tr> 
			<tr>
                <td>Sisa Stock</td>
                <td>:</td>
                <td><input disabled="true" id="sisa_stock" name="sisa_stock" value="" style="text-align: right;" /></td>            
            </tr>  
			<tr>
				<div id="div_stock"><font style="color:#FF0000; font-size:30px" face="tahoma">TIDAK BOLEH MELEBIHI STOCK.!</font></div><br/>
                <td>Jumlah Ambil</td>
                <td>:</td>
                <td>
				<input id="jml_ambil" name="jml_ambil" value="" style="text-align: right;" onkeypress="return(isNumberKey(event));" onkeyup="hitung();"/></td>            
            </tr> 
            <tr>
                <td>Harga Satuan</td>
                <td>:</td>
                <td><input disabled="true" id="hrg" name="hrg" value="" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event));" onkeyup="hitung();"/>  </td>            
            </tr> 
            <tr>
                <td>Total Harga</td>
                <td>:</td>
                <td><input  id="tot" name="tot" value="" style="text-align: right;border:0;" readonly="true" />  </td>            
            </tr><br/>  
            <!--tr>
                <td>Keterangan</td>
                <td>:</td>
                <td><textarea id="ket" style="width: 155px; height: 60px;" placeholder="*isi sesuai keperluan pengeluaran barang"></textarea> </td>            
            </tr--> 
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td width="150"><textarea id="ket" style="width: 155px; height: 60px;" placeholder="*isi sesuai keperluan pengeluaran barang"></textarea> </td>
                <td rowspan="9"></td>   
                <td rowspan="9" width="355"  >
                    <table  id="trd_stock" title="STOCK BARANG KEGIATAN" style="width:350px;height:170px;" > </table>           
                </td>         
            </tr> 
			<tr hidden="true">
                <td>Asal Usul</td>
                <td>:</td>
                <td><input id="asal" name="asal" value="" disabled="true"/>  </td>                            
            </tr> 
			<tr hidden="true">
                <td>Sumber Dana</td>
                <td>:</td>
                <td><input id="dana" name="dana" value="" disabled="true"/>  </td>                            
            </tr>    
            <tr hidden="true">
                <td>Merek</td>
                <td>:</td>
                <td><input id="merek" name="merek" value="" disabled="true"/>  </td>            
            </tr>     
            <tr hidden="true">
                <td>Koderek</td>
                <td>:</td>
                <td><input id="koderek" name="koderek" value="" disabled="true"/>  </td>            
            </tr>      
            <tr hidden="true">
                <td>tgl masuk</td>
                <td>:</td>
                <td><input id="tgl_dokumen" name="tgl_dokumen" value="" disabled="true"/>  </td>            
            </tr> 
        </table>     
		
    </fieldset>  
    <fieldset>
        <table align="center">
            <tr>
                <td><!--a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong2();">Tambah</a-->
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" id="save" onclick="javascript:append_save();">Simpan</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>                               
                </td>
            </tr>
        </table>   
    </fieldset>
</div>

