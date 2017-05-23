<script src="<?php echo base_url(); ?>lib/sweet-alert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>lib/numberFormat.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.maskMoney.min.js"></script>
<link   rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>lib/sweet-alert.css">
<script type="text/javascript">


var cgol ='';
var lpnodok1 = '';
var rekening_transaksi = '';
var kode_bidang = '';
var plonline = '<?php echo ($this->session->userdata('plonline')); ?>';
var hkode_rek = '';
var hkode_keg = '';
var hnm_rek = '';
var hnm_keg = '';
var cekdouble = '';
var kode_tot = '';

updt = 'f';
total_updt=0;
$(document).ready(function() {
    $("#tabs").tabs();
    $("#dialog-modal").dialog({
        height: 650,
        width: 800,
        modal: true, 
        background:'#2da305',           
        autoOpen:false                
    });
    $("#dialog-modal-update").dialog({
        height: 650,
        width: 800,
        modal: true, 
        background:'#2da305',           
        autoOpen:false                
    });
    $("#dialog-modal_bap").dialog({
        height: 650,
        width: 800,
        modal: true, 
        background:'#2da305',           
        autoOpen:false                
    });              
    //set_grid();
});


//rebuild novar kahfi 2016
    
$(document).ready(function(){
      $('#jumlah').maskMoney({thousands:',', decimal:'.', precision:0});
      $('#jumlah_u').maskMoney({thousands:',', decimal:'.', precision:0});
    });    

$(function(){ 
    $('#trh').edatagrid({
        url: '<?php echo base_url(); ?>index.php/transaksi_int/trh_isianbrg',
        idField:'no_dokumen',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true", 
        /*rowStyler: function(index,row){
                        if (row.invent=='1' ){
                            return 'background-color:#FCE6E6 ;';
                        }else if(row.tes>0){
                            return 'background-color:#FFFF00;';
                        }
                    },                */      
        columns:[[
    	    {field:'no_dokumen',title:'Nomor Dokumen',width:50},
            {field:'tgl_dokumen',title:'Tanggal',width:30},
            {field:'nm_comp',title:'Perusahaan/Rekanan',width:100},
            {field:'kd_kegiatan',title:'Kegiatan',width:80},
			{field:'nilai_kontrak2',title:'Nilai Realisasi',width:50,align:'right'},
			{field:'sisa2',title:'Sisa',width:50,align:'right'},
			{field:'hapus',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
			
        ]],
        onSelect:function(rowIndex,rowData){
            idx 		= rowIndex;
            lpnodok1 	= rowData.no_dokumen;
            no 			= rowData.no_dokumen;
            tgl 		= rowData.tgl_dokumen;
            nilkon 		= number_format(rowData.nilai_kontrak,2,'.',',');
			sisa2 		= rowData.sisa2;
            nilapbd 	= number_format(rowData.nilai_apbd,2,'.',',');
            kdcomp  	= rowData.kd_comp;
            kdmilik  	= rowData.kd_milik;
            kdwilayah 	= rowData.kd_wilayah;
            kduskpd  	= rowData.kd_uskpd;
            kd_unit  	= rowData.kd_unit;
            jnsdana  	= rowData.jns_dana;
            tahunang  	= rowData.tahun_ang;
            buktibyr  	= rowData.bukti_byr;
            dasaroleh  	= rowData.dasar_oleh;
            nooleh  	= rowData.no_oleh;
            tgloleh 	= rowData.tgl_oleh;
            tahunoleh 	= rowData.tahun_oleh;            
            tot 		= rowData.total;
            cr_oleh 	= rowData.kd_cr_oleh;    
			kode_tot	= tot;
			cekdouble = 0;		
            getData(no,tgl,nilkon,nilapbd,kdcomp,kdmilik,kdwilayah,kduskpd,kd_unit,jnsdana,tahunang,buktibyr,dasaroleh,nooleh,tgloleh,tahunoleh,tot,cr_oleh,sisa2);            
                   
        },
        onDblClickRow:function(rowIndex,rowData){  
		    idx 		= rowIndex;
            lpnodok1 	= rowData.no_dokumen;
            no 		 	= rowData.no_dokumen;
            tgl 	 	= rowData.tgl_dokumen;
            nilkon  	= number_format(rowData.nilai_kontrak,2,'.',',');
			sisa2 		= rowData.sisa2;
            nilapbd 	= number_format(rowData.nilai_apbd,2,'.',',');
            kdcomp  	= rowData.kd_comp;
            kdmilik  	= rowData.kd_milik;
            kdwilayah 	= rowData.kd_wilayah;
            kduskpd  	= rowData.kd_uskpd;
            kd_unit  	= rowData.kd_unit;
            jnsdana  	= rowData.jns_dana; 
            tahunang  	= rowData.tahun_ang;
            buktibyr  	= rowData.bukti_byr;
            dasaroleh  	= rowData.dasar_oleh;
            nooleh  	= rowData.no_oleh;
            tgloleh 	= rowData.tgl_oleh;
            tahunoleh 	= rowData.tahun_oleh;            
            tot 		= rowData.total;
            cr_oleh 	= rowData.kd_cr_oleh;   
			kode_tot	= tot;
			get_nomor_bap(lpnodok1);
            getData(no,tgl,nilkon,nilapbd,kdcomp,kdmilik,kdwilayah,kduskpd,kd_unit,jnsdana,tahunang,buktibyr,dasaroleh,nooleh,tgloleh,tahunoleh,tot,cr_oleh,sisa2);            
              $('#nomor').combogrid('disable'); 
            loadDetail(); 
            //load_sum_trd_isianbrg();    
            tab2();          
			cekdouble = 0;	
        }
    });    
       
	function get_nomor_bap(lpnodok1){
			var kode = lpnodok1;
			if(kode==''){
				kode = $('#nomor').combogrid('getValue');
			}
			//alert(kode);
		$('#kontrak_bap').combogrid({  
            panelWidth:630,  
            idField:'no_dokumen',  
            textField:'no_dokumen',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/master_int/nomor_kontrak_bap/?kode='+kode,  
            columns:[[  
                {field:'no_dokumen',title:'No Kontrak',width:180},                  
				{field:'tgl_dokumen',title:'Tanggal',width:80},
				{field:'kd_kegiatan',title:'Kegiatan',width:180},   				
				{field:'nilai_kontrak2',title:'Nilai',width:120,align:'right'}                 								
            ]],
            onSelect:function(rowIndex,rowData){                
				//tgl_kontrak_bap								
				var nilai_kontrakk = rowData.nilai_kontrak;
				$('#tgl_kontrak_bap').datebox("setValue",rowData.tgl_dokumen);
                $('#kegiatan_bap').attr('value',rowData.kd_kegiatan);
                $('#kegiatan_bap_nm').attr('value',rowData.nm_kegiatan);
				$('#nilai_bap').attr('value',nilai_kontrakk);
                $('#nilai_bap2').attr('value',number_format(nilai_kontrakk,2));
                $('#dana_bap').attr('value',rowData.s_dana);				                                        
            }  
            });
		}   
	   
	   
    $('#trd').edatagrid({                
            //toolbar:'#toolbar',   		
            idField:"idx",            
            rownumbers:"true",            
            singleSelect:"true",
            autoRowHeight:"false",             
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    //nilx = rowData.nilai;
            },
        columns:[[ 
        {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},
        {field:'no_dokumen',title:'Nomor',width:100,hidden:true},
        {field:'jns',title:'Jenis',width:100,hidden:true }  ,
        {field:'nm_jenis',title:'Nama Jenis',width:100,hidden:true }  ,
		{field:'kd_rek5',title:'Kode Rekening',width:100 } ,
        {field:'nm_rek5',title:'Nama Rekening',width:100,hidden:true }  ,
        {field:'kd_bidang',title:'Kode Bidang',width:100,hidden:true},
        {field:'nm_bidang',title:'Nama Bidang',width:100,hidden:true},
        {field:'kd_brg',title:'Kode Barang',width:150 }  ,
        {field:'nm_brg',title:'Nama Barang',width:250 }  ,
        {field:'kd_unit',title:'Unit',width:100,hidden:true }  ,
        {field:'kd_uskpd',title:'SKPD',width:100,hidden:true }  ,
        {field:'s_dana',title:'Sumber Dana',width:100,hidden:true }  ,
        {field:'no_sp2d',title:'No SP2D',width:150,hidden:true }  ,
        {field:'tgl_sp2d',title:'TGL SP2D',width:100,hidden:true }  ,
        {field:'nilai_sp2d',title:'Nilai SP2D',width:100,align:'right',hidden:true }  ,
        {field:'nilai_kontrak',title:'Nilai Realisasi',width:100,hidden:true }  ,
        {field:'kd_kegiatan',title:'Kode Kegiatan',width:100,hidden:true }  ,
        {field:'nm_kegiatan',title:'Nama Kegiatan',width:100,hidden:true }  ,        
        {field:'jumlah',title:'Jumlah',width:100,hidden:true }  ,
        {field:'harga',title:'Harga/Unit',width:100,align:'right' }  ,
        {field:'ppn',title:'PPN',width:100,hidden:true }  ,
        {field:'total',title:'Total',width:100,align:'right' }  ,
        {field:'keterangan',title:'Keterangan',width:100,hidden:true }  ,
        {field:'invent',title:'Inventaris',width:100,hidden:true } , 
        {field:'hapus',width:30,align:'center',formatter:function(value,rec)
        {return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();''/>";}}

        ]]
        
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
	
    $('#dstgl').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return y+'-'+m+'-'+d;
        }
    });
    
     $('#tgl_kontrak_bap').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return d+'-'+m+'-'+y;
        }
    });
    
     $('#tgl_kep').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return d+'-'+m+'-'+y;
        }
    });
    
     $('#tgl_bap').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return d+'-'+m+'-'+y;
        }
    });
    
     $('#tgl_ctk_bap').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return d+'-'+m+'-'+y;
        }
    });
    
    
    $('#compy').combobox({           
        valueField:'kd_comp',  
        textField:'nm_comp',
        mode:'remote',
        width:300,
        url:'<?php echo base_url(); ?>index.php/master_int/ambil_compy'
    });        
    $('#milik').combobox({           
        valueField:'kd_milik',  
        textField:'nm_milik',
        mode:'remote',
        width:300,
        url:'<?php echo base_url(); ?>index.php/master_int/ambil_milik'
    });
    $('#wilayah').combobox({           
        valueField:'nm_wilayah',  
        textField:'nm_wilayah',
        mode:'remote',
        width:300,
        url:'<?php echo base_url(); ?>index.php/master_int/ambil_wilayah'
    });
	
	 $('#unit').combogrid({  
            panelWidth:450,  
			width:300,
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',                   
            url:'<?php echo base_url(); ?>index.php/master_int/ambil_msskpd',  
            columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:350},
               {field:'kd_lokasi',title:'Kode SKPD',hide:true},  
               {field:'nm_lokasi',title:'Nama SKPD',hide:true},
            ]],  
            onSelect:function(rowIndex,rowData){
               cuskpd = rowData.kd_skpd;   
               ckd_lokasi = rowData.kd_lokasi;  
               cnm_lokasi = rowData.nm_lokasi;
				cnmunit = rowData.nm_skpd;
               $('#nmunit').attr('value',rowData.nm_skpd);        
               $('#mlokasi').attr('value',ckd_lokasi);        
               $('#nmlokasi').attr('value',cnm_lokasi);                             
            } 
         });  		 	
	
    $('#dana').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'APBN'},{kode:'2',nama:'APBD'},{kode:'3',nama:'APBD 1'},{kode:'4',nama:'ADD'},
        {kode:'5',nama:'APBDESA'}]
    });
	
    $('#tahun').combobox({           
        valueField:'tahun',  
        textField:'tahun',
        mode:'remote',
        width:70,
        url:'<?php echo base_url(); ?>index.php/master_int/tahun'
    });
    $('#thn2').combobox({           
        valueField:'tahun',  
        textField:'tahun',
        mode:'remote',
        width:70,
        url:'<?php echo base_url(); ?>index.php/master_int/tahun'
    });
    $('#bukti').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'SPMU'},{kode:'2',nama:'SPM'},{kode:'3',nama:'BUKTI SETORAN'},{kode:'4',nama:'SP2D'}]
    });
    
    $('#jabat_awas1').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    
    $('#jabat_awas2').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    $('#jabat_awas3').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    $('#jabat_awas4').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    $('#jabat_awas5').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    $('#jabat_awas6').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    $('#jabat_awas7').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'Ketua'},{kode:'2',nama:'Sekretaris'},{kode:'3',nama:'Bendahara'},{kode:'4',nama:'Anggota'}]
    });
    
    $('#hari').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:75,
        data:[{kode:'1',nama:'Minggu'},{kode:'2',nama:'Senin'},{kode:'3',nama:'Selasa'},
        {kode:'4',nama:'Rabu'},{kode:'5',nama:'Kamis'},{kode:'6',nama:'Jumat'},{kode:'7',nama:'Sabtu'}]
    });
    
    $('#bln_bap').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:100,
        data:[{kode:'1',nama:'Januari'},{kode:'2',nama:'Februari'},{kode:'3',nama:'Maret'},
        {kode:'4',nama:'April'},{kode:'5',nama:'Mei'},{kode:'6',nama:'Juni'},
        {kode:'7',nama:'Juli'},{kode:'8',nama:'Agustus'},{kode:'9',nama:'September'},{kode:'10',nama:'Oktober'},
        {kode:'11',nama:'November'},{kode:'12',nama:'Desember'}]
    });
    
    $('#perolehan').combobox({           
        valueField:'cara_perolehan',  
        textField:'cara_perolehan',
        mode:'remote',
        width:150,
        url:'<?php echo base_url(); ?>index.php/master_int/perolehan'                    
    });
    
     $('#pengawas1').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas7').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas2').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas3').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas4').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas5').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     $('#pengawas6').combobox({           
        valueField:'nama',  
        textField:'nama',
        mode:'remote',
        width:220,
        url:'<?php echo base_url(); ?>index.php/master_int/pengawas'                    
    });
     
    
    $('#dasar').combobox({           
        valueField:'nama',  
        textField:'nama',        
        width:150,
        data:[{kode:'1',nama:'BERITA ACARA'},{kode:'2',nama:'SERTIFIKAT'}]
    });    
	

	
	 $('#cmkel').combogrid({  
            panelWidth:600, 
            width:400, 
            idField:'kelompok',  
            textField:'nm_kelompok',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kelompok',title:'Kode Barang',width:100},  
               {field:'nm_kelompok',title:'Nama Barang',width:500}    
            ]],  
             onSelect:function(rowIndex,rowData){
				ckelompok=rowData.kelompok;
				$('#cmsubkel').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_kelompok1',
				queryParams:({kelompok:ckelompok})});            
        }  
    });		
	
    $('#cmsubkel').combogrid({  
            panelWidth:600, 
            width:400, 
            idField:'kd_kelompok',  
            textField:'nm_kelompok',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_kelompok',title:'Kode Barang',width:100},  
               {field:'nm_kelompok',title:'Nama Barang',width:500}    
            ]],  
             onSelect:function(rowIndex,rowData){
				csubkel=rowData.kd_kelompok;
				$('#kdbrg').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/load_brg',
				queryParams:({subkel:csubkel})});             
        }  
    });
	
    $('#kdbrg').combogrid({  
            panelWidth:600, 
            width:400, 
            idField:'kd_brg',  
            textField:'nm_brg',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'Kode Barang',width:100},  
               {field:'nm_brg',title:'Nama Barang',width:500}    
            ]],  
            onSelect:function(rowIndex,rowData){                                                             
                cnm = rowData.nm_brg;              
                $('#nmbrg').attr('value',cnm);                
            } 
    }); 
	
     $('#sbrdana').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'APBN'},{kode:'2',nama:'APBD'},{kode:'3',nama:'APBD 1'},{kode:'4',nama:'ADD'},
        {kode:'5',nama:'APBDESA'}]
    });
    $('#sbrdana_u').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:150,
        data:[{kode:'1',nama:'APBN'},{kode:'2',nama:'APBD'},{kode:'3',nama:'APBD 1'},{kode:'4',nama:'ADD'},
        {kode:'5',nama:'APBDESA'}]
    });
	
    $('#tglsp2d').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return y+'-'+m+'-'+d;
        }
    });
    $('#tglsp2d_u').datebox({  
        required:true,
        formatter :function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();    
        return y+'-'+m+'-'+d;
        }
    }); 
	
    /*$('#nosp2d').combogrid({  
                   panelWidth : 700,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   //multiple   : true,  
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:200},  
                       {field:'tgl_sp2d',title:'Tanggal',width:80},
                       {field:'nilai2',title:'Nilai',width:100,align:'right'},
                       {field:'keperluan',title:'Keterangan',width:320}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                    nil=rowData.nilai;
                    $('#tglsp2d').datebox("setValue",rowData.tgl_sp2d);
                    $('#nilsp2d').attr('value',rowData.nilai2);
                    $('#nilsp2d_hide').attr('value',number_format(nil,2,'.',''));
                       
                   } 
            }); 
    $('#nosp2d_u').combogrid({  
                   panelWidth : 700,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                     
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:200},  
                       {field:'tgl_sp2d',title:'Tanggal',width:80},
                       {field:'nilai2',title:'Nilai',width:100,align:'right'},
                       {field:'keperluan',title:'Keterangan',width:320}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                    nil=rowData.nilai;
                    $('#tglsp2d_u').datebox("setValue",rowData.tgl_sp2d);
                    $('#nilsp2d_u').attr('value',rowData.nilai2);
                    $('#nilsp2d_hide_u').attr('value',number_format(nil,2,'.',''));
                       
                   } 
            });  */
});

