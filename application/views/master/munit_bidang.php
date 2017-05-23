
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
     
     $('#kdbidskpd').combogrid({  
       panelWidth:500,  
       idField:'kd_bidskpd',  
       textField:'kd_bidskpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_bidskpd',  
       columns:[[  
           {field:'kd_bidskpd',title:'KODE BIDANG',width:100},  
           {field:'nm_bidskpd',title:'NAMA BIDANG',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           if (lcstatus == 'tambah'){
           $("#kdunit").attr("value",rowData.kd_bidskpd.toUpperCase()+'.');
           }                 
       }  
     });   
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_unit_bidang',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_uskpd',
    		title:'Kode Unit',
    		width:15,
            align:"center"},
            {field:'nm_uskpd',
    		title:'Nama Unit',
    		width:40,
            align:"left"},
            {field:'kd_bidskpd',
    		title:'Kode Bidang',
    		width:15,
            align:"center"},
			{field:'hapus',width:10,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/public/images/cross.png' onclick='javascript:hapus();'' />";}}
        ]],
        onSelect:function(rowIndex,rowData){
          lcidx = rowIndex;
          kdunit = rowData.kd_uskpd;
          nmunit = rowData.nm_uskpd;
          kdbidskpd  = rowData.kd_bidskpd;
          lcalamat = rowData.alamat;
          get(kdunit,nmunit,kdbidskpd,lcalamat);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Bidang'; 
          lcstatus = 'edit';
          kdunit = rowData.kd_uskpd;
          nmunit = rowData.nm_uskpd;
          kdbidskpd  = rowData.kd_bidskpd;
          lcalamat = rowData.alamat;
          get(kdunit,nmunit,kdbidskpd,lcalamat);  
          edit_data();   
        }
        
        });
       
    });        

      
    function  get(kdunit,nmunit,kdbidskpd,lcalamat){
        $("#kdunit").attr("value",kdunit);
        $("#nmunit").attr("value",nmunit);
        $("#kdbidskpd").combogrid("setValue",kdbidskpd);
        $("#alamat").attr("value",lcalamat);
                       
    }
    
    function kosong(){
        $("#kdunit").attr("value",'');
        $("#nmunit").attr("value",'');
        $("#kdbidskpd").combogrid("setValue",'');
        $("#alamat").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    //alert(kriteria);
    $(function(){
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/master/load_unit_bidang',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan(){
        
        var ckdunit = document.getElementById('kdunit').value;
        var cnmunit = document.getElementById('nmunit').value;
        var ckdbidskpd = $('#kdbidskpd').combogrid('getValue');
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
            
            lcinsert = "(kd_uskpd,nm_uskpd,kd_bidskpd,alamat)";
            lcvalues = "('"+ckdunit+"','"+cnmunit+"','"+ckdbidskpd+"','"+calamat+"')";
            
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/master/simpan_master',
                    data: ({tabel:'unit_skpd',kolom:lcinsert,nilai:lcvalues,cid:'kd_uskpd',lcid:ckdunit}),
                    dataType:"json"
                });
            });    
            
            
        $('#dg').datagrid('appendRow',{kd_uskpd:ckdunit,nm_uskpd:cnmunit,kd_bidskpd:ckdbidskpd,alamat:calamat});
        }else {
            
            lcquery = "UPDATE unit_skpd SET nm_uskpd='"+cnmunit+"',kd_bidskpd='"+ckdbidskpd+"', alamat='"+calamat+"' where kd_uskpd='"+ckdunit+"'";
            
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
            		kd_uskpd: ckdunit,
            		nm_uskpd: cnmunit,
                    kd_bidskpd: ckdbidskpd,
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
        judul = 'Edit Data Bidang';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kdunit").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Unit Bidang';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kdunit").disabled=false;
        document.getElementById("kdbidskpd").focus();
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
         $.post(urll,({tabel:'unit_skpd',cnid:ckdunit,cid:'kd_uskpd'}),function(data){
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
        });    }}
    } 
    
    
  
   </script>



<div id="content1"> 
<h3 align="center"><u><b><a>INPUTAN UNIT BIDANG SKPD</a></b></u></h3>
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
        <table id="dg" align="center" title="LISTING DATA UNIT BIDANG SKPD" style="width:900px;height:365px;" >  
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
                <td>BIDANG</td>
                <td>:</td>
                <td><input id="kdbidskpd" name="kdbidskpd" style="width: 100px;" /></td>
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

