<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_int extends CI_Controller {
        
	public function __construct() {
		parent::__construct();
	}
	public function index($data) {
    	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	    //echo $data['table'];
			//$query = 'select * from mgolongan order by golongan';
	  		//$data = $this->Mdata->getall($query,"golongan/index");
         	$this->template->set('title','.::SIMBAKDA::.');
         	$this->template->load('index',$data['tabel'],$data['isi']);		
		}	
	}

//rebuild novar kahfi 2016
    
    function refuser()
    {
        $data['page_title']= 'INPUT USER';
        $this->template->set('title', 'INPUT USER');   
        $this->template->load('index','referensi/ref_user',$data) ; 
    }
    
    function otorisasi()
    {
        $data['page_title']= 'OTORISASI';
        $this->template->set('title', 'OTORISASI');   
        $this->template->load('index','referensi/otorisasi',$data) ; 
    }
    
    function konfigurasi()
    {
        $data['page_title']= 'OTORISASI';
        $data = $this->ambil_konfigurasi();
        $this->template->set('title', 'OTORISASI');   
        $this->template->load('index','referensi/konfigurasi',$data) ;
    }
	    
		function ganti_pass()
    {
        $data['page_title']= 'OTORISASI';
        $data = $this->ambil_konfigurasi();
        $this->template->set('title', 'OTORISASI');   
        $this->template->load('index','referensi/ganti_pass',$data) ;
    }
    
    function ambil_konfigurasi(){

        $csql = " select * from config ";
        $query1 = $this->db->query($csql);  
        foreach($query1->result_array() as $resulte)
        { 
            $resulte = array(              
                         'nm_client'      => $resulte['nm_client'],                                              					
                         'kepala'         => $resulte['kepala'],                                              					
                         'nip_kepala'     => $resulte['nip_kepala'],                                              					
                         'pkt_kepala'     => $resulte['pkt_kepala'],
                         'nama_bendahara' => $resulte['nama_bendahara'],
                         'nip_bendahara'  => $resulte['nip_bendahara'],
                         'pkt_bendahara'  => $resulte['pkt_bendahara'],
                         'lprint'         => $resulte['lprint'],
                         'kota'           => $resulte['kota'],
                         'logo'           => $resulte['logo'],
                         'plonline'       => $resulte['plonline'],
                         'basis'          => $resulte['basis'],
                         );
        }
	   
       $query1->free_result(); 
	   return $resulte;        	
	}	
    
    function simpan_konfigurasi(){
        $client   = $this->input->post('client');
        $pimpinan = $this->input->post('pimpinan');
        $nip_pimp = $this->input->post('nip_pimp');
        $pkt_pimp = $this->input->post('pkt_pimp');
        $kota     = $this->input->post('kota');
        $logo     = $this->input->post('logo');
        $status   = $this->input->post('cstatus');
        $basis    = $this->input->post('basis');
        
        $csql     = "update config set nm_client='$client',kepala='$pimpinan',nip_kepala='$nip_pimp', pkt_kepala='$pkt_pimp',kota='$kota',logo='$logo',plonline='$status',basis='$basis'";
        $query1   = $this->db->query($csql);  
        
    }
	
	function simpan_password(){
	//nm_admin:nm_admin,email:email,password:password,reply_pass:reply_pass
        $skpd  			= $this->input->post('skpd');
        $uskpd  		= $this->input->post('uskpd');
        $nm_admin  		= $this->input->post('nm_admin');
        $email  		= $this->input->post('email');
        $password  		= $this->input->post('password');
        $reply_pass  	= $this->input->post('reply_pass');
        $username  		= $this->input->post('username');
        $waktu  		= $this->input->post('waktu');
        $msg = array();
        $csql = "update muser set iduser=md5('$username'),password=md5('$password'),nama_admin='$nm_admin',email_admin='$email' where unit_skpd='$uskpd' and skpd='$skpd'";
		$query1 = $this->db->query($csql);  
			$csq2 = "insert into muser_temp values('','$username','$password','$nm_admin','','','$skpd','$uskpd','$nm_admin','$email','$waktu')";
			$query2 = $this->db->query($csq2); 
				if($query1){
					$msg = array('pesan'=>'1');
					echo json_encode($msg);
				}else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
    }
    
	  function import_data()
    {
        $data['page_title']= 'IMPORT';
        $this->template->set('title', 'IMPORT');   
        $this->template->load('index','referensi/import_data',$data) ; 
    }
    
	  function export_data()
    {
        $data['page_title']= 'EXSPORT';
        $this->template->set('title', 'EXSPORT');   
        $this->template->load('index','referensi/export_data',$data) ; 
    }
    public function subkelompok() {
    	if($this->auth->is_logged_in() == false){
        	redirect(site_url().'welcome/login');
      	}else{
			$query = "select kd_kelompok, nm_kelompok,(select concat(kelompok,' - ',nm_kelompok) as co from 
            mkelompok where mkelompok.kelompok = mkelompok1.kelompok) as kelompok from mkelompok1 order by kd_kelompok";
	  		$data['isi'] = $this->Mdata->getall($query,"/master/subkelompok");
            $data['tabel'] = "mkelompok1/index" ;
            $this->index($data); 		
		}	
	}
	public function cari_subkel(){
	   if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$this->auth->restrict();
		$cari = $this->input->post('cari');
    	$query = "select kd_kelompok, nm_kelompok ,(select concat(kelompok,' - ',nm_kelompok) as co from 
        mkelompok where mkelompok.kelompok = mkelompok1.kelompok) as kelompok from mkelompok1 where kd_kelompok 
        like '%".$cari."%' or nm_kelompok like '%".$cari."%' order by kd_kelompok";
        
		//$query = 'select * from mbidang where bidang like "%'.$cari.'%" or nm_bidang like "%'.$cari.'%"  order by bidang'; 
		$data = $this->Mdata->getall($query,'berita/index');
        $this->template->set('title','.::SIMBAKDA::.');
        $this->template->load('index','mkelompok1/index',$data);
        }
   	}
    
    function ambil_warna() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_warna, nm_warna FROM mwarna where upper(kd_warna) like upper('%$lccr%') or upper(nm_warna) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_warna' => $resulte['kd_warna'],  
                        'nm_warna' => $resulte['nm_warna'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
function kibpelihara() {
        $lccr = $this->input->post('q');
        $sql = "SELECT id_barang, kd_brg, nm_brg, sisa_umur, nilai,kd_brg,pemeliharaan_ke,kd_rek5 FROM trkib_b where upper(id_barang) like upper('%$lccr%') or upper(nm_brg) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'id_barang' => $resulte['id_barang'],  
                        'kd_brg' => $resulte['kd_brg'],
						'nm_brg' => $resulte['nm_brg'],  
                        'sisa_umur' => $resulte['sisa_umur'],
					    'nilai' => $resulte['nilai'],
					    'kd_brg' => $resulte['kd_brg'],
					    'pemeliharaan_ke' => $resulte['pemeliharaan_ke'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
	
    function ambil_bahan() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_bahan, nm_bahan FROM mbahan where upper(kd_bahan) like upper('%$lccr%') or upper(nm_bahan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bahan' => $resulte['kd_bahan'],  
                        'nm_bahan' => $resulte['nm_bahan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_satuan() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_satuan, nm_satuan FROM msatuan where upper(kd_satuan) like upper('%$lccr%') or upper(nm_satuan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_satuan' => $resulte['kd_satuan'],  
                        'nm_satuan' => $resulte['nm_satuan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
	function ambil_rekbrg() {
        //$lccr = $this->input->post('q');				
		$rekbrg = $_REQUEST['kode'];
        $sql = "SELECT kd_brg, nm_brg FROM mbarang where left(kd_brg,5)='$rekbrg'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
						 'kd_barang' => $resulte['kd_brg'],  
                        'nm_barang' => $resulte['nm_brg'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
	
	public function input_subkel()
	{
	   if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
	   // mencegah user yang belum login untuk mengakses halaman ini
    	   $this->auth->restrict();
    	 
    	   $this->load->library('form_validation');
    	 
    	   $this->form_validation->set_rules('kd_kel', 'Sub Kelompok', 'trim|required');
    	   $this->form_validation->set_rules('nama', 'Uraian', 'trim|required');
    	   $this->form_validation->set_rules('jns_kel', 'Kelompok', 'trim|required');
    	   $this->form_validation->set_error_delimiters(' <span style="color:#FF0000; font-size:9;">', '</span>');
    	 
    	   if ($this->form_validation->run() == FALSE)
    	   {  
    	      //$query = "select bidang,nm_bidang from mbidang";
              $query = "select kelompok,concat(kelompok,' - ',nm_kelompok) as nm_kelompok from mkelompok";
              $data_pilih = $this->Mdata->viewdata($query);
              $lcb = $data_pilih->num_rows();
              $lcdata = "";
    
              foreach($data_pilih->result()as $dt_kel){
                $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
              }    
               
              //$data['option'] = array('1'=>'Aset','2'=>'Non Aset');
    		  $this->template->set('title','.::SIMBAKDA::.');
    		  $this->template->load('index','mkelompok1/input',$data);
    	   }
    	   else
    	   {
    	   	  $id = $this->input->post('kd_kel');
    		  $cek = array('kd_kelompok'=>$id);
    	   	  if($this->Mdata->check_id($cek,'mkelompok1') == false) {
    	   	    
              //$query = "select bidang,nm_bidang from mbidang";
              $query = "select kelompok,concat(kelompok,' - ',nm_kelompok) as nm_kelompok from mkelompok";
              $data_pilih = $this->Mdata->viewdata($query);
              $lcb = $data_pilih->num_rows();
              $lcdata = "";
    
              foreach($data_pilih->result()as $dt_kel){
                $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
              }  
    		  	//$data['option'] = array('1'=>'Aset','2'=>'Non Aset');
    			$data['errinput'] = 'Sub Kelompok sudah terdaftar!';
    		  	$this->template->set('title','.::SIMBAKDA::.');
    		  	$this->template->load('index','mkelompok1/input',$data);
    	   	  }else {
    		  $data_input = array(
    			 'kd_kelompok' =>$this->input->post('kd_kel'),
    			 'nm_kelompok'   =>$this->input->post('nama'),
    			 'kelompok'   =>$this->input->post('jns_kel')
    		  );
    		  $this->Mdata->save($data_input,'mkelompok1');
    		  // kembalikan ke halaman manajemen user
    		  redirect(site_url().'/master/subkelompok');
    		  }
    	   }
        }
	}
    
    function perolehan() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM cara_peroleh ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kd_cr_oleh' => $resulte['kd_cr_oleh'],        
                        'cara_perolehan' => $resulte['cara_peroleh']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function mkondisi() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mkondisi order by kode ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'kondisi' => $resulte['kondisi']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}   
	
	function mriwayat() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mriwayat order by kode ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'riwayat' => $resulte['riwayat']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
     function pengawas() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM pengawas ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'nip' => $resulte['nip'],        
                        'nama' => $resulte['nama']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function load_oto(){

		$coba[] = array('oto' => '01','ket' => 'Administrator');		
		$coba[] = array('oto' => '02','ket' => 'Operator 1');		
		$coba[] = array('oto' => '03','ket' => 'Operator 2');		

		$result["rows"] = $coba;   
        echo json_encode($result);
	 
	}
    
    function load_otorisasi() {
       
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 30;
	    $offset = ($page-1)*$rows;        

        $rs = $this->db->query("select count(*) as tot FROM ms_menu where parent<>'0' ");
        $trh = $rs->row();

		$sql = "SELECT idmenu,judul,if(m01='1','YA','TIDAK') as m01,if(m02='1','YA','TIDAK') as m02,if(m03='1','YA','TIDAK') as m03 FROM ms_menu where parent<>'0' order by idmenu limit $offset,$rows  ";
        $query1 = $this->db->query($sql);  


        $ii = 0;
        foreach($query1->result_array() as $resulte){ 

            $coba[] = array(
                        'idmenu' => $resulte['idmenu'],
                        'judul'  => $resulte['judul'],
                        'administrator' => $resulte['m01'],
                        'operator1'	   => $resulte['m02'],
                        'operator2'	=> $resulte['m03']
                        );
                        $ii++;
        }
        
        $result["rows"] = $coba;   
		$result["total"] = $trh->tot; 				
        echo json_encode($result);
    	$query1->free_result();   
	}
    
	   function malasan() {
        $sql 	= "SELECT * FROM malasan ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,
                        'no' => $resulte['no'],        
                        'alasan' => $resulte['alasan']
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
	function simpan_malasan(){
	$id_barang	= $this->input->post('id');
	$keterangan	= $this->input->post('ket');
	$kode		= $this->input->post('kode');
	$kd_brg		= substr($kode,0,2);
	if ($kd_brg=='01'){
	$this->db->query("update trhapus_a set keterangan='$keterangan' where id_barang='$id_barang'");
	}if($kd_brg=='02'){
	$this->db->query("update trhapus_b set keterangan='$keterangan' where id_barang='$id_barang'");
	}if($kd_brg=='03'){
	$this->db->query("update trhapus_c set keterangan='$keterangan' where id_barang='$id_barang'");
	}if($kd_brg=='04'){
	$this->db->query("update trhapus_d set keterangan='$keterangan' where id_barang='$id_barang'");
	}if($kd_brg=='05'){
	$this->db->query("update trhapus_e set keterangan='$keterangan' where id_barang='$id_barang'");
	}else{
	$this->db->query("update trhapus_f set keterangan='$keterangan' where id_barang='$id_barang'");
	} 
	
	}
	
    function yatidak1() {
		$result[] = array('administrator' => 'YA');
		$result[] = array('administrator' => 'TIDAK');                       
        echo json_encode($result);    	   
	}

 	function yatidak2() {
		$result[] = array('operator1' => 'YA');
		$result[] = array('operator1' => 'TIDAK');                       	
        echo json_encode($result);
	}

 	function yatidak3() {
		$result[] = array('operator2' => 'YA');
		$result[] = array('operator2' => 'TIDAK');                       
        echo json_encode($result);
	}
    
    function simpan_otorisasi(){

		$id			=trim($this->input->post('id'));	
		$adm		=trim($this->input->post('adm'));	
		$ope1		=trim($this->input->post('oper1'));	
		$ope2       =trim($this->input->post('oper2'));
        

		if($adm=='YA'){
			$m01='1';
		}else{
			$m01='0';
		} 
		if($ope1=='YA'){
			$m02='1';
		}else{
			$m02='0';
		} 
		if($ope2=='YA'){
			$m03='1';
		}else{
			$m03='0';
		}

		$this->db->query(" update ms_menu set m01='$m01',m02='$m02',m03='$m03' where rtrim(idmenu)='$id' ");
		
	}
    
    function load_user_a() {
       
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria != ''){                               
            $where=" where nmuser like '%$kriteria%' OR ket like '%$kriteria%' ";            
        }			   

        $rs = $this->db->query("select count(*) as tot FROM muser $where order by nmuser");
        $trh = $rs->row();
        

        $sql = "SELECT top $rows * FROM muser $where order by skpd,nmuser ";
        $query1 = $this->db->query($sql);  

		$nmoto='';
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
			$otori=$resulte['oto'];
			if($otori=='01' || $otori=='04'){
				$nmoto='Administrator';
			}elseif($otori=='02'){
				$nmoto='Operator 1';			
			}elseif($otori=='03'){
				$nmoto='Operator 2';			
			}

            $coba[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],
                        'nmuser' => $resulte['nmuser'],
                        'oto'    => $resulte['oto'],
                   //     'nmoto'  => $nmoto,
                        'ket'    => $resulte['ket'],
						'skpd'	 => $resulte['skpd'],
						'uskpd'	 => $resulte['unit_skpd']
                        );
                        $ii++;
        }
        
		$result["total"] = $trh->tot; 				
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
	}
    
   
    function simpan_user(){
        $user = md5(trim($this->input->post('user')));
        $pass = md5(trim($this->input->post('pass')));
        $skpd = $this->input->post('skpd');
        $nmskpd = $this->input->post('user');
        $unit_skpd = $this->input->post('unit_skpd');
        $oto = $this->input->post('oto');
        $ket = $this->input->post('ket');
        $del = trim($this->input->post('del'));
        $nama_admin = '';
        $email_admin = '';

		$query1 = $this->db->query("delete from muser where nmuser='$nmskpd' and iduser='$user' and password='$pass'");  
		if ($del=='0'){
		   $query1 = $this->db->query("insert into muser values('$user','$pass','$nmskpd','$oto','$ket','$skpd','$unit_skpd','$nama_admin','$email_admin','' ) ");  
		}
	}

    function update_user_dh(){
        $kode = $this->input->post('kode');
        $user = md5(trim($this->input->post('user')));
        $pass = md5(trim($this->input->post('pass')));
        $skpd = $this->input->post('skpd');
        $nmuser = $this->input->post('user');
        $unit_skpd = $this->input->post('unit_skpd');
        $oto = $this->input->post('oto');
        $ket = $this->input->post('ket');
        $msg = array();

        $sql="update muser set password='$pass', nmuser='$nmuser',oto='$oto',ket='$ket',iduser='$user',skpd='$skpd',unit_skpd='$unit_skpd' where kode='$kode'";
        $asg = $this->db->query($sql);
                if($asg){
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }else{
                   $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                }  
        
    }
    
    
    function ambil_lokasi() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_lokasi, nm_lokasi,kd_skpd FROM mlokasi where upper(kd_lokasi) like upper('%$lccr%') or upper(nm_lokasi) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_lokasi' => $resulte['kd_lokasi'],  
                        'nm_lokasi' => $resulte['nm_lokasi'],  
						'kd_skpd'	=> $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function mstatus() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM st_tanah order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'status' => $resulte['status']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
	function mjenis() {
        $sql = "SELECT * FROM mjenis order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'jns_bangunan' => $resulte['jns_bangunan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	}	

	function mkonstruksi() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mkonstruksi ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'nm_konstruksi' => $resulte['nm_konstruksi']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
	function mkonstruksi2() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mkonstruksi2 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'nm_konstruksi' => $resulte['nm_konstruksi']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
     function mdana() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mdana ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kd_sumberdana'],        
                        'sumber_dana' => $resulte['nm_sumberdana']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function mbukti() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mbukti ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'Bukti' => $resulte['bukti_pembayaran']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function dasar_perolehan() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mdasar ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'dasar_perolehan' => $resulte['dasar_peroleh']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    
	public function edit_subkel() {
	   if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
	   $this->auth->restrict();
      
	   $this->load->library('form_validation');
	   //$this->form_validation->set_rules('bid', 'bid', 'trim|required');
	   $this->form_validation->set_rules('nama', 'nama', 'trim|required');
	   $this->form_validation->set_rules('jns_kel', 'jns_kel', 'trim|required');
	   $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	 
	   // dapatkan id user dari segment ke-3 dari URI
	   $id = $this->uri->segment(3);
	   $cond = array ('kd_kelompok'=>$id);
	   if ($this->form_validation->run() == FALSE)
	   {
	      
          $query = "select kelompok,concat(kelompok,' - ',nm_kelompok) as nm_kelompok from mkelompok";
          $data_pilih = $this->Mdata->viewdata($query);
          $lcb = $data_pilih->num_rows();
          $lcdata = "";
    
           foreach($data_pilih->result()as $dt_kel){
            $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
          }
          

		  $data['msubkel'] = $this->Mdata->getdata($cond,'mkelompok1');
		  $this->template->set('title','.::SIMBAKDA::.');
		  $this->template->load('index','mkelompok1/edit',$data);
	   }
	   else
	   {
	       $data_user = array(
           	 'nm_kelompok'   =>$this->input->post('nama'),
			 'kelompok'   =>$this->input->post('jns_kel')
		  );
          
//          echo $this->input->post('nama')."<br>";
//          echo $skdjskd."<br>";
//          echo $this->input->post('jns_bid')."<br>";
          
		  $this->Mdata->update($data_user,$cond,'mkelompok1');
		  // kembalikan ke halaman manajemen user
		  redirect(site_url().'/master/subkelompok');
	   }	
       }
	}
	public function del_subkel()
    {
	   
	   // mencegah user yang belum login untuk mengakses halaman ini
	   $this->auth->restrict();
	   // dapatkan id user dari segment ke-3 dari URI
	   $id = $this->uri->segment(3);
	   $cond = array('kd_kelompok'=>$id);
	   $this->Mdata->delete($cond,'mkelompok1');
	   // kembalikan ke halaman manajemen user
	   redirect(site_url().'/master/subkelompok');
	}
//=========================== end of master sub kelompok ===============================

//===========================start of master barang ====================================
    public function barang() {
    	if($this->auth->is_logged_in() == false){
        	redirect(site_url().'welcome/login');
      	}else{
			$query = "select kd_brg,kd_rek5,nm_brg,(select concat(kd_kelompok,' - ',nm_kelompok) as co from 
            mkelompok1 where mkelompok1.kd_kelompok = mbarang.kd_kelompok) as kd_kelompok from mbarang order by kd_brg";
	  		$data['isi'] = $this->Mdata->getall($query,"/master/barang");
            $data['tabel'] = "mbarang/index" ;
            $this->index($data); 		
		}	
	}
    
    function submkel1()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER SUB KELOMPOK';
        $this->template->set('title', 'INPUT MASTER SUB KELOMPOK');   
        $this->template->load('index','master/msubkel',$data) ;
        } 
    }
    
    function mkel1()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
       	}else{
            $data['page_title']= 'INPUT MASTER KELOMPOK';
            $this->template->set('title', 'INPUT MASTER KELOMPOK');   
            $this->template->load('index','master/mkelompok',$data) ;
        } 
    }
    function mruang()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT MASTER RUANG';
            $this->template->set('title', 'INPUT MASTER RUANG');   
            $this->template->load('index','master/mruang',$data) ;
        } 
    }
    
    function mpangkat()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT MASTER PANGKAT';
            $this->template->set('title', 'INPUT MASTER PANGKAT');   
            $this->template->load('index','master/mpangkat',$data) ; 
       }
    }
    function mwarna()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT MASTER WARNA';
            $this->template->set('title', 'INPUT MASTER WARNA');   
            $this->template->load('index','master/mwarna',$data) ;
        } 
    }
    
    
    
    function mgol1()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT MASTER GOLONGAN';
            $this->template->set('title', 'INPUT MASTER GOLONGAN');   
            $this->template->load('index','master/mgolongan',$data) ;
        } 
    }
    
    function mttd()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT PENANDATANGAN';
            $this->template->set('title', 'INPUT PENANDATANGAN');   
            $this->template->load('index','master/mttd',$data) ;
        } 
    }
    
    function musaha()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER GOLONGAN';
        $this->template->set('title', 'INPUT MASTER GOLONGAN');   
        $this->template->load('index','master/mperusahaan',$data) ;
        } 
		
		
    }
    function mlokasi()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER LOKASI';
        $this->template->set('title', 'INPUT MASTER LOKASI');   
        $this->template->load('index','master/mlokasi',$data) ;
        } 
    }
    
    function mbid1()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER BIDANG';
        $this->template->set('title', 'INPUT MASTER BIDANG');   
        $this->template->load('index','master/mbidang',$data) ;
        } 
    }
    
    function msatuan()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER SATUAN';
        $this->template->set('title', 'INPUT MASTER SATUAN');   
        $this->template->load('index','master/msatuan',$data) ;
        } 
    }
    
    function mbidang_skpd()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER BIDANG SKPD';
        $this->template->set('title', 'INPUT MASTER BIDANG SKPD');   
        $this->template->load('index','master/mbidang_skpd',$data) ;
        } 
    }
    
    function munit_bidang()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER UNIT BIDANG';
        $this->template->set('title', 'INPUT MASTER UNIT BIDANG');   
        $this->template->load('index','master/munit_bidang',$data) ;
        } 
    }
    function munit_kerja()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER UNIT KERJA';
        $this->template->set('title', 'INPUT MASTER UNIT KERJA');   
        $this->template->load('index','master/munit_kerja',$data) ;
        } 
    }
    
    
    function mmilik()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER BIDANG';
        $this->template->set('title', 'INPUT MASTER PEMILIK');   
        $this->template->load('index','master/mmilik',$data) ;
        } 
    }
    
    function mwilayah()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER WILAYAH';
        $this->template->set('title', 'INPUT MASTER WILAYAH');   
        $this->template->load('index','master/mwilayah',$data) ;
        } 
    }
    
    function mbarang()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER BIDANG';
        $this->template->set('title', 'INPUT MASTER BIDANG');   
        $this->template->load('index','master/mbarang',$data) ;
        } 
    }

	    function mmasa()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'INPUT MASTER UMUR BARANG';
            $this->template->set('title', 'INPUT MASTER UMUR BARANG');   
            $this->template->load('index','master/mmasa',$data) ;
        } 
    }
    
	   function mcari()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
            $data['page_title']= 'PENCARIAN BARANG';
            $this->template->set('title', 'PENCARIAN BARANG');   
            $this->template->load('index','master/mcari',$data) ;
        } 
    }
	
	public function cari_barang(){
		$this->auth->restrict();
		$cari = $this->input->post('cari');
    	$query = "select kd_brg,kd_rek5,nm_brg,(select concat(kd_kelompok,' - ',nm_kelompok) as co from 
        mkelompok1 where mkelompok1.kd_kelompok = mbarang.kd_kelompok) as kd_kelompok from mbarang where kd_kelompok 
        like '%".$cari."%' or nm_kelompok like '%".$cari."%' order by kd_kelompok";
        
		//$query = 'select * from mbidang where bidang like "%'.$cari.'%" or nm_bidang like "%'.$cari.'%"  order by bidang'; 
		$data = $this->Mdata->getall($query,'berita/index');
        $this->template->set('title','.::SIMBAKDA::.');
        $this->template->load('index','mkelompok1/index',$data);
   	}
	public function input_barang()
	{
	   // mencegah user yang belum login untuk mengakses halaman ini
	   $this->auth->restrict();
	 
	   $this->load->library('form_validation');
	 
	   $this->form_validation->set_rules('kd_kel', 'Sub Kelompok', 'trim|required');
	   $this->form_validation->set_rules('nama', 'Uraian', 'trim|required');
	   $this->form_validation->set_rules('jns_kel', 'Kelompok', 'trim|required');
	   $this->form_validation->set_error_delimiters(' <span style="color:#FF0000; font-size:9;">', '</span>');
	 
	   if ($this->form_validation->run() == FALSE)
	   {  
	      //$query = "select bidang,nm_bidang from mbidang";
          $query = "select kelompok,concat(kelompak,' - ',nm_kelompok) as nm_kelompok from mkelompok";
          $data_pilih = $this->Mdata->viewdata($query);
          $lcb = $data_pilih->num_rows();
          $lcdata = "";

          foreach($data_pilih->result()as $dt_kel){
            $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
          }    
           
          //$data['option'] = array('1'=>'Aset','2'=>'Non Aset');
		  $this->template->set('title','.::SIMBAKDA::.');
		  $this->template->load('index','mkelompok1/input',$data);
	   }
	   else
	   {
	   	  $id = $this->input->post('kd_kel');
		  $cek = array('kd_kelompok'=>$id);
	   	  if($this->Mdata->check_id($cek,'mkelompok1') == false) {
	   	    
          //$query = "select bidang,nm_bidang from mbidang";
          $query = "select kelompok,concat(kelompak,' - ',nm_kelompok) as nm_kelompok from mkelompok";
          $data_pilih = $this->Mdata->viewdata($query);
          $lcb = $data_pilih->num_rows();
          $lcdata = "";

          foreach($data_pilih->result()as $dt_kel){
            $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
          }  
		  	//$data['option'] = array('1'=>'Aset','2'=>'Non Aset');
			$data['errinput'] = 'Sub Kelompok sudah terdaftar!';
		  	$this->template->set('title','.::SIMBAKDA::.');
		  	$this->template->load('index','mkelompok1/input',$data);
	   	  }else {
		  $data_input = array(
			 'kd_kelompok' =>$this->input->post('kd_kel'),
			 'nm_kelompok'   =>$this->input->post('nama'),
			 'kelompok'   =>$this->input->post('jns_kel')
		  );
		  $this->Mdata->save($data_input,'mkelompok1');
		  // kembalikan ke halaman manajemen user
		  redirect(site_url().'/master/subkelompok');
		  }
	   }
	}
	public function edit_barang() {
	   $this->auth->restrict();
      
	   $this->load->library('form_validation');
	   //$this->form_validation->set_rules('bid', 'bid', 'trim|required');
	   $this->form_validation->set_rules('nama', 'nama', 'trim|required');
	   $this->form_validation->set_rules('jns_kel', 'jns_kel', 'trim|required');
	   $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	 
	   // dapatkan id user dari segment ke-3 dari URI
	   $id = $this->uri->segment(3);
       $skdjskd = $this->uri->segment(3);
	   $cond = array ('kd_kelompok'=>$id);
	   if ($this->form_validation->run() == FALSE)
	   {
	      
          $query = "select kelompok,concat(kelompok,' - ',nm_kelompok) as nm_kelompok from mkelompok";
          $data_pilih = $this->Mdata->viewdata($query);
          $lcb = $data_pilih->num_rows();
          $lcdata = "";
    
           foreach($data_pilih->result()as $dt_kel){
            $data['option'][$dt_kel->kelompok]=$dt_kel->nm_kelompok;
          }
          

		  $data['msubkel'] = $this->Mdata->getdata($cond,'mkelompok1');
		  $this->template->set('title','.::SIMBAKDA::.');
		  $this->template->load('index','mkelompok1/edit',$data);
	   }
	   else
	   {
	       $data_user = array(
           	 'nm_kelompok'   =>$this->input->post('nama'),
			 'kelompok'   =>$this->input->post('jns_kel')
		  );
          
//          echo $this->input->post('nama')."<br>";
//          echo $skdjskd."<br>";
//          echo $this->input->post('jns_bid')."<br>";
          
		  $this->Mdata->update($data_user,$cond,'mkelompok1');
		  // kembalikan ke halaman manajemen user
		  redirect(site_url().'/master/subkelompok');
	   }	
	}
	public function del_barang()
    {
	
	   // mencegah user yang belum login untuk mengakses halaman ini
	   $this->auth->restrict();
	   // dapatkan id user dari segment ke-3 dari URI
	   $id = $this->uri->segment(3);
	   $cond = array('kd_kelompok'=>$id);
	   $this->Mdata->delete($cond,'mkelompok1');
	   // kembalikan ke halaman manajemen user
	   redirect(site_url().'/master/subkelompok');
	}