$(function(){
    $('#cmbjenis').combogrid({           
        idField:'golongan',  
        textField:'golongan',
        mode:'remote',
        panelWidth:400,
        url:'<?php echo base_url(); ?>index.php/master_int/ambil_gol',
        columns:[[  
               
               {field:'golongan',title:'Kode Golongan',width:100},  
               {field:'nm_golongan',title:'Nama Golongan',width:500}
            ]], 
        onSelect:function(rowIndex,rowData){
            cgol=rowData.golongan;
            ngol=rowData.nm_golongan;
            
            $('#nmgolongan').attr('value',ngol);
            $('#bidang').combogrid('clear');
            $('#kdbarang').combogrid('clear');
            $('#nmbidang').attr('value','');
            $('#nmkelompok').attr('value','');
            
            $('#nmbrg').attr('value','');
            $('#harga').attr('value','');
            $('#total1').attr('value','');
            $('#total2').attr('value','');
            //$('#ket').attr('value','');
            $('#bidang').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_bidang',
            queryParams:({gol:cgol})
        });            
        }                    
    });
$('#cmbjenis_u').combogrid({           
        idField:'golongan',  
        textField:'golongan',
        mode:'remote',
        panelWidth:400,
        url:'<?php echo base_url(); ?>index.php/master_int/ambil_gol',
        columns:[[  
               
               {field:'golongan',title:'Kode Golongan',width:100},  
               {field:'nm_golongan',title:'Nama Golongan',width:500}
            ]], 
        onSelect:function(rowIndex,rowData){
            cgol=rowData.golongan;
            ngol=rowData.nm_golongan;
            
            $('#nmgolongan_u').attr('value',ngol);
            $('#bidang_u').combogrid('clear');
            //$('#kdbarang_u').combogrid('clear');
            //$('#kdbarang_u').combogrid('disable');
            $('#nmbidang_u').attr('value','');
            $('#nmkelompok_u').attr('value','');
            
            $('#nmbrg_u').attr('value','');
           
            $('#bidang_u').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_bidang',
            queryParams:({gol:cgol})
        });            
        }                    
    });

     $('#bidang').combogrid({  
            panelWidth:550, 
            idField:'bidang',  
            textField:'bidang',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               
               {field:'bidang',title:'Kode Barang',width:100},  
               {field:'nm_bidang',title:'Nama Barang',width:500}
            ]],  
             onSelect:function(rowIndex,rowData){
                bidang=rowData.bidang;
                nmbidang=rowData.nm_bidang;
                 
                //$('#bidang').attr('value',bidang);
                $('#nmbidang').attr('value',nmbidang);

                $('#kdbarang').combogrid("clear");
                $('#nmkelompok').attr('value',''); 
                $('#kdbarang').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_brg_dh',
                queryParams:({bidang:bidang})});            
        }  
    });

    $('#bidang_u').combogrid({  
            panelWidth:550, 
            idField:'bidang',  
            textField:'bidang',              
            mode:'remote',            
			url:'<?php echo base_url(); ?>index.php/master_int/load_bidang_ada',
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               
               {field:'bidang',title:'Kode Bidang',width:100},  
               {field:'nm_bidang',title:'Nama Bidang',width:500}
            ]],  
             onSelect:function(rowIndex,rowData){
				kode_rekbrg=rowData.bidang;
                nmbidang=rowData.nm_bidang;
				get_rekskpd(kode_rekbrg);
				$('#nmbidang_u').attr('value',nmbidang);
                //$('#kdbarang_u').combogrid('disable');
                $('#kdbarang_u').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_brg_dh',
                queryParams:({bidang:bidang})});            
        }  
    });
    
    $('#kdbarang').combogrid({
        panelWidth:550, 
            idField:'kd_brg',  
            textField:'kd_brg',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'KODE BARANG',width:100}, 
               {field:'nm_brg',title:'NAMA BARANG',width:450}  
            ]],  
             onSelect:function(rowIndex,rowData){
                
                ckd_kelompok  = rowData.kd_brg;                                                        
                cnmkelompok   = rowData.nm_brg;
                $('#nmkelompok').attr('value',cnmkelompok);
            }                                                          
                
    });


    $('#kdbarang_u').combogrid({
        panelWidth:550, 
            idField:'kd_brg',  
            textField:'kd_brg',              
            //mode:'remote',            
            //loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_brg',title:'KODE BARANG',width:100}, 
               {field:'nm_brg',title:'NAMA BARANG',width:450}  
            ]],
            onSelect:function(rowIndex,rowData){
                $('#nmkelompok_u').attr('value',rowData.nm_brg);
            }                                                      
                
    });

    /*$('#rekening').combogrid({
        panelWidth:500, 
            idField:'kd_rek5',  
            textField:'kd_rek5',              
            mode:'remote',            
            loadMsg:"Tunggu Sebentar....!!",                                                 
            columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:150}, 
               {field:'nm_rek5',title:'Nama Rekening',width:350}  
            ]],  
             onSelect:function(rowIndex,rowData){
                $('#nm_rekening').attr('value',rowData.nm_rek5);
            }                                                          
                
    });*/
		
   $('#nomor').combogrid({  
            panelWidth:790,  
            idField:'no_kontrak',  
            textField:'no_kontrak',  
            mode:'remote',
			groupFormatter: function(group){
					return '<div style="color:red"></div>';
				},
            url:'<?php echo base_url(); ?>index.php/master_int/ambil_nomor_kontrak',  
            columns:[[  
				{field:'id',title:'ID',width:20},                  
                {field:'no_kontrak',title:'No Kontrak',width:110},                  
				{field:'kd_kegiatan',title:'Kegiatan',width:130},   
				{field:'kd_rek5',title:'Rekening',width:60},   
				{field:'nilai2',title:'Nilai Realisasi',width:110,align:'right'}, 
				{field:'nilai_bel2',title:'Nilai Aset',width:110,align:'right'}, 				
				{field:'nilai_sisa2',title:'Nilai Sisa',width:110,align:'right'},                 				
				{field:'keterangan',title:'Keterangan',width:500}	
            ]],
            onSelect:function(rowIndex,rowData){
                nospp=rowData.no_spp;
				kode_rebrg=rowData.kd_rek5;
				ostatus=rowData.status;
				kd_kegiatan=rowData.kd_kegiatan;
				//alert(kode_rekbrg);
				$('#nomorspm').combogrid({url:'<?php echo base_url(); ?>index.php/master_int/ambil_nomor_spm',
				queryParams:({kd_kegiatan:kd_kegiatan})});
				$('#nilkont').attr('value',rowData.nilai2);
                $('#nkon').attr('value',rowData.nilai2);
                $('#nkon_hide').attr('value',rowData.nilai);
                $('#nilkont_hide').attr('value',rowData.nilai);
				$('#sisabel').attr('value',rowData.nilai_sisa2);
                $('#sisabel_hide').attr('value',rowData.nilai_sisa);
                $('#unit').combogrid('setValue',rowData.kd_skpd);
                $('#sp2d').attr('value',rowData.no_sp2d);
                //$('#sp2d_dh_update').attr('value',rowData.no_sp2d);
                //$('nosp2d').combogrid('setValue',rowData.no_sp2d);
				$('#nosp2d').attr('value',rowData.no_sp2d);
                $('#kegiatan').attr('value',rowData.kd_kegiatan);
                $('#kegiatan_u').attr('value',rowData.kd_kegiatan);				
                $('#rekening').attr('value',rowData.kd_rek5);
                $('#rekening_u').attr('value',rowData.kd_rek5);
                $('#nm_kegiatan').attr('value',rowData.nm_kegiatan);
                $('#nm_kegiatan_u').attr('value',rowData.nm_kegiatan);
                $('#nm_rekening').attr('value',rowData.nm_rek5);
                $('#nm_rekening_u').attr('value',rowData.nm_rek5);
				
				hkode_rek = rowData.kd_rek5;
				hkode_keg = rowData.kd_kegiatan;
				hnm_rek = rowData.nm_rek5;
				hnm_keg = rowData.nm_kegiatan;
				
                $('#ket').attr('value',rowData.keterangan);
                if (plonline =='1')
                {
                    $('#compy').combobox('setValue',rowData.pimpinan);    
                    // cpimp=rowData.pimpinan;
                    // $('#compy').combobox({url:'<?php echo base_url(); ?>index.php/master_int/ambil_compy/'+cpimp,
                    // queryParams:({q:cpimp})});      					
                }								
				
				/*if(cekdouble!=0){									
					if(ostatus=='1'){
					alert("No Kontrak Sudah Terdaftar, Silahkan Pilih No Kontrak yang Lain");
						$('#tombol_brg').linkbutton('disable');	
						document.getElementById("tombol_simpan").disabled = true;
						document.getElementById("tombol_cetak").disabled = true;						
						$('#totbel').attr('value',rowData.nilai_bel);						
					}else{
						$('#tombol_brg').linkbutton('enable');	
						document.getElementById("tombol_simpan").disabled = false;
						document.getElementById("tombol_cetak").disabled = false;
						$('#totbel').attr('value','0');						
					}						
				}else{
					$('#tombol_brg').linkbutton('enable');
					document.getElementById("tombol_simpan").disabled = false;
					document.getElementById("tombol_cetak").disabled = false;					
				}*/				
                
            }  
            });						
		
		   $('#nomorspm').combogrid({  
            panelWidth:550,  
            idField:'no_spm',  
            textField:'no_spm',  
            mode:'remote',
			groupFormatter: function(group){
					return '<div style="color:red"></div>';
				},
            //url:'<?php echo base_url(); ?>index.php/master_int/ambil_nomor_spm',  
            columns:[[  
				{field:'id',title:'ID',width:20},                  
                {field:'no_spm',title:'No SPM',width:250},                  
				{field:'tgl_spm',title:'Tanggal',width:100},   
				{field:'nilai',title:'Nilai',align:'right',width:150}   
			]],
            onSelect:function(rowIndex,rowData){
                spm=rowData.no_spm;
				tglspm=rowData.tgl_spm;
				nilaispm=rowData.nilai;
				//alert(kode_rekbrg);
                //$('#nomorspm').attr('value',rowData.no_spm);
                $('#tglspm').attr('value',rowData.tgl_spm);
                $('#nilapbd').attr('value',rowData.nilai);
                
            }  
            });
			
        $('#trd').edatagrid({
            toolbar:'#toolbar',
            rownumbers:"true",            
            //singleSelect:"true",
            autoRowHeight:"false",
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    //nilx = rowData.nilai;
            },
        columns:[[ 
        {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},
        {field:'no_dokumen',title:'Nomor',width:100,hidden:true},
        {field:'jns',title:'Jenis',width:100,hidden:true }  ,
        {field:'nm_jenis',title:'Nama Jenis',width:100,hidden:true }  ,
		{field:'kd_rek5',title:'Kode Rekening',width:100 } ,
        {field:'nm_rek5',title:'Nama Rekening',width:100,hidden:true }  ,
        {field:'kd_bidang',title:'Kode Bidang',width:100,hidden:true},
        {field:'nm_bidang',title:'Nama Bidang',width:100,hidden:true},
        {field:'kd_brg',title:'Kode Barang',width:150 }  ,
        {field:'nm_brg',title:'Nama Barang',width:250 }  ,
        {field:'kd_unit',title:'Unit',width:100,hidden:true }  ,
        {field:'kd_uskpd',title:'SKPD',width:100,hidden:true }  ,
        {field:'s_dana',title:'Sumber Dana',width:100,hidden:true }  ,
        {field:'no_sp2d',title:'No SP2D',width:150,hidden:true }  ,
        {field:'tgl_sp2d',title:'TGL SP2D',width:100,hidden:true }  ,
        {field:'nilai_sp2d',title:'Nilai SP2D',width:100,align:'right',hidden:true }  ,
        {field:'nilai_kontrak',title:'Nilai Realisasi',width:100,hidden:true }  ,
        {field:'kd_kegiatan',title:'Kode Kegiatan',width:100,hidden:true }  ,
        {field:'nm_kegiatan',title:'Nama Kegiatan',width:100,hidden:true }  ,        
        {field:'jumlah',title:'Jumlah',width:100,hidden:true }  ,
        {field:'harga',title:'Harga/Unit',width:100,align:'right' }  ,
        {field:'ppn',title:'PPN',width:100,hidden:true }  ,
        {field:'total',title:'Total',width:100,align:'right' }  ,
        {field:'keterangan',title:'Keterangan',width:100,hidden:true }  ,
        {field:'invent',title:'Inventaris',width:100,hidden:true } , 
        {field:'hapus',width:30,align:'center',formatter:function(value,rec)        
        {return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();''/>";}}

        ]]
    });

    
}); 

