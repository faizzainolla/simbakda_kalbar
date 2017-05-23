    <script type="text/javascript">
    
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
	                
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 500,
            width: 800,
            modal: true,
            autoOpen:false,
        });
        });    
     
     $(function(){
        $('#dgkibmuta').edatagrid({
		url: '',
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

		
		$('#dghmuta').edatagrid({
		url: '',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        }*/
        
        });
	
		$("#txtcari").attr("value",'');
		$("#txtcari2").attr("value",'');
    
	     $('#kib').combogrid({ 
            panelWidth:400,  
            width:160,
            idField:'golongan',  
            textField:'golongan',  
            mode:'remote',
			url:'<?php echo base_url(); ?>index.php/master/ambil_gol',  
            loadMsg:"Tunggu Sebentar....!!",                                                 
			columns:[[  
               {field:'golongan',title:'Kode Golongan',width:100},  
               {field:'nm_golongan',title:'Nama Golongan',width:300}    
            ]],
		onSelect:function(rowIndex,rowData){
                       jenis_kib = rowData.golongan;     
                    	//alert(coba);
						load_datagrid(jenis_kib);
		}
		 })
	
	$('#tanggal').datebox({  
            required:true,
            formatter :function(date){
          	var y = date.getFullYear();
           	var m = date.getMonth()+1;
           	var d = date.getDate();    
           	return y+'-'+m+'-'+d;
            }
         });
        /*
	    $('#pilkib').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'ka',nama:'KIB A'},{kode:'kb',nama:'KIB B'},{kode:'kc',nama:'KIB C'},{kode:'kd',nama:'KIB D'},
        {kode:'ke',nama:'KIB E'},{kode:'kf',nama:'KIB F'}],
		OnSelect: function(kode){
			var coba=kode;
			load_datagrid(coba);
		}
		});
		
		
	
    
	     $('#kib').combogrid({ 
            panelWidth:400,  
            width:160,
            idField:'golongan',  
            textField:'nm_golongan',  
            mode:'remote',
			url:'<?php echo base_url(); ?>index.php/master/ambil_gol',  
            loadMsg:"Tunggu Sebentar....!!",                                                 
			columns:[[  
               {field:'golongan',title:'Kode Golongan',width:100},  
               {field:'nm_golongan',title:'Nama Golongan',width:300}    
            ]],  
             onSelect:function(rowIndex,rowData){
				golongan=rowData.golongan;
				
            $('#dgkibmuta').edatagrid({url:'<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_b',
              queryParams:({golongan:golongan})});
			} 
    
    });
	*/
	
	$('#uskpdb').combogrid({  
                    panelWidth:700,  
                    width:100,  
                    idField:'kd_skpd',  
                    textField:'kd_skpd',  
                    mode:'remote',                      
                    url:'<?php echo base_url(); ?>index.php/master/ambil_msskpd2',  
                    columns:[[  
                       {field:'kd_skpd',title:'Kode SKPD',width:100},  
                       {field:'nm_skpd',title:'Nama SKPD',width:250},
                       {field:'kd_lokasi',title:'Kode Unit',width:100},  
                       {field:'nm_lokasi',title:'Nama Unit',width:250}    
                    ]],  
                    onSelect:function(rowIndex,rowData){
                       cskpd = rowData.kd_lokasi;     
                       cuskpd = rowData.kd_skpd;              
                       $('#nmuskpdb').attr('value',rowData.nm_skpd);
                       $('#skpdx').attr('value',cskpd);    
                    } 
                 });   
		
    });
	

		function load_datagrid(jenis_kib){
		if(jenis_kib=='01'){
						$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_a',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:20,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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
					
					
					
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_a',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        */
        });
	
	
		}else if(jenis_kib=='02'){
					$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_b',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:20,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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
					

	$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_b',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        */
        });
		
		}else if(jenis_kib=='03'){
					$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_c',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:20,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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

					$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_c',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        }*/
        
        });
	
	
		}else if(jenis_kib=='04'){
					$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_d',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:20,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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
					
$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_d',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        */
        });
	
		}else if(jenis_kib=='05'){
					$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_e',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:20,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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
$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_e',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        */
        });
	
		}else if(jenis_kib=='06'){
					$('#dgkibmuta').edatagrid({
					url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_f',
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
						width:40,
						align:"left"},
						{field:'tahun',
						title:'tahun',
						width:10,
						align:"center"},
						{field:'nilai',
						title:'nilai',
						width:10,
						align:"right"},
						{field:'kd_skpd',
						title:'kode',
						width:0,
						align:"right"},	
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
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_f',
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
			{field:'no_uhapus',
    		title:'no Usulan',
    		width:15,
            align:"right"},
			{field:'tgl_uhapus',
    		title:'Tanggal Usulan',
    		width:20,
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
                                       
        }/*,
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
        */
        });
	
		
		}
		}
		
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
		$("#txtcari").attr("value",'');
		$("#txtcari2").attr("value",'');
		$("#ket").attr("value",'');		
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
   if(jenis_kib=='01'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_a',
        queryParams:({cari:kriteria})
        });
					}

		if(jenis_kib=='02'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_b',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='03'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_c',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='04'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_d',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='05'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_e',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='06'){                    
		$('#dghmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_hapus_header_f',
        queryParams:({cari:kriteria})
        });
					}					
     });

    }

    function cari2(){
    var kriteria = document.getElementById("txtcari2").value; 
    $(function(){
	if(jenis_kib=='01'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_a',
        queryParams:({cari:kriteria})
        });
					}

		if(jenis_kib=='02'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_b',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='03'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_c',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='04'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_d',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='05'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_e',
        queryParams:({cari:kriteria})
        });
					}
		if(jenis_kib=='06'){                    
		$('#dgkibmuta').edatagrid({
		url: '<?php echo base_url(); ?>index.php/transaksi/ambil_kib_hapus_f',
        queryParams:({cari:kriteria})
        });
					}					
     });
    }
    
	function tab1(){
    $('#tabs1').click();
	$('#dghmuta').edatagrid('reload');
    $("#txtcari").attr("setvalue",'');	
}
	function tab2(){	
   $('#tabs2').click()
   $('#dgkibmuta').edatagrid('reload');
   $("#txtcari2").attr("setvalue",'');	

   cekdouble=1;
}

    function simpan(){
        var simkd	= document.getElementById('unit').value;
		//var simkib	= document.getElementById('kode').value;
		var simid 	= document.getElementById('idbrang').value;
		var simnm 	= document.getElementById('nmbrang').value;	
        var simthn	= document.getElementById('thn').value;                
        var simreg  = document.getElementById('noregis').value;
        var simket  = document.getElementById('ket').value;
		//var simpenyu= document.getElementById('akum_penyu').value;
		//var simsisa = document.getElementById('sisaumur').value;
		var simtgl	= $('#tanggal').datebox('getValue');
//		var simkdbaru	= $('#uskpdb').datebox('getValue');
		var simnomts=  document.getElementById('nomts').value;
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
		
		if (simnomts==''){
            alert('Isi no Usulan');
            exit();
        } 
		
		if (simtgl==''){
            alert('Isi tanggal');
            exit();
        }
	                   if(lcstatus=='tambah'){
            
            lcinsert = "(no_reg,id_barang,nm_brg,nilai,kd_skpd,tahun,akum_penyu,nilai_sisa)";
            lcvalues = "('"+simreg+"','"+simid+"','"+simnm+"','"+simnilai+"','"+simkd+"','"+simthn+"','"+simpenyu+"','"+simsisa+"')";
            
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/master/simpan_master',
                    data: ({tabel:'trkib_b',kolom:lcinsert,nilai:lcvalues,cid:'id_barang',lcid:simid}),
                    dataType:"json"
                });
            });    
            
            
        $('#dgkibmuta').datagrid;

		}else 		
					{	if(jenis_kib=='01'){                    
                    lcquery = "UPDATE trkib_a SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }else if(jenis_kib=='02'){                    
                    lcquery = "UPDATE trkib_b SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }else if(jenis_kib=='03'){                    
                    lcquery = "UPDATE trkib_c SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }else if(jenis_kib=='04'){                    
                    lcquery = "UPDATE trkib_d SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }else if(jenis_kib=='05'){                    
                    lcquery = "UPDATE trkib_e SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }else if(jenis_kib=='06'){                    
                    lcquery = "UPDATE trkib_f SET no_uhapus='"+simnomts+"',tgl_uhapus='"+simtgl+"',ket_hapus='"+simket+"' where id_barang='"+simid+"'";
                    }
		
		
		
		
		
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/master/update_master',
                        data: ({st_query:lcquery}),
                        dataType:"json"
                    });
                    });
                    
						$('#dghmuta').edatagrid('reload');
						$('#dgkibmuta').edatagrid('reload');
						                    
                    
                        $('#dgkibmuta').datagrid('updateRow',{
                    	index: lcidx,
                    	row: {
							
							no_uhapus : simnomts,
							tgl_uhapus  : simtgl,
							ket_hapus : simket
						}
                    });
                }
               
                
                alert("Data Berhasil disimpan");
                $("#dialog-modal").dialog('close');
              

    } 
  
   function segarkan(){

        $('#dgkibmuta').edatagrid('reload');
        $('#dghmuta').edatagrid('reload');
		
		}   
    
     function tambah(){  
        lcstatus = 'tambah';
        judul = 'Input Data Wilayah';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        $('#dgkibmuta').edatagrid('reload');
		
		} 
		