//=========================== end of master barang ===============================
function load_cari() {
    if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		
        $kriteria = '';
        $gol	 	= $this->input->post('gol');
        $kriteria 	= $this->input->post('cari');
        $skpd	 	= $this->input->post('skpd');
        $tahun	 	= $this->input->post('tahun');
        $tahun2	 	= $this->input->post('tahun2');
		
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
		
        $oto  = $this->session->userdata('otori');
        $where1 = '';     
		$kunci	= '';
		
		if($gol=='01'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.no_sertifikat) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%') 
					or upper(a.penggunaan) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					)";
		}if($gol=='02'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					)";
		}if($gol=='03'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.luas_gedung) like upper('%$kriteria%') 
					or upper(a.luas_tanah) like upper('%$kriteria%')
					or upper(a.luas_lantai) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					)";
		}if($gol=='04'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					)";
		}if($gol=='05'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					)";
		}if($gol=='06'){
			$kunci	= "(upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					)";
		}
		
        if($tahun2 == ''){ 
            $where1 = "where a.kd_skpd like '%$skpd%' and a.tahun like '%$tahun%' and $kunci";
        }else{
            $where1 = "where a.kd_skpd like '%$skpd%' and $kunci AND a.tahun>='$tahun' AND a.tahun<='$tahun2'";
        }        
 if($gol=='01'){        
$sql="SELECT a.kd_brg,b.nm_brg,'-' AS merek,a.nilai,a.tahun,a.kd_skpd,a.kondisi,a.keterangan
FROM trkib_a a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";}
 elseif($gol=='02'){        
$sql="SELECT a.kd_brg,b.nm_brg,ifnull((a.merek),'-') as merek,a.nilai,a.tahun,a.kd_skpd,a.kondisi,a.keterangan
FROM trkib_b a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";} 
 elseif($gol=='03'){        
$sql="SELECT a.kd_brg,b.nm_brg,ifnull((a.luas_tanah),'-') as merek,a.nilai,a.tahun,a.kd_skpd,a.kondisi,a.keterangan
FROM trkib_c a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";}
 elseif($gol=='04'){        
$sql="SELECT a.kd_brg,b.nm_brg,ifnull((a.panjang),'-') as merek,a.nilai,a.tahun,a.kd_skpd,a.kondisi,a.keterangan
FROM trkib_d a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";}
 elseif($gol=='05'){        
$sql="SELECT a.kd_brg,b.nm_brg,ifnull((a.judul),'-') as merek,a.nilai,a.tahun,a.kd_skpd ,a.kondisi,a.keterangan
FROM trkib_e a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";}
 elseif($gol=='06'){        
$sql="SELECT a.kd_brg,b.nm_brg,'-' AS merek,a.nilai,a.tahun,a.kd_skpd,a.kondisi,a.keterangan
FROM trkib_f a INNER JOIN mbarang b ON a.kd_brg=b.kd_brg 
$where1";}
		
       // $sql = "SELECT * from mbarang_umur $where order by kd_barang";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,        
                        'golongan' 		=> $resulte['kd_brg'],
                        'nm_golongan' 	=> $resulte['nm_brg'],
                        'jenis' 		=> $resulte['merek'],    
                        'nilai' 		=> $resulte['nilai'],
                        'tahun' 		=> $resulte['tahun'],      
                        'kd_skpd' 		=> $resulte['kd_skpd']                                                                                             
                        );
                        $ii++;
        }
           
        echo json_encode($result);
       }
    	   
	}


 function load_masa() {
    if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nama) like upper('%$kriteria%') or kd_barang like'%$kriteria%')";            
        }
        
        $sql = "SELECT * from mbarang_umur $where order by kd_barang";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'golongan' => $resulte['kd_barang'],
                        'nm_golongan' => $resulte['nama'],
                        'jenis' => $resulte['umur']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
       }
    	   
	}


 function load_golongan() {
    if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where nm_golongan like '%$kriteria%' or golongan like'%$kriteria%'";            
        }
        
        //$sql = "SELECT *,IF(jenis=1,'Aset','Non Aset') AS ketjenis from mgolongan $where order by golongan";
		$sql = "SELECT *,CASE WHEN jenis=1 THEN 'Aset' ELSE 'Non Aset' END AS ketjenis from mgolongan $where order by golongan";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'golongan' => $resulte['golongan'],
                        'nm_golongan' => $resulte['nm_golongan'],
                        'jenis' => $resulte['jenis'],
                        'ketjenis' => $resulte['ketjenis']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
       }
    	   
	}

 function load_golongan_ada() {
    if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where nm_golongan like '%$kriteria%' or golongan like'%$kriteria%'";            
        }
        
        //$sql = "SELECT *,IF(jenis=1,'Aset','Non Aset') AS ketjenis from mgolongan $where order by golongan";
		$sql = "SELECT *,CASE WHEN jenis=1 THEN 'Aset' ELSE 'Non Aset' END AS ketjenis from mgolongan $where order by golongan";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'golongan' => $resulte['golongan'],
                        'nm_golongan' => $resulte['nm_golongan'],
                        'jenis' => $resulte['jenis'],
                        'ketjenis' => $resulte['ketjenis']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
       }
    	   
	}

    
	/*
    function load_bidang_skpd() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria <> ''){                               
            $where="where nm_bidskpd like'%$kriteria%'";            
        }
        $rs = $this->db->query("select count(*) as tot FROM mbidskpd $where order by kd_bidskpd");
        $trh = $rs->row();
        $sql = "SELECT * FROM mbidskpd $where limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_bidskpd' => $resulte['kd_bidskpd'],
                        'nm_bidskpd' => $resulte['nm_bidskpd'],
                        'kd_skpd' => $resulte['kd_skpd']                                                                                      
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
    */
	
	 function load_bidang_skpd() {
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mbidskpd where upper(kd_bidskpd) like upper('%$lccr%') or upper(nm_bidskpd) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%')";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bidskpd' => $resulte['kd_bidskpd'],  
                        'nm_bidskpd' => $resulte['nm_bidskpd'],  
                        'kd_skpd' 	 => $resulte['kd_skpd']                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
/*	
    function load_unit_bidang() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        //$coba = array();
	    $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where nm_uskpd like'%$kriteria%' or kd_uskpd like'%$kriteria%' or kd_skpd like '%$kriteria%'";            
        }
        $rs = $this->db->query("select count(*) as tot FROM unit_skpd $where order by kd_uskpd");
        $trh = $rs->row();
        $sql = "SELECT * FROM unit_skpd $where order by kd_uskpd limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_uskpd'],
                        'nm_uskpd' => $resulte['nm_uskpd'],
                        'kd_bidskpd' => $resulte['kd_bidskpd'],
                        'alamat' => $resulte['alamat'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd']                                                                                      
                        );
                        $ii++;
        }
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
*/  

  
      function load_unit_bidang() {
        $lccr = $this->input->post('q');
        $sql = "SELECT * from unit_skpd where upper(kd_uskpd) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') or upper(kd_bidskpd) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_uskpd'],  
                        'nm_uskpd' => $resulte['nm_uskpd'],  
						'kd_bidskpd' => $resulte['kd_bidskpd'],  
						'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
  
  
/*   
    function load_unit_kerja() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria <> ''){                               
            $where="where nm_uker like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM unit_kerja $where order by kd_uker");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM unit_kerja $where limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],
                        'nm_uker' => $resulte['nm_uker'],
                        'kd_uskpd' => $resulte['kd_uskpd'],
                        'alamat' => $resulte['alamat'],
                        'kd_skpd' => $resulte['kd_skpd']                                                                                      
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
  */

function load_unit_kerja() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_uker like'%$kriteria%' or kd_uker like'%$kriteria%' or kd_uskpd like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM unit_kerja $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM unit_kerja $where order by kd_uker";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],
                        'nm_uker' => $resulte['nm_uker'],
						'kd_uskpd' => $resulte['kd_uskpd'],
                        'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}

		    function load_bidang_mutasi_a() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='01' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  

	
	    function load_bidang_mutasi_b() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='02' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  
    
	    function load_bidang_mutasi_c() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='03' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}

	    function load_bidang_mutasi_d() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='04' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  
    
	    function load_bidang_mutasi_e() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='05' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  

		    function load_bidang_mutasi_f() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and and left (a.bidang,2)='06' order by a.bidang";
        
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	} 
	
    function load_bidang_ada() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and order by a.bidang";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  
    
	function ambil_nomor_spm() {
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
		$kd_kegiatan  = $this->input->post('kd_kegiatan'); 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and kd_skpd ='$skpd' ";
        }
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(no_spm) like upper('%$lccr%') ";
        }
		
		$sql = "select no_spm,tgl_spm,nilai,kd_skpd,kd_kegiatan from trhspm WHERE kd_kegiatan ='$kd_kegiatan' $where1 $where2";
			  
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' 			=> $ii,
						'no_spm' 	=> $resulte['no_spm'],
						'tgl_spm' 	=> $resulte['tgl_spm'],
						'nilai' 	=> $resulte['nilai'],
						'kd_skpd' 	=> $resulte['kd_skpd']
						);
                        $ii++;
        }
        echo json_encode($result);
	}

	
    function load_bidang() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
			$and="and a.nm_bidang like'%$kriteria%' or a.bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a 
		where a.bidang not in (SELECT TOP $offset a.bidang FROM mbidang a $where order by a.bidang)
		$and order by a.bidang";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],
                        'nm_bidang' => $resulte['nm_bidang'],
                        'golongan' => $resulte['golongan'],
                        'nmgol' => $resulte['nmgol']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}  
    

	function load_comp() {        
        //$lccr = $this->input->post('q');    
        $sql = "SELECT kd_comp, nm_comp, bentuk FROM mcompany order by nm_comp,kd_comp ";
        
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_comp' => $resulte['kd_comp'],  
                        'nm_comp' => $resulte['nm_comp'],  
						'betuk' => $resulte['bentuk']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
	}      
	  
    function load_subkelompok() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
        if ($kriteria <> ''){                               
            $where="where a.nm_kelompok like'%$kriteria%' or a.kd_kelompok like'%$kriteria%'";            
			$and="and a.nm_kelompok like'%$kriteria%' or a.kd_kelompok like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mkelompok1 a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.kelompok+'-'+(SELECT nm_kelompok FROM mkelompok WHERE kelompok = a.kelompok)) AS nmkel FROM mkelompok1 a 
		where a.kd_kelompok not in (SELECT TOP $offset a.kd_kelompok FROM mkelompok1 a $where order by a.kd_kelompok)
		$and order by a.kd_kelompok";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_kelompok' => $resulte['kd_kelompok'],
                        'nm_kelompok' => $resulte['nm_kelompok'],
                        'kelompok' => $resulte['kelompok'],
                        'nmkel' => $resulte['nmkel']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	} 
    
    function load_barang() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';

        if ($kriteria <> ''){                               
            $where="where a.nm_brg like'%$kriteria%' or a.kd_brg like'%$kriteria%'";            
			$and="and a.nm_brg like'%$kriteria%' or a.kd_brg like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbarang a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*, (a.kd_kelompok+'-'+(SELECT nm_kelompok FROM mkelompok1 WHERE kd_kelompok = a.kd_kelompok)) AS nmkel FROM mbarang a 
		where kd_brg not in (SELECT TOP $offset kd_brg FROM mbarang a $where order by kd_brg)
		$and order by kd_brg";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_kelompok' => $resulte['kd_kelompok'],
                        'nmkel' => $resulte['nmkel']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
    
    function load_wilayah() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_wilayah like'%$kriteria%' or kd_wilayah like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mwilayah $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mwilayah $where order by kd_wilayah";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_wilayah' => $resulte['kd_wilayah'],
                        'nm_wilayah' => $resulte['nm_wilayah'],
                        'kd_provinsi' => $resulte['kd_provinsi'],
                        'nm_provinsi' => $resulte['nm_provinsi']                                                                               
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
	
	    function load_lokasi() {
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mlokasi where upper(kd_lokasi) like upper('%$lccr%') or upper(nm_lokasi) like upper('%$lccr%') or upper(kd_uker) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'kd_lokasi' => $resulte['kd_lokasi'],
                        'nm_lokasi' => $resulte['nm_lokasi'],
                        'kd_uker' => $resulte['kd_uker'],
						'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    

	
  /* 
  
       function load_lokasi() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $skpd 		= $this->session->userdata('unit_skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_skpd like '%' ";
        }else{
            $where1 = "where kd_lokasi ='$skpd' ";
        }        
          
        $kriteria = '';
        $kriteria = $this->input->post('cari');
          $where2='';
        if($kriteria <> ''){
            $where2 ="and UPPER(nm_lokasi) LIKE UPPER('%$kriteria%')";
        }
		
        $rs = $this->db->query("select count(*) as tot FROM mlokasi a $where1 order by kd_lokasi");
        $trh = $rs->row();
        
        $sql = "SELECT * FROM mlokasi $where1 $where2 order by kd_lokasi,kd_uker,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql); 
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_lokasi' => $resulte['kd_lokasi'],
                        'nm_lokasi' => $resulte['nm_lokasi'],
                        'kd_uker' => $resulte['kd_uker'],
						'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();  
        } 
	}
*/


    function load_lokasi_dh() {
       if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        
        $skpd       = $this->session->userdata('unit_skpd');
        $oto        = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_lokasi ='$skpd' ";
        }        
          
        $kriteria = '';
        $kriteria = $this->input->post('cari');
          $where2='';
        if($kriteria <> ''){
            $where2 ="and UPPER(a.nm_lokasi) LIKE UPPER('%$kriteria%')";
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mlokasi a $where1 order by kd_lokasi");
        $trh = $rs->row();
        
        $sql = "SELECT a.kd_lokasi,a.nm_lokasi,a.kd_uker,b.nm_uker,a.kd_skpd,c.nm_skpd FROM mlokasi a LEFT JOIN unit_kerja b ON a.kd_uker=b.kd_uker LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.kd_lokasi,a.kd_uker,a.kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql); 
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_lokasi' => $resulte['kd_lokasi'],
                        'nm_lokasi' => $resulte['nm_lokasi'],
                        'kd_uker' => $resulte['kd_uker'],
                        'nm_uker' => $resulte['nm_uker'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
        $query1->free_result();  
        } 
    }
    
	    /*function load_hapus() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria <> ''){                               
            $where="where nm_lokasi like'%$kriteria%' or kd_lokasi like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mlokasi a $where order by kd_lokasi");
        $trh = $rs->row();
        
        $sql = "SELECT * FROM mlokasi $where order by kd_lokasi,kd_uker,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql); 
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_lokasi' => $resulte['kd_lokasi'],
                        'nm_lokasi' => $resulte['nm_lokasi'],
                        'kd_uker' => $resulte['kd_uker'],
						'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();  
        } 
	}*/
	
    function load_ruang() {
      if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria <> ''){                               
            $where="where nm_ruang like'%$kriteria%' or kd_ruang like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mruang  $where order by kd_ruang");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mruang $where order by kd_ruang limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_ruang' => $resulte['kd_ruang'],
                        'nm_ruang' => $resulte['nm_ruang'],
                        'kd_uker' => $resulte['kd_uker']                                                                               
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
    
	
	function load_satuan() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_satuan like'%$kriteria%' or kd_satuan like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM msatuan $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM msatuan $where order by kd_satuan";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_satuan' => $resulte['kd_satuan'],
                        'nm_satuan' => $resulte['nm_satuan'],
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
/*	
    function load_pangkat() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria <> ''){                               
            $where="where nm_pangkat like'%$kriteria%' or kd_pangkat like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mpangkat  $where order by kd_pangkat");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mpangkat $where order by kd_pangkat limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_pangkat' => $resulte['kd_pangkat'],
                        'nm_pangkat' => $resulte['nm_pangkat']                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }
}
		*/	


    function load_pangkat() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_pangkat like'%$kriteria%' or kd_pangkat like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mpangkat $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mpangkat $where order by kd_pangkat	";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_pangkat' => $resulte['kd_pangkat'],
                        'nm_pangkat' => $resulte['nm_pangkat'],
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}

///////////////////////////////////////////////////


    function load_kib_grid_b() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where id_brg like'%$kriteria%' or no_reg like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM trkib_b $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM trkib_b $where order by no_reg and kd_brg";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'no_reg' => $resulte['no_reg'],
                        'id_barang' => $resulte['id_barang'],
						'nm_brg' => $resulte['nm_brg'],
						'tahun' => $resulte['tahun'],
						'nilai' => $resulte['nilai'],
						
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
	function load_kib_grid_c() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where id_brg like'%$kriteria%' or no_reg like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM trkib_c $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM trkib_c $where order by no_reg and kd_brg";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'no_reg' => $resulte['no_reg'],
                        'id_barang' => $resulte['id_barang'],
						'nm_brg' => $resulte['nm_brg'],
						'tahun' => $resulte['tahun'],
						'nilai' => $resulte['nilai'],
						
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
	function load_kib_grid_d() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where id_brg like'%$kriteria%' or no_reg like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM trkib_d $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM trkib_d $where order by no_reg and kd_brg";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'no_reg' => $resulte['no_reg'],
                        'id_barang' => $resulte['id_barang'],
						'nm_brg' => $resulte['nm_brg'],
						'tahun' => $resulte['tahun'],
						'nilai' => $resulte['nilai'],
						
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}