function load_detail_kosong_dh(){
    var nomor = $('#nomor').combogrid('getValue');
     $(function(){
            $('#trd').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/transaksi_int/trd_isianbrg',
                queryParams:({ no:nomor }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;  
                                                        
                },
                 columns:[[
                     {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},
					{field:'no_dokumen',title:'Nomor',width:100,hidden:true},
					{field:'jns',title:'Jenis',width:100,hidden:true }  ,
					{field:'nm_jenis',title:'Nama Jenis',width:100,hidden:true }  ,
					{field:'kd_rek5',title:'Kode Rekening',width:100 } ,
					{field:'nm_rek5',title:'Nama Rekening',width:100,hidden:true }  ,
					{field:'kd_bidang',title:'Kode Bidang',width:100,hidden:true},
					{field:'nm_bidang',title:'Nama Bidang',width:100,hidden:true},
					{field:'kd_brg',title:'Kode Barang',width:150 }  ,
					{field:'nm_brg',title:'Nama Barang',width:250 }  ,
					{field:'kd_unit',title:'Unit',width:100,hidden:true }  ,
					{field:'kd_uskpd',title:'SKPD',width:100,hidden:true }  ,
					{field:'s_dana',title:'Sumber Dana',width:100,hidden:true }  ,
					{field:'no_sp2d',title:'No SP2D',width:150,hidden:true }  ,
					{field:'tgl_sp2d',title:'TGL SP2D',width:100,hidden:true }  ,
					{field:'nilai_sp2d',title:'Nilai SP2D',width:100,align:'right',hidden:true }  ,
					{field:'nilai_kontrak',title:'Nilai Realisasi',width:100,hidden:true }  ,
					{field:'kd_kegiatan',title:'Kode Kegiatan',width:100,hidden:true }  ,
					{field:'nm_kegiatan',title:'Nama Kegiatan',width:100,hidden:true }  ,        
					{field:'jumlah',title:'Jumlah',width:100,hidden:true }  ,
					{field:'harga',title:'Harga/Unit',width:200,align:'right' }  ,
					{field:'ppn',title:'PPN',width:100,hidden:true }  ,
					{field:'total',title:'Total',width:150,align:'right' }  ,
					{field:'keterangan',title:'Keterangan',width:100,hidden:true }  ,
					{field:'invent',title:'Inventaris',width:100,hidden:true } , 
					{field:'hapus',width:30,align:'center',formatter:function(value,rec)        
						 {return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();''/>";}}
                     
                ]]  
            });
        });
}

function getRowIndex(target){  
            var tr = $(target).closest('tr.datagrid-row');  
            return parseInt(tr.attr('datagrid-row-index'));  
        } 

function sp2d_dh(){
    var kduskpd     = $('#unit').combogrid('getValue');
    var sp2d = document.getElementById('sp2d').value;
    
    /*$(function(){
    $('#nosp2d').combogrid({  
                   panelWidth : 700,
                   //multiple   : true,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/master_int/ambil_sp2d',
                   queryParams :({kdskpd:kduskpd,sp2d:sp2d}),  
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:200},  
                       {field:'tgl_sp2d',title:'Tanggal',width:80},
                       {field:'nilai2',title:'Nilai',width:100,align:'right'},
                       {field:'keperluan',title:'Keterangan',width:320}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                    nil=rowData.nilai;
                    $('#tglsp2d').datebox("setValue",rowData.tgl_sp2d);
                    $('#nilsp2d').attr('value',rowData.nilai2);
                    $('#nilsp2d_hide').attr('value',number_format(nil,2,'.',''));
                       
                   } 
            });*/
    
    //});
}
function sp2d_dh_update(){
    var kduskpd     = $('#unit').combogrid('getValue');
   
    /*var sp2d = document.getElementById('sp2d_u').value;
    alert('sp2d update   '+sp2d);*/
    
	/*$(function(){
    $('#nosp2d_u').combogrid({  
                   panelWidth : 700,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/master_int/ambil_sp2d',
                   queryParams :({kdskpd:kduskpd}),  
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:200},  
                       {field:'tgl_sp2d',title:'Tanggal',width:80},
                       {field:'nilai2',title:'Nilai',width:100,align:'right'},
                       {field:'keperluan',title:'Keterangan',width:320}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                    nil=rowData.nilai;
                    $('#tglsp2d_u').datebox("setValue",rowData.tgl_sp2d);
                    $('#nilsp2d_u').attr('value',rowData.nilai2);
                    $('#nilsp2d_hide_u').attr('value',number_format(nil,2,'.',''));
                       
                   } 
            });
    
    });*/
} 

/*function rekening(){
    var kegiatan = document.getElementById('kegiatan').value;
	var rekening = rekening_transaksi;
    $(function(){
        $('#rekening').combogrid({
            panelWidth  : 500,
            idField     : 'kd_rek5',
            textField   : 'kd_rek5',
            mode        : 'remote',
            url         : '<?php echo base_url(); ?>index.php/master_int/ambil_rekening',
            queryParams : ({keg:kegiatan,rek:rekening}),
            columns     :[[
                {field:'kd_rek5',title:'Kode Rekening',width:150},
                {field:'nm_rek5',title:'Nama Rekening',width:350}
            ]],
            onSelect:function(rowIndex,rowData){
                $('#nm_rekening').attr('value',rowData.nm_rek5);
            }
        });
    });
}*/ 

function tab1(){
    $('#tabs1').click();
    //$('#trh').edatagrid('reload');      
}
function tab2(){
   $('#tabs2').click()
   cekdouble=1;
}

function kosong(){
	var cthn = '<?php echo ($this->session->userdata('ta_simbakda')); ?>';
	var skpd = '<?php echo ($this->session->userdata('skpd'));?>';
	//var unit_skpd = '<?php echo ($this->session->userdata('unit_skpd'));?>';
    var cdate 	= '<?php echo date("Y-m-d"); ?>';
    $('#unit').combogrid('setValue',skpd);
    $('#unit').combogrid("clear");
    $('#mlokasi').attr('value','');
    $('#tahun').combobox('setValue',cthn);
    $('#txtnodok_h').attr('value','');
    $('#sp2d').attr('value','');
    $('#tanggal').datebox('setValue',cdate);
    $('#nilkont').attr('value','');
    $('#nilapbd').attr('value','');
    $('#compy').combobox('setValue','');
    $('#milik').combobox('setValue','');
    $('#wilayah').combobox('setValue','');
    $('#dana').combobox('setValue','');
    $('#bukti').combobox('setValue','');
    $('#dasar').combobox('setValue','');
    $('#dsno').attr('value','');
    $('#dstgl').datebox('setValue',cdate);
    $('#thn2').combobox('setValue','');    
    $('#perolehan').combobox('setValue','');
    $('#krg').attr('value','');
    $('#nmunit').attr('value',''); 
    $('#nmlokasi').attr('value','');
    $('#bidang_u').attr('value','');
    $('#rekbrg').attr('value','');
	$('#nmbidang_u').attr('value','');
    $('#nmrekbrg').attr('value','');
	$('#total').attr('value','0');
    $("#total2").attr('value','');    
    $("#nomor").combogrid('grid').datagrid('reload');
    $("#nomorspm").combogrid('grid').datagrid('reload');
    $('#nilkont_hide').attr('value','');
    $('#nomor').combogrid('clear');
    $('#nomorspm').combogrid('clear');
    $('#nkon').attr('value','0');
    $('#nkon_hide').attr('value','');
    $('#totbel').attr('value','0');
    $('#totbel_hide').attr('value','');
	$('#sisabel').attr('value','0');
    $('#sisabel_hide').attr('value','');
	
    $('#nomor').combogrid('enable');
    var pidx  = 0   ;
    updt = 'f';
    load_detail_kosong_dh();
	//max_rinci();
}

