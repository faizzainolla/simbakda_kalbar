<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {
        
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
                         'nm_client' => $resulte['nm_client'],                                              					
                         'kepala' => $resulte['kepala'],                                              					
                         'nip_kepala'=> $resulte['nip_kepala'],                                              					
                         'pkt_kepala' => $resulte['pkt_kepala'],
                         'nama_bendahara' => $resulte['nama_bendahara'],
                         'nip_bendahara' => $resulte['nip_bendahara'],
                         'pkt_bendahara' => $resulte['pkt_bendahara'],
                         'lprint' => $resulte['lprint'],
                         'kota' => $resulte['kota'],
                         'logo' => $resulte['logo']                                                                       					
                         );
        }
	   
       $query1->free_result(); 
	   return $resulte;        	
	}	
    
    function simpan_konfigurasi(){
        $client  = $this->input->post('client');
        $pimpinan  = $this->input->post('pimpinan');
        $nip_pimp  = $this->input->post('nip_pimp');
        $pkt_pimp  = $this->input->post('pkt_pimp');
        $kota  = $this->input->post('kota');
        $logo  = $this->input->post('logo');
        
        $csql = "update config set nm_client='$client',kepala='$pimpinan',nip_kepala='$nip_pimp', pkt_kepala='$pkt_pimp',kota='$kota',logo='$logo'";
        $query1 = $this->db->query($csql);  
        
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
    
    function load_user() {
       
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';

        if ($kriteria != ''){                               
            $where=" and nmuser like '%$kriteria%' OR ket like '%$kriteria%' ";            
        }			   

        $rs = $this->db->query("select count(*) as tot FROM muser ");
        $trh = $rs->row();
        

        $sql = "SELECT top $rows * FROM muser where kode (select top $offset kode from muser) $where";
        $query1 = $this->db->query($sql);  

	
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
			$otori=$resulte['oto'];
			if($otori=='01'){
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
                        'nmoto'  => $nmoto,
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
        $nmskpd = $this->input->post('cnmskpd');
        $unit_skpd = $this->input->post('unit_skpd');
        $oto = $this->input->post('oto');
        $ket = $this->input->post('ket');
        $del = trim($this->input->post('del'));
        $nama_admin = '';
        $email_admin = '';

		$query1 = $this->db->query("delete from muser where nmuser='$nmskpd' and iduser='$user' and password='$pass'");  
		if ($del=='0'){
		   $query1 = $this->db->query("insert into muser values('','$user','$pass','$nmskpd','$oto','$ket','$skpd','$unit_skpd','$nama_admin','$email_admin' ) ");  
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
            $where="where (upper(nm_golongan) like upper('%$kriteria%') or golongan like'%$kriteria%')";            
        }
        
        $sql = "SELECT *,'' AS ketjenis from mgolongan $where order by golongan";
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
            $where="and nm_bidskpd like'%$kriteria%'";            
        }
        $rs = $this->db->query("select count(*) as tot FROM mbidskpd ");
        $trh = $rs->row();
        $sql = "SELECT top $rows * FROM mbidskpd where kd_bidskpd not in (select top $offset kd_bidskpd from mbidskpd) $where";
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
    
    function load_unit_bidang() {
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
            $where="and nm_uskpd like'%$kriteria%' or kd_uskpd like'%kriteria%'";            
        }
        $rs = $this->db->query("select count(*) as tot FROM unit_skpd ");
        $trh = $rs->row();
        $sql = "SELECT top $rows * FROM unit_skpd where kd_uskpd not in (select top $offset kd_uskpd from unit_skpd) $where";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_uskpd' => $resulte['kd_uskpd'],
                        'nm_uskpd' => $resulte['nm_uskpd'],
                        'kd_bidskpd' => $resulte['kd_bidskpd'],
                        'alamat' => $resulte['alamat']                                                                                      
                        );
                        $ii++;
        }
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
    
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
            $where="and nm_uker like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM unit_kerja ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows * FROM unit_kerja where kd_uker not in (select top $offset kd_uker from unit_kerja) $where";
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
                        'alamat' => $resulte['alamat']                                                                                      
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
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

        if ($kriteria <> ''){                               
            $where="and a.nm_bidang like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbidang a ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*,(golongan+'-'+(SELECT nm_golongan FROM mgolongan WHERE golongan = a.golongan)) AS nmgol FROM mbidang a where a.bidang not in (select top $offset bidang from mbidang) $where";
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
    
      function load_usaha() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari'); 
        $where2='';
        if($kriteria <> ''){
          $where2 ="and UPPER(a.nm_comp) LIKE UPPER('%$kriteria%')";
        }
		
        $where1 ='';
        $skpd 		= $this->session->userdata('unit_skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_unit like '%' ";
        }else{
            $where1 = "where kd_unit ='$skpd' ";
        }        
         
        $rs = $this->db->query("select count(*) as tot FROM mcompany a $where1 $where2 and kd_comp not in (select top $offset kd_comp from mcompany)");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*, CASE 
                    WHEN a.bentuk='1' THEN 'PT/NV'
                    WHEN a.bentuk='2' THEN 'CV'
                    WHEN a.bentuk='3' THEN 'FIRMA'
                    WHEN a.bentuk='4' THEN 'Lain-lain'
                    END AS nmbentuk FROM mcompany a $where1 $where2 and kd_comp not in (select top $offset kd_comp from mcompany)";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_comp' => $resulte['kd_comp'],
                        'nm_comp' => $resulte['nm_comp'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_unit' => $resulte['kd_unit'],
                        'bentuk' => $resulte['bentuk'],
                        'alamat' => $resulte['alamat'],
                        'pimpinan' => $resulte['pimpinan'],
                        'kd_bank' => $resulte['kd_bank'],
                        'rekening' => $resulte['rekening'],
                        'nmbentuk' => $resulte['nmbentuk']
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();
        }   
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

        if ($kriteria <> ''){                               
            $where="where a.nm_kelompok like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mkelompok1 a ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*, (kelompok+'-'+(SELECT nm_kelompok FROM mkelompok WHERE kelompok = a.kelompok)) AS nmkel FROM mkelompok1 a where kd_kelompok not in (select top $offset kd_kelompok from mkelompok1) $where";
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

        if ($kriteria <> ''){                               
            $where="and a.nm_brg like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mbarang a ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*, Cast(kd_kelompok as nvarchar(4000)) + '-'+(SELECT nm_kelompok FROM mkelompok1 
		WHERE kd_kelompok = a.kd_kelompok) AS nmkel 
		FROM mbarang a where a.kd_brg not in (select top $offset kd_brg from mbarang) $where";
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
            $where="AND nm_wilayah like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mwilayah a ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT TOP $rows * FROM mwilayah WHERE kd_wilayah NOT IN (SELECT  TOP $offset kd_wilayah FROM mwilayah) $where";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_wilayah' => $resulte['kd_wilayah'],
                        'nm_wilayah' => $resulte['nm_wilayah'],
                        'kd_provinsi' => $resulte['kd_provinsi']                                                                               
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
		
        $rs = $this->db->query("select count(*) as tot FROM mlokasi a $where1 and kd_lokasi not in (select top $offset kd_lokasi from mlokasi)");
        $trh = $rs->row();
        
        $sql = "SELECT top $rows * FROM mlokasi $where1 $where2 and kd_lokasi not in (select top $offset kd_lokasi from mlokasi)";
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
            $where="and nm_ruang like'%$kriteria%' or kd_ruang like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mruang ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows * FROM mruang where kd_ruang not in (select top $offset kd_ruang from mruang) $where";
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
            $where="and nm_satuan like'%$kriteria%' or kd_satuan like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM msatuan where kd_satuan not in (select top $offset kd_satuan from msatuan) $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows * FROM msatuan where kd_satuan not in (select top $offset kd_satuan from msatuan) $where";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_satuan' => $resulte['kd_satuan'],
                        'nm_satuan' => $resulte['nm_satuan']                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result(); 
        }  
	}
    
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
            $where="and nm_pangkat like'%$kriteria%' or kd_pangkat like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mpangkat WHERE kd_pangkat NOT IN 
		(SELECT  TOP $offset kd_pangkat FROM mpangkat) ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT * FROM mpangkat WHERE kd_pangkat NOT IN 
		(SELECT  TOP $offset kd_pangkat FROM mpangkat) $where";
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
            $where="and nm_warna like'%$kriteria%' or kd_warna like '%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mwarna where kd_warna not in (select top $offset kd_warna from mwarna) $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows * FROM mwarna where kd_warna not in (select top $offset kd_warna from mwarna) $where";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_warna' => $resulte['kd_warna'],
                        'nm_warna' => $resulte['nm_warna']                                                                           
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
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
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where unit like '%' ";
        }else{
            $where1 = "where unit ='$skpd' ";
        }        
          
        $kriteria = '';
        $kriteria = $this->input->post('cari');
          $where2='';
        if($kriteria <> ''){
            $where2 ="and UPPER(nama) LIKE UPPER('%$kriteria%')";
        }
		
        $rs = $this->db->query("select count(*) as tot FROM ttd $where1 $where2 ORDER BY nm_skpd");
        $trh = $rs->row();
        $result["total"] = $trh->tot;
        
        $sql = "SELECT *,IF((COUNT(skpd)+COUNT(nip)+COUNT(nama))='3','OK','NO') AS tanda FROM ttd $where1 $where2 GROUP BY unit,nip,nm_skpd,kd_pangkat,ckey,nama ORDER BY nm_skpd limit $offset,$rows";//
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
            $where="and  nm_milik like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mmilik a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows * FROM mmilik where kd_milik not in (select top $offset kd_milik from mmilik) $where";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_milik' => $resulte['kd_milik'],
                        'nm_milik' => $resulte['nm_milik']                                                                               
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

        if ($kriteria <> ''){                               
            $where="and a.nm_kelompok like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM mkelompok a ");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT top $rows a.*,(bidang+'-'+(SELECT nm_bidang FROM mbidang WHERE bidang = a.bidang)) AS nmbid FROM mkelompok a where kelompok not in (select top $offset kelompok from mkelompok) $where";
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
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_a a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd is not null $where";//where a.kd_skpd='$kd_unit'
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
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,
				a.keterangan,a.nilai,a.kd_satuan from trkib_b a
				left join mbarang b on a.kd_brg=b.kd_brg 
				where a.kd_skpd is not null $where"; //where a.kd_skpd='$kd_unit'
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
    function load_kibc() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_c a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd is not null $where";//where a.kd_skpd='$kd_unit'
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
	
    function load_kibd() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $kd_unit = $this->session->userdata('skpd');
        $lccr = $this->input->post('q');
		$where ="";
		if($lccr<>''){
		$where="and b.nm_brg like '%$lccr%'";
		}
        $sql = "select a.no_reg,a.id_barang,b.nm_brg as nama,b.kd_rek_pelihara,a.kd_brg,a.kondisi,a.tahun,a.keterangan,a.nilai,'' as kd_satuan from trkib_d a
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd is not null $where";//where a.kd_skpd='$kd_unit'
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
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd is not null $where";//where a.kd_skpd='$kd_unit'
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
				left join mbarang b on a.kd_brg=b.kd_brg where a.kd_skpd is not null $where";//where a.kd_skpd='$kd_unit'
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
        $lccr = $this->input->post('gol');//$where 
        $sql = "SELECT no_dokumen,kd_brg,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket FROM trd_planbrg where left(kd_brg,2)='$lccr' order by kd_brg";
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
        $sql = "SELECT kd_brg,nm_brg,kd_kegiatan,jumlah,harga,total,keterangan 
		FROM trd_isianbrg where 
		left(kd_brg,2)='$lccr' order by kd_brg";// kd_uskpd='$skpd' and 
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
        $sql = "SELECT b.* FROM trh_terimabrg a LEFT JOIN trd_terimabrg b ON a.kd_unit=b.kd_unit AND a.kd_uskpd=a.kd_uskpd
				WHERE a.no_dokumen='$dok'"; //AND b.kd_unit='$unit'
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
        $sql = "SELECT golongan, nm_golongan FROM mgolongan where upper(golongan) like upper('%$lccr%') or upper(nm_golongan) like upper('%$lccr%') order by golongan ";
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
    
    function ambil_kelompok() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('bidang');
        $sql = "SELECT kelompok,nm_kelompok FROM mkelompok where bidang='$lccr' ";
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
                        'nm_pangkat' => strtoupper($resulte['nm_pangkat']),  
                       
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
        
        $sql = "SELECT kd_skpd,kd_lokasi, nm_lokasi FROM mlokasi $where1 $where2 order by nm_lokasi";
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
    
      function ambil_msskpd2() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		
		$skpd 		= $this->session->userdata('unit_skpd');
        $oto  		= $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where b.kd_lokasi ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and UPPER(a.nm_skpd) LIKE UPPER('%$lccr%') order by nm_skpd";
        }
		
        $sql 	= "SELECT a.kd_skpd,a.nm_skpd,b.kd_lokasi,b.nm_lokasi FROM ms_skpd a inner 
		join mlokasi b on a.kd_skpd=b.kd_skpd $where1 $where2  ";//GROUP BY a.kd_skpd order by nm_skpd
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 		=> $ii,        
                        'kd_skpd' 	=> $resulte['kd_skpd'],  
                        'kd_lokasi' => $resulte['kd_lokasi'],  
                        'nm_skpd' 	=> $resulte['nm_skpd'],    
                        'nm_lokasi' => $resulte['nm_lokasi'] 
                       
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
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd' ";
        }        
          
        $lccr = $this->input->post('q');
          $where2='';
        if($lccr <> ''){
            $where2 ="and UPPER(a.nm_skpd) LIKE UPPER('%$lccr%') order by nm_skpd";
        }
		
        $sql 	= "SELECT a.kd_skpd,a.nm_skpd FROM ms_skpd a $where1 $where2";
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
                        'skpd' 	  => $resulte['kd_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        }
    	   
	}
    
    function ambil_bidskpd() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_bidskpd, nm_bidskpd FROM mbidskpd where upper(kd_bidskpd) like upper('%$lccr%') or upper(nm_bidskpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bidskpd' => $resulte['kd_bidskpd'],  
                        'nm_bidskpd' => $resulte['nm_bidskpd'],  
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
	}
	
	function ambil_ubidskpd() {
              
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$skpd 			= $this->input->post('skpd');
        $unit  			= $this->input->post('unit');
        $oto  			= $this->session->userdata('otori');
        $uskpd 			= $this->session->userdata('unit_skpd');
		$uskpdx			= substr($uskpd,9,11);	
		$where1			= "";
		if($oto=='01'){
         $where1 = "where a.kd_skpd ='$skpd' ";
		}else{
			if($uskpdx=='01'){
			$where1 = "where a.kd_skpd ='$skpd' ";
			}else{
			$where1 = "where a.kd_lokasi ='$uskpd' ";
			}	          
		}
        $sql = "SELECT a.kd_lokasi, a.nm_lokasi,a.kd_skpd from mlokasi a $where1 order by kd_lokasi";
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
        $mskpd 			= $this->session->userdata('unit_skpd');
		$uskpdx			= substr($mskpd,9,11);	
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_lokasi='$uskpd' ";
        }elseif($skpd=='1.02.01.00' || $skpd=='1.01.01.00'){
            $where1 = "where kd_skpd='$skpd' ";
		}
		else{
            $where1 = "where kd_lokasi ='$uskpd' ";
        }