//////////////////////////////////////////////////	

    function load_warna() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_warna like'%$kriteria%' or kd_warna like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mwarna $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mwarna $where order by kd_warna";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_warna' => $resulte['kd_warna'],
                        'nm_warna' => $resulte['nm_warna'],
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
	    function load_user() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nmuser like'%$kriteria%' or ket like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM muser $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM muser $where order by nmuser";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],
                        'nmuser' => $resulte['nmuser'],
                        'ket' => $resulte['ket'],
                        'oto' => $resulte['oto'],				
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
    function ceknip(){
        $cnip=$this->input->post('nip');
        $sub=$this->input->post('kd_lokasi');
        $query=$this->db->query("SELECT COUNT(*) AS jumlah FROM ttd WHERE nip='$cnip' AND kd_lokasi='$sub'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('jumlah' =>$resulte['jumlah']);
        }
        echo json_encode($result);
    }
    
    function load_ttd() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        $skpd	 	= $this->input->post('kdlokasi');
        $oto  		= $this->session->userdata('otori');
        $coba = array();
        /*$where1 = '';       
        if($oto == '01'){ 
            $where1 = "where unit like '%' ";
        }else{
            $where1 = "where unit ='$skpd' ";
        } */       
          
        $kriteria = '';
        $kriteria = $this->input->post('cari');
          $where2='';
        if($kriteria <> ''){
            $where2 ="and UPPER(nama) LIKE UPPER('%$kriteria%')";
        }
		
        $rs = $this->db->query("select count(*) as tot FROM ttd where kd_lokasi='$skpd' $where2 ORDER BY nm_skpd");
        $trh = $rs->row();
        $result["total"] = $trh->tot;
        
        $sql = "SELECT *,IF((COUNT(skpd)+COUNT(nip)+COUNT(nama))='3','OK','NO') AS tanda FROM ttd where kd_lokasi='$skpd' $where2 GROUP BY unit,nip,nm_skpd,kd_pangkat,ckey,nama ORDER BY nm_skpd limit $offset,$rows";//
        $query1 = $this->db->query($sql);  
        $result = array();
               
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' 		=> $ii,        
                        'nip' 		=> $resulte['nip'],
                        'nama' 		=> $resulte['nama'],
                        'jabatan'	=> $resulte['jabatan'],
                        'kd_skpd' 	=> $resulte['skpd'],
                        'unit'    	=> $resulte['unit'],
                        'nm_skpd' 	=> $resulte['nm_skpd'],
                        'ckey' 		=> $resulte['ckey'],
                        'kd_pangkat' => $resulte['kd_pangkat'],
                        'status' 	=> $resulte['tanda']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"]  = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
    
    
    function load_milik() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_milik like'%$kriteria%' or kd_milik like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mmilik $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mmilik $where order by kd_milik";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_milik' => $resulte['kd_milik'],
                        'nm_milik' => $resulte['nm_milik'],
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	} 
    
    function load_kelompok() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
		$and ='';
		
        if ($kriteria <> ''){                               
            $where="where a.nm_kelompok like'%$kriteria%' or a.kelompok like'%$kriteria%'";            
			$and="and a.nm_kelompok like'%$kriteria%' or a.kelompok like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mkelompok a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows a.*, (a.bidang+'-'+(SELECT nm_bidang FROM mbidang WHERE bidang = a.bidang)) AS nmbid FROM mkelompok a 
		where a.kelompok not in (SELECT TOP $offset a.kelompok FROM mkelompok a $where order by a.kelompok)
		$and order by a.kelompok";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kelompok' => $resulte['kelompok'],
                        'nm_kelompok' => $resulte['nm_kelompok'],
                        'bidang' => $resulte['bidang'],
                        'nmbid' => $resulte['nmbid']                                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();
        }   
	}  
    
    function ambil_kelompok_dh(){

        $lccr=$this->input->post('q');

        if($lccr!=''){
            $where="and(upper(kelompok) like upper('%$lccr%') or upper(nm_kelompok) like upper('%$lccr%'))  ";
        }else{
            $where="";
        }

        $sql="SELECT * FROM mkelompok WHERE LEFT(kelompok,2) IN ('02','03','04') AND kelompok NOT IN (SELECT kd_barang FROM mbarang_umur) $where order by kelompok";
        $query1   = $this->db->query($sql);  
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                'id'      => $ii,        
                'kelompok' => $resulte['kelompok'],  
                'nm_kelompok' => $resulte['nm_kelompok']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    }



    function update_master(){
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        }
    }
	
    function simpan_master(){
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        
        $sql = "delete from $tabel where $cid='$lcid'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
        }}
    }

    function simpan_mbidang(){
        $tabel= $this->input->post('tabel');
        $lckolom= $this->input->post('kolom');
        $lcnilai= $this->input->post('nilai');
        $cid= $this->input->post('cid');
        $lcid= $this->input->post('lcid');
        $skpd= $this->input->post('skpd');

        $sql = "delete from $tabel where $cid='$lcid' and kd_skpd='$skpd'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql="insert into $tabel $lckolom values $lcnilai";
            $asg=$this->db->query($sql);
        }
    }
    function del_bid(){
        $tabel= $this->input->post('tabel');
        $cnid= $this->input->post('cnid');
        $cid= $this->input->post('cid');
        $cid2= $this->input->post('cid2');
        $skpd= $this->input->post('skpd');

         $csql = "delete from $tabel where $cid = '$cnid' and $cid2='$skpd'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }

    function simpan_unitbidang(){
        $tabel= $this->input->post('tabel');
        $lckolom= $this->input->post('kolom');
        $lcnilai= $this->input->post('nilai');
        $cid= $this->input->post('cid');
        $cid2= $this->input->post('cid2');
        $lcid= $this->input->post('lcid');
        $skpd= $this->input->post('skpd');

        $sql = "delete from $tabel where $cid='$lcid' and $cid2='$skpd'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql="insert into $tabel $lckolom values $lcnilai";
            $asg=$this->db->query($sql);
        }
    }

    function del_ubid(){
        $tabel= $this->input->post('tabel');
        $cnid= $this->input->post('cnid');
        $cid= $this->input->post('cid');
        $cid2= $this->input->post('cid2');
        $skpd= $this->input->post('skpd');

         $csql = "delete from $tabel where $cid = '$cnid' and $cid2='$skpd'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }

    function del_lokasi(){
        $tabel=$this->input->post('tabel');
        $cnid=$this->input->post('cnid');
        $cid=$this->input->post('cid');
        $cnid2=$this->input->post('cnid2');
        $cid2=$this->input->post('cid2');
        $cnid3=$this->input->post('cnid3');
        $cid3=$this->input->post('cid3');

        $csql = "delete from $tabel where $cid = '$cnid' and $cid2='$cnid2' and $cid3='$cnid3'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }

    function simpan_lokasi(){
        $tabel= $this->input->post('tabel');
        $lckolom= $this->input->post('kolom');
        $lcnilai= $this->input->post('nilai');
        $cid= $this->input->post('cid');
        $cid2= $this->input->post('cid2');
        $lcid= $this->input->post('lcid');
        $skpd= $this->input->post('skpd');
        $cid3=$this->input->post('cid3');
        $kd_uker=$this->input->post('kd_uker');
        $sql = "delete from $tabel where $cid='$lcid' and $cid2='$skpd' AND $cid3='$kd_uker'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql="insert into $tabel $lckolom values $lcnilai";
            $asg=$this->db->query($sql);
        }
    }

    function simpan_usaha(){
        $ckdusaha   =$this->input->post('ckdusaha');
        $cnmusaha   =$this->input->post('cnmusaha');
        $cjnsusaha  =$this->input->post('cjnsusaha');
        $calamat    =$this->input->post('calamat');
        $cpimpin    =$this->input->post('cpimpin');
        $ckdbank    =$this->input->post('ckdbank');
        $ckdrek     =$this->input->post('ckdrek');

        $query = $this->db->query("SELECT IF(MAX(kd_comp)IS NULL,LPAD('1',4,0),LPAD(MAX(kd_comp)+1,4,0))AS nomor FROM mcompany");

          foreach($query->result() as $res){
          $no  =$res->nomor;
           }
           if($ckdusaha<=$no){
                $sql="INSERT INTO mcompany (kd_comp,nm_comp,bentuk,alamat,pimpinan,kd_bank,rekening) VALUES
                                   ('$no','$cnmusaha','$cjnsusaha','$calamat','$cpimpin','$ckdbank','$ckdrek')";
                $query1 = $this->db->query($sql);
           }else{
                $sql="INSERT INTO mcompany (kd_comp,nm_comp,bentuk,alamat,pimpinan,kd_bank,rekening) VALUES
                                   ('$ckdusaha','$cnmusaha','$cjnsusaha','$calamat','$cpimpin','$ckdbank','$ckdrek')";
                $query1 = $this->db->query($sql);
           } 
        
    }
    
    
     function hapus_master(){
        //no:cnomor,skpd:cskpd
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $ctabel = $this->input->post('tabel');
        $cid 	= $this->input->post('cid');
        $cnid 	= $this->input->post('cnid');
        
        $csql = "delete from $ctabel where $cid = '$cnid'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
        }              
    }
	
	function hapus_master_unit(){
        //no:cnomor,skpd:cskpd
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $cunit = $this->input->post('unit');
        $ckd_unit = $this->input->post('kolom');
        
        $csql = "delete from $ctabel where $cid = '$cnid' and $ckd_unit='$cunit'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
        }              
    }
	
	function hapus_master_lengkap(){
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $ctabel 	= $this->input->post('tabel');
        $cid 		= $this->input->post('cid');
        $cnid 		= $this->input->post('cnid');
        $cid2 		= $this->input->post('cid2');
        $cnid2 		= $this->input->post('cnid2');
        $cid3 		= $this->input->post('cid3');
        $cnid3 		= $this->input->post('cnid3');
        
        $csql = "delete from $ctabel where $cid = '$cnid' and $cid2 = '$cnid2' and $cid2 = '$cnid2'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
        }              
    }
    
    function load_kiba() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = "1.20.10.01";//$this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_a a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'],  
                        'nm_bidang' 	=> $resulte['nama'], 
						'kd_brg' 		=> $resulte['kd_brg'], 
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'],
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
	function load_kibb() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,a.kd_satuan from trkib_b a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg'	 	=> $resulte['no_reg'],  
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_bidang' 	=> $resulte['nama'], 
						'kd_brg' 		=> $resulte['kd_brg'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'], 
                        'kondisi' 		=> $resulte['kondisi'],  
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'],
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function load_kibb_dh() {
       $kdbrg = $this->input->post('kdbrg');
       $tahun = $this->input->post('tahun');
       $skpd = $this->input->post('skpd');
        
        $lccr = $this->input->post('q');
        $where ="";
        if($lccr<>''){
        $where="and b.nm_brg like '%$lccr%'";
        }
        $sql = "SELECT a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg AS nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.kd_satuan,a.masa_manfaat,IF(MAX(a.pemeliharaan_ke)IS NULL,LPAD('1',4,0),LPAD(MAX(a.pemeliharaan_ke)+1,4,0))AS pelihara 
                FROM trkib_b a LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg where a.kd_skpd='$skpd' and a.tahun='$tahun' and a.kd_brg='$kdbrg' $where GROUP BY a.no_reg";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'            => $ii,        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'no_reg'        => $resulte['no_reg'],
                        'id_barang'     => $resulte['id_barang'],
                        'nama'          => $resulte['nama'],
                        'kd_rek_pelihara'=> $resulte['kd_rek_pelihara'],
                        'kd_brg'        => $resulte['kd_brg'],
                        'kondisi'       => $resulte['kondisi'],
                        'tahun'         => $resulte['tahun'],
                        'keterangan'    => $resulte['keterangan'],
                        'nilai'         => $resulte['nilai'],
                        'nilai2'        => number_format($resulte['nilai'],2,',','.'),
                        'kd_satuan'     => $resulte['kd_satuan'],
                        'masa_manfaat'  => $resulte['masa_manfaat'],
                        'pelihara'      => $resulte['pelihara']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
   
           
    }

     function load_kibc_dh() {
       $kdbrg = $this->input->post('kdbrg');
       $tahun = $this->input->post('tahun');
       $skpd = $this->input->post('skpd');
        
        $lccr = $this->input->post('q');
        $where ="";
        if($lccr<>''){
        $where="and b.nm_brg like '%$lccr%'";
        }
        /*$sql = "SELECT a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg AS nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.masa_manfaat,IF(MAX(a.pemeliharaan_ke)IS NULL,LPAD('1',4,0),LPAD(MAX(a.pemeliharaan_ke)+1,4,0))AS pelihara 
                FROM trkib_c a LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg where a.kd_skpd='$skpd' and a.tahun='$tahun' and a.kd_brg='$kdbrg' $where 
				GROUP BY a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg AS nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.masa_manfaat";*/
		$sql = "SELECT a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg AS nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.masa_manfaat,a.pemeliharaan_ke 
                FROM trkib_c a LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg where a.kd_skpd='$skpd' and a.tahun='$tahun' and a.kd_brg='$kdbrg' $where 
				GROUP BY a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.masa_manfaat,a.pemeliharaan_ke";			
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
		   $nor = $resulte['pemeliharaan_ke'];
		   if($nor==null){
				$nor = '1';
				$no_urut = "0001";
			} else {
				$nor = $nor + 1;
				if(strlen($nor)==1) {
					$no_urut = '000'.$nor;	
				} else if(strlen($nor)==2) {
					$no_urut = '00'.$nor;
				} else if(strlen($nor)==3) {
					$no_urut = '0'.$nor;	
				} else if(strlen($nor)==4) {
					$no_urut = ''.$nor;	
				}		
			}
		   
            $result[] = array(
                        'id'            => $ii,        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'no_reg'        => $resulte['no_reg'],
                        'id_barang'     => $resulte['id_barang'],
                        'nama'          => $resulte['nama'],
                        'kd_rek_pelihara'=> $resulte['kd_rek_pelihara'],
                        'kd_brg'        => $resulte['kd_brg'],
                        'kondisi'       => $resulte['kondisi'],
                        'tahun'         => $resulte['tahun'],
                        'keterangan'    => $resulte['keterangan'],
                        'nilai'         => $resulte['nilai'],
                        'nilai2'        => number_format($resulte['nilai'],2,',','.'),
                        'masa_manfaat'  => $resulte['masa_manfaat'],
                        'pelihara'      => $no_urut
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
   
           
    }

    function load_kibc() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = "1.20.10.01";//$this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_c a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg'		=> $resulte['no_reg'], 
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_bidang' 	=> $resulte['nama'],  
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'], 
                        'kondisi' 		=> $resulte['kondisi'],  
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'],
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function load_kibd_dh() {
       $kdbrg = $this->input->post('kdbrg');
       $tahun = $this->input->post('tahun');
       $skpd = $this->input->post('skpd');
        
        $lccr = $this->input->post('q');
        $where ="";
        if($lccr<>''){
        $where="and b.nm_brg like '%$lccr%'";
        }
        $sql = "SELECT a.no_dokumen,a.no_reg,a.id_barang,b.nm_brg AS nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,
                a.masa_manfaat,IF(MAX(a.pemeliharaan_ke)IS NULL,LPAD('1',4,0),LPAD(MAX(a.pemeliharaan_ke)+1,4,0))AS pelihara 
                FROM trkib_d a LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg where a.kd_skpd='$skpd' and a.tahun='$tahun' and a.kd_brg='$kdbrg' $where GROUP BY a.no_reg";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'            => $ii,        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'no_reg'        => $resulte['no_reg'],
                        'id_barang'     => $resulte['id_barang'],
                        'nama'          => $resulte['nama'],
                        'kd_rek_pelihara'=> $resulte['kd_rek_pelihara'],
                        'kd_brg'        => $resulte['kd_brg'],
                        'kondisi'       => $resulte['kondisi'],
                        'tahun'         => $resulte['tahun'],
                        'keterangan'    => $resulte['keterangan'],
                        'nilai'         => $resulte['nilai'],
                        'nilai2'        => number_format($resulte['nilai'],2,',','.'),
                        'masa_manfaat'  => $resulte['masa_manfaat'],
                        'pelihara'      => $resulte['pelihara']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
   
           
    }
	
    function load_kibd() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$kd_skpd = $this->session->userdata('skpd');
        $kd_skpd = $this->input->post('skpd');
        $kd_unit = $this->input->post('unit');

        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_d a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_skpd' and a.kd_unit='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg' 		=> $resulte['no_reg'],  
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_bidang' 	=> $resulte['nama'],
						'kd_brg' 		=> $resulte['kd_brg'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'], 
                        'kondisi' 		=> $resulte['kondisi'],  
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'], 
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
	function load_kibe() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,a.kd_satuan from trkib_e a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg' 		=> $resulte['no_reg'],  
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_bidang' 	=> $resulte['nama'], 
						'kd_brg' 		=> $resulte['kd_brg'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'], 
                        'kondisi' 		=> $resulte['kondisi'],  
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'],
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
    function load_kibf() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_f a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd='$kd_unit' $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],  
                        'id_barang' 	=> $resulte['id_barang'], 
                        'nm_bidang' 	=> $resulte['nama'], 
						'kd_brg' 		=> $resulte['kd_brg'],
                        'no_reg2' 		=> $resulte['kd_rek_pelihara'], 
                        'kondisi' 		=> $resulte['kondisi'],  
                        'tahun' 		=> $resulte['tahun'], 
						'keterangan' 	=> $resulte['keterangan'],
                        'nilai' 		=> $resulte['nilai'],
                        'satuan' 		=> $resulte['kd_satuan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
//-- formulir pengadaaan --//
   function ambil_barang() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $oto	 = $this->session->userdata('oto');
        $kd_unit = $this->session->userdata('unit_skpd');
		$where="";
		if($oto=='01'){
		$where="";
		}else{
		$where="and kd_unit='$kd_unit'";
		}
        $lccr = $this->input->post('gol');
        $sql = "SELECT no_dokumen,kd_brg,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket FROM trd_planbrg where left(kd_brg,2)='$lccr' $where order by kd_brg";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,        
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'kd_uskpd' 		=> $resulte['kd_uskpd'], 
                        'nm_brg' 		=> $resulte['nm_brg'], 
                        'merek' 		=> $resulte['merek'], 
                        'jumlah' 		=> $resulte['jumlah'], 
                        'harga' 		=> $resulte['harga'], 
                        'total' 		=> $resulte['total'],   
                        'ket' 	 		=> $resulte['ket']
                       
                        );
                        $ii++;
        }   
        echo json_encode($result);
        } 
	}