function kosong2(){  
    
    $("#dialog-modal :checkbox").attr("checked",false);
	//$('#cmbjenis').combobox('setValue','');
    $('#cmbjenis').combogrid('clear');
	$('#bidang').combogrid('setValue','');
	$('#sbrdana').combobox('setValue','');
    $('#nmbrg').attr('value','');
    //$('#kegiatan').attr('value','');
    $('#nosp2d').attr('value','');
    $('#rekening').attr('value','');
    $('#jumlah').attr('value','');
    $('#harga').attr('value','');
    $('#total1').attr('value','');
    $('#nilppn').attr('value','');
    $('#total2').attr('value','');
    //$('#ket').attr('value','');
    $('#bidang_u').combogrid('clear');
    $('#rekbrg').combogrid('clear');
	$('#nmbidang_u').attr('value','');
    $('#nmrekbrg').attr('value','');
	$('#nmgolongan').attr('value','');
    $('#nmbidang').attr('value','');
    $('#nmkelompok').attr('value','');
    $('#kdbarang').combogrid('clear');
    //$('#nm_kegiatan').attr('value','');
    $('#nm_rekening').attr('value','');
    $('#ket_u').attr('value','');
    cdate 		= '<?php echo date("Y-m-d"); ?>';
    $('#dstgl').datebox('setValue',cdate);
    updt = 't'; 
}

function get_rekskpd(kode_rekbrg){
			var x = kode_rekbrg;
	$('#rekbrg').combogrid({  
            panelWidth:600,  
			width:300,
            idField:'kd_barang',  
            textField:'kd_barang',  
            mode:'remote',                      
            url:'<?php echo base_url(); ?>index.php/master_int/ambil_rekbrg?kode='+x,  
            columns:[[  
               {field:'kd_barang',title:'Kode Barang',width:100},  
               {field:'nm_barang',title:'Nama Barang',width:370}     
            ]],  
            onSelect:function(rowIndex,rowData){
               $('#nmrekbrg').attr('value',rowData.nm_barang);  				
            } 
         });  	 
	}

function getData(no,tgl,nilkon,nilapbd,kdcomp,kdmilik,kdwilayah,kduskpd,kd_unit,jnsdana,tahunang,buktibyr,dasaroleh,nooleh,tgloleh,tahunoleh,tot,cr_oleh,sisa2)
	{
    //kosong();
    $('#txtnodok_h').attr('value',no);
    $('#nomor').combogrid('setValue',no); 
    $('#tanggal').datebox('setValue',tgl);
    $('#nilkont').attr('value',nilkon);
    $('#nkon_hide').attr('value',nilkon);
    $('#nkon').attr('value',nilkon);	
    $('#nilapbd').attr('value',nilapbd);
    $('#compy').combobox('setValue',kdcomp);
    $('#milik').combobox('setValue',kdmilik);
    $('#wilayah').combobox('setValue',kdwilayah);
    $('#mlokasi').attr('value',kd_unit); 
    $('#unit').combogrid('setValue',kduskpd);
    $('#dana').combobox('setValue',jnsdana);
    $('#tahun').combobox('setValue',tahunang);
    $('#bukti').combobox('setValue',buktibyr);
    $('#dasar').combobox('setValue',dasaroleh);
    $('#dsno').attr('value',nooleh);
    $('#dstgl').datebox('setValue',tgloleh);
    $('#thn2').combobox('setValue',tahunoleh);    
    $('#total').attr('value',number_format(tot,2,'.',','));
    $('#totbel').attr('value',number_format(tot,2,'.',','));
    $('#totbel_hide').attr('value',tot);
    $('#perolehan').combobox('setValue',cr_oleh); 	
    $('#sisabel').attr('value',sisa2);  
}


function getDetail(no,cjns,cnmjns,kdbrg,nmbrg,sdana,nosp2d,tglsp2d,nilsp2d,nilkont,kdgiat,kdrek5,jml,hrg,cppn,tot,ket,invt)
{
    kosong2();
    updt = 't';
    $("#cmbjenis").combobox("setValue",cjns);
    $("#bidang").combobox("setValue",cbdg);
	$("#cmkel").combobox("setValue",ckel);
    $("#cmsubkel").combobox("setValue",csubkel);
    $("#kdbrg").combogrid("setValue",kdbrg);
    $("#nmbrg").attr("value",nmbrg);
    $("#sbrdana").combobox("setValue",sdana);
    $("#nosp2d").attr("value",nosp2d);
    $("#tglsp2d").datebox("setValue",tglsp2d);
    $("#nilsp2d").attr("value",nilsp2d);
    $("#nilkon1").attr("value",nilkont);
    $("#kegiatan").attr("value",kdgiat);
    $("#rekening").attr("value",kdrek5);
    $("#jumlah").attr("value",jml);
    $("#harga").attr("value",hrg);
    $("#nilppn").attr("value",cppn);
    if (angka(cppn)==0){
        $("#ppn").attr("checked",false);
    }else{
        $("#ppn").attr("checked",true);
    }
    $("#total2").attr("value",tot);
    total_updt = tot;
    $("#ket").attr("value",ket);    
    hitung();    
    tambah_detail();
}


function loadDetail(){
    var nomor = $('#nomor').combogrid('getValue');
     $(function(){
            $('#trd').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/transaksi_int/trd_isianbrg',
                queryParams:({ no:nomor }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx; 
                                                         
                },
                onDblClickRow:function(rowIndex,rowData){
                    idx=rowData.idx;
                    no_dokumen=rowData.no_dokumen ;
                    jns=rowData.jns ;
                    nm_jenis=rowData.nm_jenis ;
                    kd_bidang=rowData.kd_bidang ;
                    nm_bidang=rowData.nm_bidang ;
                    kd_brg=rowData.kd_brg ;
                    nm_brg=rowData.nm_brg ;
                    kd_unit=rowData.kd_unit ;
                    kd_uskpd=rowData.kd_uskpd ;
                    s_dana=rowData.s_dana ;
                    no_sp2d=rowData.no_sp2d ;
                    tgl_sp2d=rowData.tgl_sp2d ;
                    nilai_sp2d=number_format(rowData.nilai_sp2d,2,'.',',');
                    nilai_kontrak=rowData.nilai_kontrak ;
                    kd_kegiatan=rowData.kd_kegiatan ;
                    nm_kegiatan=rowData.nm_kegiatan ;
                    kd_rek5=rowData.kd_rek5 ;
                    nm_rek5=rowData.nm_rek5 ;
                    jumlah=rowData.jumlah ;
                    harga=number_format(rowData.harga,2,'.',',');
                    ppn=number_format(rowData.ppn,2,'.',',');
                    total=number_format(rowData.total,2,'.',',');
                    keterangan=rowData.keterangan ;
                    invent=rowData.invent ;
                    //$('#kdbarang_u').combogrid('disable');
                    
                    //update_detail(idx);

                },
                 columns:[[
                     {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},
        {field:'no_dokumen',title:'Nomor',width:100,hidden:true},
        {field:'jns',title:'Jenis',width:100,hidden:true }  ,
        {field:'nm_jenis',title:'Nama Jenis',width:100,hidden:true }  ,
		{field:'kd_rek5',title:'Kode Rekening',width:100 } ,
        {field:'nm_rek5',title:'Nama Rekening',width:100,hidden:true }  ,
        {field:'kd_bidang',title:'Kode Bidang',width:100,hidden:true},
        {field:'nm_bidang',title:'Nama Bidang',width:100,hidden:true},
        {field:'kd_brg',title:'Kode Barang',width:150 }  ,
        {field:'nm_brg',title:'Nama Barang',width:250 }  ,
        {field:'kd_unit',title:'Unit',width:100,hidden:true }  ,
        {field:'kd_uskpd',title:'SKPD',width:100,hidden:true }  ,
        {field:'s_dana',title:'Sumber Dana',width:100,hidden:true }  ,
        {field:'no_sp2d',title:'No SP2D',width:150,hidden:true }  ,
        {field:'tgl_sp2d',title:'TGL SP2D',width:100,hidden:true }  ,
        {field:'nilai_sp2d',title:'Nilai SP2D',width:100,align:'right',hidden:true }  ,
        {field:'nilai_kontrak',title:'Nilai Realisasi',width:100,hidden:true }  ,
        {field:'kd_kegiatan',title:'Kode Kegiatan',width:100,hidden:true }  ,
        {field:'nm_kegiatan',title:'Nama Kegiatan',width:100,hidden:true }  ,        
        {field:'jumlah',title:'Jumlah',width:100,hidden:true }  ,
        {field:'harga',title:'Harga/Unit',width:100,align:'right' }  ,
        {field:'ppn',title:'PPN',width:100,hidden:true }  ,
        {field:'total',title:'Total',width:100,align:'right' }  ,
        {field:'keterangan',title:'Keterangan',width:100,hidden:true }  ,
        {field:'invent',title:'Inventaris',width:100,hidden:true } , 
        {field:'hapus',width:30,align:'center',formatter:function(value,rec)        
             {return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();''/>";}}
                     
                ]]  
            });
        });
    $('#trd').edatagrid('unselectAll');
}

function update_detail(idx){    
    $("#dialog-modal-update").dialog('open');
    var skpd= $('#unit').combogrid('getValue');
       alert('index update_detail   '+idx);
    nilkont     = document.getElementById('nilkont').value;
    nilkont_hide     = document.getElementById('nilkont_hide').value;
    nilapbd     = document.getElementById('nilapbd').value;
    nilsp2d     = document.getElementById('nilsp2d').value;
    kegiatan     = document.getElementById('kegiatan').value;
    rekening     = document.getElementById('rekening').value;
    //nm_kegiatan     = document.getElementById('nm_kegiatan').value;
    //nm_rekening     = document.getElementById('nm_rekening').value;
    $('#idx').attr('value',idx);
    $('#cmbjenis_u').combogrid("setValue",jns);
    $('#cmbjenis_u').combogrid('disable');
    $('#nmgolongan_u').attr('Value',nm_jenis);
    $('#bidang_u').combogrid('setValue',kd_bidang);
    $('#bidang_u').combogrid('disable');
    $('#nmbidang_u').attr('Value',nm_bidang);
    $('#kdbarang_u').combogrid('setValue',kd_brg);
    $('#kdbarang_u').combogrid('disable');
    $('#nmkelompok_u').attr('Value',nm_brg);
    $('#nosp2d_u').attr("value",no_sp2d);
    //$('#sp2d_dh_update').attr('Value',no_sp2d);
    sbrdana     = $('#dana').combobox('getValue');
    sp2d_dh_update();
    $('#jumlah_u').attr('value',jumlah);
    $('#harga_u').attr('value',harga);
    $('#nilppn_u').attr('value',ppn);
    if (angka(ppn)==0){
        $("#ppn_u").attr("checked",false);
    }else{
        $("#ppn_u").attr("checked",true);
    }
    $('#total1_u').attr('value',total);
    hitung_update();

    $('#tglsp2d_u').datebox('setValue',tgl_sp2d);
    $('#nilkon1_u').attr('value',nilkont);
    $('#nilkon1_hide_u').attr('value',nilkont_hide); 
    $('#nilsp2d_u').attr('value',nilai_sp2d);
    $('#ket_u').attr('value',keterangan);   
    
    $('#sbrdana_u').combobox('setValue',sbrdana);
    $('#nmbrg_u').attr('value','');
    
    
    $('#kegiatan_u').attr('value',kd_kegiatan);
    $('#rekening_u').attr('value',kd_rek5);
    $('#nm_kegiatan_u').attr('value',nm_kegiatan);
    $('#nm_rekening_u').attr('value',nm_rek5);
    
    
    
    
    //$('#total2_u').attr('value','');
    
    
    
    
    $('#nilsp2d_hide_u').attr('value',nilai_sp2d);

    
}

function load_sum_trd_isianbrg(){
        var kduskpd     = $('#unit').combogrid('getValue');                
        var nomor = document.getElementById('nomor').value; 
		var nkontrak = document.getElementById('nilkon1_hide').value; 		
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({no:nomor,kduskpd:kduskpd}),
            url       : "<?php echo base_url(); ?>index.php/transaksi_int/load_sum_trd_isianbrg",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    
                    $("#total").attr("value",n['rektotal']);					
                });
            }
         });
        });
    }

