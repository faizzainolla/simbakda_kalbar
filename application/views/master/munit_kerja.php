
    <script type="text/javascript">
    
    var kdkel= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 300,
            width: 800,
            modal: true,
            autoOpen:false,
        });
        });    
     
     $(function(){
     
     $('#kdubidskpd').combogrid({  
       panelWidth:500,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_skpd',  
       columns:[[  
           {field:'kd_skpd',title:'KODE UNIT BIDANG',width:100},  
           {field:'nm_skpd',title:'NAMA UNIT BIDANG',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           if (lcstatus == 'tambah'){
           $("#kdunit").attr("value",rowData.kd_skpd.toUpperCase()+'.');
           }                 
       }  
     });   
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_unit_kerja',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_uker',
    		title:'Kode Unit Kerja',
    		width:15,
            align:"center"},
            {field:'nm_uker',
    		title:'Nama Unit Kerja',
    		width:40,
            align:"left"},
            {field:'kd_uskpd',
    		title:'Kode Unit Bidang',
    		width:15,
            align:"center"},
			{field:'hapus',width:5,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
        ]],
        onSelect:function(rowIndex,rowData){
          lcidx = rowIndex;
          kdunit = rowData.kd_uker;
          nmunit = rowData.nm_uker;
          kdubidskpd  = rowData.kd_uskpd;
          lcalamat = rowData.alamat;
          get(kdunit,nmunit,kdubidskpd,lcalamat);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Unit Kerja'; 
          lcstatus = 'edit';
          kdunit = rowData.kd_uker;
          nmunit = rowData.nm_uker;
          kdubidskpd  = rowData.kd_uskpd;
          lcalamat = rowData.alamat;
          get(kdunit,nmunit,kdubidskpd,lcalamat);  
          edit_data();   
        }
        
        });
       
    });        

      
    function  get(kdunit,nmunit,kdubidskpd,lcalamat){
        $("#kdunit").attr("value",kdunit);
        $("#nmunit").attr("value",nmunit);
        $("#kdubidskpd").combogrid("setValue",kdubidskpd);
        $("#alamat").attr("value",lcalamat);
                       
    }
    
    function kosong(){
        $("#kdunit").attr("value",'');
        $("#nmunit").attr("value",'');
        $("#kdubidskpd").combogrid("setValue",'');
        $("#alamat").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    
    $(function(){
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_unit_kerja',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan(){
        
        var ckdunit = document.getElementById('kdunit').value;
        var cnmunit = document.getElementById('nmunit').value;
        var ckdbidskpd = $('#kdubidskpd').combogrid('getValue');
        var calamat = document.getElementById('alamat').value;
                        
        if (ckdunit==''){
            alert('Kode Unit Tidak Boleh Kosong');
            exit();
        } 
        
        if (cnmunit==''){
            alert('Nama Unit Tidak Boleh Kosong');
            exit();
        } 
        
        if (ckdbidskpd==''){
            alert('Bidang Tidak Boleh Kosong');
            exit();
        } 

        if(lcstatus=='tambah'){
            
            lcinsert = "(kd_uker,nm_uker,kd_uskpd,alamat)";
            lcvalues = "('"+ckdunit+"','"+cnmunit+"','"+ckdbidskpd+"','"+calamat+"')";
            
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/master/simpan_master',
                    data: ({tabel:'unit_kerja',kolom:lcinsert,nilai:lcvalues,cid:'kd_uker',lcid:ckdunit}),
                    dataType:"json"
                });
            });    
            
            
        $('#dg').datagrid('appendRow',{kd_uker:ckdunit,nm_uker:cnmunit,kd_uskpd:ckdbidskpd,alamat:calamat});
        }else {
            
            lcquery = "UPDATE unit_kerja SET nm_uker='"+cnmunit+"',kd_uskpd='"+ckdbidskpd+"', alamat='"+calamat+"' where kd_uker='"+ckdunit+"'";
            
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
            		kd_uker: ckdunit,
            		nm_uker: cnmunit,
                    kd_uskpd: ckdbidskpd,
                    alamat:calamat             
            	}
            });
        }
       
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Unit Kerja';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kdunit").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Unit Kerja';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kdunit").disabled=false;
        document.getElementById("kdubidskpd").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckdunit = document.getElementById('kdunit').value;
         if (ckdunit !=''){
		var del=confirm('Apakah Anda yakin ingin Menhapus data '+ckdunit+'?');
		if (del==true){       
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'unit_kerja',cnid:ckdunit,cid:'kd_uker'}),function(data){
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
        });   }} 
    } 
    
    
  
   </script>



<div id="content1"> 
<h3 align="center"><u><b><a>INPUTAN UNIT KERJA</a></b></u></h3>
    <div align="center">
    <p>     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" align="center" title="LISTING DATA UNIT KERJA" style="width:900px;height:365px;" >  
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
                <td>UNIT BIDANG</td>
                <td>:</td>
                <td><input id="kdubidskpd" name="kdubidskpd" style="width: 100px;" /></td>
           </tr>
           <tr>
                <td width="30%">KODE UNIT</td>
                <td width="1%">:</td>
                <td><input type="text" id="kdunit" style="width:100px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA UNIT</td>
                <td width="1%">:</td>
                <td><input type="text" id="nmunit" style="width:450px;"/></td>  
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td width="1%">:</td>
                <td><textarea rows="2" cols="50" id="alamat" style="width: 450px;"></textarea>
                </td> 
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

