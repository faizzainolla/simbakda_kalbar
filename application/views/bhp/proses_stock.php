    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="<?php echo base_url(); ?>lib/sweet-alert.min.js"></script>
	<link   rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>lib/sweet-alert.css">
	<script type="text/javascript">
    
    var kdwil= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
	
	function opt(val){       
        del = val; 
        $("#hps").attr("value",del);
	}
 
	function trans_stock(){
		var hps 		= document.getElementById('hps').value;
		var cskpd 		= $("#kdubidskpd").combogrid("getValue");
		var ctahun 		= $("#tahun").combobox("getValue");
        var tgl_satu  	= $('#tgl_satu').datebox('getValue');
        var tgl_dua 	= $('#tgl_dua').datebox('getValue');
				
		document.getElementById('load').style.visibility='visible';
		$(function(){     
		 $.ajax({
			type: 'POST',
			data: ({skpd:cskpd,tahun:ctahun,hps:hps,tgl_satu:tgl_satu,tgl_dua:tgl_dua}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/bhp/trans_stock",
			success:function(data){
				if (data == '1'){
					swal("Good job!", "Transfer Stock Selesai !!", "success");
				}else{
					swal("Oops...", "Transfer Stock Gagal!", "error");					
				}
				document.getElementById('load').style.visibility='hidden';
			}
		 });
		});
		
	}

     
    $(function(){
        $('#kdubidskpd').combogrid({  
           panelWidth:500,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/ambil_ubidskpd2',  
           columns:[[  
               {field:'kd_skpd',title:'KODE',width:100},  
               {field:'nm_skpd',title:'SKPD',width:400}    
           ]],  
           onSelect:function(rowIndex,rowData){
               lcskpd = rowData.kd_skpd;
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#tahu').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pa',queryParams:({kduskpd:lcskpd}) });
               $('#bend').combogrid({url:'<?php echo base_url(); ?>index.php/master/ambil_pb',queryParams:({kduskpd:lcskpd}) });
                                
           }  

         });

       $('#tahun').combobox({           
        valueField:'nama',  
        textField:'nama',
        width:50,
        data:[{kode:'0',nama:'2012'},{kode:'1',nama:'2013'},{kode:'2',nama:'2014'},{kode:'3',nama:'2015'},{kode:'4',nama:'2016'},
        {kode:'5',nama:'2017'}]
    });
         $('#tgl_satu').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        });  
		$('#tgl_dua').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+(m<10? '0'+m:m)+'-'+(d<10? '0'+d:d);
            }
        }); 
    }); 
   </script>

<style>
.myButton {
	-moz-box-shadow: 0px 10px 24px -8px #276873;
	-webkit-box-shadow: 0px 10px 24px -8px #276873;
	box-shadow: 0px 10px 24px -8px #276873;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #599bb3), color-stop(1, #408c99));
	background:-moz-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-webkit-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-o-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-ms-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#599bb3', endColorstr='#408c99',GradientType=0);
	background-color:#599bb3;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Verdana;
	font-size:22px;
	font-weight:bold;
	padding:12px 58px;
	text-decoration:none;
	text-shadow:-1px 5px 0px #3d768a;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #408c99), color-stop(1, #599bb3));
	background:-moz-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-webkit-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-o-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-ms-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#408c99', endColorstr='#599bb3',GradientType=0);
	background-color:#408c99;
}
.myButton:active {
	position:relative;
	top:1px;
}

   </style>

<div id="content1"> 
    <h3 align="center"><b>.: PROSES TRANSFER STOCK TAHUN LALU :.</b></h3>
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input id="kdubidskpd" name="kdubidskpd" style="width: 100px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 500px;border:0" />
                </td>
            </tr>		
            <tr>
                <td width="20%">PERIODE STOCK</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_satu" style="width: 140px;" />S/D<input type="text" id="tgl_dua" style="width: 140px;" /></td>  
            </tr>
            <tr>
                <td width="20%">TAHUN TRANSFER</td>
                <td width="1%">:</td>
                <td><input type="text" id="tahun" style="width: 150px;" /></td>  
            </tr>
			<tr>
                <td width="20%">HAPUS PROSES SEBELUMNYA</td>
                <td width="1%">:</td>
                <td width="10px"><input type="checkbox" value="1" style="width: 10px;"  onclick="opt(this.value)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input id="hps" name="hps" hidden="true" style="width: 10px; border:0;" /><i><font color="red">*perhatikan stock yang ditransfer belum dikeluarkan.!</font></i></td>  
            </tr>
            <tr>
			<div id="content"> 
					<div id="accordion">
						<p align="right" >         
							<table id="" title="Proses Stock" style="width:800px;height:100px;" border="0px"> 
							</table>               
							<table id="" title="Proses Stock" style="width:920px;height:50px;" border="0px"> 
							<tr>
								<td width="100%" align="center"> <a onclick="javascript:trans_stock();" class="myButton">TRANSFER STOCK</a></td>
							</tr>
							<tr height="100%" >
								<td colspan="3" align="center" style="visibility:hidden;height:50px" ></td>
							</tr>
							<tr height="50%" >
								<td colspan="3" align="center" style="visibility:hidden" >	
								<DIV id="load"> <IMG src="<?php echo base_url(); ?>public/images/upload.gif" WIDTH="250" HEIGHT="120" BORDER="0" ALT=""></DIV></td>
							</tr>
							</table>             
						</p> 
					</div>
			</div>              
            </tr>
        </table>  
            
    </fieldset>  
</div>