function set_grid(){
    //$(function(){
    $('#trd').edatagrid({
        columns:[[
            {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},
        {field:'no_dokumen',title:'Nomor',width:100,hidden:true},
        {field:'jns',title:'Jenis',width:100,hidden:true }  ,
        {field:'nm_jenis',title:'Nama Jenis',width:100,hidden:true }  ,
		{field:'kd_rek5',title:'Kode Rekening',width:100 } ,
        {field:'nm_rek5',title:'Nama Rekening',width:100,hidden:true }  ,
        {field:'kd_bidang',title:'Kode Bidang',width:100,hidden:true},
        {field:'nm_bidang',title:'Nama Bidang',width:100,hidden:true},
        {field:'kd_brg',title:'Kode Barang',width:150 }  ,
        {field:'nm_brg',title:'Nama Barang',width:250 }  ,
        {field:'kd_unit',title:'Unit',width:100,hidden:true }  ,
        {field:'kd_uskpd',title:'SKPD',width:100,hidden:true }  ,
        {field:'s_dana',title:'Sumber Dana',width:100,hidden:true }  ,
        {field:'no_sp2d',title:'No SP2D',width:150,hidden:true }  ,
        {field:'tgl_sp2d',title:'TGL SP2D',width:100,hidden:true }  ,
        {field:'nilai_sp2d',title:'Nilai SP2D',width:100,align:'right',hidden:true }  ,
        {field:'nilai_kontrak',title:'Nilai Realisasi',width:100,hidden:true }  ,
        {field:'kd_kegiatan',title:'Kode Kegiatan',width:100,hidden:true }  ,
        {field:'nm_kegiatan',title:'Nama Kegiatan',width:100,hidden:true }  ,        
        {field:'jumlah',title:'Jumlah',width:100,hidden:true }  ,
        {field:'harga',title:'Harga/Unit',width:100,align:'right' }  ,
        {field:'ppn',title:'PPN',width:100,hidden:true }  ,
        {field:'total',title:'Total',width:100,align:'right' }  ,
        {field:'keterangan',title:'Keterangan',width:100,hidden:true }  ,
        {field:'invent',title:'Inventaris',width:100,hidden:true } , 
        {field:'hapus',width:30,align:'center',formatter:function(value,rec)        
             {return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus_detail();''/>";}}

		]]
    });
   // });
}

	function hitung(){
	jml 	= angka(document.getElementById('jumlah').value);
 //   nil 	= angka(document.getElementById('nkon').value);
    hrg 	= angka(document.getElementById('harga').value);
    chk 	= document.getElementById('ppn').checked;
    total 	= jml * hrg;
	//nol		= nil - total;
    if (chk==true){
        totppn = total * 10/100;
        tothrg = total - totppn;        
    } else {
        totppn = 0;
        tothrg = total;
    }
    total 	= number_format(total,2,'.',',');
    $('#total1').attr('value',total);        
    totppn 	= number_format(totppn,2,'.',',');
    $('#nilppn').attr('value',totppn);
    tothrg 	= number_format(tothrg,2,'.',',');
    $('#total2').attr('value',tothrg);
	}

function hitung_update(){
    jml     = angka(document.getElementById('jumlah_u').value);
    hrg     = angka(document.getElementById('harga_u').value);
    chk     = document.getElementById('ppn_u').checked;
    total   = jml * hrg ;    
    if (chk==true){
        totppn = total * 10/100;
        tothrg = total - totppn;
		
    } else {
        totppn = 0;
        tothrg = total;
    }
    total   = number_format(total,2,'.',',');
    $('#total1_u').attr('value',total);        
    totppn  = number_format(totppn,2,'.',',');
    $('#nilppn_u').attr('value',totppn);
    tothrg  = number_format(tothrg,2,'.',',');
    $('#total2_u').attr('value',tothrg);
}

function hapus(){
      var rows  = $('#trh').datagrid('getSelected');
      var nomor = rows.no_dokumen;
      var skp   = rows.kd_uskpd;
	  var ntotal = kode_tot;
      var invent= rows.invent; 
      if(invent==1){
        alert('Data Sudah Di-Inventariskan dan Tidak Bisa Dihapus');
        exit();
      }else{
        var del   = confirm("Apakah Anda Yakin ingin menghapus data   "+nomor+"  "+skp+"?");
      }
    if(del==true){
        var urll = '<?php echo base_url(); ?>index.php/transaksi_int/hapus_isianbrg';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:skp,total:ntotal}),function(data){
            status = data;
            if (status=='0'){
                //alert('Gagal Hapus..!!');
				swal({
                            title: "Berhasil",
                            text: "Data telah dihapus!!",
                            imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
                            });				
                exit();
            } else {
                swal({
                            title: "Berhasil",
                            text: "Data telah dihapus!!",
                            imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
                            });
				
				$('#trh').edatagrid('reload');
				
                exit();
            }
         });
        });    
      }
    } 




function hapus_detail(){
    var kduskpd     = $('#unit').combogrid('getValue');                
    var nomor = document.getElementById('nomor').value;
    var kdunit = document.getElementById('mlokasi').value;
    var rows = $('#trd').datagrid('getSelected');
    ckd =   rows.kd_brg;
    cnm =   rows.nm_brg;
    ctot =   rows.total; 
                                    
    var idx = $('#trd').datagrid('getRowIndex',rows);
    var tny = confirm('Yakin Ingin Menghapus Data, Kode Barang : '+ckd+' Nama Barang : '+cnm+' Nilai : '+ctot);
    if (tny==true){        
        $('#trd').datagrid('deleteRow',idx);            
        
        /* var urll = '<?php  echo base_url(); ?>index.php/transaksi_int/dsimpan_trd_delete_dh';
             $(document).ready(function(){
             $.post(urll,({cskpd:kduskpd,no:nomor,kd_brg:ckd,kdunit:kdunit}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             }); */
        
        total = angka(document.getElementById('total').value) - ctot;  
              
        $('#total').attr('value',number_format(total,2,'.',','));
        $('#totbel').attr('value',number_format(total,2,'.',',')); 
        $('#totbel_hide').attr('value',number_format(total,2,'.',''));                          
        kosong2();
    }                     
}

function tambah_detail(){    
    $("#dialog-modal").dialog('open');   
    nilkont 	= document.getElementById('nilkont').value;
    nilkont_hide     = document.getElementById('nilkont_hide').value;
    nilapbd 	= document.getElementById('nilapbd').value;
    nilsp2d     = document.getElementById('nilsp2d').value;
    kegiatan     = hkode_keg;//document.getElementById('kegiatan').value;
    rekening     = hkode_rek;//document.getElementById('rekening').value;
    nm_kegiatan     = hnm_keg;//document.getElementById('nm_kegiatan').value;
    nm_rekening     = hnm_rek;//document.getElementById('nm_rekening').value;

	//alert(rekening);
	//alert(nm_rekening);
	//hkode_rek = rowData.kd_rek5;
	//hkode_keg = rowData.kd_kegiatan;
	//hnm_rek = rowData.nm_rek5;
	//hnm_keg = rowData.nm_kegiatan;	
	
    sbrdana 	= $('#dana').combobox('getValue');
    cdate 		= '<?php echo date("Y-m-d"); ?>';
    $('#tglsp2d').datebox('setValue',cdate);
    $('#nilkon1').attr('value',nilkont);
    $('#nilkon1_hide').attr('value',nilkont_hide); 
    $('#nilsp2d').attr('value','');
	$('#cmbjenis').combogrid("clear");
    $('#sbrdana').combobox('setValue',sbrdana);
    $('#nmbrg').attr('value','');
	$('#bidang').combogrid('setValue','');
    $('#nosp2d').attr('value','');
    $('#kegiatan').attr('value',kegiatan);
    $('#rekening').attr('value',rekening);
    $('#nm_kegiatan').attr('value',nm_kegiatan);
    $('#nm_rekening').attr('value',nm_rekening);
    $('#jumlah').attr('value','');
    $('#harga').attr('value','');
    $('#total1').attr('value','');
    $('#nilppn').attr('value','');
    $('#total2').attr('value','');
    //$('#ket').attr('value','');
    $('#nmgolongan').attr('Value','');
    $('#bidang').combogrid("clear");
    $('#kdbarang').combogrid("clear");
    $('#nmbidang').attr('Value','');
    $('#nmkelompok').attr('Value','');
    $('#nilsp2d_hide').attr('value','');	
    sp2d_dh();
    //rekening(kegiatan);
}

function cetak_bap(){    
    $("#dialog-modal_bap").dialog('open');                    
}

function tutup(){ 
		 swal({
					title: "Jangan lupa disimpan.!!",
					type:"warning"
					});   
    $("#dialog-modal").dialog('close');
    $("#dialog-modal-update").dialog('close');
    $("#dialog-modal_bap").dialog('close');                      
}

function simpan(){
    
    var no          = $('#nomor').combogrid('getValue');   
    var tgl         = $('#tanggal').datebox('getValue');  
    var nilkon      = document.getElementById('nilkont_hide').value;  
    var nilapbd     = angka(document.getElementById('nilapbd').value);  
	var nomorspm    = $('#nomorspm').combogrid('getValue');
	var tglspm		= document.getElementById('tglspm').value;
	if(nilapbd==''){
		nilapbd=0;
	}
	
    var kdcomp      = $('#compy').combobox('getValue');
    //alert(kdcomp);
    var kdmilik     = $('#milik').combobox('getValue');  
    var kdwilayah   = $('#wilayah').combobox('getValue');  
    var kduskpd     = $('#unit').combogrid('getValue');  
    var mlokasi     = document.getElementById('mlokasi').value;  
    var jnsdana     = $('#dana').combobox('getValue');  
    var tahunang    = $('#tahun').combobox('getValue');  
    var buktibyr    = $('#bukti').combobox('getValue');  
    var dasaroleh   = $('#dasar').combobox('getValue');  
    var nooleh      = document.getElementById('dsno').value;  
    var tgloleh     = $('#dstgl').datebox('getValue');  
    var tahunoleh   = '<?php echo ($this->session->userdata('ta_simbakda')); ?>';   
    var tot         = angka(document.getElementById('total').value);     
    var cr_oleh     = $('#perolehan').combobox('getValue');
    var nkon        = document.getElementById('nkon_hide').value;
	var nkon2       = document.getElementById('nilkon1_hide').value;
    var ntotbel     = document.getElementById('totbel_hide').value;
    //var sel         = nkon2 - ntotbel;
	var sisa		= document.getElementById('sisabel_hide').value;
	var sel         = sisa - ntotbel;
	var sisa_view     = number_format(sisa,2,',','.');    
	var nkon2_view     = number_format(nkon2,2,',','.');    
    var selisih     = number_format(sel,2,',','.');    	
	var rrek 		= hkode_rek;
	var rkeg		= hkode_keg;
	var rrek_nm		= hnm_rek;
	var rkeg_nm		= hnm_keg;
	var no_sp2d     = "";
	var nilai_sisa  = nilkon - tot;
	var stt         = "1";
//	var spm			= document.getElementById('nomorspm').value;
//	var tglspm		= document.getElementById('tglspm').value;
	
	
	//cek_rekan();
    // alert(kdcomp);  
    // alert('/');  
    

    $('#trd').datagrid('selectAll');
    var rows = $('#trd').datagrid('getSelections');

        for(var i=0; i<rows.length; i++){
                cidx        = rows[i].idx;
                jns         = rows[i].jns;

            }
	
	if(sel<0){
        sweetAlert("MAAF...!!","Total Aset Tidak Boleh Melebihi Nilai Sisa "+sisa_view);
        $('#trd').datagrid('unselectAll');
        exit();
    }
	
		if(sel>0){
        sweetAlert("MAAF...!!","Total Aset Tidak Boleh Kurang dari "+sisa_view);
        $('#trd').datagrid('unselectAll');
        exit();
    }
	/*		
    if(sel<0){
        sweetAlert("MAAF...!!","Total Transaksi Tidak Boleh Melebihi Nilai Realisasi "+nkon2_view);
        $('#trd').datagrid('unselectAll');
        exit();
    }
	    if(sel>0){
        sweetAlert("MAAF...!!","Total Transaksi Tidak Boleh Kurang dari Nilai Realisasi "+nkon2_view);
        $('#trd').datagrid('unselectAll');
        exit();
    }*/
    if (no==''){
		sweetAlert("MAAF..!!", "Nomor Dokumen mohon diisi", "error");
        exit();
    } 
    if (tgl==''){
		sweetAlert("MAAF..!!", "Nomor Tanggal Dokumen mohon diisi", "error");
        exit();
    }
    if (jns!='01' ){
        if(kdcomp==''){
    		sweetAlert("MAAF..!!", "Rekanan mohon diisi", "error");
            exit();
        }
    }
    if (kdwilayah==''){
		sweetAlert("MAAF..!!", "Wilayah mohon diisi", "error");
        exit();
    }            
    if (kduskpd==''){
		sweetAlert("MAAF..!!", "SKPD mohon diisi", "error");
        exit();
    }       
	
	
    //csql_h = " values('"+no+"','"+kdcomp+"','"+tgl+"','"+kdmilik+"','"+kdwilayah+"','"+mlokasi+"','"+kduskpd+"','"+jnsdana+"','"+buktibyr+"','"+dasaroleh+"','"+nooleh+"','"+tahunang+"','','"+tgloleh+"','"+tahunoleh+"','"+nilkon+"','"+nilapbd+"','user','<?php echo date('y-m-d H:i:s'); ?>','"+tot+"','"+cr_oleh+"')";
 
	csql_sisa = "values('"+no+"','"+no_sp2d+"','"+rkeg+"','"+rkeg_nm+"','"+rrek+"','"+rrek_nm+"','"+tot+"','"+nilai_sisa+"','"+kduskpd+"','"+stt+"','"+nilkon+"')";	
 
    $(document).ready(function(){
        $.ajax({
            type: "POST",       
            dataType : 'json',         
            data: ({tabel:'trh_isianbrg',nomorspm:nomorspm,tglspm:tglspm,no:no,tgl:tgl,nilkon:nilkon,nilapbd:nilapbd,kdcomp:kdcomp,kd_unit:kduskpd,kdmilik:kdmilik,kdwilayah:kdwilayah,mlokasi:mlokasi,jnsdana:jnsdana,tahunang:tahunang,buktibyr:buktibyr,dasaroleh:dasaroleh,nooleh:nooleh,tgloleh:tgloleh,tahunoleh:tahunoleh,tot:tot,cr_oleh:cr_oleh,csql_sisa:csql_sisa}),
            url: '<?php echo base_url(); ?>index.php/transaksi_int/simpan_isianbrg',
            success:function(data){
                simpan_detail_dh();
                                                                                                                    
            }
        });
   });  
                                  
}