//----------PENERIMAAN 
		 function ambil_penerimaan_barang() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $skpd = $this->input->post('skpd');
        $lccr = $this->input->post('gol');
        $sql = "SELECT kd_brg,nm_brg,kd_kegiatan,jumlah,harga,total,keterangan FROM trd_isianbrg where kd_uskpd='$skpd' and left(kd_brg,2)='$lccr' order by kd_brg";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,        
                        //'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],  
                        'nm_brg' 		=> $resulte['nm_brg'], 
                        'kd_kegiatan' 	=> $resulte['kd_kegiatan'], 
                        'jumlah' 		=> $resulte['jumlah'], 
                        'harga' 		=> $resulte['harga'], 
                        'total' 		=> $resulte['total'],   
                        'keterangan' 	=> $resulte['keterangan']
                       
                        );
                        $ii++;
        }   
        echo json_encode($result);
        } 
	}
//------------------------//	
//----------KELUAR BARANG--------// 
	 function ambil_keluar_barang() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $unit = $this->input->post('unit');
        $dok = $this->input->post('dok');
        $sql = "SELECT b.* FROM trh_terimabrg a LEFT JOIN trd_terimabrg b ON a.`kd_unit`=b.`kd_unit` AND a.`kd_uskpd`=a.`kd_uskpd`
				WHERE a.`no_dokumen`='$dok' AND b.`kd_unit`='$unit'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 		=> $ii,    
                        'no_bap' 	=> $resulte['no_bap'],
						'no_dokumen'=> $resulte['no_dokumen'],
						'kd_brg' 	=> $resulte['kd_brg'],
						'kd_unit' 	=> $resulte['kd_unit'],
						'kd_uskpd' 	=> $resulte['kd_uskpd'],
						'nm_brg' 	=> $resulte['nm_brg'],
						'merek' 	=> $resulte['merek'],
						'tahun' 	=> $resulte['tahun'],
						'jumlah' 	=> $resulte['jumlah'],
						'harga' 	=> $resulte['harga'],
						'total' 	=> $resulte['total'],
						'cad' 		=> $resulte['cad'],
						'ket' 		=> $resulte['ket']
                        );
                        $ii++;
        }   
        echo json_encode($result);
        } 
	}
	
