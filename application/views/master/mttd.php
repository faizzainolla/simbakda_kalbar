<script type="text/javascript">
    
    var kdkel= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 700,
            width: 950,
            modal: true,
            autoOpen:false,
        });
        });    
     
     $(function(){
     
     $('#stkey').combogrid({  
       panelWidth:230,  
       idField:'nm_kunci',  
       textField:'nm_kunci',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_key',  
       columns:[[  
           {field:'nm_kunci',title:'KEY',width:40},
           {field:'singkatan',title:'SINGKAT',width:160}    
       ]] 
     });
     
     $('#pangkat').combogrid({  
       panelWidth:500,  
       idField:'kd_pangkat',  
       textField:'kd_pangkat',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_pangkat',  
       columns:[[  
           {field:'kd_pangkat',title:'KODE JABATAN',width:100},  
           {field:'nm_pangkat',title:'NAMA JABATAN',width:370}    
       ]],
           onSelect:function(rowIndex,rowData){
           $("#nmpangkat").attr("value",rowData.nm_pangkat.toUpperCase());
          } 
     }); 
     $('#kduker').combogrid({  
       panelWidth:500,  
       idField:'kd_uskpd',  
       textField:'kd_uskpd',  
       mode:'remote',
       //url:'<?php echo base_url(); ?>index.php/master/ambil_uker',  
       columns:[[  
           {field:'kd_uskpd',title:'KODE UNIT KERJA',width:100},  
           {field:'nm_uskpd',title:'NAMA UNIT KERJA',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           //if (lcstatus == 'tambah'){
		   lcstatus = 'tambah';
		   kd_lokasi=rowData.kd_uskpd; 
		   nm_lokasi=rowData.nm_uskpd;
           //$("#kdlokasi").attr("value",kd_lokasi.toUpperCase()+'.');
           $("#nmlokasi").attr("value",nm_lokasi.toUpperCase());   
		   nomer_akhir()            
		   $('#iz').edatagrid({url:'<?php echo base_url(); ?>index.php/master/load_ttd',queryParams:({kdlokasi:kd_lokasi}) });

           //}                 
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
       ]]  ,
	   onSelect:function(rowIndex,rowData){
               lcskpd = rowData.kd_lokasi; 
               $('#kduker').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_uskpd',queryParams:({kduskpd:lcskpd}) });
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
                                
           }
     });       
		
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_lokasi',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_lokasi',title:'Kode Lokasi',width:15,align:"center"},
            {field:'kd_uker',title:'Kode Unit Kerja',width:15,align:"center"},
            {field:'nm_lokasi',title:'Nama Lokasi',width:30,align:"left"},
			{field:'kd_skpd',title:'Kode SKPD',width:10,align:"center"},			
			{field:'home',title:'Add Sign',width:7,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/brush.png' onclick='javascript:edit_data();'' />";}},
			//{field:'hapus',title:'Del',width:4,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}

        ]],
        onSelect:function(rowIndex,rowData){
          lcidx = rowIndex;
          kdlok = rowData.kd_lokasi;
          nmlok = rowData.nm_lokasi;
          kduker  = rowData.kd_uker;
		  kdskpd  = rowData.kd_skpd
          get(kdlok,nmlok,kduker,kdskpd); 		   
		  //nomer_akhir()            
           
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Lokasi'; 
		  lcstatus = 'tambah';
          kdlok = rowData.kd_lokasi;
          nmlok = rowData.nm_lokasi;
          kduker  = rowData.kd_uker;
		  kdskpd  = rowData.kd_skpd
          get(kdlok,nmlok,kduker,kdskpd); 
          edit_data(); 
		 // nomer_akhir()		  
        }
        
        });
       
	   $('#iz').edatagrid({
		//url: '<?php echo base_url(); ?>index.php/master/ambil_ruang',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'nip',title:'NIP',width:3,align:"left"},
            {field:'nama',title:'NAMA',width:7,align:"left"},
            {field:'jabatan',title:'JABATAN',width:4,align:"left"},
            {field:'nm_skpd',title:'UNIT KERJA',width:5,align:"left"},
			{field:'hapus',width:1,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
	   ]],
        onSelect:function(rowIndex,rowData){
          lcidx = rowIndex;
          nip = rowData.nip;
		/* 
          lcidx = rowIndex;
          kdlok = rowData.kd_lokasi;
          nmlok = rowData.nm_lokasi;
          kduker  = rowData.kd_uker;
		  kdskpd  = rowData.kd_skpd
          get(kdlok,nmlok,kduker,kdskpd);   */ 
                                       
        },
        onDblClickRow:function(rowIndex,rowData){/* 
          lcidx = rowIndex;
          judul = 'Edit Data Lokasi'; 
          lcstatus = 'edit';
          kdlok = rowData.kd_lokasi;
          nmlok = rowData.nm_lokasi;
          kduker  = rowData.kd_uker;
		  kdskpd  = rowData.kd_skpd
          get(kdlok,nmlok,kduker,kdskpd); 
          edit_data();    */
        }
        
        });
	   
    });        

       	function nomer_akhir(){
        var i 		  = 0; 
		var kd_lokasi = $('#kduker').combogrid('getValue');
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>index.php/master/ambil_maxkode_ruang',
            data: ({kdlokasi:kd_lokasi}),
            dataType:"json",
            success:function(data){                                          
                $.each(data,function(i,n){                                    
                    max_kode      = n['max_kode']; 
					kode_ruangan  = kd_lokasi+"."+max_kode; 
                    $("#kdlokasi").attr("value",kode_ruangan ); 
                    $("#no_urut").attr("value",max_kode );                              
                });
            }
        });         
    }
	
    function  get(kdlok,nmlok,kduker){
        //$("#kdlokasi").attr("value",kdlok);
        //$("#nmlokasi").attr("value",nmlok);
        //iini dihidupkan bisa $("#kduker").combogrid("setValue",kduker);
        $("#kdskpd").combogrid("setValue",kdskpd);                
    }
    
    function kosong(){
        $("#nama").attr("value",'');
        $("#nip").attr("value",'');
        $("#pangkat").combogrid("setValue",'');
		$("#stkey").combogrid("setValue",''); 
        //$("#nmlokasi").attr("value",'');
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
    
    function simpan(){        
		var ckdnip 		= document.getElementById('nip').value;
        var cnama 		= document.getElementById('nama').value;
		var ckey 		= $('#stkey').combogrid('getValue');
        var cpangkat 	= $('#pangkat').combogrid('getValue');
        var cjabat 		= document.getElementById('nmpangkat').value;
        var cunit 		= $('#kduker').combogrid('getValue');
		var ckdskpd 	= $('#kdskpd').combogrid('getValue');
        var cnm_skpd 	= document.getElementById('nmlokasi').value; 
        if (ckdnip==''){
            alert('NIP Tidak Boleh Kosong');
            exit();
        } 
		
        if (cnama==''){
            alert('Nama Tidak Boleh Kosong');
            exit();
        } 
        
        if (cunit==''){
            alert('Kode Unit Kerja Tidak Boleh Kosong');
            exit();
        } 
	if (ckdskpd==''){
            alert('Kode Unit Kerja Tidak Boleh Kosong');
            exit();
        } 
        if(lcstatus=='tambah'){
            lcinsert = "(nip,nama,jabatan,skpd,unit,nm_skpd,ckey,kd_pangkat,tingkat)";
            lcvalues = "('"+ckdnip+"','"+cnama+"','"+cjabat+"','"+ckdskpd+"','"+cunit+"','"+cnm_skpd+"','"+ckey+"','"+cpangkat+"','1')";
            
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/master/simpan_master',
                    data: ({tabel:'ttd',kolom:lcinsert,nilai:lcvalues,cid:'nip',lcid:ckdnip}),
                    dataType:"json"
                }); 
            });    
            
       // $('#dg').datagrid('appendRow',{kd_lokasi:ckdlok,nm_lokasi:cnmlok,kd_uker:ckduker,kd_skpd:ckdskpd,keterangan:keterangan});
        }else {
            //lcquery = "UPDATE mruang SET nm_ruang='"+cnmlok+"',kd_unit='"+ckduker+"',kd_skpd='"+ckdskpd+"' where kd_ruang='"+ckdlok+"'";
            lcquery = "insert into ttd values ('"+ckdnip+"','"+cnama+"','"+cjabat+"','"+ckdskpd+"','"+cunit+"','"+cnm_skpd+"','"+ckey+"','"+cpangkat+"','1')";
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
            
                $('#dg').datagrid('updateRow',{
            	index: lcidx,
            	row: {
            		nm_lokasi: cnmlok,
                    kd_uker: ckduker,
                    keterangan: keterangan,
					kd_skpd: ckdskpd  					
            	}
            });
        }
       
        
        $("#iz").edatagrid("reload");
        //alert("Data Berhasil disimpan");
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penanda Tangan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        $("#iz").edatagrid("reload");
        //document.getElementById("kdlokasi").disabled=false;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Penanda Tangan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        //document.getElementById("kdlokasi").disabled=false;
        document.getElementById("kduker").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
	 
        if (nip !=''){
		var del=confirm('Apakah Anda yakin ingin Menhapus data '+nip+'?');
		if (del==true){         
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ttd',cnid:nip,cid:'nip'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                //$('#dg').datagrid('deleteRow',lcidx); 
				$("#iz").edatagrid("reload");  
                exit();
            }
         });
        });    }}
    } 
    
    
  
   </script>