function simpan_detail_dh(){
    var no = $('#nomor').combogrid('getValue');
    var kduskpd     = $('#unit').combogrid('getValue');
	var kd_comp     = $('#compy').combogrid('getValue');
    var csql = '';
    $('#trd').datagrid('selectAll');
    var rows = $('#trd').datagrid('getSelections');
	
        for(var i=0; i<rows.length; i++){
                cidx        = rows[i].idx;
                no_dokumen  =rows[i].no_dokumen;
                jns         =rows[i].jns;
                nmjns       =rows[i].nm_jenis;
                kd_bidang   =rows[i].kd_bidang;
                cnmbidang   =rows[i].nm_bidang;
                kd_brg      =rows[i].kd_brg;
                nm_brg      =rows[i].nm_brg;
                kd_unit     =rows[i].kd_unit;
                kd_uskpd    =rows[i].kd_uskpd;
                s_dana      =rows[i].s_dana;
                no_sp2d     =rows[i].no_sp2d;
                tgl_sp2d    =rows[i].tgl_sp2d;
                nilai_sp2d  =rows[i].nilai_sp2d;
                nilai_kontrak =rows[i].nilai_kontrak;
                kd_kegiatan =rows[i].kd_kegiatan;
                nm_kegiatan =rows[i].nm_kegiatan;
                kd_rek5     =rows[i].kd_rek5;
                nm_rek5     =rows[i].nm_rek5;
                jumlah      =rows[i].jumlah;
                harga       =rows[i].harga;
                ppn         =angka(rows[i].ppn);
                total       =rows[i].total;
                keterangan  =rows[i].keterangan;
                invent      =rows[i].invent;
                stt 		= "1";
				nilai_sisa 	= nilai_kontrak - total;
				
			   
				if(nilai_sp2d==''){
					nilai_sp2d=0;
				}
				
                if(i>0){
                    csql = csql+","+"('"+no_dokumen+"','"+kd_unit+"','"+kd_uskpd+"','"+jns+"','"+nmjns+"','"+kd_bidang+"','"+cnmbidang+"','"+kd_brg+"','"+nm_brg+"','"+kd_kegiatan+"','"+nm_kegiatan+"','"+kd_rek5+"','"+nm_rek5+"','"+jumlah+"','"+harga+"','"+total+"','"+no_sp2d+"','"+tgl_sp2d+"','"+nilai_sp2d+"','"+keterangan+"','"+invent+"','"+ppn+"','0','"+s_dana+"','"+jns+"','"+nilai_kontrak+"')";
					//csql_sisa = csql_sisa+","+"('"+no_dokumen+"','"+no_sp2d+"','"+kd_kegiatan+"','"+nm_kegiatan+"','"+kd_rek5+"','"+nm_rek5+"','"+total+"','"+nilai_sisa+"','"+kd_uskpd+"','"+stt+"','"+nilai_kontrak+"')";
                } else {
                    csql = "values('"+no_dokumen+"','"+kd_unit+"','"+kd_uskpd+"','"+jns+"','"+nmjns+"','"+kd_bidang+"','"+cnmbidang+"','"+kd_brg+"','"+nm_brg+"','"+kd_kegiatan+"','"+nm_kegiatan+"','"+kd_rek5+"','"+nm_rek5+"','"+jumlah+"','"+harga+"','"+total+"','"+no_sp2d+"','"+tgl_sp2d+"','"+nilai_sp2d+"','"+keterangan+"','"+invent+"','"+ppn+"','0','"+s_dana+"','"+jns+"','"+nilai_kontrak+"')";                                            
					//csql_sisa = "values('"+no_dokumen+"','"+no_sp2d+"','"+kd_kegiatan+"','"+nm_kegiatan+"','"+kd_rek5+"','"+nm_rek5+"','"+total+"','"+nilai_sisa+"','"+kd_uskpd+"','"+stt+"','"+nilai_kontrak+"')";
                }				
				
        }
                $(document).ready(function(){
                $.ajax({
                    type: "POST", 
                    dataType:'json', 
                    url: '<?php echo base_url(); ?>/index.php/transaksi_int/simpan_isianbrg',   
                    data: ({tabel:'trd_isianbrg',no:no,sql:csql,kd_unit:kduskpd,kdcomp:kd_comp}),
                    
                    success:function(data){
                        status=data.pesan;
                        if(status=='1'){
                            $('#trd').edatagrid('unselectAll');
                        swal({
                            title: "Berhasil",
                            text: "Data telah disimpan.!!",
                            imageUrl:"<?php echo base_url();?>/lib/images/accept.png"
                            });
                            //$("#dialog-modal").dialog('close');
                            tab1();
                            $("#trh").edatagrid("reload");
							kosong();
                        }else{
                            alert('gagal');
                        }   
                    }                                        
                });
            });
        //}
}

function append_save(){
    $('#trd').edatagrid('selectAll');
    var rows = $('#trd').edatagrid('getSelections');
    jgrid = rows.length ;
    
	var xkode_brg = $('#rekbrg').combogrid('getValue');     
	//alert(xkode_brg);
	var xgol = xkode_brg.substr(0,2);
	var xbid = xkode_brg.substr(0,4);
	
	//alert(xgol);
	//alert(xbid);
    var no_dokumen  = $('#nomor').combogrid('getValue');    
    var cjns        = xgol;//$('#cmbjenis').combobox('getValue');     
    var nmjns       = document.getElementById('nmgolongan').value;     
    var cbdg        = xbid;//$('#bidang').combobox('getValue');    
    var cnmbidang   = document.getElementById('nmbidang').value;   
    var kdbrg       = xkode_brg;//$('#kdbarang').combogrid('getValue');     
    var cnmbrg      = document.getElementById('nmrekbrg').value;//document.getElementById('nmkelompok').value;     
    var nosp2d      = document.getElementById('nosp2d').value;//$('#nosp2d').combogrid('getValue');   
    var tglsp2d     = $('#tglsp2d').datebox('getValue');    
    var nilsp2d     = document.getElementById('nilsp2d_hide').value;    
    var nilkont     = document.getElementById('nilkon1_hide').value;    
    var kdgiat      = document.getElementById('kegiatan').value;    
    var nmkegi      = document.getElementById('nm_kegiatan').value;      
    var kdrek5      = document.getElementById('rekening').value;      
    var nmrek5      = document.getElementById('nm_rekening').value;    
    var jml         = document.getElementById('jumlah').value;     
    var hrg         = angka(document.getElementById('harga').value);         
    var tot1        = angka(document.getElementById('total1').value);       
    var cppn        = document.getElementById('nilppn').value;          
    var tot         = angka(document.getElementById('total2').value);       
    var ket         = document.getElementById('ket').value;         
    var total       = angka(document.getElementById('total').value);   
    var kd_unit     = document.getElementById('mlokasi').value;     
    var kd_uskpd    = $('#unit').combogrid('getValue');     
    var sdana       = $('#dana').combobox('getValue');      
    var invt        ='';    
    var totalseluruh=0;	
	//var sisa		= 0;
	var sisa		= document.getElementById('sisabel_hide').value;
	
    if(updt='t'){
     pidx = jgrid ;
    pidx = pidx + 1 ;
    
    }else if(updt='f'){
      pidx = pidx + 1 ; 
    }
    
    if(tot!=0){
        
        totalseluruh=total+tot;
        $('#trd').edatagrid('appendRow',{idx:pidx,no_dokumen:no_dokumen,jns:cjns,nm_jenis:nmjns,kd_bidang:cbdg,nm_bidang:cnmbidang,kd_brg:kdbrg,nm_brg:cnmbrg,kd_unit:kd_unit,kd_uskpd:kd_uskpd,s_dana:sdana,no_sp2d:nosp2d,tgl_sp2d:tglsp2d,nilai_sp2d:nilsp2d,nilai_kontrak:nilkont,kd_kegiatan:kdgiat,nm_kegiatan:nmkegi,kd_rek5:kdrek5,nm_rek5:nmrek5,jumlah:jml,harga:hrg,ppn:cppn,total:tot,keterangan:ket,invent:invt});
        $('#total').attr('value',number_format(totalseluruh,2,'.',','));
        $('#totbel').attr('value',number_format(totalseluruh,2,'.',','));
		$('#totbel_hide').attr('value',totalseluruh);
		//sisa = nilkont - totalseluruh;	
        $('#sisabel').attr('value',number_format(sisa,2,'.',','));
		$('#sisabel_hide').attr('value',sisa);
        $('#trd').edatagrid('unselectAll');
        kosong2();
        alert('Data Telah Ditambahkan.....');
        tutup();
        exit();
	
    }

}

function append_save_update(){    
    var rows = $('#trd').datagrid('getSelected');
    if(rows!=''){
        var idx = $('#trd').datagrid('getRowIndex',rows);
        $('#trd').edatagrid('deleteRow',idx);
    }else{
        var idx = document.getElementById('idx').value;
        $('#trd').edatagrid('deleteRow',idx);
    }

    
    $('#trd').edatagrid('unselectAll');
    
    $('#trd').edatagrid('selectAll');
    var rows = $('#trd').edatagrid('getSelections');
    jgrid = rows.length ;
    //alert('idx jgrid   '+jgrid);
    pidx = jgrid ;
    //alert('id akhir'+pidx);
    //$('#trd').edatagrid('unselectAll');
    var no_dokumen  = $('#nomor').combogrid('getValue');    
    var cmbjenis_u = $('#cmbjenis_u').combobox('getValue'); 
    var nmgolongan_u = document.getElementById('nmgolongan_u').value; 
    var bidang_u = $('#bidang_u').combobox('getValue'); 
    var nmbidang_u = document.getElementById('nmbidang_u').value; 
    var kdbarang_u = $('#kdbarang_u').combobox('getValue');
    
    var nmkelompok_u = document.getElementById('nmkelompok_u').value; 
    var sbrdana_u = $('#dana').combobox('getValue'); 
    var nosp2d_u = document.getElementById('nosp2d_u').value; //$('#nosp2d_u').combobox('getValue'); 
    
    var tglsp2d_u = $('#tglsp2d_u').datebox('getValue'); 
    var nilsp2d_hide_u = document.getElementById('nilsp2d_hide_u').value; 
    var nilkon1_hide_u = document.getElementById('nilkon1_hide_u').value; 
    var kegiatan_u = document.getElementById('kegiatan_u').value; 
    var nm_kegiatan_u = document.getElementById('nm_kegiatan_u').value;
    var rekening_u = document.getElementById('rekening_u').value; 
    var nm_rekening_u = document.getElementById('nm_rekening_u').value; 
    var jumlah_u = document.getElementById('jumlah_u').value; 
    var harga_u = angka(document.getElementById('harga_u').value); 
    var total1_u = angka(document.getElementById('total1_u').value); 
    var nilppn_u = document.getElementById('nilppn_u').value; 
    var total2_u = angka(document.getElementById('total2_u').value); 
    var ket_u = document.getElementById('ket_u').value; 
    var kd_uskpd    = $('#unit').combogrid('getValue'); 
    var kd_unit     = document.getElementById('mlokasi').value; 
    var invt        ='';
    
    var total = angka(document.getElementById('total').value);
    var totalseluruh=0;

    /*if(updt='t'){
     pidx = jgrid ;
    pidx = pidx + 1 ;
    
    }else if(updt='f'){
      pidx = pidx + 1 ;
        
    }*/
    
    if(total2_u!=0){
        
        totalseluruh=total+total2_u;
        $('#trd').edatagrid('appendRow',{idx:pidx,no_dokumen:no_dokumen,jns:cmbjenis_u,nm_jenis:nmgolongan_u,kd_bidang:bidang_u,nm_bidang:nmbidang_u,kd_brg:kdbarang_u,nm_brg:nmkelompok_u,kd_unit:kd_unit,kd_uskpd:kd_uskpd,s_dana:sbrdana_u,no_sp2d:nosp2d_u,tgl_sp2d:tglsp2d_u,nilai_sp2d:nilsp2d_hide_u,nilai_kontrak:nilkon1_hide_u,kd_kegiatan:kegiatan_u,nm_kegiatan:nm_kegiatan_u,kd_rek5:rekening_u,nm_rek5:nm_rekening_u,jumlah:jumlah_u,harga:harga_u,ppn:nilppn_u,total:total2_u,keterangan:ket_u,invent:invt});
    
        $('#total').attr('value',number_format(totalseluruh,2,'.',','));
        $('#trd').edatagrid('unselectAll');
        
        //$('#trd').edatagrid('reload');
    //    kosong2();
    }

}