//------------------------//	
	
    function ambil_gol() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
		
		$where = "";
		if($lccr==''){
			$where = "where upper(golongan) like upper('%$lccr%') or upper(nm_golongan) like upper('%$lccr%')"; 
		}
		
        $sql = "SELECT golongan, nm_golongan FROM mgolongan order by golongan ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'golongan' => $resulte['golongan'],  
                        'nm_golongan' => $resulte['nm_golongan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function ambil_gol_fil() {
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr   = $this->input->post('q');
        $sql    = "SELECT golongan, nm_golongan FROM mgolongan where  golongan NOT IN ('01','06','07') order by golongan ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'golongan' => $resulte['golongan'],  
                        'nm_golongan' => $resulte['nm_golongan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
           
    }
  
    function ambil_bidang() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('gol');
        $sql = "SELECT bidang,nm_bidang FROM mbidang where golongan='$lccr' order by bidang";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],  
                        'nm_bidang' => $resulte['nm_bidang']                   
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function ambil_golongan_dh() {
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr = $this->input->post('gol');
        $sql = "SELECT golongan,nm_golongan FROM mgolongan where golongan in ('02','03','04') order by golongan";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'gol' => $resulte['golongan'],  
                        'nm_golongan' => $resulte['nm_golongan']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
           
    }
    
    function ambil_kelompok() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('bidang');
        $sql = "SELECT kelompok,nm_kelompok FROM mkelompok where bidang='$lccr' order by kelompok";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kelompok' => $resulte['kelompok'],  
                        'nm_kelompok' => $resulte['nm_kelompok']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
     function ambil_kelompok1() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('kelompok');
        $sql = "SELECT kd_kelompok,nm_kelompok FROM mkelompok1 WHERE kelompok='$lccr' order by kd_kelompok";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kelompok' => $resulte['kd_kelompok'],  
                        'nm_kelompok' => $resulte['nm_kelompok']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function load_brg() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('subkel');
        $sql = "SELECT * FROM mbarang WHERE kd_kelompok='$lccr' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],  
                        'nm_brg' => $resulte['nm_brg']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
        
    
	
	  function mmetode() {
       // $lccr = $this->input->post('q');
        $sql = "SELECT * FROM mmetode order by kode ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kode' => $resulte['kode'],        
                        'metode' => $resulte['metode']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_kel() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kelompok, nm_kelompok FROM mkelompok where upper(kelompok) like upper('%$lccr%') or upper(nm_kelompok) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kelompok' => $resulte['kelompok'],  
                        'nm_kelompok' => $resulte['nm_kelompok'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_pangkat() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_pangkat, nm_pangkat FROM mpangkat where upper(kd_pangkat) like upper('%$lccr%') or upper(nm_pangkat) like upper('%$lccr%') order by kd_pangkat";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_pangkat' => $resulte['kd_pangkat'],  
                        'nm_pangkat' => $resulte['nm_pangkat'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_key() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT nm_kunci,singkatan FROM kunci_ttd where upper(nm_kunci) like upper('%$lccr%')";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nm_kunci'  => $resulte['nm_kunci'],
                        'singkatan' => $resulte['singkatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
		
	/*
	kahfi
	function ambil_key() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';		

        if ($kriteria <> ''){                               
            $where="where nm_kunci like'%$kriteria%' or singkatan like'%$kriteria%'";            			
        }
        
        $rs = $this->db->query("select count(*) as tot FROM kunci_ttd $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM kunci_ttd $where order by nm_kunci";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'nm_kunci' => $resulte['nm_kunci'],
                        'singkatan' => $resulte['singkatan']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
*/

	function no_urutx() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $sql = "SELECT max(no_urut)+1 as no_urut FROM trkib_a";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_urut' => $resulte['no_urut']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
	function master_max() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $table = $this->input->post('table');
        $kolom = $this->input->post('kolom');
        $sql = "SELECT IFNULL(MAX($kolom),0)+1 AS kode FROM $table";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        //'id' => $ii,        
                        'no_urut' => $resulte['kode']
                        );
                        //$ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function max_satuan() {
        
        $table = $this->input->post('table');
        $kolom = $this->input->post('kolom');
        $sql = "SELECT IF(MAX($kolom)IS NULL,LPAD('1',2,0),LPAD(MAX($kolom)+1,2,0))AS kode FROM $table";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        //'id' => $ii,        
                        'no_urut' => $resulte['kode']
                        );
                        //$ii++;
        }
           
        echo json_encode($result);
        
           
    }

    function ambil_bulan(){
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where="where (upper(n_bulan) like upper('%$lccr%') or upper(nama_bulan) like upper('%$lccr%'))";
        }else{
            $where="";
        }
        $sql="SELECT * FROM bulan $where";
        $query1 = $this->db->query($sql); 
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'n_bulan'   => $resulte['n_bulan'], 
                        'bulan' => $resulte['nama_bulan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    }

    function ambil_ubidskpdh() {
              
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{

        $unit_skpd       = $this->session->userdata('unit_skpd');
        $oto        = $this->session->userdata('otori');
        $skpd       = $this->input->post('skpd');
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_skpd='$skpd' ";
        }else{
            $where1 = "where kd_skpd ='$skpd' and kd_lokasi='$unit_skpd' ";
        } 
         
        
        
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_skpd) like upper('%$lccr%') or upper(nm_lokasi) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT kd_skpd,kd_lokasi, nm_lokasi FROM mlokasi $where1 $where2 ORDER BY kd_skpd,kd_lokasi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd'   => $resulte['kd_skpd'], 
                        'kd_uskpd' => $resulte['kd_lokasi'],  
                        'nm_uskpd'   => $resulte['nm_lokasi']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
           
    }
	
	function ambil_ubidskpd2() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd 		= $this->session->userdata('skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_skpd like '%' ";
        }else{
            $where1 = "where kd_skpd ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_skpd) like upper('%$lccr%') or upper(nm_lokasi) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT kd_skpd,kd_lokasi, nm_lokasi FROM mlokasi $where1 $where2 group by kd_skpd ORDER BY kd_skpd,kd_lokasi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' 	=> $resulte['kd_skpd'], 
                        'kd_lokasi' => $resulte['kd_lokasi'],  
                        'nm_skpd' 	=> $resulte['nm_lokasi']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
	function ambil_transaksi() { 
    $dbsimakda=$this->load->database('simakda', TRUE);
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        
        $skpd       = $this->session->userdata('skpd');
        $oto        = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = " a.kd_skpd like '%' and ";
        }else{
            $where1 = " a.kd_skpd ='$skpd' and ";
        }       
        //$skpd= $this->input->post('kdskpd');  
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 =" and UPPER(no_kontrak) LIKE UPPER('%$lccr%') ";
        }
        
        $sql    = "SELECT a.no_bukti,a.kd_skpd,a.total FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti WHERE LEFT(kd_rek5,3)='522'";
        $query1 = $dbsimakda->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'          => $ii,        
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'no_bukti'  => $resulte['no_bukti'],
                        'nilai'     => $resulte['total'] 
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    }

    
      function ambil_msskpd2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		
		$skpd 		= $this->session->userdata('unit_skpd');
        $oto  		= $this->session->userdata('otori');
        $lccr = $this->input->post('q');
        $where1 = '';       
        if($oto == '01'){ 
            //$where1 = "where a.kd_skpd like '%' ";
            $where1="where upper(a.kd_skpd) like upper('%$lccr%') or upper(a.nm_skpd) like upper('%$lccr%')";
        }       
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(a.kd_skpd) like upper('%$lccr%') or UPPER(a.nm_skpd) LIKE UPPER('%$lccr%')";
        }
		
        $sql 	= "SELECT a.kd_skpd,a.nm_skpd FROM ms_skpd a $where1 $where2  ORDER BY a.kd_skpd";//GROUP BY a.kd_skpd order by nm_skpd
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 		=> $ii,        
                        'kd_skpd' 	=> $resulte['kd_skpd'],  
                        //'kd_lokasi' => $resulte['kd_lokasi'],  
                        'nm_skpd' 	=> $resulte['nm_skpd'],    
                        //'nm_lokasi' => $resulte['nm_lokasi'] 
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    	   
	}
	
	function ambil_msskpd() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$skpd 		= $this->session->userdata('skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and UPPER(a.nm_skpd) LIKE UPPER('%$lccr%') or UPPER(a.kd_skpd) LIKE UPPER('%$lccr%') ";
        }
		
        $sql 	= "SELECT a.kd_skpd,a.nm_skpd FROM ms_skpd a $where1 $where2 order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],    
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    	   
	}

	
	function ambil_msskpd_mutasi() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$skpd 		= $this->session->userdata('skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and UPPER(a.nm_skpd) LIKE UPPER('%$lccr%') or UPPER(a.kd_skpd) LIKE UPPER('%$lccr%') ";
        }
		
        $sql 	= "SELECT a.kd_skpd,a.nm_skpd FROM ms_skpd a $where1 $where2 order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],    
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    	   
	}
	
    function cek_kd_bidang(){
        
        $kd_bid=$this->input->post('kd_bid');
        $kd_skpd=$this->input->post('kd_skpd');

        $query=$this->db->query("SELECT COUNT(*) AS jumlah FROM mbidskpd WHERE kd_bidskpd='$kd_bid' AND kd_skpd='$kd_skpd'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('jumlah' =>$resulte['jumlah']);
        }
        echo json_encode($result);
    }

    function cek_kd_unit(){
        
        $kd_bid=$this->input->post('kd_bid');
        $kd_skpd=$this->input->post('kd_skpd');
        $kd_unit=$this->input->post('kd_unit');
        $query=$this->db->query("SELECT COUNT(*) AS jumlah FROM unit_skpd WHERE kd_uskpd='$kd_unit' AND kd_skpd='$kd_skpd' AND kd_bidskpd='$kd_bid'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('jumlah' =>$resulte['jumlah']);
        }
        echo json_encode($result);
    }

    function cek_kd_unit_kerja(){
        
        $kd_bid=$this->input->post('bidang');
        $kd_unit=$this->input->post('kdunit');
        $skpd=$this->input->post('kd_skpd');
        $query=$this->db->query("SELECT COUNT(*) AS jumlah FROM unit_kerja WHERE kd_uker='$kd_unit' AND kd_uskpd='$kd_bid' and kd_skpd='$skpd'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('jumlah' =>$resulte['jumlah']);
        }
        echo json_encode($result);
    }

    function cek_mlokasi(){
        
        $skpd=$this->input->post('skpd');
        $kduker=$this->input->post('kduker');
        $kdlokasi=$this->input->post('kdlokasi');
        $query=$this->db->query("SELECT COUNT(*) AS jumlah FROM mlokasi WHERE kd_lokasi='$kdlokasi' AND kd_skpd='$skpd' and kd_uker='$kduker'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('jumlah' =>$resulte['jumlah']);
        }
        echo json_encode($result);
    }

	 
    function ambil_mrekap() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT gol,nama FROM mrekap WHERE UPPER(gol) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%') and gol is not null order by gol";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'gol' => $resulte['gol'],  
                        'nama' => $resulte['nama'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
		 
    function ambil_mrekap_penyusutan() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT gol,nama FROM mrekap 
		WHERE gol is not null 
		and gol<>'01' and gol<>'06' order by gol";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'gol' => $resulte['gol'],  
                        'nama' => $resulte['nama'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
	function ambil_skpdsek() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$skpd	= $this->session->userdata('skpd');
        $lccr 	= $this->input->post('q');
        $sql 	= "SELECT kd_lokasi,nm_lokasi FROM mlokasi WHERE UPPER(kd_lokasi) LIKE UPPER('%$lccr%') OR UPPER(nm_lokasi) LIKE UPPER('%$lccr%') and kd_skpd='$skpd' order by kd_skpd,nm_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_lokasi'],  
                        'nm_skpd' => $resulte['nm_lokasi'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
    function ambil_skpd() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_lokasi,nm_lokasi,kd_skpd FROM mlokasi WHERE UPPER(kd_lokasi) LIKE UPPER('%$lccr%') OR UPPER(nm_lokasi) LIKE UPPER('%$lccr%')";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_lokasi'],  
                        'nm_skpd' => $resulte['nm_lokasi'],  
                        'kd_skpd' 	  => $resulte['kd_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function ambil_skpd_dh() {
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $oto        = $this->session->userdata('otori');
        $skpd       = $this->session->userdata('skpd');
        $where1 = '';
        $where2 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_skpd like '%' ";
        }else{
            $where1 = "where kd_skpd ='$skpd' ";
        } 
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(kd_skpd) LIKE UPPER('%$lccr%') OR UPPER(nm_skpd) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd $where1 $where2  ORDER BY kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
           
    }

    function ambil_skpd2() {
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM unit_skpd WHERE UPPER(kd_uskpd) LIKE UPPER('%$lccr%') OR UPPER(nm_uskpd) LIKE UPPER('%$lccr%')";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_uskpd'],  
                        'nm_uskpd' => $resulte['nm_uskpd'],  
                        'kd_skpd'    => $resulte['kd_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
           
    }
    
    function ambil_bidskpd() {
        /*if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{*/
            $skpd=$this->input->post('skpd');
        $lccr = $this->input->post('q');
        $cari='';
        if($lccr!=''){
            $cari="AND upper(kd_bidskpd) like upper('%$lccr%') or upper(nm_bidskpd) like upper('%$lccr%')";
        }else{
            $cari="";
        }
        $sql = "SELECT kd_bidskpd, nm_bidskpd,kd_skpd FROM mbidskpd where kd_skpd='$skpd' $cari";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bidskpd' => $resulte['kd_bidskpd'],  
                        'nm_bidskpd' => $resulte['nm_bidskpd'],  
                        'kd_skpd'    => $resulte['kd_skpd']
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
	}
	
     function ambil_ubidskpd() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$tabel 	= $this->input->post('tabel');
        $unit_skpd 	= $this->session->userdata('unit_skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_lokasi ='$unit_skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(a.kd_lokasi) like upper('%$lccr%') or upper(a.nm_lokasi) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.kd_lokasi, a.nm_lokasi,a.kd_skpd from mlokasi a $where1 $where2 order by nm_lokasi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_lokasi'],  
                        'nm_uskpd' => $resulte['nm_lokasi'],  
						'kd_skpd'  => $resulte['kd_skpd'], 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
	 function ambil_uskpd() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $skpd = $this->input->post('skpd');
        $uskpd = $this->input->post('kduskpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_lokasi='$uskpd' ";
        }elseif($skpd=='1.02.01.00' || $skpd=='1.01.01.00'){
            $where1 = "where kd_skpd='$skpd' ";
		}
		else{
            $where1 = "where kd_lokasi ='$uskpd' ";
        }
        $sql = "SELECT kd_lokasi, nm_lokasi FROM mlokasi $where1 
		and upper(nm_lokasi) like upper('%$lccr%') order by nm_lokasi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_lokasi'],  
                        'nm_uskpd' => $resulte['nm_lokasi'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}

     function ambil_uskpd_dh() {
       if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr = $this->input->post('q');
        $skpd = $this->input->post('skpd');
        /*$uskpd = $this->input->post('kduskpd');
        $oto  = $this->session->userdata('otori');*/
 
        /*$where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_lokasi='$uskpd' ";
        }elseif($skpd=='1.02.01.00' || $skpd=='1.01.01.00'){
            $where1 = "where kd_skpd='$skpd' ";
        }
        else{
            $where1 = "where kd_lokasi ='$uskpd' ";
        }*/
        $sql = "SELECT kd_uker,nm_uker FROM unit_kerja WHERE kd_skpd='$skpd' and upper(nm_uker) like upper('%$lccr%') order by kd_uker";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_uker'],  
                        'nm_uskpd' => $resulte['nm_uker'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    }

    function ambil_lokasi_dh() {
       /*if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{*/
        $lccr = $this->input->post('q');
        $sub = $this->input->post('sub');

        if($lccr!=''){
            $where="and upper(nm_lokasi) like upper('%$lccr%') or upper(kd_lokasi) like upper('%$lccr%')";
        }else{
            $where="";
        }
        
        $sql = "SELECT * FROM mlokasi WHERE kd_uker='$sub' $where order by kd_uker";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub' => $resulte['kd_lokasi'],  
                        'nm_sub' => $resulte['nm_lokasi'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
    }
	
	function max_gol_hbs() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto  	= $this->session->userdata('otori');
        
        $sql = "SELECT MAX(LEFT(kode,2))+1 AS kode FROM mbarang_hbs";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'max_kode' => $resulte['kode'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}	
	
	function max_bid_hbs() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto  	= $this->session->userdata('otori');
        $kode 	= $this->input->post('h');
        
        $sql = "SELECT IFNULL(MAX(RIGHT(kode,2)),0)+1 AS max_gol FROM mbarang_hbs 
		WHERE LEFT(kode,2)='$kode' AND LENGTH(kode)='4'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'max_kode' => $resulte['max_gol'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
	
	function max_kel_hbs() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto  	= $this->session->userdata('otori');
        $kode 	= $this->input->post('h');
        
        $sql = "SELECT IFNULL(MAX(RIGHT(kode,2)),0)+1 AS max_gol FROM mbarang_hbs 
		WHERE LEFT(kode,4)='$kode' AND LENGTH(kode)='6'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'max_kode' => $resulte['max_gol'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
	
	function max_subkel_hbs() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto  	= $this->session->userdata('otori');
        $kode 	= $this->input->post('h');
        
        $sql = "SELECT IFNULL(MAX(RIGHT(kode,2)),0)+1 AS max_gol FROM mbarang_hbs 
		WHERE LEFT(kode,6)='$kode' AND LENGTH(kode)='8'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'max_kode' => $resulte['max_gol'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
	
	function max_sub2_hbs() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto  	= $this->session->userdata('otori');
        $kode 	= $this->input->post('h');
        
        $sql = "SELECT IFNULL(MAX(RIGHT(kode,4)),0)+1 AS max_gol FROM mbarang_hbs 
				WHERE LEFT(kode,8)='$kode' AND LENGTH(kode)='12'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'kode' => $resulte['max_gol'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
	
	function ambil_maxkode_ruang() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr 	= $this->input->post('q');
        $skpd 	= $this->input->post('kdlokasi');
		$oto  	= $this->session->userdata('otori');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_unit = '$lccr' and ckey='PA' and tingkat='1'";
        }else {
            $csql1 = "where kd_unit = '$lccr' and ckey='PA' and tingkat='1'";
        }
		
        $sql = "SELECT IFNULL(MAX(no_urut),0)+1 AS max_kode FROM mruang where kd_lokasi='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result [] = array(
                        'id' => $ii,        
                        'max_kode' => $resulte['max_kode'],   
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}

	
	
         function ambil_ruang() {
        $lccr 			= $this->input->post('q');
        $unit_skpd 		= $this->input->post('kdlokasi');
		$oto  			= $this->session->userdata('otori');
		//$unit_skpd  	= $this->session->userdata('unit_skpd');
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_lokasi ='$unit_skpd'";
        }else {
            $csql1 = "where kd_lokasi ='$unit_skpd'";
        }
        $sql = "SELECT * FROM mruang $csql1"; // and upper(kd_ruang) like upper('%$lccr%') or upper(nm_ruang) like upper('%$lccr%') 
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_ruang' => $resulte['kd_ruang'],  
                        'nm_ruang' => $resulte['nm_ruang'],
                        'kd_skpd'  => $resulte['kd_skpd'], 
                        'kd_unit'  => $resulte['kd_unit'],
                        'kd_lokasi'=> $resulte['kd_lokasi'],  
                        'keterangan'  => $resulte['keterangan'],  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}



    function ambil_ruangdh() {
        $lccr           = $this->input->post('q');
        $unit_skpd      = $this->input->post('kdlokasi');
        $oto            = $this->session->userdata('otori');
        //$unit_skpd    = $this->session->userdata('unit_skpd');
        $csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_unit ='$unit_skpd'";
        }else {
            $csql1 = "where kd_unit ='$unit_skpd'";
        }
        $sql = "SELECT * FROM mruang $csql1"; // and upper(kd_ruang) like upper('%$lccr%') or upper(nm_ruang) like upper('%$lccr%') 
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_ruang' => $resulte['kd_ruang'],  
                        'nm_ruang' => $resulte['nm_ruang'],
                        'kd_skpd'  => $resulte['kd_skpd'], 
                        'kd_unit'  => $resulte['kd_unit'],
                        'kd_lokasi'=> $resulte['kd_lokasi'],  
                        'keterangan'  => $resulte['keterangan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
	
	function ambil_ruang_bidang() {
        $lccr 			= $this->input->post('q');
        $skpd 			= $this->input->post('skpd');
		$oto  			= $this->session->userdata('otori');
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_skpd ='$skpd'";
        }else {
            $csql1 = "where kd_skpd ='$skpd'";
        }
        $sql = "SELECT * FROM mruang $csql1"; // and upper(kd_ruang) like upper('%$lccr%') or upper(nm_ruang) like upper('%$lccr%') 
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_ruang' => $resulte['kd_ruang'],  
                        'nm_ruang' => $resulte['nm_ruang'],
                        'kd_skpd'  => $resulte['kd_skpd'], 
                        'kd_unit'  => $resulte['kd_unit'],  
                        'keterangan'  => $resulte['keterangan'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
     function ambil_jenis() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kode = $this->input->post('kode');
        $sql = "SELECT * FROM jenis_kib where kode LIKE '$kode%' order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],  
                        'jenis' => $resulte['jenis'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	} 
    function ambil_jenis2() {
       if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr = $this->input->post('q');
        $where='';
        if($lccr!=''){
            $where="AND UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(jenis) LIKE UPPER('%$lccr%')";
        }else{
            $where="";
        }
        $sql = " SELECT * FROM jenis_kib WHERE LEFT(kode,2) IN ('01','02','03','04','05') $where ORDER BY kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],  
                        'jenis' => $resulte['jenis'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    }

     function ambil_jenis_kib() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kode = $this->input->post('kode');
        $sql = "SELECT * FROM mbarang_umur where kd_barang LIKE '$kode%' order by kd_barang";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kd_barang'],  
                        'jenis' => $resulte['nama'],   
                        'umur' => $resulte['umur']
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
    
    function ambil_uker() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_uker, nm_uker FROM unit_kerja where upper(kd_uker) like upper('%$lccr%') or upper(nm_uker) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],  
                        'nm_uker' => $resulte['nm_uker']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}

    function ambil_uker_dh() {
       /* if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{*/
        $lccr = $this->input->post('q');
        $skpd=$this->input->post('skpd');
        if($lccr!=''){
            $where=" and upper(kd_uker) like upper('%$lccr%') or upper(nm_uker) like upper('%$lccr%')";
        }else{
            $where="";
        }
        $sql = "SELECT kd_uker, nm_uker FROM unit_kerja where kd_skpd='$skpd' $where ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],  
                        'nm_uker' => $resulte['nm_uker']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        //}
           
    }
    
    function ambil_pa() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $oto  	= $this->session->userdata('otori');
        $lccr 	= $this->input->post('kduskpd');
        $kode 	= $this->session->userdata('kode');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where skpd = '$lccr' and ckey='QQ'"; //and tingkat='1'
        }else {
            $csql1 = "where skpd = '$lccr' and ckey='QQ'"; //and tingkat='1'
        }
		        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	function ambil_pa2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $oto  	= $this->session->userdata('otori');
        $lccr 	= $this->input->post('kduskpd');
        $kode 	= $this->session->userdata('kode');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_lokasi = '$lccr' and ckey='QQ'";
        }else {
            $csql1 = "where kd_lokasi = '$lccr' and ckey='QQ'";
        }
		        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
	function ambil_pb() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $oto  = $this->session->userdata('otori');
        $lccr = $this->input->post('kduskpd');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where skpd = '$lccr' and ckey='BK'"; //and tingkat='1'
        }else {
            $csql1 = "where skpd = '$lccr' and ckey='BK'"; //and tingkat='1'
        }
        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}	
	
	function ambil_pn() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $oto  = $this->session->userdata('otori');
        $lccr = $this->input->post('kduskpd');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where skpd = '$lccr' and ckey='PN'"; //and tingkat='1'
        }else {
            $csql1 = "where skpd = '$lccr' and ckey='PN'"; //and tingkat='1'
        }
        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}	
	
	function ambil_pb2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $oto  = $this->session->userdata('otori');
        $lccr = $this->input->post('kduskpd');
        
		$csql1='';
        if ($oto =='01'){
            $csql1 = "where kd_lokasi = '$lccr' and ckey='BK'";
        }else {
            $csql1 = "where kd_lokasi = '$lccr' and ckey='BK'";
        }
        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
	
    function ambil_bb() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $lccr = $this->input->post('kduskpd');
        
        if ($lccr!=''){
            $csql1 = "where skpd = '$lccr' and ckey='BB'";
        }else {
            $csql1 = "where ckey='BB'";
        }
        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_bb2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $lccr = $this->input->post('kduskpd');
        
        if ($lccr!=''){
            $csql1 = "where unit = '$lccr' and ckey='BB'";
        }else {
            $csql1 = "where ckey='BB'";
        }
        
        $sql = "SELECT nip,nama FROM ttd $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nama' => $resulte['nama'],
                        'nip' => $resulte['nip']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    function ambil_uker2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        //$lccr = $this->input->post('q');
        $lccr = $this->input->post('kduskpd');
        
        if ($lccr!=''){
            $csql1 = "where kd_uskpd = '$lccr'";
        }else {
            $csql1 = "";
        }
        
        $sql = "SELECT kd_uker, nm_uker FROM unit_kerja $csql1 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],  
                        'nm_uker' => $resulte['nm_uker']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_compy() {        
        //$lccr = $this->input->post('q');    
        $sql = "SELECT kd_comp, nm_comp FROM mcompany order by nm_comp,kd_comp ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_comp' => $resulte['kd_comp'],  
                        'nm_comp' => $resulte['nm_comp']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
	}
	    
	function ambil_rekanan($lccr='') { 
		//$lccr= $this->uri->segment(3);     
        $lccr = $this->input->post('kode');
        $sql 	= "SELECT kd_ruang, nm_ruang FROM mruang where nm_ruang like '%$lccr%' order by nm_ruang ";
        $query1 = $this->db->query($sql);  
        $arr	= array();
		foreach($query1->result_array() as $resulte){ 
			$arr['query'] = $lccr;
			$arr['suggestions'][] = array(
				'value'	=>$resulte['nm_ruang'],
				'data'	=>$resulte['kd_ruang']
			);
        }

        echo json_encode($arr);
    	$query1->free_result();   
           
	}
/* 	
    function ambil_ruangan($lccr='') {        

		//$lccr= $this->uri->segment(3);
        $sql 	= "SELECT * FROM mruang where nm_ruang like '%$lccr%' order by nm_ruang ";
        $query1 = $this->db->query($sql);  
        $arr	= array();
		foreach($query1->result_array() as $resulte){ 
			$arr['query'] = $lccr;
			$arr['suggestions'][] = array(
				'value'	=>$resulte['nm_ruang'],
				'data'	=>$resulte['kd_ruang']
			);
        }

        echo json_encode($arr);
    	$query1->free_result();   
           
	} */
    function ambil_milik() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_milik, nm_milik FROM mmilik where upper(kd_milik) like upper('%$lccr%') or upper(nm_milik) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_milik' => $resulte['kd_milik'],  
                        'nm_milik' => $resulte['nm_milik']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_wilayah() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_wilayah, nm_wilayah FROM mwilayah where upper(kd_wilayah) like upper('%$lccr%') or upper(nm_wilayah) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_wilayah' => $resulte['kd_wilayah'],  
                        'nm_wilayah' => $resulte['nm_wilayah']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_kel1() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_kelompok, nm_kelompok FROM mkelompok1 where upper(kd_kelompok) like upper('%$lccr%') or upper(nm_kelompok) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kelompok' => $resulte['kd_kelompok'],  
                        'nm_kelompok' => $resulte['nm_kelompok'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
      function ambil_bank() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kode, nama FROM mbank where upper(kode) like upper('%$lccr%') or upper(nama) like upper('%$lccr%') order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bank' => $resulte['kode'],  
                        'nm_bank' => $resulte['nama']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_rek5() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek5, nm_rek5 FROM mrek5 where upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_bid() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT bidang, nm_bidang FROM mbidang where upper(bidang) like upper('%$lccr%') or upper(nm_bidang) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],  
                        'nm_bidang' => $resulte['nm_bidang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_bidbar_e() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT bidang, nm_bidang FROM mbidang where golongan='05' and (upper(bidang) like upper('%$lccr%') or upper(nm_bidang) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'bidang' => $resulte['bidang'],  
                        'nm_bidang' => $resulte['nm_bidang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function tahun() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where="where (upper(tahun) like upper('%$lccr%'))";
        }else{
            $where="";
        }
        $sql = "SELECT tahun FROM tahun $where order by tahun";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array('tahun' => $resulte['tahun']);
            $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
       } 
	}

	
	 function ambil_brg() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');    
        $lccr2 = $this->input->post('r');           
        //$gol  = $this->input->post('subkel');
        $gol  = $this->input->post('gol');
        //$sts  = $this->input->post('sts'); 
		$csql1 ="";
        if ($gol!=''){
            //$csql1 = "left(kd_brg,11) = '$gol' and ";
                    $csql1 = "left(kd_brg,2) = '$gol' and ";
		}else {
            $csql1 = "";
        }
        /* if ($lccr=='' && $lccr2 != ''){
            $lccr = $lccr2;
        }
        if ($sts=='mrek5'){
            $field2 = ",b.nm_rek5";
            $csql2  = " inner join mrek5 b on a.kd_rek5=b.kd_rek5";    
        }else{
            $field2 = ",'' as nm_rek5";
            $csql2  = "";
        } */
                //$sql = "SELECT a.kd_brg,a.nm_brg,a.kd_rek5 $field2 FROM mbarang a $csql2 where $csql1 (upper(a.kd_brg) like upper('%$lccr%') or upper(a.nm_brg) like upper('%$lccr%')) order by kd_brg ";//limit 0,100    

/**kode pke rek5**/
		/*         $sql = "SELECT a.kd_brg,a.nm_brg,b.nm_rek5 ,a.kd_rek5 FROM mbarang a 
		left join mrek5 b on a.kd_rek5=b.kd_rek5 
		where $csql1 (upper(a.kd_brg) like upper('%$lccr%') or upper(a.nm_brg) like upper('%$lccr%')) order by kd_brg ";//limit 0,100    
		*/		
 /**end kode**/
 
 $sql = "SELECT * FROM mbarang a
		 where $csql1 (upper(a.kd_brg) like upper('%$lccr%') or upper(a.nm_brg) like upper('%$lccr%')) order by kd_brg ";//limit 0,100    

 
 $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'kd_rek5'=> $resulte['kd_rek5'],
                        'nm_rek5'=> $resulte['nm_rek5']                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
       }
    }


    function ambil_brg_dh() {
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        $lccr = $this->input->post('q');    
        $lccr2 = $this->input->post('r');           
        //$gol  = $this->input->post('subkel');
        $gol  = $this->input->post('bidang');
        //$sts  = $this->input->post('sts'); 
       /* $csql1 ="";
        if ($gol!=''){
            //$csql1 = "left(kd_brg,11) = '$gol' and ";
                    $csql1 = "left(a.kd_brg,5) = '$gol' and ";
        }else {
            $csql1 = "";
        }*/
        if($lccr!=''){
            $like="and (upper(a.kd_brg) like upper('%$lccr%') or upper(a.nm_brg) like upper('%$lccr%'))";
        }else{
            $like="";
        }
        
 
 $sql = "SELECT * FROM ambil_vbrg a
         where left(a.kd_brg,5) = '$gol' $like order by kd_brg ";//limit 0,100    

 
 $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'kd_rek5'=> $resulte['kd_rek5'],
                        'nm_rek5'=> $resulte['nm_rek5']                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
       $query1->free_result();
       }
    }
	
	function ambil_brg_kib() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr 	= $this->input->post('q');    
        $lccr2 	= $this->input->post('r');           
        $gol  	= $this->input->post('subkel');
        $sts  	= $this->input->post('sts');       

        //$sql = "SELECT a.kd_brg,a.nm_brg $field2 FROM mbarang a $csql2 where $csql1 (upper(a.kd_brg) like upper('%$lccr%') or upper(a.nm_brg) like upper('%$lccr%')) order by kd_brg limit 0,100 ";    
       $sql = "SELECT a.kd_brg,a.nm_brg FROM mbarang a 
	   where left(a.kd_brg,11) = '$gol' and (upper(a.kd_brg) like upper('%$lccr%') 
	   or upper(a.nm_brg) like upper('%$lccr%')) order by kd_brg";    
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],  
                        'nm_brg' => $resulte['nm_brg']                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
       }
    }
    
    function ambil_dana() {        
        $lccr = $this->input->post('q');    
        $sql = "SELECT kd_sumberdana, nm_sumberdana FROM mdana where upper(nm_sumberdana) like upper('%$lccr%') order by kd_sumberdana, nm_sumberdana ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sumberdana' => $resulte['kd_sumberdana'],  
                        'nm_sumberdana' => $resulte['nm_sumberdana']                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
	}
    
    function ambil_unit() {        
        $lccr = $this->input->post('q');
        $kode = $this->input->post('uskpd');
        if ($kode == ""){
            $csql = "";
        }else{
            $csql = " ltrim(kd_uskpd) = ltrim('$kode') and";
        }
        $sql = "SELECT kd_uker, nm_uker FROM unit_kerja where $csql upper(nm_uker) like upper('%$lccr%')  order by kd_uker, nm_uker ";        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_uker' => $resulte['kd_uker'],  
                        'nm_uker' => $resulte['nm_uker']                         
                        );
                        $ii++;
        }    
           
        echo json_encode($result);
	   $query1->free_result();
	} 
    
    function ambil_lap() 
    {        
        $oto="m".$this->session->userdata('otori_simbakda');
        $lccr = $this->input->post('q');    
        $sql = "SELECT idmenu,judul,link  FROM ms_menu where upper(judul) like upper('%$lccr%') and parent='4' and $oto='1' order by judul ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'idmenu' => $resulte['idmenu'],  
                        'judul' => $resulte['judul'],
                        'link' => $resulte['link']                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	   $query1->free_result();
	} 
    
	///////////////////////////////////////////////////////////////////////////////
	
	function ambil_kib_sisa_b() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
		
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
		
		
        $sql = "SELECT * from trkib_b";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg' => $resulte['no_reg'],
						'id_barang' => $resulte['id_barang'],  
                        'nm_brg' => $resulte['nm_brg'], 
                        'tahun' => $resulte['tahun'], 
                        'nilai' => $resulte['nilai']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}	

	function ambil_kib_sisa_c() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
		
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
		
		
        $sql = "SELECT * from trkib_c";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_reg' => $resulte['no_reg'],
						'id_barang' => $resulte['id_barang'],  
                        'nm_brg' => $resulte['nm_brg'], 
                        'tahun' => $resulte['tahun'], 
                        'nilai' => $resulte['nilai']  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}		
	////////////////////////////////////////////////////////////
	
	
	function ambil_kiba() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_a a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}
	
   	function ambil_kibb() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_b a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}
	function ambil_kibc() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_c a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}
	function ambil_kibd() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_d a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}
	function ambil_kibe() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_e a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}
	function ambil_kibf() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
      	 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where  kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and upper(kd_unit) like upper('%$lccr%') or upper(nm_uskpd) like upper('%$lccr%') ";
        }
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg=a.kd_brg) as nama_barang FROM trkib_f a $where1 $where2  order by kd_unit";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_unit' => $resulte['kd_unit'],  
                        'no_reg' => $resulte['no_reg'], 
                        'kd_brg' => $resulte['kd_brg'], 
                        'nama_barang' => $resulte['nama_barang'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
	}	
	
	
		function do_upload(){
		//DATANYA DI UPLOAD DULU
		$fl= $this->input->post('datasql');
		$upload_path_url = base_url().'upload/';
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'jpg|jpeg|txt|dat|sql|txt|abc';
        $config['max_size'] = '100000';
        $config['overwrite'] = TRUE; //overwrite user avatar

        $this->load->library('upload', $config);
	    $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('datasql')) {
			$data['status_upload']= $this->upload->display_errors();
        } else {
	
			
			//PROSES RESTORE DATA
			$dat =  $this->upload->data();
			$lfile=$dat['full_path'];


			$fl=fopen($lfile,'r');
			$jum=0;
			while (!feof($fl)) {
				$contents = fread($fl, filesize($lfile));

				//$this->_randomsleep();
			    //$query = $this->db->query($contents);  
				
				$baris=explode(";".chr(13),$contents);
				for($i=0;$i<=count($baris)-1;$i++){
					$q=$baris[$i];			        
					$jum++;	
					//$this->_randomsleep();
					//$query = $this->db->query($q);  

					$nm=FCPATH.'/restore/temp'.($i).'.txt';
					$fltemp=fopen($nm,'w+');
					fwrite($fltemp,$q);		
					fclose($fltemp);


				}			
			}

			$nm=FCPATH.'/restore/jumquery.txt';
			$fltemp=fopen($nm,'w+');
			fwrite($fltemp,$jum);		
			fclose($fltemp);


			fclose($fl);

			$data['page_title']= 'Backup';
			$data['status_upload']= 'Upload Berhasil'; //'Restore Berhasil';
			$this->template->set('title', 'Backup');   
			$this->template->load('template','backup/backup',$data) ;
		}

	}

    function ambil_sp2d() { 
    $dbsimakda=$this->load->database('simakda', TRUE);
        /*if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{
        
        $skpd       = $this->session->userdata('unit_skpd');
        $oto        = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_skpd like '%' ";
        }else{
            $where1 = "where kd_skpd ='$skpd' and ";
        } */       
        $skpd= $this->input->post('kdskpd');  
        $lccr = $this->input->post('q');
        $sp2d =$this->input->post('sp2d');
          $where2='';
        if($lccr <> ''){
            $where2 =" and UPPER(no_sp2d) LIKE UPPER('%$lccr%') ";
        }
        
        $sql    = "SELECT no_sp2d,tgl_sp2d,nilai,keperluan FROM trhsp2d where kd_skpd='$skpd' and no_sp2d='$sp2d' and jns_spp!='4' $where2 order by no_sp2d ";
        $query1 = $dbsimakda->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'        => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'nilai2' => number_format($resulte['nilai'],2,',','.'),
                        'nilai'=>$resulte['nilai'],
                        'keperluan' => $resulte['keperluan'] 
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
           		   	
	function nomor_kontrak_bap($kode='') { 
    /*$dbsimakda=$this->load->database('simbakda_biak', TRUE);
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{*/
        $kode = $_REQUEST['kode'];
        //$skpd       = $this->session->userdata('skpd');
		$skpd		= "1.20.10.01";
        //$oto        = $this->session->userdata('otori');
		$oto        = "01";
		
			$where1 = '';       		
				if($oto == '01'){ 
					$where1 = " and a.kd_uskpd ='$skpd' ";
				}else{
					$where1 = " and a.kd_uskpd ='$skpd' ";
				}
		                               			
		$sql = "SELECT a.no_dokumen,a.tgl_dokumen,a.nilai_kontrak,a.s_dana FROM trh_isianbrg a where a.no_dokumen='$kode' $where1";				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
		   $sqq = $this->db->query("SELECT TOP 1 a.kd_kegiatan,a.nm_kegiatan FROM trd_isianbrg a 
				where a.no_dokumen='$kode'")->row();
		   $sqq2 = $sqq->kd_kegiatan;
		   $sqq3 = $sqq->nm_kegiatan;
		   
            $result[] = array(
						'id'          	 => $ii,        
                        'no_dokumen'     => $resulte['no_dokumen'],
                        'tgl_dokumen'  	 => $resulte['tgl_dokumen'],
						'kd_kegiatan'  	 => $sqq2,
						'nm_kegiatan'  	 => $sqq3,
                        'nilai_kontrak'  => $resulte['nilai_kontrak'],
                        'nilai_kontrak2' => number_format($resulte['nilai_kontrak'],2,',','.'),
                        's_dana'       	 => $resulte['s_dana']                        
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
    }

	
    function ambil_nomor_kontrak() { 
    /*$dbsimakda=$this->load->database('simbakda_biak', TRUE);
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{*/
        
        $skpd       = $this->session->userdata('skpd');
		//$skpd		= "1.20.12.01";
        //$oto        = $this->session->userdata('otori');
		$oto        = "01";
 
			$where1 = '';       		
				if($oto == '01'){ 
					$where1 = " a.kd_skpd ='$skpd' and ";
				}else{
					$where1 = " a.kd_skpd ='$skpd' and ";
				}
		        
		$sqlsc="SELECT count(distinct a.no_dokumen)+1 no_k FROM trd_isianbrg a inner join trh_isianbrg b on a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd where b.kd_uskpd='$skpd' ";
            $sqlsclient=$this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc)
            {
                   
                $no_k  = $rowsc->no_k;
            }
				
        /*$sql    = " SELECT a.kd_skpd,a.no_bukti AS no_kontrak,''AS no_spp,b.no_sp2d,a.total AS nilai,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.ket as keterangan,'' as pimpinan FROM trhtransout a JOIN trdtransout b ON a.no_bukti=b.no_bukti
                    WHERE $where1 LEFT(b.kd_rek5,3)='523' AND a.no_bukti NOT IN (SELECT no_dokumen FROM trh_isianbrg)";*/
					
		$sql = "SELECT a.kd_skpd,b.kd_kegiatan+'/'+b.kd_rek5+'/'+'$no_k' AS no_kontrak,''AS no_spp,'' no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,sum(b.nilai) AS nilai,'' as keterangan,'' as pimpinan FROM trhtransout a JOIN trdtransout b ON a.no_bukti=b.no_bukti
				WHERE left(b.kd_rek5,3)='523' AND a.kd_skpd='$skpd'
				group by a.kd_skpd,a.no_bukti,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,b.nilai,a.ket order by b.kd_kegiatan, b.kd_rek5";				
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
		$nilai_kontrak=0;
        foreach($query1->result_array() as $resulte)
        { 
           
		   $kkd_keg = $resulte['kd_kegiatan'];
		   $kkd_rek = $resulte['kd_rek5'];
		   $kkd_dok = $resulte['no_kontrak'];
		   
		   $sqlcek = $this->db->query("select COUNT(kd_kegiatan+kd_rek5) as kd from trd_isianbrg where
		   kd_kegiatan = '$kkd_keg' and kd_rek5 = '$kkd_rek' and kd_uskpd='$skpd'
		   ")->row();
		   $kd = $sqlcek->kd;
		   
		   if($kd==0){
			   $nilai_kontrak=$resulte['nilai'];
			   $sisa_kontrak =$resulte['nilai'];
			   $bel_kontrak = 0;
			   $status = 0;
		   }else{
			   $sqlcek = $this->db->query("select sum(nilai) nilai, sum(nilai_kontrak-nilai) nilai_sisa, sum(nilai_kontrak) nilai_kontrak from (
				select 0 nilai, sum(nilai) nilai_kontrak from trdtransout a inner join trhtransout b 
				on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where
				a.kd_kegiatan = '$kkd_keg' and a.kd_rek5 = '$kkd_rek' and a.kd_skpd='$skpd'
				union all
				select sum(a.total) nilai, 0 nilai_kontrak from trd_isianbrg a inner join trh_isianbrg b 
				on a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd where
				a.kd_kegiatan = '$kkd_keg' and a.kd_rek5 = '$kkd_rek' and a.kd_uskpd='$skpd'
				) a
			   ")->row();
			   $nilai_kontrak = $sqlcek->nilai_kontrak;
			   $sisa_kontrak = $sqlcek->nilai_sisa;
			   $bel_kontrak = $sqlcek->nilai;
			   $status = 1;
		   }
		   
		   $sql = $this->db->query("select nilai_sisa from trhtransout_sisa ");
		   
            $result[] = array(
                        'id'          => $ii,        
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'no_kontrak'  => $resulte['no_kontrak'],
                        'no_sp2d'     => $resulte['no_sp2d'],
                        'nilai2'      => number_format($nilai_kontrak,2,',','.'),
                        'nilai'       => $nilai_kontrak,
						 'nilai_bel2'      => number_format($bel_kontrak,2,',','.'),
                        'nilai_bel'       => $bel_kontrak,
						'nilai_sisa2' => number_format($sisa_kontrak,2,',','.'),
                        'nilai_sisa'  => $sisa_kontrak,
                        'no_spp'      => $resulte['no_spp'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'kd_rek5'     => $resulte['kd_rek5'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'nm_rek5'     => $resulte['nm_rek5'],
                        'keterangan'  => $resulte['keterangan'], 
                        'pimpinan'    => $resulte['pimpinan'],
						'status'      => $status
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
    }
	
	

	
	
    function ambil_nomor_kontrak_asli() { 
    /*$dbsimakda=$this->load->database('simbakda_biak', TRUE);
        if($this->auth->is_logged_in() == false){
            redirect(site_url().'/welcome/login');
        }else{*/
        
        $skpd       = $this->session->userdata('skpd');
		//$skpd		= "1.20.12.01";
        //$oto        = $this->session->userdata('otori');
		$oto        = "01";
 
			$where1 = '';       		
				if($oto == '01'){ 
					$where1 = " a.kd_skpd ='$skpd' and ";
				}else{
					$where1 = " a.kd_skpd ='$skpd' and ";
				}
		                       
        /*$sql    = " SELECT a.kd_skpd,a.no_bukti AS no_kontrak,''AS no_spp,b.no_sp2d,a.total AS nilai,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.ket as keterangan,'' as pimpinan FROM trhtransout a JOIN trdtransout b ON a.no_bukti=b.no_bukti
                    WHERE $where1 LEFT(b.kd_rek5,3)='523' AND a.no_bukti NOT IN (SELECT no_dokumen FROM trh_isianbrg)";*/
					
		$sql = "SELECT a.kd_skpd,a.no_bukti+'/'+b.kd_rek5 AS no_kontrak,''AS no_spp,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,b.nilai AS nilai,a.ket as keterangan,'' as pimpinan FROM trhtransout a JOIN trdtransout b ON a.no_bukti=b.no_bukti
                    WHERE $where1 LEFT(b.kd_rek5,3)='523' AND a.no_bukti NOT IN (SELECT no_dokumen FROM trh_isianbrg)
                    group by a.kd_skpd,a.no_bukti,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,b.nilai,a.ket";				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
		$nilai_kontrak=0;
        foreach($query1->result_array() as $resulte)
        { 
           
		   $kkd_keg = $resulte['kd_kegiatan'];
		   $kkd_rek = $resulte['kd_rek5'];
		   $kkd_dok = $resulte['no_kontrak'];
		   
		   $sqlcek = $this->db->query("select COUNT(kd_rek5) as kd from trhtransout_sisa where
		   kd_kegiatan = '$kkd_keg' and kd_rek5 = '$kkd_rek' and no_dokumen='$kkd_dok'
		   ")->row();
		   $kd = $sqlcek->kd;
		   
		   if($kd==0){
			   $nilai_kontrak=$resulte['nilai'];
			   $sisa_kontrak = 0;
			   $bel_kontrak = 0;
			   $status = 0;
		   }else{
			   $sqlcek = $this->db->query("select nilai,nilai_sisa,nilai_kontrak from trhtransout_sisa where 
			   kd_kegiatan = '$kkd_keg' and kd_rek5 = '$kkd_rek' and no_dokumen='$kkd_dok'
			   ")->row();
			   $nilai_kontrak = $sqlcek->nilai_kontrak;
			   $sisa_kontrak = $sqlcek->nilai_sisa;
			   $bel_kontrak = $sqlcek->nilai;
			   $status = 1;
		   }
		   
		   $sql = $this->db->query("select nilai_sisa from trhtransout_sisa where ");
		   
            $result[] = array(
                        'id'          => $ii,        
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'no_kontrak'  => $resulte['no_kontrak'],
                        'no_sp2d'     => $resulte['no_sp2d'],
                        'nilai2'      => number_format($nilai_kontrak,2,',','.'),
                        'nilai'       => $nilai_kontrak,
						 'nilai_bel2'      => number_format($bel_kontrak,2,',','.'),
                        'nilai_bel'       => $bel_kontrak,
						'nilai_sisa2' => number_format($sisa_kontrak,2,',','.'),
                        'nilai_sisa'  => $sisa_kontrak,
                        'no_spp'      => $resulte['no_spp'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'kd_rek5'     => $resulte['kd_rek5'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'nm_rek5'     => $resulte['nm_rek5'],
                        'keterangan'  => $resulte['keterangan'], 
                        'pimpinan'    => $resulte['pimpinan'],
						'status'      => $status
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
    }
	
    function ambil_rekening() { 
    //$dbsimakda=$this->load->database('simakda', TRUE);
      //  if($this->auth->is_logged_in() == false){
        //    redirect(site_url().'/welcome/login');
        //}else{
        
        $skpd       = "1.20.10.01";//$this->session->userdata('skpd');
        $oto        = "01";//$this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = " a.kd_skpd ='$skpd' and ";
        }else{
            $where1 = " a.kd_skpd ='$skpd' and ";
        }       
		
        $keg= $this->input->post('keg');  
        $rek= $this->input->post('rek');  
        
        $sql    = "SELECT kd_rek5,nm_rek5 from trhtransout a JOIN trdtransout b ON a.no_bukti=b.no_bukti
					where $where1 and b.kd_kegiatan='$keg' and b.kd_rek5='$rek'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'        => $ii,
                        'kd_rek5'   => $resulte['kd_rek5'],
                        'nm_rek5'   => $resulte['nm_rek5']        
                        
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        //}
    }
	
	/*****import export data****/
	 function import_data_kib()
	{
        $config['upload_path'] = FCPATH.'/upload/';
		$config['allowed_types'] = 'xls';
        $config['max_size'] = '100000';
        $config['file_name'] = 'upload' . time();

		$this->load->library('upload', $config);
          
		if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			
		}
		else
		{
            $data = array('error' => false);
			$upload_data = $this->upload->data();
            
            $this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
        //    $this->excel_reader->setOutputEncoding('230787');
			$file = $upload_data['full_path'];
			$this->excel_reader->read($file);
			error_reporting(E_ALL ^ E_NOTICE);

			// Sheet 1
			$data = $this->excel_reader->sheets[0] ;
         
            $dataexcel = Array();
			for ($i = 1; $i <= $data['numRows']; $i++) {
                         if($data['cells'][$i+1][1] == '') break;
								$dataexcel[$i-1]['no_reg'] = $data['cells'][$i+1][1];
								$dataexcel[$i-1]['id_barang'] = $data['cells'][$i+1][2];
								$dataexcel[$i-1]['no'] = $data['cells'][$i+1][3];
								$dataexcel[$i-1]['no_oleh'] = $data['cells'][$i+1][4];
								$dataexcel[$i-1]['tgl_reg'] = $data['cells'][$i+1][5];
								$dataexcel[$i-1]['tgl_oleh'] = $data['cells'][$i+1][6];
								$dataexcel[$i-1]['no_dokumen'] = $data['cells'][$i+1][7];
								$dataexcel[$i-1]['kd_brg'] = $data['cells'][$i+1][8];
								$dataexcel[$i-1]['detail_brg'] = $data['cells'][$i+1][9];
								$dataexcel[$i-1]['kd_tanah'] = $data['cells'][$i+1][10];
								$dataexcel[$i-1]['nilai'] = $data['cells'][$i+1][11];
								$dataexcel[$i-1]['asal'] = $data['cells'][$i+1][12];
								$dataexcel[$i-1]['dsr_peroleh'] = $data['cells'][$i+1][13];
								$dataexcel[$i-1]['total'] = $data['cells'][$i+1][14];
								$dataexcel[$i-1]['kondisi'] = $data['cells'][$i+1][15];
								$dataexcel[$i-1]['konstruksi'] = $data['cells'][$i+1][16];
								$dataexcel[$i-1]['jenis'] = $data['cells'][$i+1][17];
								$dataexcel[$i-1]['bangunan'] = $data['cells'][$i+1][18];
								$dataexcel[$i-1]['luas'] = $data['cells'][$i+1][19];
								$dataexcel[$i-1]['jumlah'] = $data['cells'][$i+1][20];
								$dataexcel[$i-1]['tgl_awal_kerja'] = $data['cells'][$i+1][21];
								$dataexcel[$i-1]['status_tanah'] = $data['cells'][$i+1][22];
								$dataexcel[$i-1]['nilai_kontrak'] = $data['cells'][$i+1][23];
								$dataexcel[$i-1]['alamat1'] = $data['cells'][$i+1][24];
								$dataexcel[$i-1]['alamat2'] = $data['cells'][$i+1][25];
								$dataexcel[$i-1]['alamat3'] = $data['cells'][$i+1][26];
								$dataexcel[$i-1]['no_mutasi'] = $data['cells'][$i+1][27];
								$dataexcel[$i-1]['no_pindah'] = $data['cells'][$i+1][28];
								$dataexcel[$i-1]['no_hapus'] = $data['cells'][$i+1][29];
								$dataexcel[$i-1]['keterangan'] = $data['cells'][$i+1][30];
								$dataexcel[$i-1]['kd_skpd'] = $data['cells'][$i+1][31];
								$dataexcel[$i-1]['kd_unit'] = $data['cells'][$i+1][32];
								$dataexcel[$i-1]['milik'] = $data['cells'][$i+1][33];
								$dataexcel[$i-1]['wilayah'] = $data['cells'][$i+1][34];
								$dataexcel[$i-1]['username'] = $data['cells'][$i+1][35];
								$dataexcel[$i-1]['tgl_update'] = $data['cells'][$i+1][36];
								$dataexcel[$i-1]['tahun'] = $data['cells'][$i+1][37];
								$dataexcel[$i-1]['foto'] = $data['cells'][$i+1][38];
								$dataexcel[$i-1]['foto2'] = $data['cells'][$i+1][39];
								$dataexcel[$i-1]['no_urut'] = $data['cells'][$i+1][40];
								$dataexcel[$i-1]['lat'] = $data['cells'][$i+1][41];
								$dataexcel[$i-1]['lon'] = $data['cells'][$i+1][42];
								$dataexcel[$i-1]['kd_riwayat'] = $data['cells'][$i+1][43];
								$dataexcel[$i-1]['tgl_riwayat'] = $data['cells'][$i+1][44];
								$dataexcel[$i-1]['detail_riwayat'] = $data['cells'][$i+1][45]; 

			}
                     
            unlink($upload_data['full_path']);
            $this->load->model('Exmodel');
            $this->Exmodel->save_kib($dataexcel);
           
		}
      redirect('master/import_data');
   	}
	/************END************/
	/******EXPORT SEND TO MODEL******/
	function export_kib_a(){
	$this->mdata2->export_kib_a();
	}
	function export_kib_b(){
	$this->mdata2->export_kib_b();
	}
	function export_kib_c(){
	$this->mdata2->export_kib_c();
	}
	function export_kib_d(){
	$this->mdata2->export_kib_d();
	}
	function export_kib_e(){
	$this->mdata2->export_kib_e();
	}
	function export_kib_f(){
	$this->mdata2->export_kib_f();
	}
	/******END EXPORT TO MODEL*******/
}
