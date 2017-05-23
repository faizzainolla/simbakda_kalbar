
<script type="text/javascript">
	
	$(function(){ 
		           $('#bulan').combogrid({  
                   panelWidth:160,
                   panelHeight:300,  
                   idField:'n_bulan',  
                   textField:'bulan',  
                   mode:'remote',
                   url:'<?php echo base_url(); ?>index.php/transaksi_int/ambil_bulan',  
                   columns:[[ 
                       {field:'n_bulan',title:'Bulan ke',width:60},
					   {field:'bulan',title:'Nama Bulan',width:100}
                   ]],
					onSelect:function(rowIndex,rowData){
						bulan = rowData.bulan;
						$("#n_bulan").combogrid("setValue",'');
						$("#bulan").attr("value",rowData.bulan);	
					}
               }); 
		  });
	
	function data_realisasi(){
		var nbulan = $("#bulan").combogrid("getValue",'');
		var del=confirm('Update data realisasi akan menghapus data yang sudah ada di Bulan ini | Anda yakin?');
			if  (del==true){
		document.getElementById('load').style.visibility='visible';
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:'<?php echo base_url(); ?>index.php/transaksi_int/proses_update_data_realisasi/'+nbulan,
			success:function(data){
			if (data = 1){
					alert('PROSES SELESAI');
					document.getElementById('load').style.visibility='hidden';
				} else{
					alert('PROSES GAGAL');
					document.getElementById('load').style.visibility='hidden';					
					}
			}
		 });
		});
			}
	}
	
	function data_kapitalisasi(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/transaksi_int/proses_update_data_kapitalisasi",
			success:function(data){
			if (data = 1){
					alert('PROSES SELESAI');
					document.getElementById('load').style.visibility='hidden';
				} else{
					alert('PROSES GAGAL');
					document.getElementById('load').style.visibility='hidden';					
					}
			}
		 });
		});
	}
	
	function update_mutasi_hapus(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/transaksi/proses_update_mutasi_hapus",
			success:function(data){
			if (data = 1){
					alert('PROSES SELESAI');
					document.getElementById('load').style.visibility='hidden';
				} else{
					alert('PROSES GAGAL');
					document.getElementById('load').style.visibility='hidden';					
					}
			}
		 });
		});
	}
	
	function update_kapitalisasi(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/transaksi/proses_update_kapitalisasi_aset",
			success:function(data){
			if (data = 1){
					alert('PROSES SELESAI');
					document.getElementById('load').style.visibility='hidden';
				} else{
					alert('PROSES GAGAL');
					document.getElementById('load').style.visibility='hidden';					
					}
			}
		 });
		});
	}
	

</script>
<div id="content1"> 
    <h3 align="center"><b>UPDATE DATA</b></h3>
    <fieldset>
     <table id="sp2d" title="Mapping Realisasi" style="width:870px;height:300px;" >  
		<h3><tr >
			<td width="1000%" align="left" ><B>&nbsp;&nbsp;&nbsp;&nbsp;* PILIH BULAN HANYA UNTUK UPDATE REALISASI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B><input type="text" id="bulan" style="width: 100px;"/></td>
		</tr>
		<tr></tr><tr></tr><tr></tr>
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="UPDATE DATA REALISASI DARI SIMAKDA" style="height:40px;width:450px" onclick="data_realisasi()" ></td>
		</tr>
		<!--
		<tr></tr><tr></tr><tr></tr>
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="UPDATE DATA KAPITALISASI KE SIMAKDA" style="height:40px;width:450px" onclick="data_kapitalisasi()" ></td>
		</tr>
		<tr></tr><tr></tr><tr></tr>
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="UPDATE KAPITALISASI ASET DARI SIMAKDA" style="height:40px;width:450px" onclick="update_kapitalisasi()" ></td>
		</tr>
		<tr></tr><tr></tr><tr></tr>
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="UPDATE MUTASI PENGHAPUSAN" style="height:40px;width:450px" onclick="update_mutasi_hapus()" ></td>
		</tr>
		--></h3>
		<tr height="100%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>public/images/proses.gif" WIDTH="200" HEIGHT="200" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>
    </fieldset>  
</div>




        