function segarkan(){
    $('#trh').edatagrid('reload');
}

function tes(){
       $('#trd').datagrid('selectAll');
       var rows = $('#trd').datagrid('getSelections');                       
       for(var lp=0;lp<rows.length;lp++){
       }
}

function cetak(){
      
      $('#trd').datagrid('selectAll');
      var rows = $('#trd').datagrid('getSelections');    
      var ltotbaris = rows.length;
      lcisi = '&total_baris='+ltotbaris;
      for(var lp=0;lp<rows.length;lp++){ 
         lp1 = lp+1;
         lcnmbar = 'nmbar'+lp1;
         lcisibar = rows[lp].nm_brg;
         lcvol = 'vol'+lp1;
         lcisivol = rows[lp].jumlah;
         lcharga = 'harga'+lp1;
         lcisiharga = rows[lp].harga;
         lctot = 'total'+lp1;
         lcisitot = rows[lp].total;
         
         lcisi = lcisi+'&'+lcnmbar+'='+lcisibar+'&'+lcvol+'='+lcisivol+'&'+lcharga+'='+lcisiharga+'&'+lctot+'='+lcisitot;
      }
      url1 = "<?php echo base_url(); ?>index.php/laporan/ctk_bap1";
      var lcnmunit = $('#unit').combogrid('getValue'); 
      var lcnobap = document.getElementById('no_bap').value;
      var lchari = $('#hari').combobox('getValue'); 
      var lccom = $('#compy').combobox('getValue');
      var ldtglbap = $('#tgl_bap').datebox('getValue'); 
      var ldtgl_cetak = $('#tgl_ctk_bap').datebox('getValue');
      var lckepda = document.getElementById('kepda').value;
      var lcbln = $('#bln_bap').combobox('getValue'); 
      var ldtgl_kep = $('#tgl_kep').datebox('getValue');
      var lcthn = document.getElementById('tahun_bap').value;
      var lcawas1 = $('#pengawas1').combobox('getValue');
      var lcawas2 = $('#pengawas2').combobox('getValue');
      var lcawas3 = $('#pengawas3').combobox('getValue');
      var lcawas4 = $('#pengawas4').combobox('getValue');
      var lcawas5 = $('#pengawas5').combobox('getValue');
      var lcawas6 = $('#pengawas6').combobox('getValue');
      var lcawas7 = $('#pengawas7').combobox('getValue');
      var lcjabat1 = $('#jabat_awas1').combobox('getValue');
      var lcjabat2 = $('#jabat_awas2').combobox('getValue');
      var lcjabat3 = $('#jabat_awas3').combobox('getValue');
      var lcjabat4 = $('#jabat_awas4').combobox('getValue');
      var lcjabat5 = $('#jabat_awas5').combobox('getValue');
      var lcjabat6 = $('#jabat_awas6').combobox('getValue');
      var lcjabat7 = $('#jabat_awas7').combobox('getValue');
      
      var lckontrak = document.getElementById('kontrak_bap').value;
      var ldtgl_kontrak = $('#tgl_kontrak_bap').datebox('getValue');
      var lckegiatan = document.getElementById('kegiatan_bap').value;
	  var lckegiatan_nm = document.getElementById('kegiatan_bap_nm').value;
      var lckerja = document.getElementById('pekerjaan_bap').value;
      var lclokasi = document.getElementById('lokasi_bap').value;
      var lnnilai_bap = document.getElementById('nilai_bap').value;
      var lcsumberdana = document.getElementById('dana_bap').value;
       
      lc1 = '?no_bap='+lcnobap+'&hari='+lchari+'&tgl_bap='+ldtglbap+'&tgl_cetak='+ldtgl_cetak+'&kepda='+lckepda+'&bln_bap='+lcbln+'&tgl_kep='+ldtgl_kep;
      lc2 = '&tahun_bap='+lcthn+'&pengawas1='+lcawas1+'&pengawas2='+lcawas2+'&pengawas3='+lcawas3+'&pengawas4='+lcawas4+'&pengawas5='+lcawas5+'&pengawas6='+lcawas6+'&pengawas7='+lcawas7;
      lc3 = '&jabat1='+lcjabat1+'&jabat2='+lcjabat2+'&jabat3='+lcjabat3+'&jabat4='+lcjabat4+'&jabat5='+lcjabat5+'&jabat6='+lcjabat6+'&jabat7='+lcjabat7+'&comp='+lccom;
      lc4 = '&kontrak='+lckontrak+'&tgl_kontrak='+ldtgl_kontrak+'&kegiatan='+lckegiatan+'&pekerjaan='+lckerja+'&lokasi='+lclokasi+'&nilai='+lnnilai_bap+'&dana='+lcsumberdana+'&nmunit='+lcnmunit;
      
      lc = lc1+lc2+lc3+lc4;
      var pariabel = url1+lc+lcisi;
      cetak_bap1(pariabel);
      window.open(url+lc,'_blank');
      window.focus();  
      
    } 
    
    function cetak_bap1(pariabel){
        window.open(pariabel,'_blank');
        window.focus();
    }

    function coba(){
        lc = '';
        for(var lp=0;lp<5;lp++){
            lcpar = 'par'+lp
            lcisi = 'isi'+lp
                        
            lc = lc + '&'+lcpar+'='+lcisi
            
        }
    }
	
	function max_rinci(){  
	var organisasi = $('#unit').combogrid('getValue');
		$.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/transaksi_int/load_idmax',
            data: ({skpd:organisasi,table:'trh_isianbrg',kolom:'no_dokumen',kolom_skpd:'kd_uskpd'}),
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

    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#trh').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/transaksi_int/trh_isianbrg',
        queryParams:({cari:kriteria})
        });        
     });
    }

    function cek_rekan(){
    var nm_comp = $('#compy').combobox('getValue');
    $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/master_int/ambil_compy',
            data: ({q:nm_comp}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    kd_comp            = n['kd_comp'];
                    nm_comp            = n['nm_comp'];
                    $('#compy').combobox('setValue',kd_comp);  
                    //alert(kd_comp);
                });
            }
        }); 
    } 

</script>

<div id="tabs" class="easyui-tabs">
		<p><h3 align="center">FORMULIR PENGADAAN BARANG</h3></p>
    <ul style="background-color:#2da305;">
        <li><a href="#tabs-1" style="width: 450px;" id="tabs1" onclick="javascript:segarkan()">List View</a></li>
        <li><a href="#tabs-2" style="width: 450px;" id="tabs2">Form Input</a></li>        
    </ul>
    <div id="tabs-1">
        <div>
            <p align="right">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();tab2();">Tambah</a>
                <!--a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a-->                           
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();" >Cari</a>
                <input type="text" value="" id="txtcari"/>
                <input type="hidden" value="" id="txtnodok_h"/>
                <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit">              
                <table  id="trh" title="List Dokumen" style="width:940px;height:370px;" >  
                </table>                
                </div>
            </p>
        </div>
    </div>
    <div id="tabs-2">  
        <br /><br />
        <table align="center" border="0">
            <tr>
                <td >No. Dokumen</td>
                <td>:</td>
                <td><input type="text" id="nomor" name="nomor" style="width: 145px;" /> &nbsp;&nbsp; <input type="text" id="tanggal" style="width: 140px;" />
                <input type="hidden" id="sp2d" name="sp2d" style="width: 140px;" /></td>
                <td width="50px"></td>
                <td >Jenis Dana</td>
                <td>:</td>
                <td><input type="text" id="dana" style="width: 140px;" /></td>                
            </tr>
            <tr>       
                <td>No. SPM</td>
                <td>:</td>
				<td><input type="text" id="nomorspm" style="width: 145px;" /> &nbsp;&nbsp; <input type="text" id="tglspm" style="width: 140px;" hidden ="true"/></td>
                <td></td>
                <td>Tahun Anggaran</td>
                <td>:</td>
                <td><input type="text" id="tahun" style="width: 140px;" /></td>     
            </tr>
            <tr>
                <td >Nilai Realisasi</td>
                <td>:</td>
                <td><input type="text" id="nilkont" name="nilkont" style="width: 140px;text-align: right;" disabled="true" /><input type="hidden" id="nilkont_hide" name="nilkont_hide" style="width: 140px;text-align: right;" /></td>
                <td></td>
                <td >Bukti Pembayaran</td>
                <td>:</td>
                <td><input type="text" id="bukti" style="width: 140px;" /></td> 
            </tr>
            <tr>
                <td>Nilai SPM</td> 
                <td>:</td>
                <td><input type="text" id="nilapbd" style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" disabled="true" /></td>
                <td></td>
                <td >Cara Perolehan</td>
                <td>:</td>
                <td><input type="text" id="perolehan" style="width: 140px;" /></td>
                                               
            </tr>       
            <tr>
                <td>Perusahaan/Rekanan</td>
                <td>:</td>
                <td><input id="compy" name="compy" style="width: 140px;" value="" />  </td>
                <td></td>
                <td >Dasar Perolehan</td>
                <td>:</td>
                <td><input type="text" id="dasar" style="width: 140px;" /></td>  
                         
            </tr>                       
            <tr>
                <td colspan="3"><hr /></td>
                <td></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;a.Nomor</td>
                <td>:</td>
                <td><input type="text" id="dsno" style="width: 140px;" /></td>    
                
            </tr>
            <tr>
                <td>Kepemilikan</td>
                <td>:</td>
                <td><input id="milik" name="milik" style="width: 140px;" value=""/></td>
                <td></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;b.Tanggal</td>
                <td>:</td>
                <td><input type="text" id="dstgl" style="width: 140px;" /></td>
                
            </tr>                
            <tr>
                <td>Wilayah</td>
                <td>:</td>
                <td><input id="wilayah" name="wilayah" style="width: 140px;" value=""/></td>
                <td></td>
                <td hidden="tru">Kurang Bayar</td>
                <td hidden="tru">:</td>
                <td hidden="tru"><input type="text" id=""  style="width: 140px;" disabled="true"/></td>
            </tr>
            <tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="unit" name="unit" style="width: 140px;" value=""/> </td>
                <td></td>
                </td>
                <td  hidden="true">:</td>
                <!--td  hidden="true"><input type="text" id="thn2" style="width: 140px;" /></td-->
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><input id="nmunit" name="nmunit" style="width: 350px; border:0;" readonly="true" value=""/>
                <input id="mlokasi" name="mlokasi" style="width: 350px; border:0;" readonly="true" value=""/></td>
				<td></td>
                <td  colspan="3">
				<input id="nmlokasi" name="nmlokasi" style="width: 350px; border:0;" readonly="true" value=""/></td>
                <td  hidden="true">:</td>
                <!--td  hidden="true"><input type="text" id="thn2" style="width: 140px;" /></td-->
            </tr>
            <tr>
                <td >*Keterangan</td>
                <td colspan="6">Nilai Realisasi &nbsp;&nbsp;:&nbsp;&nbsp;<input id="nkon" name="nkon" style="width: 140px;text-align: right;" disabled="true"><input type="hidden" id="nkon_hide" name="nkon_hide" style="width: 140px;text-align: right;" disabled="true">&nbsp;&nbsp;
                    Total Transaksi &nbsp;&nbsp;:&nbsp;&nbsp;<input id="totbel" name="totbel" style="width: 140px;text-align: right;" disabled="true"><input type="hidden" id="totbel_hide" name="totbel_hide" style="width: 140px;text-align: right;" disabled="true">
					Sisa &nbsp;&nbsp;:&nbsp;&nbsp;<input id="sisabel" name="sisabel" style="width: 140px;text-align: right; " disabled="true"><input type="hidden" id="sisabel_hide" name="sisabel_hide" style="width: 140px;text-align: right;" disabled="true"></td>
            </tr>
        </table>  
        <br />
        <!--fieldset>
        <div align="center">
        	<!--a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a-->
            <!--a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_bap();">cetak</a> 
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tab1();">Kembali</a>          
		</div>
        </fieldset-->
        <br /> 
        <div id="toolbar" align="center" >
    		<a id="tombol_brg" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_detail();"><b>Tambah Barang</b></a>   		                            		
        </div>
        <!-- <div title="Untuk Edit Data, Klik 2x Di Baris Yang Akan di Edit"> -->
        <table  id="trd" title="Detail Barang" style="width:940px;height:300px;" >  
        </table>
        <!-- </div>  -->      
        <div align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></div>        
         <br />
        <div align="center">
		<fieldset>
		<INPUT id="tombol_simpan" TYPE="button" VALUE="SIMPAN" style="height:40px;width:100px" onclick="javascript:simpan();" >
		<INPUT id="tombol_cetak" TYPE="button" VALUE="CETAK BAP" style="height:40px;width:100px" onclick="javascript:cetak_bap();">
		<INPUT TYPE="button" VALUE="KEMBALI" style="height:40px;width:100px" onclick="javascript:tab1();" >
        </fieldset>
		</div>  
    </div>
