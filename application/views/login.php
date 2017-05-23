<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div id="loginbox">
	<div id="title">LOGIN SIMBAKDA</div>
    <div id="isi">
    	<?php echo form_open('welcome/login'); ?>
    	<table width="auto" cellpadding="0" cellspacing="0" >
			<tr>
            	<td rowspan="11" width="300px">
                	<img src="<?php echo base_url();?>public/images/partner2.png" width="200px" height="200px" />
                </td>
                <td width="330px">
             	<?php
                	if(isset($login_info))
					{
			   			echo "<span style='color:red;padding:3px;text-align:center;font-size:10px;'>";
			   			echo $login_info;
			   			echo '</span>';
					}else{
					?> &nbsp; <?php
					}
				?>
                </td>
            </tr>
            <tr>
            	<td><?php echo form_label('USERNAME'); ?>
                </td>
            </tr>
            <tr>
            	<td><?php echo form_input('username',set_value('username')); ?>
                </td>
            </tr>
            <tr>
            	<td><span style="font-size:9px; color:#FF0000;"><?php echo form_error('username'); ?></span>
                </td>
            </tr>
            <tr>
            	<td><?php echo form_label('PASSWORD'); ?>
                </td>
            </tr>
            <tr>
            	<td><?php echo form_password('password',set_value('password')); ?>
                </td>
            </tr>
            <tr>
            	<td><span style="font-size:9px; color:#FF0000;"><?php echo form_error('password'); ?></span>
                </td>
            </tr>
			<tr>
            	<td><?php echo form_label('TAHUN'); ?>
                </td>
            </tr>
            <tr>
            	<td><?php 
					$options = array(
                  		'2005'  => '2005',
                  		'2006'  => '2006',
                  		'2007'  => '2007',
                  		'2008'  => '2008',
                  		'2009'  => '2009',
                  		'2010'  => '2010',
                  		'2011'  => '2011',
                  		'2012'  => '2012',
                  		'2013'  => '2013',
                  		'2014'  => '2014',
                  		'2015'  => '2015',
                  		'2016'  => '2016'
                	);
					echo form_dropdown('ta',$options,'2016','class="select"'); ?>
                </td>
            </tr>
            <tr>
            	<td><span style="font-size:9px; color:#FF0000;"><?php echo form_error('ta'); ?></span>
                </td>
            </tr>
			<tr>
            	<td><?php echo form_submit('submit','Login','class="button red"'); ?>
                </td>
            </tr>
        </table>
      <?php echo form_close(); ?>
    </div>
</div>

<div id="marqlog">
    <marquee scrolldelay="80" ><p style="color:#008000" align="center"><h2>.:Selamat Datang di Portal Sistem Informasi Menejemen Barang dan Kekayaan Daerah Pemerintah Kabupaten Kapuas Hulu:.</h2></p></marquee>
</div>