/* 		if($oto=='01'){
         $where1 = "where a.kd_skpd ='$skpd' ";
		}else{
			if($uskpdx=='01'){
			$where1 = "where a.kd_skpd ='$skpd' ";
			}else{
			$where1 = "where a.kd_lokasi ='$uskpd' ";
			}	          
		} */
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
		
        $sql = "SELECT IFNULL(MAX(no_urut),0)+1 AS max_kode FROM mruang where kd_unit='$skpd'";
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
            $csql1 = "where skpd = '$lccr' and ckey='PA'"; //and tingkat='1'
        }else {
            $csql1 = "where skpd = '$lccr' and ckey='PA'"; //and tingkat='1'
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
            $csql1 = "where unit = '$lccr' and ckey='PA'";
        }else {
            $csql1 = "where unit = '$lccr' and ckey='PA'";
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
            $csql1 = "where skpd = '$lccr' and ckey='PB'"; //and tingkat='1'
        }else {
            $csql1 = "where skpd = '$lccr' and ckey='PB'"; //and tingkat='1'
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
            $csql1 = "where unit = '$lccr' and ckey='PB'";
        }else {
            $csql1 = "where unit = '$lccr' and ckey='PB'";
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
        $lccr = $this->input->post('q');    
        $sql = "SELECT kd_comp, nm_comp FROM mcompany where upper(nm_comp) like upper('%$lccr%') order by nm_comp,kd_comp ";
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
        $sql = "SELECT tahun FROM tahun order by tahun desc";
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
        $gol  = $this->input->post('subkel');
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
 
 $sql = "SELECT a.*,b.nm_rek5 FROM mbarang a join mrek5 b on a.kd_rek5=b.kd_rek5
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