</div>


    <div id="dialog-modal" title="Input Barang" >
    <p class="validateTips" >Semua Inputan Harus Di Isi.</p>     
    <fieldset title="Spesifikasi Barang" >    
        <table>
			<tr>
                <!--<td>Golongan Barang</td>
                <td>:</td>-->
                <td colspan="3"><input disabled id="cmbjenis" name="cmbjenis" style="width: 200px;" />&nbsp;&nbsp;<input type="hidden" id="nmgolongan" name="nmgolongan" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
			<tr>
                <!--<td>Bidang Barang</td>
                <td>:</td>-->
                <td colspan="3"><input disabled id="bidang" name="bidang" style="width: 200px;" />&nbsp;&nbsp;<input type="hidden" id="nmbidang" name="nmbidang" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
            <tr>
                <!--<td>Kode Barang</td>
                <td>:</td>-->
                <td colspan="3"><input disabled id="kdbarang" name="kdbarang" style="width: 200px;" />&nbsp;&nbsp;<input type="hidden" id="nmkelompok" name="nmkelompok" style="width: 300px;border: 0;" readonly="true"/></td>
            </tr>
		    <!--tr>
                <td>Kelompok Barang</td>
                <td>:</td>
                <td><input type="text" id="cmkel" style="width: 140px;" /></td>
            </tr>
			<tr>
                <td>Sub Kelompok Barang</td>
                <td>:</td>
                <td><input type="text" id="cmsubkel" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td>:</td>
                <td><input type="text" id="kdbrg" style="width: 140px;"/></td>
            </tr-->
            <tr>
                <td></td>
                <td></td>
                <!-- <td><input type="text" id="nmbidang" style="width: 500px;border: 0;" readonly="true"/></td> -->
                <td><input type="text" id="nmbrg" style="width: 500px;border: 0;" readonly="true"/><input type="text" id="kdbrgx" style="width: 500px;border: 0;" hidden="true"/></td>
            </tr>            
        </table>
    </fieldset>
    <fieldset title="Bukti / SP2D">
        <table>
            <tr hidden="true">
                <td>Sumber Dana</td>
                <td>:</td>
                <td><input type="text" id="sbrdana" style="width: 140px;"/></td>
            </tr>            
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><input type="hidden" id="nosp2d" name="nosp2d" style="width: 200px;"/><input type="text" id="tglsp2d" style="width: 140px;"/></td>
            </tr>                        
            <tr>
                <td>Kegiatan</td>
                <td>:</td>
                <td><input type="text" id="kegiatan" name="kegiatan" style="width: 200px;" readonly="true"/></td>
                <td><input type="text" id="nm_kegiatan" name="nm_kegiatan" style="width: 400px;border:0;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Rekening</td>
                <td>:</td>
                <td><input type="text" id="rekening" name="rekening" style="width: 200px;" readonly="true"/></td>
                <td><input type="text" id="nm_rekening" name="nm_rekening" style="width: 400px;border:0;" readonly="true"/></td>
            </tr>
			<tr>
                <td>Nilai Realisasi</td>
                <td>:</td>
                <td><input type="text" id="nilkon1" name="nilkon1" style="width: 140px;text-align:right;" readonly="true"/><input type="hidden" id="nilkon1_hide" name="nilkon1_hide" style="width: 140px;text-align:right;" readonly="true"/></td>
                <td><input type="hidden" id="nilsp2d" name="nilsp2d" style="width: 140px;text-align:right;" readonly="true"  /><input type="hidden" id="nilsp2d_hide" name="nilsp2d_hide" style="width: 140px;text-align:right;" readonly="true"/></td>
            </tr>
        </table>
    </fieldset>
	<fieldset title="Keterangan Barang">
        <table>
            <tr>
                <td>Bidang Barang</td>
                <td>:</td>
                <td><input  id="bidang_u" name="bidang_u" style="width: 200px;" />&nbsp;&nbsp;<input type="text" id="nmbidang_u" name="nmbidang_u" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
			<tr>
                <td>Kode Barang</td>
                <td>:</td>
                <td><input type="text" id="rekbrg" name="rekbrg" style="width: 50px;" /><input type="text" id="nmrekbrg" name="nmrekbrg" style="width: 400px; border:0;" readonly="true"/></td>
            </tr>            
        </table>
    </fieldset>
    <fieldset title="Keterangan Barang">
        <table>
            <tr>
                <td>Jumlah</td>
                <td>:</td>
                <td><input type="text" id="jumlah" style="width: 140px;text-align: right;" onkeyup="hitung();" /></td>
            </tr>
            <tr>
                <td>Harga Satuan</td>
                <td>:</td>
                <td><input type="text" id="harga" style="width: 140px;text-align: right;" onkeyup="hitung();" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
            </tr>
            <tr>
                <td>Total Harga</td>
                <td>:</td>
                <td><input type="text" id="total1" style="width: 140px;text-align: right;"  readonly="true"/></td>
            </tr>
            <tr>
                <td>PPN&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ppn" onclick="hitung();" /></td>
                <td>:</td>
                <td><input type="text" id="nilppn" style="width: 140px;text-align: right;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total2" style="width: 140px;text-align: right;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td><textarea id="ket" style="width: 140px;"></textarea></td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <div align="center">
        	<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong2();">Tambah</a>
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Tampung</a>               		  
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup();">Kembali</a>
        </div>
    </fieldset>
    </div>  
        
    <div id="dialog-modal_bap" title="Cetak Berita Acara Penerimaan Barang" >
        <fieldset title="Spesifikasi Barang" >    
            <table border="0" width="100%">
                <tr>
                    <td width="20%">No BAP</td>
                    <td width="1%">:</td>
                    <td width="25%"><input type="text" id="no_bap" style="width: 200px;" /></td>
                    <td width="8%">&nbsp;</td>
                    <td width="20%">Hari</td>
                    <td width="1%">:</td>
                    <td width="25%"><input type="text" id="hari" style="width: 50px;" /></td>
                </tr>          
                <tr>
                    <td>Tgl. BAP</td>
                    <td>:</td>
                    <td><input type="text" id="tgl_bap" style="width: 100px;" /></td>
                    <td></td>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><input type="text" id="tgl_ctk_bap" style="width: 100px;" /></td>
                </tr>
                <tr>
                    <td>Keputusan Bupati</td>
                    <td>:</td>
                    <td><input type="text" id="kepda" style="width: 200px;" /></td>
                    <td></td>
                    <td>Bulan</td>
                    <td>:</td>
                    <td><input type="text" id="bln_bap" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>Tgl.Keputusan</td>
                    <td>:</td>
                    <td><input type="text" id="tgl_kep" style="width: 100px;" /></td>
                    <td></td>
                    <td>Tahun</td>
                    <td>:</td>
                    <td><input type="text" id="tahun_bap" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:1.</td>
                    <td colspan="5"><input type="text" id="pengawas1" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas1" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;2.</td>
                    <td colspan="5"><input type="text" id="pengawas2" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas2" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;3.</td>
                    <td colspan="5"><input type="text" id="pengawas3" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas3" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;4.</td>
                    <td colspan="5"><input type="text" id="pengawas4" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas4" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;5.</td>
                    <td colspan="5"><input type="text" id="pengawas5" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas5" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;6.</td>
                    <td colspan="5"><input type="text" id="pengawas6" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas6" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;7.</td>
                    <td colspan="5"><input type="text" id="pengawas7" style="width: 200px;" />&ensp;<input type="text" id="jabat_awas7" style="width: 140px;" /></td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td>No Kontrak</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="kontrak_bap" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>Tgl Kontrak</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="tgl_kontrak_bap" style="width: 100px;" /></td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>:</td>
                    <td colspan="5"><input type="hidden" id="kegiatan_bap" style="width: 580px;" /><input type="text" id="kegiatan_bap_nm" style="width: 580px;" /></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="pekerjaan_bap" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="lokasi_bap" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>Nilai Realisasi</td>
                    <td>:</td>
                    <td colspan="5"><input type="hidden" id="nilai_bap" style="width: 200px;" /><input type="text" id="nilai_bap2" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="dana_bap" style="width: 200px;" /></td>
                </tr>
            </table>
        </fieldset>
         <fieldset style="alignment-adjust: ;">
            <div align="center">
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();return false">Cetak</a>                               
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup();">Kembali</a>
            </div>
            
        </fieldset>
    
    </div>

    <div id="dialog-modal-update" title="Update Barang" >
    <p class="validateTips" >Semua Inputan Harus Di Isi.</p>     
    <fieldset title="Spesifikasi Barang" >    
        <table>
            <tr>
                <td>Golongan Barang</td>
                <td>:</td>
                <td><input id="cmbjenis_u" name="cmbjenis_u" style="width: 200px;" />&nbsp;&nbsp;<input type="text" id="nmgolongan_u" name="nmgolongan_u" style="width: 200px;border: 0;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Kode Barang</td>
                <td>:</td>
                <td><input  id="kdbarang_u" name="kdbarang_u" style="width: 200px;" />&nbsp;&nbsp;<input type="text" id="nmkelompok_u" name="nmkelompok_u" style="width: 300px;border: 0;" readonly="true"/></td>
            </tr>
           
            <tr>
                <td></td>
                <td></td>
                <!-- <td><input type="text" id="nmbidang" style="width: 500px;border: 0;" readonly="true"/></td> -->
                <td><input type="text" id="idx" style="width: 500px;border: 0;" readonly="true"/></td>
            </tr>            
        </table>
    </fieldset>
    <fieldset title="Bukti / SP2D">
        <table>
            <tr hidden="true">
                <td>Sumber Dana</td>
                <td>:</td>
                <td><input type="text" id="sbrdana_u" style="width: 140px;"/></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><input type="text" id="tglsp2d_u" style="width: 140px;"/><input type="hidden" id="nosp2d_u" name="nosp2d_u" style="width: 200px;"/></td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td>:</td>
                <td><input type="text" id="kegiatan_u" name="kegiatan_u" style="width: 200px;" readonly="true"/></td>
                <td><input type="text" id="nm_kegiatan_u" name="nm_kegiatan_u" style="width: 400px;border:0;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Rekening</td>
                <td>:</td>
                <td><input type="text" id="rekening_u" name="rekening_u" style="width: 200px;" readonly="true"/></td>
                <td><input type="text" id="nm_rekening_u" name="nm_rekening_u" style="width: 400px;border:0;" readonly="true"/></td>
            </tr>
			<tr>
                <td>Nilai Realisasi</td>
                <td>:</td>
				<td><input type="text" id="nilkon1_u" name="nilkon1_u" style="width: 140px;text-align:right;" readonly="true"/><input type="hidden" id="nilkon1_hide_u" name="nilkon1_hide_u" style="width: 140px;text-align:right;" readonly="true"/></td>
                <td><input type="hidden" id="nilsp2d_u" name="nilsp2d_u" style="width: 140px;text-align:right;" readonly="true"  /><input type="hidden" id="nilsp2d_hide_u" name="nilsp2d_hide_u" style="width: 140px;text-align:right;" readonly="true"  /></td>                
            </tr>
            
        </table>
    </fieldset>
    <fieldset title="Keterangan Barang">
        <table>
            <tr>
                <td>Jumlah</td>
                <td>:</td>
                <td><input type="text" id="jumlah_u" style="width: 140px;text-align: right;" onkeyup="hitung_update();" /></td>
            </tr>
            <tr>
                <td>Harga Satuan</td>
                <td>:</td>
                <td><input type="text" id="harga_u" style="width: 140px;text-align: right;" onkeyup="hitung_update();" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
            </tr>
            <tr>
                <td>Total Harga</td>
                <td>:</td>
                <td><input type="text" id="total1_u" style="width: 140px;text-align: right;"  readonly="true"/></td>
            </tr>
            <tr>
                <td>PPN&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ppn_u" onclick="hitung_update();" /></td>
                <td>:</td>
                <td><input type="text" id="nilppn_u" style="width: 140px;text-align: right;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total2_u" style="width: 140px;text-align: right;" readonly="true"/></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td><textarea id="ket_u" name="ket_u" style="width: 140px;"></textarea></td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <div align="center">
            <!-- <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong2();">Tambah</a> -->
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_update();">Tampung</a>                         
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup();">Kembali</a>
        </div>
    </fieldset>
    </div>




        