function kosong(){
	$('#akum_penyu').attr('value','');
	$('#sisaumur').attr('value','');
	$('#tanggal').datebox('setValue','');
	$('#uskpdb').combogrid('setValue','');
	$('#nomts').attr('value','');	
}
		
		function hilangkan(){
		var simtgl	= $('#tanggal').datebox('getValue');
		//var simkdbaru	= $('#uskpdb').datebox('getValue');
		var simnomts=  document.getElementById('nomts').value;                    
        var simid = document.getElementById('idbrang').value;
               if (simid !=''){
		var del=confirm('Apakah Anda yakin ingin Menghapus data '+simid+'?');
		if (del==true){
			
			{
				if(jenis_kib=='01'){                    
                    lcquery = "UPDATE trkib_a SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }else if(jenis_kib=='02'){                    
                    lcquery = "UPDATE trkib_b SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }else if(jenis_kib=='03'){                    
                    lcquery = "UPDATE trkib_c SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }else if(jenis_kib=='04'){                    
                    lcquery = "UPDATE trkib_d SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }else if(jenis_kib=='05'){                    
                    lcquery = "UPDATE trkib_e SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }else if(jenis_kib=='06'){                    
                    lcquery = "UPDATE trkib_f SET no_uhapus=null,tgl_uhapus=null,ket_hapus=null where id_barang='"+simid+"'";
                    }
			}
                
        
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/master/update_master',
                        data: ({st_query:lcquery}),
                        dataType:"json"
                    });
                    });
						$('#dghmuta').edatagrid('reload');
						$('#dgkibmuta').edatagrid('reload');
						
                       
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
<h1 align="center"><b>USULAN PENGHAPUSAN KIB</b></h1>
	<div>
			
	<p align="left">
				<td width="20%">PILIH KIB</td>
                            <td width="1%">:</td>
                <td><input type="text" id="kib" style="width: 140px;" /></td>
	<td rowspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td>		
		        
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tab2()">Tambah</a>
                <!--a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a-->                           
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                <input type="text" value="" id="txtcari"/>
                <input type="hidden" value="" id="txtnodok_h"/>
                <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit">              
                <table  id="dghmuta" title="List Data Usulan Penghapusan" style="width:940px;height:370px;" >  
                </table>                
                </div>
            </p>
        </div>
    </div>
        <div id="tabs-2">