<div id="content1"> 
<h3 align="center"><u><b><a>INPUTAN MASTER PENANDA TANGAN</a></b></u></h3>
    <div align="center">
    <p>     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <!--a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a--></td>               
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" align="center" title="LISTING DATA" style="width:900px;height:365px;" >  
        </table>
        </td>
        </tr>
    </table>   
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td width="30%">NIP</td>
                <td width="1%">:</td>
                <td><input type="text" id="nip" style="width:250px;"/></td>  
            </tr>
           <tr>
                <td width="30%">NAMA </td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:450px;"/></td>  
            </tr>
			<tr>
                <td>S K P D</td>
                <td>:</td>
                <td><input id="kdskpd" name="kdskpd" style="width: 120px;" />&nbsp;<input readonly="true" id="nmskpd" name="nmskpd" style="width: 350px;" /></td>
           </tr>
           <tr>
                <td>U N I T</td>
                <td>:</td>
                <td><input id="kduker" name="kduker" style="width: 120px;" />&nbsp;<input readonly="true" id="nmlokasi" name="nmlokasi" style="width: 350px;" /></td>
           </tr>
           <tr>
                <td>KEY</td>
                <td>:</td>
                <td><input id="stkey" name="stkey" style="width: 100px;" /></td>
            </tr>
            <tr>
                <td>JABATAN</td>
                <td>:</td>
                <td><input id="pangkat" name="pangkat" style="width: 100px;" />&nbsp;
                <input type="text" id="nmpangkat" style="border:0;width: 250px;" readonly="true"/></td>
            </tr> 
              <tr>
             <td colspan="4">&nbsp;</td>
            </tr> 
            <tr>
                <td colspan="4" align="center">
				<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();nomer_akhir();">Tambah</a>
				<a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>  
        <table id="iz" align="center" title="LISTING DATA PENANDA TANGAN" style="width:900px;height:365px;" >  
        </table>      
    </fieldset> 
</div>