<h1 align="center"><b>USULAN PENGHAPUSAN KIB</b></h1>
	<div>
            <p align="right">
                <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tab2();">Tambah</a>
                <!--a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a-->                           
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari2();">Cari</a>
                <input type="text" value="" id="txtcari2"/>
                <input type="hidden" value="" id="txtnodok_h"/>
                <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit">              
                <table  id="dgkibmuta" title="List Data KIB" style="width:940px;height:370px;" >  
                </table>                
                </div>
            </p>
        </div>
    </div>
<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
          <table align="left" style="width:50%;" border="0">
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
                <td>SKPD</td>
                <td>:</td>
                <td><input id="unit" disabled = true name="unit" style="width: 200px;" value=""/>
                <!--td  hidden="true"><input type="text" id="thn2" style="width: 140px;" /></td-->
            </tr>
			<tr>
                <td width="20">No. Usulan</td>
                <td width="1%">:</td>
                <td><input type="text" id="nomts" style="width:200px;"/></td>       
			<td width="100px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Tgl Usulan</td>
                <td>:</td>
                <td height="10px"><input type="text" id="tanggal" style="width: 140px;" /></td>
			</tr>		
			
			<tr>
                <td>Keterangan</td>
                <td>:</td>
                <td><textarea id="ket" style="width: 155px; height: 60px;"></textarea> </td>            
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

