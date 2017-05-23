<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//rebuild novar kahfi 2016
    
class Transaksi_int extends CI_Controller {
        
	public function __construct() {
		parent::__construct();
	}
	public function index($data) {
    	if($this->auth->is_logged_in() == false){
        	redirect(site_url().'/welcome/login');
      	}else{
         	$this->template->set('title','.::SIMBAKDA::.');
         	$this->template->load('index',$data['tabel'],$data['isi']);		
		}	
	}

    function input_neraca()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT MASTER SUB KELOMPOK';
        $this->template->set('title', 'INPUT NERACA');   
        $this->template->load('index','transaksi/input_neraca',$data) ;
        } 
    }
    
    function input_kdp()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'INPUT KDP';
        $this->template->set('title', 'INPUT KDP');   
        $this->template->load('index','transaksi/input_kdp',$data) ;
        } 
    }

	
    function upload()
	{
		$data['page_title']= 'UPLOAD FOTO';
        $this->template->set('title', 'UPLOAD FOTO');   
        $this->template->load('index','upload_form',$data) ;   
        //$this->load->view('upload_form', array('error' => ' ' ));
	}
     ////// Fomulir Pengadaan Barang ///////
    function fomulir_pengadaan_barang(){
   	    if($this->auth->is_logged_in() == false) {
        	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'TRANSAKSI FOMULIR PENGADAAN BARANG';
        $this->template->set('title', 'TRANSAKSI FOMULIR PENGADAAN BARANG');   
        $this->template->load('index','transaksi_int/tr_isian_barang',$data) ;    
        }     
    }

	function update_data()
    {
   	    if($this->auth->is_logged_in() == false) {
        	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'UPDATE DATA';
        $this->template->set('title', 'UPDATE DATA');   
        $this->template->load('index','transaksi_int/update_data',$data) ;    
        }

	}
	
	function proses_update_data_realisasi($bulan=''){
		$thn  	  = $this->session->userdata('ta_simbakda');
		$skpd     = $this->session->userdata('skpd');
		$this->db->query("exec transfer_realisasi_simakda '$skpd', $thn, $bulan");
		echo '1';	
	}
	
	function proses_update_data_kapitalisasi(){
		$thn  	  = $this->session->userdata('ta_simbakda');
		$skpd     = $this->session->userdata('skpd');
		$this->db->query("exec data_kapitalisasi '$skpd', $thn");
		echo '1';	
	}

	function proses_update_kapitalisasi_aset(){
		$thn  	  = $this->session->userdata('ta_simbakda');
		$skpd     = $this->session->userdata('skpd');
		$this->db->query("exec transfer_kapitalisasi_simakda '$skpd'");

		echo '1';	
	}
	
	function proses_update_mutasi_hapus(){
		$thn  	  = $this->session->userdata('ta_simbakda');
		$skpd     = $this->session->userdata('skpd');
		$this->db->query("exec data_mutasi_hapus '$skpd', $thn");

		echo '1';	
	}
	
     function load_neraca() {
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
            $where="where a.kd_skpd like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM nrc_aset a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT a.* , (select nm_skpd from mskpd where kd_skpd = a.kd_skpd) as nm_skpd  
        FROM nrc_aset a $where limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        //$result = array();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'n_nrc' => $resulte['n_nrc'],
                        'keterangan' => $resulte['keterangan'],
                        'tahun' => $resulte['tahun'],
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
    
    function load_kdp() {
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
            $where="where a.kd_uskpd like'%$kriteria%'";            
        }
        
        $rs = $this->db->query("select count(*) as tot FROM trh_kdp a $where");
        $trh = $rs->row();
        //$result["total"] = $trh->tot;
        
        $sql = "SELECT a.* , (select nm_uskpd from unit_skpd where kd_uskpd = a.kd_uskpd) as nm_uskpd  
        FROM trh_kdp a $where limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba[] = array(
                        'id' => $ii,        
                        'no_kontrak' => $resulte['no_kontrak'],
                        'tgl_kontrak' => $resulte['tgl_kontrak'],
                        'kd_uskpd' => $resulte['kd_uskpd'],
                        'nm_uskpd' => $resulte['nm_uskpd'],
                        'tahun' => $resulte['tahun'],
                        'nilai_kontrak' => $resulte['nilai_kontrak']                                                                                     
                        );
                        $ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
    

    function load_dkdp() {
        $kriteria = $this->input->post('nokon');
        
        $sql = "SELECT a.*,(select nm_brg from mbarang where kd_brg = a.kd_brg) as nm_brg 
        from trd_kdp a where a.no_kontrak = '$kriteria' order by a.no_kontrak";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'no_kontrak' => $resulte['no_kontrak'],
                        'kd_brg' => $resulte['kd_brg'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'no_sp2d' =>  $resulte['no_sp2d'],
                        'tgl_sp2d' =>  $resulte['tgl_sp2d'],
                        'nilai_sp2d' =>  $resulte['nilai_sp2d'],
                        's_dana' =>  $resulte['s_dana'],
                        'nm_brg' =>  $resulte['nm_brg']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
     

///////////////////////////////////////////////////////////////////////////////////////

    function update_sisa(){
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        }
    }


//////////////////////////////////////////////////////////

function trh_isianbrg(){
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_uskpd like '%' ";
        }else{
            $where1 = "where a.kd_uskpd ='$skpd'";
        }
        $result 	= array();
        $row 		= array();
      	$page 		= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset 	= ($page-1)*$rows;        
        $kriteria 	= $this->input->post('cari');
        $where 		='';
        if ($kriteria <> ''){                               
            $where="and (upper(a.no_dokumen) like upper('%$kriteria%') or a.tgl_dokumen like '%$kriteria%' or upper(a.kd_comp) like upper('%$kriteria%')) ";            
        }
        		
        $sql = "SELECT count(*) as total from trh_isianbrg a $where1 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select a.*,b.nm_comp,c.nm_milik,SUM(d.total)AS totalsel,d.invent,d.kd_kegiatan,d.kd_rek5  
				 from trh_isianbrg a left join mcompany b on a.kd_comp=b.kd_comp 
                 left join mmilik c on a.kd_milik = c.kd_milik JOIN trd_isianbrg d ON a.no_dokumen=d.no_dokumen AND a.kd_uskpd=d.kd_uskpd $where1 $where 
				 GROUP BY a.no_dokumen,a.kd_comp,a.tgl_dokumen,a.kd_milik,a.kd_wilayah,
a.kd_unit,a.kd_uskpd,a.s_dana,a.s_ang,a.b_dasar,a.b_nomor,a.tahun,
a.kd_kegiatan,a.b_tanggal,a.b_tahun,a.nilai_kontrak,a.nilai_apbd,
a.username,a.tgl_update,a.total,a.kd_cr_oleh,a.tglspm,a.nospm,b.nm_comp,c.nm_milik,d.invent,d.kd_kegiatan,d.kd_rek5
				 order by tgl_dokumen,no_dokumen,a.kd_comp";
        $query1 = $this->db->query($sql);          
        $ii = 0;
		$sisa = 0;
        foreach($query1->result_array() as $resulte)
        {            
		
			$sisa = $resulte['nilai_kontrak'] - $resulte['total']; 
		
            $row[] = array(                        
                        'no_dokumen'   => $resulte['no_dokumen'],
                        'tgl_dokumen'  => $resulte['tgl_dokumen'],
                        'kd_comp'      => $resulte['kd_comp'],
                        'nm_comp'      => $resulte['nm_comp'],
                        'kd_milik'     => $resulte['kd_milik'],
                        'nm_milik'     => $resulte['nm_milik'],
                        'kd_wilayah'   => $resulte['kd_wilayah'],
                        'kd_unit'      => $resulte['kd_unit'],
                        'kd_uskpd'     => $resulte['kd_uskpd'],//kd_uskpd
                        'jns_dana'     => $resulte['s_dana'],//jns_dana
                        'bukti_byr'    => $resulte['s_ang'],//bukti_byr
                        'dasar_oleh'   => $resulte['b_dasar'],//dasar_oleh
                        'no_oleh'      => $resulte['b_nomor'],//no_oleh'
                        'tahun_ang'    => $resulte['tahun'],//tahun_ang
                        'tgl_oleh'     => $resulte['b_tanggal'],//tgl_oleh
                        'tahun_oleh'   => $resulte['b_tahun'],
                        'nilai_kontrak'=> $resulte['nilai_kontrak'],
						'nilai_kontrak2'=> number_format($resulte['nilai_kontrak']),
                        'nilai_apbd'   => $resulte['nilai_apbd'],                        
                        'total'        => $resulte['totalsel'],
						'sisa'        => $sisa,//$resulte['sisa'],
						'sisa2'        => number_format($sisa),//number_format($resulte['sisa']),
                        'kd_cr_oleh'   => $resulte['kd_cr_oleh'],
                        'invent'       => $resulte['invent'],
						'kd_kegiatan'       => $resulte['kd_kegiatan']						
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
	}


 
    function trh_isikib(){
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_uskpd like '%' ";
        }else{
            $where1 = "where a.kd_uskpd ='$skpd'";
        }
        $result 	= array();
        $row 		= array();
      	$page 		= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset 	= ($page-1)*$rows;        
        $kriteria 	= $this->input->post('cari');
        $where 		='';
        if ($kriteria <> ''){                               
            $where="and (upper(a.no_dokumen) like upper('%$kriteria%') or a.tgl_dokumen like '%$kriteria%' or upper(a.kd_comp) like upper('%$kriteria%')) ";            
        }
        		
        $sql = "SELECT count(*) as total from trh_isianbrg a $where1 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select a.*,b.nm_comp,c.nm_milik,SUM(d.total)AS totalsel,d.invent,d.kd_kegiatan,d.kd_rek5  
				 from trh_isianbrg a left join mcompany b on a.kd_comp=b.kd_comp 
                 left join mmilik c on a.kd_milik = c.kd_milik JOIN trd_isianbrg d ON a.no_dokumen=d.no_dokumen AND a.kd_uskpd=d.kd_uskpd $where1 $where 
				 GROUP BY a.no_dokumen,a.kd_comp,a.tgl_dokumen,a.kd_milik,a.kd_wilayah,
a.kd_unit,a.kd_uskpd,a.s_dana,a.s_ang,a.b_dasar,a.b_nomor,a.tahun,
a.kd_kegiatan,a.b_tanggal,a.b_tahun,a.nilai_kontrak,a.nilai_apbd,
a.username,a.tgl_update,a.total,a.kd_cr_oleh,b.nm_comp,c.nm_milik,d.invent,d.kd_kegiatan,d.kd_rek5
				 order by tgl_dokumen,no_dokumen,a.kd_comp";
        $query1 = $this->db->query($sql);          
        $ii = 0;
		$sisa = 0;
        foreach($query1->result_array() as $resulte)
        {            
		
			$sisa = $resulte['nilai_kontrak'] - $resulte['total']; 
		
            $row[] = array(                        
                        'no_dokumen'   => $resulte['no_dokumen'],
                        'tgl_dokumen'  => $resulte['tgl_dokumen'],
                        'kd_comp'      => $resulte['kd_comp'],
                        'nm_comp'      => $resulte['nm_comp'],
                        'kd_milik'     => $resulte['kd_milik'],
                        'nm_milik'     => $resulte['nm_milik'],
                        'kd_wilayah'   => $resulte['kd_wilayah'],
                        'kd_unit'      => $resulte['kd_unit'],
                        'kd_uskpd'     => $resulte['kd_uskpd'],//kd_uskpd
                        'jns_dana'     => $resulte['s_dana'],//jns_dana
                        'bukti_byr'    => $resulte['s_ang'],//bukti_byr
                        'dasar_oleh'   => $resulte['b_dasar'],//dasar_oleh
                        'no_oleh'      => $resulte['b_nomor'],//no_oleh'
                        'tahun_ang'    => $resulte['tahun'],//tahun_ang
                        'tgl_oleh'     => $resulte['b_tanggal'],//tgl_oleh
                        'tahun_oleh'   => $resulte['b_tahun'],
                        'nilai_kontrak'=> $resulte['nilai_kontrak'],
						'nilai_kontrak2'=> number_format($resulte['nilai_kontrak']),
                        'nilai_apbd'   => $resulte['nilai_apbd'],                        
                        'total'        => $resulte['totalsel'],
						'sisa'        => $sisa,//$resulte['sisa'],
						'sisa2'        => number_format($sisa),//number_format($resulte['sisa']),
                        'kd_cr_oleh'   => $resulte['kd_cr_oleh'],
                        'invent'       => $resulte['invent'],
						'kd_kegiatan'       => $resulte['kd_kegiatan']						
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
	}








///////////////////////////////////////////////////////////////////////////////////////
    function dsimpan_trd_delete_dh()
    {
       $skpd    = $this->input->post('cskpd');
       $nomor   = $this->input->post('no');
       $kd_brg  = $this->input->post('kd_brg');
       $kd_unit = $this->input->post('kdunit');
   
           $sql = "delete from trd_isianbrg where no_dokumen = '$nomor' and kd_uskpd = '$skpd' and kd_brg='$kd_brg' and kd_unit='$kd_unit'";
           $asg = $this->db->query($sql);
            if ($asg > 0) { 
                $sql=$this->db->query("SELECT SUM(total) AS total from trd_isianbrg 
                        where no_dokumen = '$nomor' and kd_uskpd = '$skpd'");
                foreach($sql->result() as $row){
                    $total=$row->total;
                    $asg = $this->db->query("update trh_isianbrg set total ='$total' where no_dokumen='$nomor' and kd_uskpd='$skpd'");
                }
                 echo '1' ;
                 exit();
            } else {
                 echo '0' ;
                 exit();
            }
    }
        
     function trd_isianbrg(){
		$skpd  = $this->session->userdata('skpd');
        $oto   = $this->session->userdata('otori');
        $nomor = $this->input->post('no');	
 
       $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where b.kd_uskpd like '%' ";
        }else{
            $where1 = "where b.kd_uskpd ='$skpd' ";
        }
		/*$csql = "SELECT SUM(total) AS total from trd_isianbrg 
		where no_dokumen = '$nomor' and kd_uskpd = '$skpd'"; 
        $rs   = $this->db->query($csql)->row() ;*/
		
        /*$sql = "SELECT a.no_dokumen,jns,c.nm_golongan,kd_brg,a.kd_unit,a.kd_uskpd,nm_brg,a.s_dana,no_sp2d,tgl_sp2d,nilai_sp2d,a.nilai_kontrak,a.kd_kegiatan,
                kd_rek5,jumlah,harga,ppn,a.total,keterangan,invent FROM trd_isianbrg a INNER JOIN trh_isianbrg b ON 
                a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd and a.kd_unit=b.kd_unit LEFT JOIN mgolongan c ON a.jns=c.golongan $where1 and b.no_dokumen = '$nomor'";*/
        $sql="SELECT a.* FROM trd_isianbrg a JOIN trh_isianbrg b ON a.no_dokumen=b.no_dokumen AND a.kd_uskpd=b.kd_uskpd WHERE a.no_dokumen='$nomor'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'idx'           => $ii,                                
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'kd_unit'       => $resulte['kd_unit'],
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'jns_barang'    => $resulte['jns_barang'],
                        'nm_jenis'      => $resulte['nm_jenis'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_bidang'     => $resulte['nm_bidang'],
                        'kd_brg'        => $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'total'         => $resulte['total'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'tgl_sp2d'      => $resulte['tgl_sp2d'],
                        'nilai_sp2d'    => $resulte['nilai_sp2d'],
                        'keterangan'    => $resulte['keterangan'],
                        'invent'        => $resulte['invent'],
                        'ppn'           => $resulte['ppn'],
                        'cad'           => $resulte['cad'],
                        's_dana'        => $resulte['s_dana'],
                        'jns'           => $resulte['jns'],
                        'nilai_kontrak' => $resulte['nilai_kontrak']

                                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    function load_sum_trd_isianbrg(){
        $skpd  = $this->input->post('kduskpd');
        $nomor = $this->input->post('no');
        $query1 = $this->db->query("SELECT SUM(total) AS total from trd_isianbrg 
        where no_dokumen = '$nomor' and kd_uskpd = '$skpd'"); 
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['total'],2,'.',',')
                                              
                        );
                        $ii++;
        }
           
           echo json_encode($result);
           $query1->free_result();  
    }
    function nomor(){
        $tabel 	=$this->input->post('tabel');      
        $kdskpd =$this->input->post('kd_unit');                    
        $sql = "SELECT IFNULL((MAX(no_urut)+1),1) as no_urut,IFNULL(MAX(no_reg),0) as no_reg FROM $tabel WHERE kd_unit='$kdskpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'urut'    	=> $resulte['no_urut'],      
                        'no_reg'    => $resulte['no_reg']                                                                                                                                                                  
                        );
                        $ii++;
        }           
        echo json_encode($result);
    }
	
	function mlokasi_urut(){
        $tabel 	= $this->input->post('tabel');      
        $kdskpd = $this->input->post('kd_unit');      
        $urut 	= $this->input->post('urut');     
        $reg 	= $this->input->post('reg');      
        $kd_brg	= $this->input->post('brg');                  
        //$sql 	= "SELECT IFNULL((MAX($urut)+1),1) as no_urut,IFNULL((MAX($reg)+1),1) as no_reg 
		//		   FROM $tabel WHERE kd_unit='$kdskpd' and kd_brg='$kd_brg'";
        $sql    = "SELECT IF(MAX($urut)IS NULL,LPAD('0',6,0),LPAD(MAX($urut)+0,7,0)) as no_urut,
                   IF(MAX($reg)IS NULL,LPAD('0',6,0),LPAD(MAX($reg)+0,7,0))AS no_reg FROM $tabel WHERE kd_unit='$kdskpd' and kd_brg='$kd_brg'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'urut'    	=> $resulte['no_urut'],      
                        'no_reg'    => $resulte['no_reg']                                                                                                                                                                  
                        );
                        $ii++;
        }           
        echo json_encode($result);
    }
      
	  function umur_brg(){
        $tabel =$this->input->post('tabel');      
        $kd_brg =$this->input->post('kd_brg');      
        $id =$this->input->post('id');                  
        $sql = "SELECT * FROM $tabel WHERE $id=LEFT('$kd_brg',8)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'umur'    => $resulte['umur']                                                                                                                                                                  
                        );
                        $ii++;
        }           
        echo json_encode($result);
    }
	
    function simpan_isianbrg(){
        $plonline  = $this->session->userdata('plonline');

        $tabel     = $this->input->post('tabel');
        $nomor     = $this->input->post('no'); 
        //$kdbrg   = $this->input->post('kdbrg'); 
        $unit      = $this->input->post('kd_unit'); 
        //$kolom   = $this->input->post('rows');         
        $csql      = $this->input->post('sql'); 
        $username  = $this->session->userdata('nmuser');
        $tglupdate = date('Y-m-d');      
        $msg       = array();
        $tgl       = $this->input->post('tgl');
        $nilkon    = $this->input->post('nilkon');
        $nilapbd   = $this->input->post('nilapbd');
		$csql_sisa = $this->input->post('csql_sisa');
		$nomorspm   = $this->input->post('nomorspm');
		$tglspm = $this->input->post('tglspm');
		
        if ($plonline=='1')
        {
            $kdcomp1 = $this->input->post('kdcomp'); 
            $sql    = "SELECT kd_comp,nm_comp from mcompany where kd_comp='$kdcomp1'";
            $asg    = $this->db->query($sql);
            foreach ($asg->result() as $row) {
                $kdcomp = $row->kd_comp;
            }
        
        }else{
            $kdcomp = $this->input->post('kdcomp');
        }

        $kdmilik   = $this->input->post('kdmilik');
        $kdwilayah = $this->input->post('kdwilayah');
        $mlokasi   = $this->input->post('mlokasi');
        $jnsdana   = $this->input->post('jnsdana');
        $tahunang  = $this->input->post('tahunang');
        $buktibyr  = $this->input->post('buktibyr');
        $dasaroleh = $this->input->post('dasaroleh');
        $nooleh    = $this->input->post('nooleh');
        $tgloleh   = $this->input->post('tgloleh');
        $tahunoleh = $this->input->post('tahunoleh');
        $tot       = $this->input->post('tot');
        $cr_oleh   = $this->input->post('cr_oleh');

        if ($tabel == 'trh_isianbrg') {
            $sql = "delete from trh_isianbrg where no_dokumen='$nomor' and kd_uskpd='$unit'";
            $asg = $this->db->query($sql);
            if ($asg){                
                $sql2 = "insert into trh_isianbrg(no_dokumen,kd_comp  ,tgl_dokumen,kd_milik  ,kd_wilayah  ,kd_unit   ,kd_uskpd,s_dana    ,s_ang      ,b_dasar     ,b_nomor  ,tahun       ,b_tanggal ,b_tahun     ,nilai_kontrak,nilai_apbd,username   ,tgl_update  ,total  ,kd_cr_oleh, nospm,tglspm)
                                         VALUES ('$nomor'  ,'$kdcomp','$tgl','$kdmilik','$kdwilayah','$mlokasi','$unit' ,'$jnsdana','$buktibyr','$dasaroleh','$nooleh','$tahunoleh','$tgloleh','$tahunoleh','$nilkon'    ,'$nilapbd','$username','$tglupdate','$tot','$cr_oleh','$nomorspm','$tglspm')";                        
                $asg3 = $this->db->query($sql2);			
				if($asg3){
					
					$cek = $this->db->query("select count(stt) as n from trhtransout_sisa where kd_skpd='$unit' and no_dokumen='$nomor'")->row();
					$countcek = $cek->n;
					
					if($countcek==1){																						
						$sisa = $nilkon - $tot;
						
						$sql4 = "update trhtransout_sisa set nilai='$tot',nilai_sisa='$sisa' where kd_skpd='$unit' and no_dokumen='$nomor'";
											   $asg4 = $this->db->query($sql4);
											   if($asg4){
													$msg = array('pesan'=>'1');
													echo json_encode($msg);
											   }else{
												    $msg = array('pesan'=>'0');
													echo json_encode($msg);
											   }	
					}else{
						
						$sql4 = "insert into trhtransout_sisa (no_dokumen,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,nilai_sisa,kd_skpd,stt,nilai_kontrak)";
											   $asg4 = $this->db->query($sql4.$csql_sisa);
											   if($asg4){
													$msg = array('pesan'=>'1');
													echo json_encode($msg);
											   }else{
												    $msg = array('pesan'=>'0');
													echo json_encode($msg);
											   }	
					}
										
				}	
            } else {				
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        } else if ($tabel == 'trd_isianbrg') {
            
			$nomor     = $this->input->post('no'); 
            // Simpan Detail //                       
                $sql = "delete from trd_isianbrg where no_dokumen='$nomor'  ";
                $asg = $this->db->query($sql);
                if ($asg){        
					
					$sql = "insert into trd_isianbrg(no_dokumen,kd_unit,kd_uskpd,jns_barang,nm_jenis,kd_bidang,nm_bidang,kd_brg,nm_brg,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,jumlah,harga,total,no_sp2d,tgl_sp2d,nilai_sp2d,keterangan,invent,ppn,cad,s_dana,jns,nilai_kontrak)"; 
                        $asg2 = $this->db->query($sql.$csql);
                         	    if ($asg2){
											   //$sql2 = "insert into trhtransout_sisa (no_dokumen,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,nilai_sisa,kd_skpd,stt,nilai_kontrak)";
											   //$asg2 = $this->db->query($sql2.$csql_sisa);
											   //if($asg2){
													$msg = array('pesan'=>'1');
													echo json_encode($msg);
											   //}else{
												    //$msg = array('pesan'=>'0');
													//echo json_encode($msg);
											   //}											   
												//exit();
											}  else {
											   $msg = array('pesan'=>'0');
												echo json_encode($msg);
											}
											
                }else{                                                        
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }        				
			} 
		//echo json_encode($msg);          
    } 
	
	
	//////////////////////////////////////////////////////////////////////////
	
	    function update_penyu(){
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        }
    }
	
	/////////////////////
	
	
	    function simpan_penyu(){
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

	
	///////////////////////////////////////////////////////////////////////
	
    function hapus_kib(){
        $no_mutasi          = $this->input->post('nomut');
        $tgl_mut            = $this->input->post('tgl_mut');
        $riwayat            = $this->input->post('riwayat');
        $nmuskpdb           = $this->input->post('nmuskpdb');
        $no_reg             = $this->input->post('no_reg');
        $id_barang          = $this->input->post('id_barang');
        $no                 = $this->input->post('no');
        $no_oleh            = $this->input->post('no_oleh');
        $tgl_reg            = $this->input->post('tgl_reg');
        $tgl_oleh           = $this->input->post('tgl_oleh');
        $no_dokumen         = $this->input->post('no_dokumen');
        $kd_brg             = $this->input->post('kd_brg');
        $nm_brg             = $this->input->post('nm_brg');
        $detail_brg         = $this->input->post('detail_brg');
        $nilai              = $this->input->post('nilai');
        $asal               = $this->input->post('asal');
        $dsr_peroleh        = $this->input->post('dsr_peroleh');
        $jumlah             = $this->input->post('jumlah');
        $total              = $this->input->post('total');
        $merek              = $this->input->post('merek');
        $tipe               = $this->input->post('tipe');
        $pabrik             = $this->input->post('pabrik');
        $kd_warna           = $this->input->post('kd_warna');
        $kd_bahan           = $this->input->post('kd_bahan');
        $kd_satuan          = $this->input->post('kd_satuan');
        $no_rangka          = $this->input->post('no_rangka');
        $no_mesin           = $this->input->post('no_mesin');
        $no_polisi          = $this->input->post('no_polisi');
        $silinder           = $this->input->post('silinder');
        $no_stnk            = $this->input->post('no_stnk');
        $tgl_stnk           = $this->input->post('tgl_stnk');
        $no_bpkb            = $this->input->post('no_bpkb');
        $tgl_bpkb           = $this->input->post('tgl_bpkb');
        $kondisi            = $this->input->post('kondisi');
        $tahun_produksi     = $this->input->post('tahun_produksi');
        $dasar              = $this->input->post('dasar');
        $no_sk              = $this->input->post('no_sk');
        $tgl_sk             = $this->input->post('tgl_sk');
        $keterangan         = $this->input->post('keterangan');
        //$no_mutasi  = $this->input->post('no_mutasi');
        $tgl_mutasi         = $this->input->post('tgl_mutasi');
        $no_pindah          = $this->input->post('no_pindah');
        $tgl_pindah         = $this->input->post('tgl_pindah');
        $no_hapus           = $this->input->post('no_hapus');
        $tgl_hapus          = $this->input->post('tgl_hapus');
        $kd_ruang           = $this->input->post('kd_ruang');
        $kd_lokasi2         = $this->input->post('kd_lokasi2');
        $kd_skpd            = $this->input->post('kd_skpd');
        $kd_unit            = $this->input->post('kd_unit');
        $kd_skpd_lama       = $this->input->post('kd_skpd_lama');
        $milik              = $this->input->post('milik');
        $wilayah            = $this->input->post('wilayah');
        $username           = $this->session->userdata('nmuser');//$this->input->post('username')
        $tgl_update         = date('y-m-d H:i:s');//$this->input->post('tgl_update');
        $tahun              = $this->input->post('tahun');
        $foto               = $this->input->post('foto');
        $foto2              = $this->input->post('foto2');
        $foto3              = $this->input->post('foto3');
        $foto4              = $this->input->post('foto4');
        $foto5              = $this->input->post('foto5');
        $no_urut            = $this->input->post('no_urut');
        $metode             = $this->input->post('metode');
        $masa_manfaat       = $this->input->post('masa_manfaat');
        $nilai_sisa         = $this->input->post('nilai_sisa');
        $kd_riwayat         = $this->input->post('kd_riwayat');
        $tgl_riwayat        = $this->input->post('tgl_riwayat');
        $detail_riwayat     = $this->input->post('detail_riwayat');
        $status_tanah       = $this->input->post('status_tanah');
        $no_sertifikat      = $this->input->post('no_sertifikat');
        $tgl_sertifikat     = $this->input->post('tgl_sertifikat');
        $luas               = $this->input->post('luas');
        $penggunaan         = $this->input->post('penggunaan');
        $alamat1            = $this->input->post('alamat1');
        $alamat2            = $this->input->post('alamat2');
        $alamat3            = $this->input->post('alamat3');
        $lat                = $this->input->post('lat');
        $lon                = $this->input->post('lon');
        $luas_gedung        = $this->input->post('luas_gedung');
        $jenis_gedung       = $this->input->post('jenis_gedung');
        $luas_tanah         = $this->input->post('luas_tanah');
        $konstruksi         = $this->input->post('konstruksi');
        $konstruksi2        = $this->input->post('konstruksi2');
        $luas_lantai        = $this->input->post('luas_lantai');
        $kd_tanah           = $this->input->post('kd_tanah');
        $hibah              = $this->input->post('hibah');
        $panjang            = $this->input->post('panjang');
        $lebar              = $this->input->post('lebar');
        $perolehan          = $this->input->post('perolehan');
        $judul              = $this->input->post('judul');
        $spesifikasi        = $this->input->post('spesifikasi');
        $cipta              = $this->input->post('cipta');
        $tahun_terbit       = $this->input->post('tahun_terbit');
        $penerbit           = $this->input->post('penerbit');
        $jenis              = $this->input->post('jenis');
        $bangunan           = $this->input->post('bangunan');
        $tgl_awal_kerja     = $this->input->post('tgl_awal_kerja');
        $nilai_kontrak      = $this->input->post('nilai_kontrak');
        $kd_golongan        = $this->input->post('kd_golongan');
        $kd_bidang          = $this->input->post('kd_bidang');
        $pemeliharaan_ke    = $this->input->post('pemeliharaan_ke');

        $cno_reg            = explode('||',$no_reg);
        $cid_barang         = explode('||',$id_barang);
        $cno                = explode('||',$no);
        $cno_oleh           = explode('||',$no_oleh);
        $ctgl_reg           = explode('||',$tgl_reg);
        $ctgl_oleh          = explode('||',$tgl_oleh);
        $cno_dokumen        = explode('||',$no_dokumen);
        $ckd_brg            = explode('||',$kd_brg);
        $nm_brg             = explode('||',$nm_brg);
        $cdetail_brg        = explode('||',$detail_brg);
        $cnilai             = explode('||',$nilai);
        $casal              = explode('||',$asal);
        $cdsr_peroleh       = explode('||',$dsr_peroleh);
        $cjumlah            = explode('||',$jumlah);
        $ctotal             = explode('||',$total);
        $cmerek             = explode('||',$merek);
        $ctipe              = explode('||',$tipe);
        $cpabrik            = explode('||',$pabrik);
        $ckd_warna          = explode('||',$kd_warna);
        $ckd_bahan          = explode('||',$kd_bahan);
        $ckd_satuan         = explode('||',$kd_satuan);
        $cno_rangka         = explode('||',$no_rangka);
        $cno_mesin          = explode('||',$no_mesin);
        $cno_polisi         = explode('||',$no_polisi);
        $csilinder          = explode('||',$silinder);
        $cno_stnk           = explode('||',$no_stnk);
        $ctgl_stnk          = explode('||',$tgl_stnk);
        $cno_bpkb           = explode('||',$no_bpkb);
        $ctgl_bpkb          = explode('||',$tgl_bpkb);
        $ckondisi           = explode('||',$kondisi);
        $ctahun_produksi    = explode('||',$tahun_produksi);
        $cdasar             = explode('||',$dasar);
        $cno_sk             = explode('||',$no_sk);
        $ctgl_sk            = explode('||',$tgl_sk);
        $cketerangan        = explode('||',$keterangan);
        $cno_mutasi         = explode('||',$no_mutasi);
        $ctgl_mutasi        = explode('||',$tgl_mutasi);
        $cno_pindah         = explode('||',$no_pindah);
        $ctgl_pindah        = explode('||',$tgl_pindah);
        $cno_hapus          = explode('||',$no_hapus);
        $ctgl_hapus         = explode('||',$tgl_hapus);
        $ckd_ruang          = explode('||',$kd_ruang);
        $ckd_lokasi2        = explode('||',$kd_lokasi2);
        /* $ckd_skpd  = explode('||',$kd_skpd);
        $ckd_unit  = explode('||',$kd_unit);
        $ckd_skpd_lama  = explode('||',$kd_skpd_lama); */
        $cmilik             = explode('||',$milik);
        $cwilayah           = explode('||',$wilayah);
        $cusername          = explode('||',$username);
        $ctgl_update        = explode('||',$tgl_update);
        $ctahun             = explode('||',$tahun);
        $cfoto              = explode('||',$foto);
        $cfoto2             = explode('||',$foto2);
        $cfoto3             = explode('||',$foto3);
        $cfoto4             = explode('||',$foto4);
        $cfoto5             = explode('||',$foto5);
        $cno_urut           = explode('||',$no_urut);
        $cmetode            = explode('||',$metode);
        $cmasa_manfaat      = explode('||',$masa_manfaat);
        $cnilai_sisa        = explode('||',$nilai_sisa);
        $ckd_riwayat        = explode('||',$kd_riwayat);
        $ctgl_riwayat       = explode('||',$tgl_riwayat);
        $cdetail_riwayat    = explode('||',$detail_riwayat);
        $cstatus_tanah      = explode('||',$status_tanah);
        $cno_sertifikat     = explode('||',$no_sertifikat);
        $ctgl_sertifikat    = explode('||',$tgl_sertifikat);
        $cluas              = explode('||',$luas);
        $cpenggunaan        = explode('||',$penggunaan);
        $calamat1           = explode('||',$alamat1);
        $calamat2           = explode('||',$alamat2);
        $calamat3           = explode('||',$alamat3);
        $clat               = explode('||',$lat);
        $clon               = explode('||',$lon);
        $cluas_gedung       = explode('||',$luas_gedung);
        $cjenis_gedung      = explode('||',$jenis_gedung);
        $cluas_tanah        = explode('||',$luas_tanah);
        $ckonstruksi        = explode('||',$konstruksi);
        $ckonstruksi2       = explode('||',$konstruksi2);
        $cluas_lantai       = explode('||',$luas_lantai);
        $ckd_tanah          = explode('||',$kd_tanah);
        $chibah             = explode('||',$hibah);
        $cpanjang           = explode('||',$panjang);
        $clebar             = explode('||',$lebar);
        $cperolehan         = explode('||',$perolehan);
        $cjudul             = explode('||',$judul);
        $cspesifikasi       = explode('||',$spesifikasi);
        $ccipta             = explode('||',$cipta);
        $ctahun_terbit      = explode('||',$tahun_terbit);
        $cpenerbit          = explode('||',$penerbit);
        $cjenis             = explode('||',$jenis);
        $cbangunan          = explode('||',$bangunan);
        $ctgl_awal_kerja    = explode('||',$tgl_awal_kerja);
        $cnilai_kontrak     = explode('||',$nilai_kontrak);
        $ckd_golongan       = explode('||', $kd_golongan);
        $ckd_bidang         = explode('||', $kd_bidang);
        $cpemeliharaan_ke   = explode('||', $pemeliharaan_ke);
        $plonline           = $this->session->userdata('plonline');
        //echo $plonline;

        $pj=count($cno);
        
        /* Insert ke table mutasi_brg A-F  && mutasi di trkib A-F*/
            for($i=0;$i<$pj;$i++){
                if (trim($cno[$i])!=''){
                
                /*  $sql = "insert into mutasi_brg(no_mutasi,id_barang,no_urut,tgl_mutasi,no_reg,kd_brg,kd_unit,
                 kd_unitb,kd_skpdb,kondisi,asal,tahun_oleh,jumlah_awal,harga_awal,
                 jumlah_kurang,harga_kurang,jumlah_tambah,harga_tambah,keterangan,username,tgl_update,status) 
                         values('$no_mutasi','".$pid[$i]."','".$pno[$i]."','$tgl','".$pnoreg[$i]."','".$pkdbrg[$i]."','$uskpd',
                         '$uskpdb','$skpdb','".$pkondisi[$i]."','$uskpd','".$ptahun[$i]."','1','".$pharga[$i]."','','','','','$keterangan','','','')"; */
                //$sql ="delete from trd_mutasi where kd_brg='".$ckd_brg[$i]."' and kd_skpd='".$ckd_skpd[$i]."' and id_barang='".$cid_barang[$i]."' and nilai='".$cnilai[$i]."' ";
                
                //$asg = $this->db->query($sql);
                $kdbrg = substr($ckd_brg[$i],0,2);
                $id_baru =($cid_barang[$i].".".$kd_skpd);
                //if($sql){
                    if($kdbrg=='01'){
                    //$this->db->query("UPDATE trkib_a SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,status_tanah,kondisi,asal,dsr_peroleh,no_sertifikat,tgl_sertifikat,luas,nilai,jumlah,total,penggunaan,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_lokasi2,milik,wilayah,kd_skpd,kd_unit,kd_skpd_lama,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cstatus_tanah[$i]."','".$ckondisi[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$cno_sertifikat[$i]."','".$ctgl_sertifikat[$i]."',
                                      '".$cluas[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$ctotal[$i]."',
                                      '".$cpenggunaan[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                                        null,null,null,null,
                                      '$no_mutasi','$tgl_mut','".$cketerangan[$i]."','".$ckd_lokasi2[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','$kd_skpd','$kd_unit',
                                      '$kd_skpd_lama','$username','$tgl_update','".$ctahun[$i]."',
                                      '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."',
                                      '".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
                                      '$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");
                    }
                    if($kdbrg=='02'){
                    //$this->db->query("UPDATE trkib_b SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,dasar,no_sk,tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,foto3,foto4,foto5,no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                      '".$cjumlah[$i]."','".$ctotal[$i]."','".$cmerek[$i]."','".$ctipe[$i]."','".$cpabrik[$i]."',
                                      '".$ckd_warna[$i]."','".$ckd_bahan[$i]."','".$ckd_satuan[$i]."','".$cno_rangka[$i]."',
                                      '".$cno_mesin[$i]."','".$cno_polisi[$i]."','".$csilinder[$i]."','".$cno_stnk[$i]."',
                                      '".$ctgl_stnk[$i]."','".$cno_bpkb[$i]."','".$ctgl_bpkb[$i]."','".$ckondisi[$i]."',
                                      '".$ctahun_produksi[$i]."','".$cdasar[$i]."','".$cno_sk[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut','".$ckd_ruang[$i]."',
                                      '".$ckd_lokasi2[$i]."','$kd_skpd','$kd_unit','',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                      '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."',
                                      '".$cfoto5[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                      '".$cnilai_sisa[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");
                    }
                    if($kdbrg=='03'){
                    //$this->db->query("UPDATE trkib_c SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,jumlah,asal,dsr_peroleh,total,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,konstruksi,konstruksi2,luas_lantai,kondisi,dasar,tgl_sk,keterangan,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,kd_tanah,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,hibah,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$cluas_gedung[$i]."','".$cjenis_gedung[$i]."','".$cluas_tanah[$i]."','".$cstatus_tanah[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut','".$ckonstruksi[$i]."',
                                      '".$ckonstruksi2[$i]."','".$cluas_lantai[$i]."',
                                      '".$ckondisi[$i]."','".$cdasar[$i]."','".$ctgl_sk[$i]."','".$cketerangan[$i]."',
                                      '".$ckd_lokasi2[$i]."','$kd_skpd','$kd_unit','',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_tanah[$i]."','$username','$tgl_update',
                                      '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."',
                                      '".$cfoto4[$i]."','".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$cmetode[$i]."',
                                      '".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."','".$chibah[$i]."','".$ckd_riwayat[$i]."',
                                      '".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                    
                                      }
                    if($kdbrg=='04'){
                    //$this->db->query("UPDATE trkib_d SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$cstatus_tanah[$i]."','".$cpanjang[$i]."',
                                      '".$cluas[$i]."','".$clebar[$i]."','".$ckonstruksi[$i]."','".$calamat1[$i]."','".$calamat2[$i]."',
                                      '".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut','".$cperolehan[$i]."',
                                      '".$cdasar[$i]."','".$cjumlah[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '".$cpenggunaan[$i]."','$username','$tgl_update','".$ctahun[$i]."',
                                      '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                      '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
                                      '".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                 
                                      }
                    if($kdbrg=='05'){
                    //$this->db->query("UPDATE trkib_e SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                    VALUES ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
                                    '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                    '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                    '".$ctotal[$i]."','".$cjudul[$i]."','".$cspesifikasi[$i]."',
                                    '".$ccipta[$i]."','".$ctahun_terbit[$i]."','".$cpenerbit[$i]."','".$ckd_bahan[$i]."',
                                    '".$cjenis[$i]."','".$ctipe[$i]."','".$ckd_satuan[$i]."','".$cjumlah[$i]."',
                                    '".$ckondisi[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','',
                                    '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                    null,null,null,null,'$no_mutasi','$tgl_mut',
                                    '".$ckd_ruang[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                    '".$cfoto3[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                    '".$cnilai_sisa[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."',
                                    '".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                  
                                      }
                    if($kdbrg=='06'){
                    //$this->db->query("UPDATE trkib_f SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$ckonstruksi[$i]."','".$cjenis[$i]."','".$cbangunan[$i]."','".$cluas[$i]."',
                                      '".$cjumlah[$i]."','".$ctgl_awal_kerja[$i]."','".$cstatus_tanah[$i]."','".$cnilai_kontrak[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut',
                                      '".$cketerangan[$i]."','$kd_skpd','$kd_unit','','".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '$username','$tgl_update','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                      '".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
                                      '$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                
                                      }
                    }
                //}
            }
			if ($plonline =='1')
                {
                    $dbsimakda=$this->load->database('simakda', TRUE);    
                    $tahun = $this->session->userdata('ta_simbakda');
                    $no_mutasi_aset = $no_mutasi.'/HAPUS/SIMBAKDA';
                    

                    $sqlmap = "SELECT kd_reklo,nm_reklo,kd_rinci_objek,nm_rinci_objek FROM map_penghapusan where kd_barang='$kd_brg' ";
                    $asg2  = $this->db->query($sqlmap);
                    foreach($asg2->result() as $row)
                    {
                        $kd_reklo       =$row->kd_reklo;
                        $nm_reklo       =$row->nm_reklo;
                        $kd_rinci_objek =$row->kd_rinci_objek;
                        $nm_rinci_objek =$row->nm_rinci_objek;
                        //echo $kd_reklo;
                        
                        // $csql ="SELECT a.kd_brg AS kode,b.nm_brg,a.merek,a.tahun,a.`nilai`,TRIM(c.umur) AS umur,
                        // if(a.tahun='$tahun',0,($tahun-a.tahun)) AS th_lalu,$tahun AS th_ini,a.tahun,
                        // (a.nilai/TRIM(c.umur)) AS penyusutan_pertahun,

                        // IF(a.tahun='$tahun',0,(CASE 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
                        // END)) AS tot_th_belum, 

                        // IF(a.tahun='$tahun',0,(CASE 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
                        // WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
                        // END)) AS nil_th_ini


                        // FROM trkib_b a
                        // LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
                        // LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
                        // WHERE kd_barang<>'' AND a.kd_brg='$kd_brg' AND a.id_barang='$id_barang' AND tahun<='$tahun' ";
                        // $hasil = $this->db->query($csql);
                        // foreach ($hasil->result() as $row) {
                        //     $tot_th_belum = $row->tot_th_belum;
                        //     $nil_th_ini   = $row->nil_th_ini;
                        //     $akumulasi    = $tot_th_belum + $nil_th_ini;
                        //     $nilai        = $row->nilai;
                        //     $total_buku   = $nilai - $akumulasi;
                            
                        // }
                        $sqlkibb   = "SELECT sum(nil_th_ini) as nil_akum,nilai from kibb_susut where id_barang='$id_barang' ";
                        $asg2      = $this->db->query($sqlkibb);
                            foreach($asg2->result() as $row)
                            {
                                $nilai_akum = $row->nil_akum;
                                $nilai      = $row->nilai;
                                $nil_buku   = $nilai - $nilai_akum;
                                   
                            }     

                        $sqltrd ="insert into trdju_pkd (no_voucher,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                  values ('$no_mutasi_aset','$kd_skpd','','','1540101','Aset Lain-lain','$nil_buku','0','D','0','1','1') ";
                        $query1 = $dbsimakda->query($sqltrd);  

                        $sqltrd ="insert into trdju_pkd (no_voucher,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                  values ('$no_mutasi_aset','$kd_skpd','','','$kd_rinci_objek','$nm_rinci_objek','$nilai_akum','0','D','0','2','2') ";
                        $query1 = $dbsimakda->query($sqltrd);  

                        $sqltrd ="insert into trdju_pkd (no_voucher,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                  values ('$no_mutasi_aset','$kd_skpd','','','$kd_reklo','$nm_reklo','0','$nilai','K','0','3','3') ";
                        $query1 = $dbsimakda->query($sqltrd);  



                    }
                   
                }
        }
						
     function hapus_isianbrg(){
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
		$total  = $this->input->post('total');
        $msg = array();
        $sql = "delete from trd_isianbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
        $asg = $this->db->query($sql);
        if ($asg){            
				
				$sql = "delete from trh_isianbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
				$asg = $this->db->query($sql);
				
				if($asg){
					
					$sql = "delete from trhtransout_sisa where no_dokumen='$nomor' and kd_skpd='$skpd'";
					$asg = $this->db->query($sql);		
					
					if (!($asg)){
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
					}
				}					
									            
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
		
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }                
  
    
    ///////////Rencana Pengadaan Barang/////////
    function input_sisa_umur_b()
    {
        $data['page_title']= 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR KIB B';
        $this->template->set('title', 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR');   
        $this->template->load('index','transaksi/tr_sisa_b',$data) ;         
    }
       
	function input_sisa_umur_c()
    {
        $data['page_title']= 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR KIB B';
        $this->template->set('title', 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR');   
        $this->template->load('index','transaksi/tr_sisa_c',$data) ;         
    }
       
	   function input_sisa_umur_d()
    {
        $data['page_title']= 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR KIB B';
        $this->template->set('title', 'INPUT AKUMULASI PENYUSUTAN DAN SISA UMUR');   
        $this->template->load('index','transaksi/tr_sisa_d',$data) ;         
    }
       
	   
   function rencana_pengadaan_barang()
    {
        $data['page_title']= 'TRANSAKSI RENCANA PENGADAAN BARANG';
        $this->template->set('title', 'TRANSAKSI RENCANA PENGADAAN BARANG');   
        $this->template->load('index','transaksi/tr_pengadaan_barang',$data) ;         
    }
    
    function rencana_pelihara_barang()
    {
        $data['page_title']= 'TRANSAKSI RENCANA PEMELIHARAAN BARANG';
        $this->template->set('title', 'TRANSAKSI RENCANA PEMELIHARAAN BARANG');   
        $this->template->load('index','transaksi/tr_rencana_pemeliharaan_barang',$data) ;         
    }
	function pelihara_barang()
    {
        $data['page_title']= 'TRANSAKSI PEMELIHARAAN BARANG';
        $this->template->set('title', 'TRANSAKSI PEMELIHARAAN BARANG');   
        $this->template->load('index','transaksi/tr_pemeliharaan_barang',$data) ;         
    }
    
    function inventaris_a()
    {
        $data['page_title']= 'INVENTARISASI TANAH';
        $this->template->set('title', 'INVENTARISASI TANAH');   
        $this->template->load('index','transaksi/inventaris_a',$data) ;         
    }
	function inventaris_a_kap()
    {
        $data['page_title']= 'INVENTARISASI TANAH';
        $this->template->set('title', 'INVENTARISASI TANAH');   
        $this->template->load('index','transaksi/inventaris_a_kap',$data) ;         
    }
    function mutasi_a()
    {
        $data['page_title']= 'MUTASI INVENTARIS TANAH';
        $this->template->set('title', 'MUTASI INVENTARISASI TANAH');   
        $this->template->load('index','transaksi/mutasi_a',$data) ;         
    }    
    function inventaris_b()
    {
        $data['page_title']= 'INVENTARISASI MESIN';
        $this->template->set('title', 'INVENTARISASI MESIN');   
        $this->template->load('index','transaksi/inventaris_b',$data) ;         
    }
	 
	 function inventaris_b_kap()
    {
        $data['page_title']= 'INVENTARISASI MESIN';
        $this->template->set('title', 'INVENTARISASI MESIN');   
        $this->template->load('index','transaksi/inventaris_b_kap',$data) ;         
    }
    function mutasi_b()
    {
        $data['page_title']= 'MUTASI INVENTARIS MESIN';
        $this->template->set('title', 'MUTASI INVENTARISASI MESIN');   
        $this->template->load('index','transaksi/mutasi_b',$data) ;         
    } 
    function inventaris_c()
    {
        $data['page_title']= 'INVENTARISASI GEDUNG BANGUNAN';
        $this->template->set('title', 'INVENTARISASI GEDUNG BANGUNAN');   
        $this->template->load('index','transaksi/inventaris_c',$data) ;         
    }
    
    function inventaris_c_kap()
    {
        $data['page_title']= 'INVENTARISASI GEDUNG BANGUNAN';
        $this->template->set('title', 'INVENTARISASI GEDUNG BANGUNAN');   
        $this->template->load('index','transaksi/inventaris_c_kap',$data) ;         
    }
	function mutasi_c()
    {
        $data['page_title']= 'MUTASI INVENTARIS GEDUNG BANGUNAN';
        $this->template->set('title', 'MUTASI INVENTARISASI GEDUNG BANGUNAN');   
        $this->template->load('index','transaksi/mutasi_c',$data) ;         
    } 
    function inventaris_d()
    {
        $data['page_title']= 'INVENTARISASI JALAN,IRIGASI,JARINGAN';
        $this->template->set('title', 'INVENTARISASI JALAN,IRIGASI,JARINGAN');   
        $this->template->load('index','transaksi/inventaris_d',$data) ;         
    }
    
    function inventaris_d_kap()
    {
        $data['page_title']= 'INVENTARISASI JALAN,IRIGASI,JARINGAN';
        $this->template->set('title', 'INVENTARISASI JALAN,IRIGASI,JARINGAN');   
        $this->template->load('index','transaksi/inventaris_d_kap',$data) ;         
    }
	function mutasi_d()
    {
        $data['page_title']= 'MUTASI INVENTARIS JALAN,IRIGASI,JARINGAN';
        $this->template->set('title', 'MUTASI INVENTARISASI JALAN IRIGASI JARINGAN');   
        $this->template->load('index','transaksi/mutasi_d',$data) ;         
    } 
    function inventaris_e()
    {
        $data['page_title']= 'INVENTARISASI ASET TETAP LAINNYA';
        $this->template->set('title', 'INVENTARISASI ASET TETAP LAINNYA');   
        $this->template->load('index','transaksi/inventaris_e',$data) ;         
    }
    
    function inventaris_e_kap()
    {
        $data['page_title']= 'INVENTARISASI ASET TETAP LAINNYA';
        $this->template->set('title', 'INVENTARISASI ASET TETAP LAINNYA');   
        $this->template->load('index','transaksi/inventaris_e_kap',$data) ;         
    }
		function mutasi_e()
    {
        $data['page_title']= 'MUTASI INVENTARIS SET TETAP LAINNYA';
        $this->template->set('title', 'MUTASI INVENTARISASI ASET TETAP LAINNYA');   
        $this->template->load('index','transaksi/mutasi_e',$data) ;         
    } 
    function inventaris_f()
    {
        $data['page_title']= 'INVENTARISASI KONTRUKSI DALAM PEKERJAAN';
        $this->template->set('title', 'INVENTARISASI KONTRUKSI DALAM PEKERJAAN');   
        $this->template->load('index','transaksi/inventaris_f',$data) ;         
    }
    
    function inventaris_f_kap()
    {
        $data['page_title']= 'INVENTARISASI KONTRUKSI DALAM PEKERJAAN';
        $this->template->set('title', 'INVENTARISASI KONTRUKSI DALAM PEKERJAAN');   
        $this->template->load('index','transaksi/inventaris_f_kap',$data) ;         
    }
		function mutasi_f()
    {
        $data['page_title']= 'MUTASI INVENTARIS KONSTRUKSI DALAM PEKERJAAN';
        $this->template->set('title', 'MUTASI INVENTARISASI KONSTRUKSI DALAM PEKERJAAN');   
        $this->template->load('index','transaksi/mutasi_f',$data) ;         
    } 
	    function inventaris_g()
    {
        $data['page_title']= 'INVENTARISASI ASET TAK BERWUJUD';
        $this->template->set('title', 'INVENTARISASI INVENTARISASI ASET TAK BERWUJUD');   
        $this->template->load('index','transaksi/inventaris_g',$data) ;         
    }
    
    function inventaris_g_kap()
    {
        $data['page_title']= 'INVENTARISASI INVENTARISASI ASET TAK BERWUJUD';
        $this->template->set('title', 'INVENTARISASI INVENTARISASI ASET TAK BERWUJUD');   
        $this->template->load('index','transaksi/inventaris_g_kap',$data) ;         
    }
	function riwayat_a()
    {
        $data['page_title']= 'Tambah Riwayat Tanah';
        $this->template->set('title', 'Tambah Riwayat Tanah');   
        $this->template->load('index','transaksi/riwayat_a',$data) ;         
    }
	
	function riwayat_b()
    {
        $data['page_title']= 'Tambah Riwayat Peralatan dan Mesin';
        $this->template->set('title', 'Tambah Riwayat Tanah');   
        $this->template->load('index','transaksi/riwayat_b',$data) ;         
    }
	function riwayat_c()
    {
        $data['page_title']= 'Tambah Riwayat Bangunan dan Gedung';
        $this->template->set('title', 'Tambah Riwayat Bangunan dan Gedung');   
        $this->template->load('index','transaksi/riwayat_c',$data) ;         
    }
	function riwayat_d()
    {
        $data['page_title']= 'Tambah Riwayat Jalan dan Irigasi';
        $this->template->set('title', 'Tambah Riwayat Jalan dan Irigasi');   
        $this->template->load('index','transaksi/riwayat_d',$data) ;         
    }
	function riwayat_e()
    {
        $data['page_title']= 'Tambah Riwayat Lainnya';
        $this->template->set('title', 'Tambah Riwayat Lainnya');   
        $this->template->load('index','transaksi/riwayat_e',$data) ;         
    }
	function riwayat_f()
    {
        $data['page_title']= 'Tambah Riwayat Konstruksi Dalam Pengerjaan';
        $this->template->set('title', 'Tambah Riwayat Konstruksi Dalam Pengerjaan');   
        $this->template->load('index','transaksi/riwayat_f',$data) ;         
    }
	
     function ambil_dok_a() {
        
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='01' and b.kd_uskpd ='$skpd' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='01' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		/*$sql = "select a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and (a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (kd_brg+'.'+no_dokumen) AS xy FROM trkib_c) order by b.tahun";*/
			  
        $sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";
			  //and (a.no_dokumen+'.'+a.kd_brg) NOT IN (SELECT (no_dokumen+'.'+kd_brg)AS xy FROM trkib_a)
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total' 		=> $resulte['total'],
						'total2' 		=> number_format($resulte['total']),
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'jns_barang'    => $resulte['jns_barang'],
						'kd_kegiatan'   => $resulte['kd_kegiatan'],      						
						'nm_kegiatan'   => $resulte['nm_kegiatan'],      						
						'kd_rek5'       => $resulte['kd_rek5'],      
						'nm_rek5'       => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

    function cekno_kib_b_dh(){
        $skpd   = $this->input->post('skpd');
        $unit   = $this->input->post('unit');
        $kd_brg = $this->input->post('brg');
     
        $query=$this->db->query("SELECT IF(MAX(no_reg)IS NULL,LPAD('1',5,0),LPAD(MAX(no_reg)+1,5,0)) AS no_reg FROM trkib_b WHERE kd_skpd='$skpd'AND kd_unit='$unit' AND kd_brg='$kd_brg'");
        $result=array();
        foreach ($query->result_array() as $resulte) {
            $result[]= array('no' =>$resulte['no_reg']);
        }
        echo json_encode($result);
    }
    
    function ambil_dok_b() {
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='02' and b.kd_uskpd ='$skpd' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='02' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
		$sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_uskpd as kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2  and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";
		
        /*$sql = "select a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 AND CONCAT(a.no_dokumen,'.',a.kd_brg) NOT IN (SELECT DISTINCT CONCAT(no_dokumen,'.',kd_brg)AS xy FROM trkib_b) order by b.tahun";*/
			  
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total' 		=> $resulte['total'],
						'total2' 		=> number_format($resulte['total']),						
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh'],
                        'jns_barang'    => $resulte['jns_barang'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5']                        						
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_dok_c(){
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='03' and b.kd_uskpd ='$skpd' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='03' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
        $sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total' 		=> $resulte['total'],
						'total2' 		=> number_format($resulte['total']),
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh'],
                        'jns_barang'    => $resulte['jns_barang'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'   => $resulte['kd_kegiatan'],      						
						'nm_kegiatan'   => $resulte['nm_kegiatan'],      						
						'kd_rek5'       => $resulte['kd_rek5'],      
						'nm_rek5'       => $resulte['nm_rek5']      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_dok_d() {
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='04' and b.kd_uskpd ='$skpd' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='04' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
        /*$sql = "select a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and CONCAT(a.no_dokumen,'.',a.kd_brg) NOT IN (SELECT DISTINCT CONCAT(no_dokumen,'.',kd_brg)AS xy FROM trkib_d) order by b.tahun";*/
			  
			   $sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";
			  
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total2' 		=> number_format($resulte['total']),
						'total' 		=> $resulte['total'],
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh'],
                        'jns_barang'    => $resulte['jns_barang'],
                        'kd_bidang'     => $resulte['kd_bidang']                     
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_dok_e() {
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='05' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='05' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
		$sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";
			  
        /*$sql = "select a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 order by b.tahun";*/
			  //and (a.no_dokumen+'.'+a.kd_brg) NOT IN (SELECT (no_dokumen+'.'+kd_brg)AS xy FROM trkib_e)
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total2' 		=> number_format($resulte['total']),
						'total' 		=> $resulte['total'],
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh'],
                        'jns_barang'    => $resulte['jns_barang'],
                        'kd_bidang'     => $resulte['kd_bidang']               
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function ambil_dok_f() {
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='06' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='06' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
        /*$sql = "select a.no_dokumen,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 AND CONCAT(a.no_dokumen,'.',a.kd_brg) NOT IN (SELECT DISTINCT CONCAT(no_dokumen,'.',kd_brg)AS xy FROM trkib_f) order by b.tahun";*/
			  
		$sql = "select a.invent,a.no_dokumen,a.jns_barang,a.kd_bidang,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 and a.kd_brg+'/'+b.tahun+'/'+a.no_dokumen not in (select kd_brg+'/'+tahun+'/'+no_dokumen from trkib_b where no_dokumen<>'') order by b.tahun";	  
			  
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'invent' 	=> $resulte['invent'],
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total2' 		=> number_format($resulte['total']),
						'total' 		=> $resulte['total'],
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh']   
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
	    function ambil_dok_g() {
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='06' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='06' and b.kd_uskpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(b.no_dokumen) like upper('%$lccr%') 
			or upper(a.kd_unit) like upper('%$lccr%')) ";
        }
		
        $sql = "select a.no_dokumen,a.kd_brg,a.kd_unit,a.kd_uskpd,a.nm_brg,a.jumlah,a.harga,a.total,
			  a.keterangan,a.s_dana,b.kd_milik,b.kd_wilayah,b.b_dasar,b.b_nomor,b.tahun,
			  b.b_tanggal,b.kd_cr_oleh from trh_isianbrg b left join  
			  trd_isianbrg a ON a.no_dokumen =b.no_dokumen and a.kd_unit=b.kd_unit and a.kd_uskpd=b.kd_uskpd
			  $where1 $where2 order by b.tahun";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' 			=> $ii,
						'no_dokumen' 	=> $resulte['no_dokumen'],
						'kd_brg' 		=> $resulte['kd_brg'],
						'kd_unit' 		=> $resulte['kd_unit'],
						'kd_uskpd' 		=> $resulte['kd_uskpd'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'jumlah' 		=> $resulte['jumlah'],
						'harga' 		=> $resulte['harga'],
						'total' 		=> $resulte['total'],
						'keterangan' 	=> $resulte['keterangan'],
						's_dana' 		=> $resulte['s_dana'],
						'kd_milik' 		=> $resulte['kd_milik'],
						'kd_wilayah' 	=> $resulte['kd_wilayah'],
						'b_dasar' 		=> $resulte['b_dasar'],
						'b_nomor' 		=> $resulte['b_nomor'],
						'tahun' 		=> $resulte['tahun'],
						'b_tanggal' 	=> $resulte['b_tanggal'],
						'kd_cr_oleh' 	=> $resulte['kd_cr_oleh']   
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
	
    function brg_msn() {
		$skpd = $this->session->userdata('unit_skpd');
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_b WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='02' AND 
                (upper(a.no_dokumen) like upper('%$lccr%')) and a.no_dokumen='$nodok' and c.kd_unit='$skpd' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(

                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'total' => $resulte['total'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
	}

    function brg_msn_dh() {
        //$skpd = $this->session->userdata('unit_skpd');
        $where="";
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where="AND (upper(a.no_dokumen) like upper('%$lccr%'))";
        }else{
            $where="";
        }
        $nodok = $this->input->post('nodok');
        $kdbrg = $this->input->post('kdbrg');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_b WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='02'  
                and a.no_dokumen='$nodok' AND a.kd_brg='$kdbrg' $where ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(

                        'id'         => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg'     => $resulte['kd_brg'],
                        'invent'     => $resulte['invent'],  
                        'nm_brg'     => $resulte['nm_brg'],
                        'harga'      => $resulte['harga'],
                        'total'      => $resulte['total'],
                        'jml'        => $resulte['jumlah'],
                        'no_urut'    => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan'],
                        'jns_barang' => $resulte['jns_barang'],
                        'kd_bidang'  => $resulte['kd_bidang']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
	
	function brg_msn2() {
		$lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='02' limit 20";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
    
    	    function ambil_nomor_spm() {
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_skpd ='$skpd' ";
        }
        
        $lccr = $this->input->post('q');
        $where2=''; 
        if($lccr <> ''){
            $where2=" and (upper(no_spm) like upper('%$lccr%') ";
        }
		
		$sql = "select no_spm,tgl_spm,nilai,kd_skpd from trhspm $where1 $where2";
			  
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
        foreach($query1->result_array() as $resulte)
        { 
           
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
	
	function brg_gdg() {
		$skpd = $this->session->userdata('unit_skpd');
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_c WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='03' AND 
                (upper(a.no_dokumen) like upper('%$lccr%')) and c.kd_unit='$skpd' and a.no_dokumen='$nodok' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,  
                        'no_dokumen' => $resulte['no_dokumen'],      
                        'kd_brg' => $resulte['kd_brg'], 
                        'invent' => $resulte['invent'], 
                        'nm_brg' => $resulte['nm_brg'],
						's_dana' => $resulte['s_dana'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
	}

    function brg_gdg_dh() {
        //$skpd = $this->session->userdata('unit_skpd');
        //$lccr = $this->input->post('q');
        $kdbrg = $this->input->post('kdbrg');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_c WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='03' 
                AND a.no_dokumen='$nodok' AND a.kd_brg='$kdbrg' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id'         => $ii,  
                        'no_dokumen' => $resulte['no_dokumen'],      
                        'kd_brg'     => $resulte['kd_brg'], 
                        'invent'     => $resulte['invent'], 
                        'nm_brg'     => $resulte['nm_brg'],
                        's_dana'     => $resulte['s_dana'],
                        'harga'      => $resulte['harga'],
                        'jml'        => $resulte['jumlah'],
                        'no_urut'    => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan'],
                        'jns_barang' => $resulte['jns_barang'],
                        'kd_bidang'  => $resulte['kd_bidang']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
    
	 function brg_gdg2() {
		$lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='03'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
    function brg_jln() {
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_d WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='04' AND 
                (upper(a.no_dokumen) like upper('%$lccr%') ) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

    function brg_jln_dh() {
        //$lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $kdbrg = $this->input->post('kdbrg');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_d WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg 
                WHERE LEFT(a.kd_brg,2)='04' and a.no_dokumen='$nodok' AND a.kd_brg='$kdbrg' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
	 function brg_jln2() {
		$lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='04'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
    function brg_tnh() {

		$skpd = $this->session->userdata('unit_skpd');
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,c.kd_cr_oleh,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_a WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='01'  AND 
                (upper(a.no_dokumen) like upper('%$lccr%')) and a.no_dokumen='$nodok' and c.kd_unit='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],         
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

    function brg_tnh_dh() {

        //$skpd = $this->session->userdata('unit_skpd');
        //$lccr = $this->input->post('q');
        $kdbrg = $this->input->post('kdbrg');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,c.kd_cr_oleh,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_a WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='01'  AND 
                a.no_dokumen='$nodok' AND a.kd_brg='$kdbrg' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],         
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
	function brg_tnh2() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='01'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
	function brg_tnh_bangunan() {
        $lccr = $this->input->post('q');
        $skpd = $this->input->post('skpd');
		$cari ="";
		if($lccr<>""){
		$cari="and upper(b.nm_brg) like upper('%$lccr%')";
		}
        $sql = "SELECT a.kd_brg,b.nm_brg,a.nilai from trkib_a a 
		left join mbarang b on a.kd_brg=b.kd_brg WHERE a.kd_skpd='$skpd' 
		$cari order by a.nilai";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],  
                        'nilai' => $resulte['nilai'],                   
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
    function brg_aset() {
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_e WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='05' AND 
                (upper(a.no_dokumen) like upper('%$lccr%') ) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,  
                        'no_dokumen' => $resulte['no_dokumen'],      
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
	}

    function brg_aset_dh() {
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $kdbrg = $this->input->post('kdbrg');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_e WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='05' AND 
                a.no_dokumen='$nodok' AND a.kd_brg='$kdbrg' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,  
                        'no_dokumen' => $resulte['no_dokumen'],      
                        'kd_brg' => $resulte['kd_brg'],
                        'invent' => $resulte['invent'],  
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
	
	function brg_aset2() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='05'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
    
    function brg_kont() {
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_f WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='06' AND 
                (upper(a.no_dokumen) like upper('%$lccr%') ) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg' => $resulte['kd_brg'], 
                        'invent' => $resulte['invent'], 
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	}
	
	
	function brg_kont2() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='06'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
	    function brg_twujud() {
        $lccr = $this->input->post('q');
        $nodok = $this->input->post('nodok');
        $sql = "SELECT a.*,c.kd_unit,b.nm_brg,(SELECT MAX(no_urut) FROM trkib_g WHERE kd_brg=a.kd_brg AND kd_unit=c.kd_unit) AS  no_urut 
                FROM trd_isianbrg a LEFT JOIN trh_isianbrg c ON a.no_dokumen=c.no_dokumen  LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg WHERE LEFT(a.kd_brg,2)='07' AND 
                (upper(a.no_dokumen) like upper('%$lccr%') ) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(

                        'id' => $ii,
                        'no_dokumen' => $resulte['no_dokumen'],        
                        'kd_brg' => $resulte['kd_brg'], 
                        'invent' => $resulte['invent'], 
                        'nm_brg' => $resulte['nm_brg'],
                        'harga' => $resulte['harga'],
                        'jml' => $resulte['jumlah'],
                        'no_urut' => $resulte['no_urut'],
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	}
	
	
	function brg_twujud2() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_brg,nm_brg from mbarang WHERE upper(nm_brg) like upper('%$lccr%') and LEFT(kd_brg,2)='07'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,     
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],                     
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
    
	function hapus_transaksi(){
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
	
    function mtanah() {
        $lccr = $this->input->post('q');
        $sql = "SELECT * from mbarang WHERE LEFT(kd_brg,2)='01' and (upper(kd_brg) like upper('%$lccr%') or upper(nm_brg) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_brg' => $resulte['kd_brg'],  
                        'nm_brg' => $resulte['nm_brg'],                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

  ////////////////////////kib pelihara///////////

  
    
  function ambil_kib_a_pelihara() {
         
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
		
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; // 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'      		=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	   function ambil_kib_a_kap() {
         
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }    
 
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
       if ($kriteria <> ''){                               
           $where2="and upper(d.nm_brg) like upper('%$kriteria%') ";            
        }
        $sql = "SELECT count(*) as tot from trkib_a a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT a.*,d.nm_brg FROM trkib_a_kap a INNER JOIN mbarang d on d.kd_brg = a.kd_brg $where1 
		and upper(d.nm_brg) like upper('%$kriteria%') order by a.kd_brg,a.tahun limit $offset,$rows";
     
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,     
						'id_barang' 	=> $resulte['id_barang'],
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'no_sertifikat' => $resulte['no_sertifikat'],
                        'tgl_sertifikat'=> $resulte['tgl_sertifikat'],
                        'luas' 			=> $resulte['luas'],
                        'nilai' 		=> $resulte['nilai'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'no_mutasi' 	=> $resulte['no_mutasi'],
                        'no_pindah' 	=> $resulte['no_pindah'],
                        'no_hapus' 		=> $resulte['no_hapus'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'], 
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'tahun' 		=> $resulte['tahun'], 
                        'hrg_perolehan' => $resulte['hrg_perolehan'],
                        //'hrg_perolehan' => $resulte['hrg_perolehan'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3'	 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	} 
	
	    function ambil_kib_b_pelihara() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }

        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'sisa_umur'     => $resulte['sisa_umur']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_c_pelihara() {
        
       $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'sisa_umur'       => $resulte['sisa_umur']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	 function ambil_kib_d_pelihara() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
		
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'sisa_umur'         => $resulte['sisa_umur']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	   function ambil_kib_e_pelihara() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_e a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],	
                        'sisa_umur'         => $resulte['sisa_umur']	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_f_pelihara() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'sisa_umur'         => $resulte['sisa_umur']
						);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	
	////////////end pelihara/////
	
	function riwayat_kib_a() {
         
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
		//echo $kriteria;
		//$kriteria   = 'jalan';
        $where2 ="";
        if ($kriteria<>''){                               
           $where2="and upper(d.nm_brg) like upper('%$kriteria%')";            
        }
		
        $sql = "SELECT count(*) as tot from trkib_a a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT a.*,d.nm_brg,b.nm_lokasi,c.riwayat FROM trkib_a a INNER JOIN mbarang d on d.kd_brg = a.kd_brg 
		left join mlokasi b on b.kd_lokasi=a.kd_unit left join mriwayat c on c.kode=a.kd_riwayat $where1 
		$where2	order by a.kd_brg,a.tahun limit $offset,$rows";
     
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                       //'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_a_mutasi_masuk() {
         
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
		
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2 and a.mutasi_masuk is not null" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; // 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'      		=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5'],
                        'mutasi_masuk'		=> $resulte['mutasi_masuk']
						);
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	    function ambil_kib_b_mutasi_masuk() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2 and a.mutasi_masuk is not null" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	     function ambil_kib_c_mutasi_masuk() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or b.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2 and a.mutasi_masuk is not null " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	     function ambil_kib_d_mutasi_masuk() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2 and a.mutasi_masuk is not null" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}

   function ambil_kib_e_mutasi_masuk() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
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
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2 and a.mutasi_masuk is not null" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_a() {
         
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;   
        $lccr = $this->input->post('q');
        if($lccr!=''){
            $where2="AND UPPER(a.nm_brg) LIKE UPPER('%$lccr%') OR UPPER(a.tahun) LIKE UPPER('%$lccr%')";
        }else{
            $where2="";
        }
		
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; // 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'      		=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
    function ambil_kib_b() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}

//////////////////////////////////////////////mutasi-kahfi///////////////////////////////////////////////////
	
	
	function ambil_kib_muta() {
        

		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
						'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
                        
						);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}	
		
	function ambil_muta_header() {
        
		
     	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'        => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
                       
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	/////end mutasi//////////
	
	/////start tetap///////
	
	function ambil_kib_tetap_a() {
     
	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					) ";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_pindah is null and a.tgl_mutasi is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; // 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']
						
						);
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
		
	function ambil_tetap_header_a() {
        
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					) ";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; // 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']
						
						);
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_kib_tetap_b() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_pindah is null and a.tgl_mutasi is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'        => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

                       
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
		
		
		
	function ambil_tetap_header_b() {
        
		
     	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) and a.tgl_mutasi is not null ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'        => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

                       
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_tetap_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		function ambil_tetap_header_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		
	function ambil_kib_tetap_d() {

	    $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_tetap_header_d() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
												'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function ambil_kib_tetap_e() {

		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],						
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        						
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_tetap_header_e() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],						
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']

        						
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}


	function ambil_kib_tetap_f() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']
                        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_tetap_header_f() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_pindah'     => $resulte['no_pindah'],
                        'tgl_pindah'    => $resulte['tgl_pindah'],
						'ket_mutasi'		=> $resulte['ket_mutasi']
                        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}

	
	
	
	////end tetap////

//////start hapus usulan /////
	function ambil_kib_hapus_a() {
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					)";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";  
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'     	 	=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_a() {
        
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					)";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";  
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'     	 	=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5'],
						'tgl_uhapus'         	=> $resulte['tgl_uhapus'],
						'no_uhapus'         	=> $resulte['no_uhapus'],						
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}	
		
	function ambil_kib_hapus_b() {
        
				$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'tgl_uhapus'         => $resulte['tgl_uhapus'],
						'no_uhapus'         => $resulte['no_uhapus'],
						
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_b() {
        
		
				$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'tgl_uhapus'         => $resulte['tgl_uhapus'],
						'no_uhapus'         => $resulte['no_uhapus'],
						
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_hapus_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_hapus is null and tgl_uhapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		function ambil_hapus_header_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_hapus is null and tgl_uhapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'         => $resulte['no_uhapus'],
                        'tgl_uhapus'         => $resulte['tgl_uhapus'],						
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		
	function ambil_kib_hapus_d() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_d() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'         => $resulte['no_uhapus'],
                        'tgl_hapus'         => $resulte['tgl_hapus'],                        
						);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_hapus_e() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_e() {
        
	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],	
                        'no_uhapus'         => $resulte['no_uhapus'],
						'tgl_uhapus'         => $resulte['tgl_uhapus'],
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function ambil_kib_hapus_f() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_f() {
        
		
            
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'         => $resulte['no_uhapus'],
                        'tgl_uhapus'         => $resulte['tgl_uhapus'],
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
/////end hapus///////	


////////START HAPUS TETAP///////

	function ambil_kib_hapus_a_tetap() {
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					)";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_hapus is null and a.tgl_uhapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";  
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'		=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'       => $resulte['nm_kegiatan'],
                        'kd_rek5'     	 	=> $resulte['kd_rek5'],
                        'nm_rek5'         	=> $resulte['nm_rek5'],
						'no_uhapus'       	=> $resulte['no_uhapus'],
                        'tgl_uhapus'     	=> $resulte['tgl_uhapus'],
                        'tgl_hapus'     	=> $resulte['tgl_hapus']
                        
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_a_tetap() {
        
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					) ";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2 " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"; 
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
                        'tgl_uhapus'	=> $resulte['tgl_uhapus'],
                        'tgl_hapus'	=> $resulte['tgl_hapus'],
						'no_uhapus'		=> $resulte['no_uhapus']
						);
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_kib_hapus_b_tetap() {
        
		
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
						'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],
						'no_hapus'     => $resulte['no_hapus'],
                        'tgl_hapus'    => $resulte['tgl_hapus']
                        
                        	
						);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}	
		
	function ambil_hapus_header_b_tetap() {
        
		
     	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')	
					) and a.tgl_uhapus is not null and a.tgl_hapus is null";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2 and a.tgl_mutasi is not null" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg "
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'        => $resulte['nm_rek5'],
                        'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
                        'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],  
                        'no_hapus'     => $resulte['no_hapus'],
                        'tgl_hapus'    => $resulte['tgl_hapus']                     
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_hapus_c_tetap() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'       => $resulte['no_uhapus'],
                        'tgl_uhapus'         => $resulte['tgl_uhapus']
                        
						);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		function ambil_hapus_header_c_tetap() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null  and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],
						'no_hapus'     => $resulte['no_hapus'],
                        'tgl_hapus'    => $resulte['tgl_hapus']
                        
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		
	function ambil_kib_hapus_d_tetap() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'       => $resulte['no_uhapus'],
                        'tgl_uhapus'         => $resulte['tgl_uhapus']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_d_tetap() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],
						'no_hapus'     => $resulte['no_hapus'],
                        'tgl_hapus'    => $resulte['tgl_hapus']                        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function ambil_kib_hapus_e_tetap() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
                        'no_uhapus'       => $resulte['no_uhapus'],
                        'tgl_uhapus'         => $resulte['tgl_uhapus']	
                        	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_e_tetap() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
        				'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],
                        'no_hapus'     => $resulte['no_hapus'],
                        'tgl_hapus'    => $resulte['tgl_hapus']
                        		
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}


	function ambil_kib_hapus_f_tetap() {
             $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus']
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_hapus_header_f_tetap() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_uhapus is not null and a.tgl_hapus is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						'no_uhapus'     => $resulte['no_uhapus'],
                        'tgl_uhapus'    => $resulte['tgl_uhapus'],
                        'no_hapus'     => $resulte['no_uhapus'],
                        'tgl_hapus'    => $resulte['tgl_uhapus']
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
////END HAPUS TETAP//////////////

///start mutasi/////

function ambil_kib_muta_a() {
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					)";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";  
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_muta_header_a() {
        
     $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (a.tahun like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%'
					or a.no_sertifikat like '%$kriteria%' 
					or a.luas like '%$kriteria%' 
					or a.penggunaan like '%$kriteria%'
					or a.alamat1 like '%$kriteria%'
					or a.nm_brg like '%$kriteria%'
					)";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_a a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";  
        
        //$sql1 = "SELECT a.*,d.nm_brg,c.nm_skpd FROM trkib_a a 
		//left JOIN mbarang d on d.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
		//     
	 
        $query1 = $this->db->query($sql1);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 				=> $ii,        
                        'no_reg' 			=> $resulte['no_reg'],
                        'id_barang'			=> $resulte['id_barang'],
                        'no' 				=> $resulte['no'], 
                        'no_dokumen' 		=> $resulte['no_dokumen'],
                        'kd_brg' 			=> $resulte['kd_brg'],
                        'detail_brg' 		=> $resulte['detail_brg'],
						'nm_brg' 			=> $resulte['nm_brg'],
                        'status_tanah' 		=> $resulte['status_tanah'],
                        'kondisi' 		    => $resulte['kondisi'],
                        'no_sertifikat' 	=> $resulte['no_sertifikat'],
                        'tgl_sertifikat' 	=> $resulte['tgl_sertifikat'],
                        'luas'				=> $resulte['luas'],
                        'tgl_reg' 			=> $resulte['tgl_reg'],
                        'no_oleh'			=> $resulte['no_oleh'], 
                        'tgl_oleh'			=> $resulte['tgl_oleh'],
                        'milik' 			=> $resulte['milik'],
						'wilayah'			=> $resulte['wilayah'],
                        'kd_unit' 			=> $resulte['kd_unit'],
                        'asal' 				=> $resulte['asal'],
                        'dsr_peroleh' 		=> $resulte['dsr_peroleh'],
                        'nilai' 			=> $resulte['nilai'],
                        'total' 			=> $resulte['total'],
                        'penggunaan' 		=> $resulte['penggunaan'],
                        'alamat1' 			=> $resulte['alamat1'],
                        'alamat2' 			=> $resulte['alamat2'],
                        'alamat3' 			=> $resulte['alamat3'],
                        'no_mutasi' 		=> $resulte['no_mutasi'],
                        'no_pindah'			=> $resulte['no_pindah'],
                        'no_hapus' 			=> $resulte['no_hapus'],
                        'keterangan' 		=> $resulte['keterangan'],
                        'kd_lokasi' 		=> $resulte['kd_lokasi2'],
                        'kd_skpd' 			=> $resulte['kd_skpd'], 
                        'tahun' 			=> $resulte['tahun'], 
                        'no_urut' 			=> $resulte['no_urut'],
                        'lat' 				=> $resulte['lat'],
                        'lon' 				=> $resulte['lon'],
                        'foto1' 			=> $resulte['foto1'],
                        'foto2' 			=> $resulte['foto2'],
                        'foto3' 			=> $resulte['foto3'],
                        'foto4' 			=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'nm_skpd'           => $resulte['nm_skpd'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'kd_skpd_asal'         => $resulte['kd_skpd_asal'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'         => $resulte['tgl_mutasi']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	
		
	function ambil_kib_muta_b() {
        
	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'kd_skpd_asal'         => $resulte['kd_skpd_asal'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'         => $resulte['tgl_mutasi']
							);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}	
		
	function ambil_muta_header_b() {
        
		
	$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					or upper(a.no_reg) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					or upper(a.tahun) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'kd_skpd_asal'         => $resulte['kd_skpd_asal'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'         => $resulte['tgl_mutasi']
							);
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}		
	
	function ambil_kib_muta_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		function ambil_muta_header_c() {
        
		
	   $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or a.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    	
		
		
	function ambil_kib_muta_d() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_muta_header_d() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null  and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
        
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function ambil_kib_muta_e() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_muta_header_e() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%')
					or upper(a.peroleh) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.cipta) like upper('%$kriteria%')
					or upper(a.penerbit) like upper('%$kriteria%')
					or upper(a.kd_bahan) like upper('%$kriteria%')
					or upper(a.judul) like upper('%$kriteria%')
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,a.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null  and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
        						
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}


	function ambil_kib_muta_f() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_muta_header_f() {
        
		
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.tgl_mutasi is not null  and a.tgl_pindah is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5'],
						                'no_mutasi'     => $resulte['no_mutasi'],
                        'tgl_mutasi'    => $resulte['tgl_mutasi'],
                        'kd_skpd_asal'  => $resulte['kd_skpd_asal']
        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
		
///////////////////////////////////////////////////////////////end mutasi/////////////////////////////////////////////////////
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	function ambil_kib_b_sisa() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
						'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
		
	function ambil_kib_b_sisah() {
        
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'  	=> $resulte['sisa_umur'],
						'akum_penyu'    => $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'        => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_c_sisa() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or b.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_c a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'		=> $resulte['sisa_umur'],
                        'akum_penyu'   	=> $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	function ambil_kib_c_sisah() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or b.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_c a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'sisa_umur'		=> $resulte['sisa_umur'],
                        'akum_penyu'   	=> $resulte['akum_penyu'],
						'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_d_sisa() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
						'nm_brg'    	=> $resulte['nm_brg'],
						'tahun'  	    => $resulte['tahun'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'akum_penyu'   => $resulte['akum_penyu'],
                        'sisa_umur'     => $resulte['sisa_umur'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	function ambil_kib_d_sisah() {

        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.sisa_umur is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
						'nm_brg'    	=> $resulte['nm_brg'],
						'tahun'  	    => $resulte['tahun'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'akum_penyu'   => $resulte['akum_penyu'],
                        'sisa_umur'     => $resulte['sisa_umur'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
    
	////////////////////////////////////////////////////////////////////////////
	
		function ambil_kib_b_h() {
	   
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg"
				;				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=> $resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi'=> $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'jns_barang'    => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	  function ambil_kib_b_kap() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg FROM trkib_b_kap a inner join
				mbarang b on b.kd_brg = a.kd_brg $where1 $where2 order by a.kd_brg,a.tahun,a.no_reg limit $offset,$rows";
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=>$resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi' => $resulte['tahun_produksi'],
                        'nm_brg' 			=> $resulte['nm_brg'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						//'nm_uskpd' 		=> $resulte['nm_uskpd'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        //'tgl_sp2d' 		=> $resulte['tgl_sp2d'],
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'tmbh_manfaat' 	=> $resulte['tmbh_manfaat'],
                        'hrg_perolehan' 	=> $resulte['hrg_perolehan'],
                        'foto' 			=> $resulte['foto']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
     function riwayat_kib_b() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(c.nm_uskpd) like upper('%$kriteria%') or upper(a.no_dokumen) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_b a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat FROM trkib_b a inner join
				mbarang b on b.kd_brg = a.kd_brg 
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
				$where1 $where2 order by a.kd_brg,a.tahun,a.no_reg limit $offset,$rows";
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'id_barang' 	=> $resulte['id_barang'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'milik' 		=> $resulte['milik'],
						'wilayah'		=> $resulte['wilayah'],
                        'asal' 			=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'merek' 		=> $resulte['merek'],
                        'tipe' 			=> $resulte['tipe'],
                        'pabrik' 		=> $resulte['pabrik'],
                        'kd_warna' 		=> $resulte['kd_warna'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'no_rangka' 	=> $resulte['no_rangka'],
                        'no_mesin' 		=> $resulte['no_mesin'],
                        'no_polisi' 	=> $resulte['no_polisi'],
                        'silinder' 		=>$resulte['silinder'],
                        'no_stnk' 		=> $resulte['no_stnk'],
                        'tgl_stnk' 		=> $resulte['tgl_stnk'],
                        'no_bpkb'	    => $resulte['no_bpkb'],
                        'tgl_bpkb' 		=> $resulte['tgl_bpkb'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'tahun_produksi' => $resulte['tahun_produksi'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
						'no_urut' 		=> $resulte['no_urut'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
     function ambil_kib_c() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where1 = '';       
		$and1 = '';    
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and ( a.tahun like '%$kriteria%' 
					or a.kondisi like '%$kriteria%' 
					or a.keterangan like '%$kriteria%' 
					or a.nilai like '%$kriteria%'
					or a.kd_brg like '%$kriteria%' 
					or b.nm_brg like '%$kriteria%' 
					or a.asal like '%$kriteria%' 
					or a.luas_gedung like '%$kriteria%' 
					or a.luas_tanah like '%$kriteria%'					
					or a.alamat1 like '%$kriteria%'
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'     	=> $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	function ambil_kib_c_kap() {
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and upper(b.nm_brg) like upper('%$kriteria%') 
			or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c_kap a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
     /*    $sql = "SELECT a.*,b.nm_brg,c.nilai as nil FROM trkib_c a 
		INNER JOIN mbarang b ON b.kd_brg = a.kd_brg 
		INNER JOIN trkib_f c ON a.`kd_skpd`=c.`kd_skpd` 
		AND a.`kd_unit`=c.`kd_unit` AND c.kd_riwayat=a.`id_barang`  
		$where1 $where2 
		order by a.kd_brg,a.tahun limit $offset,$rows";*/
		
		$sql = "SELECT a.*,b.nm_brg FROM trkib_c_kap a 
		INNER JOIN mbarang b ON b.kd_brg = a.kd_brg  
		$where1 $where2 
		order by a.kd_brg,a.tahun limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,           
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        //'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> number_format($resulte['nilai']),
                        'hrg_perolehan' => number_format($resulte['hrg_perolehan']),
                        //'jumlah' 		=> $resulte['jumlah'],
                        //'asal'	 		=> $resulte['asal'],
                        //'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        //'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        //'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        //'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        //'milik' 		=> $resulte['milik'],
                        //'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_perolehan' 		=> $resulte['tgl_perolehan'],
                        'tmbh_manfaat' 			=> number_format($resulte['tmbh_manfaat']),
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa']/* ,
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'] */
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function riwayat_kib_c() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat FROM trkib_c a INNER JOIN mbarang b ON b.kd_brg = a.kd_brg 
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
		$where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],    
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'asal'	 		=> $resulte['asal'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'luas_gedung' 	=> $resulte['luas_gedung'],
                        'jenis_gedung' 	=> $resulte['jenis_gedung'],
                        'luas_tanah' 	=> $resulte['luas_tanah'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'kontruksi2' 	=> $resulte['konstruksi2'],
                        'luas_lantai' 	=> $resulte['luas_lantai'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'dasar' 		=> $resulte['dasar'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],  
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'lokasi' 		=> $resulte['kd_lokasi2'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto1' 		=> $resulte['foto1'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat'	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
     function ambil_kib_d() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
				$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.panjang) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.lebar) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";  
					}
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		$sql ="SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_d a left JOIN mbarang b ON b.kd_brg = a.kd_brg 
                LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 	=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 	=> $resulte['tgl_riwayat'],
                        'detail_riwayat'=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
                        'nm_skpd'       => $resulte['nm_skpd'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	function ambil_kib_d_kap() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_d_kap a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg FROM trkib_d_kap a INNER JOIN mbarang b ON 
				a.kd_brg = b.kd_brg $where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'status_tanah'  => $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'nip' 			=> $resulte['nip'],
                        'dasar' 		=> $resulte['dasar'],
                        'no_sk' 		=> $resulte['no_sk'],
                        'tgl_sk' 		=> $resulte['tgl_sk'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'hrg_perolehan' => $resulte['hrg_perolehan'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'no_mutasi' 	=> $resulte['no_mutasi'],
                        'no_pindah' 	=> $resulte['no_pindah'],
                        'no_hapus' 		=> $resulte['no_hapus'],
                        'tahun' 		=> $resulte['tahun'], 
                        //'tgl_sp2d' => $resulte['tgl_sp2d'],
                        //'nm_uskpd' 		=> $resulte['nm_uskpd'],
                        'foto' 			=> $resulte['foto']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function riwayat_kib_d() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_d a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat FROM trkib_d a INNER JOIN mbarang b ON 
				a.kd_brg = b.kd_brg 
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
				$where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg' 	=> $resulte['detail_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'panjang' 		=> $resulte['panjang'],
                        'luas' 			=> $resulte['luas'],
                        'lebar' 		=> $resulte['lebar'],
                        'konstruksi'	=> $resulte['konstruksi'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'perolehan' 	=> $resulte['perolehan'],
                        'dasar' 		=> $resulte['dasar'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'], 
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'penggunaan' 	=> $resulte['penggunaan'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
   function ambil_kib_e() {
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){     
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
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
					) ";              
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        /*$sql = "SELECT a.* from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_e a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";				
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'detail_brg'    => $resulte['detail_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
                        'kd_golongan'   => $resulte['kd_golongan'],
                        'kd_bidang'     => $resulte['kd_bidang'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']	
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
	function ambil_kib_e_kap() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg from trkib_e_kap a inner join mbarang b on b.kd_brg = a.kd_brg
		$where1 $where2 order by a.kd_brg,a.tahun,a.no_reg limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
						'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
						'hrg_perolehan' => $resulte['hrg_perolehan'],
                        'kd_ruang' 		=> $resulte['kd_ruang'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'tahun' 		=> $resulte['tahun'],
                        'foto' 			=> $resulte['foto'] 
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    function riwayat_kib_e() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_e a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
		$where1 $where2 order by a.kd_brg,a.tahun,a.no_reg limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
						'nm_brg' 		=> $resulte['nm_brg'],
						'tgl_oleh'  	=> $resulte['tgl_peroleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'],
                        'no' 			=> $resulte['no'],   
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'jumlah'		=> $resulte['jumlah'],
                        'total' 		=> $resulte['total'],
                        'peroleh' 		=> $resulte['peroleh'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'judul' 		=> $resulte['judul'],
                        'spesifikasi' 	=> $resulte['spesifikasi'],
                        'asal' 			=> $resulte['asal'],
                        'cipta' 		=> $resulte['cipta'],
                        'tahun_terbit' 	=> $resulte['tahun_terbit'],
                        'penerbit' 		=> $resulte['penerbit'],
                        'kd_bahan' 		=> $resulte['kd_bahan'],
                        'jenis' 		=> $resulte['jenis'],
                        'tipe' 			=> $resulte['tipe'],
                        'kd_satuan' 	=> $resulte['kd_satuan'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'], 
                        'tahun' 		=> $resulte['tahun'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'lat' 			=> $resulte['lat'],
                        'lon' 			=> $resulte['lon'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'] 
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
    function ambil_kib_f() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	 function ambil_kib_f_kap() {
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $idkdp = $this->input->post('gol');
        $tabel = $this->input->post('tabel');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd' and a.id_barang='$idkdp'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') 
			or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from $tabel a $where1 " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg,c.tgl_riwayat,c.nilai as nil,c.tahun as thn from $tabel a 
		INNER JOIN trkib_f c 
		ON c.kd_skpd=a.kd_skpd 
		AND c.kd_unit=a.kd_unit AND a.id_barang=c.kd_riwayat
		INNER JOIN mbarang b ON b.kd_brg=c.kd_brg
		$where1 GROUP BY c.nilai order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           

            $row[] = array(
                           'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        //'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'nil' 			=> number_format($resulte['nil']),
                        'thn' 			=> $resulte['thn'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        //'jenis' 		=> $resulte['jenis'],
                        //'luas' 			=> $resulte['luas'],
                        //'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        //'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'no_mutasi' 	=> $resulte['no_mutasi'],
                        'no_pindah' 	=> $resulte['no_pindah'],
                        'no_hapus' 		=> $resulte['no_hapus'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_sp2d' 		=> $resulte['tgl_sp2d'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'tgl_riwayat' => $resulte['tgl_riwayat'],
                        'hrg_perolehan'	=> $resulte['hrg_perolehan']
						//,'foto' 			=> $resulte['foto']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function riwayat_kib_f() {
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_f a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat from trkib_f a inner join mbarang b on b.kd_brg=a.kd_brg 
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
		$where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           

            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'], 
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	
    function ambil_kib_f_mutasi_masuk() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(a.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					or upper(a.kd_brg) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		/*$sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		$where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";*/
		
		$sql = "SELECT TOP $rows a.*,b.nm_brg,c.nm_skpd FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd 
				where id_barang not in (SELECT TOP $offset id_barang FROM trkib_f a left join
				mbarang b on b.kd_brg = a.kd_brg LEFT JOIN ms_skpd c ON a.kd_skpd=c.kd_skpd $where1 $where2 order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg)
				$and1 $where2 and a.mutasi_masuk is not null order by a.tahun,a.kd_brg,a.no_reg,a.tgl_reg";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat'],
						'kd_kegiatan'	=> $resulte['kd_kegiatan'],
                        'nm_kegiatan'           => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'         => $resulte['nm_rek5']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	function ambil_kib_g() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%')
					or upper(a.asal) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";             
        }
        
        $sql = "SELECT count(*) as tot from trkib_g a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg from trkib_g a left join mbarang b on b.kd_brg=a.kd_brg  $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'bangunan' 		=> $resulte['bangunan'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
	 function ambil_kib_g_kap() {
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_g_kap a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg from trkib_g_kap a inner join mbarang b on b.kd_brg=a.kd_brg  $where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           

            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'no_dok' 		=> $resulte['no_dok'],
                        'tgl_dok' 		=> $resulte['tgl_dok'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'luas' 			=> $resulte['luas'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'kd_lokasi' 	=> $resulte['kd_lokasi2'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'no_mutasi' 	=> $resulte['no_mutasi'],
                        'no_pindah' 	=> $resulte['no_pindah'],
                        'no_hapus' 		=> $resulte['no_hapus'],
                        'tahun' 		=> $resulte['tahun'], 
                        'tgl_sp2d' 		=> $resulte['tgl_sp2d'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'hrg_perolehan'	=> $resulte['hrg_perolehan'],
                        'foto' 			=> $resulte['foto']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	function riwayat_kib_g() {
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }else{
            $where1 = "where a.kd_unit ='$skpd'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('q');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and upper(b.nm_brg) like upper('%$kriteria%') or upper(a.kd_brg) like upper('%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_g a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg,c.nm_lokasi,d.riwayat from trkib_g a inner join mbarang b on b.kd_brg=a.kd_brg 
				left join mlokasi c on c.kd_lokasi=a.kd_unit left join mriwayat d on d.kode=a.kd_riwayat
		$where1 $where2 order by a.kd_brg,a.tahun limit $offset,$rows";

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           

            $row[] = array(
                        'id' 			=> $ii,        
                        'no_reg' 		=> $resulte['no_reg'],
                        'id_barang' 	=> $resulte['id_barang'],
                        'no' 			=> $resulte['no'], 
                        'no_oleh' 		=> $resulte['no_oleh'],
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'tgl_oleh' 		=> $resulte['tgl_oleh'],  
                        'no_dokumen' 	=> $resulte['no_dokumen'],
                        'kd_brg' 		=> $resulte['kd_brg'], 
                        'detail_brg' 	=> $resulte['detail_brg'], 
                        'nm_brg' 		=> $resulte['nm_brg'],  
                        'kd_skpd' 		=> $resulte['kd_skpd'],  
                        'kd_tanah' 		=> $resulte['kd_tanah'],
                        'nilai' 		=> $resulte['nilai'],
                        'total' 		=> $resulte['total'],
                        'asal' 			=> $resulte['asal'],
                        'jumlah' 		=> $resulte['jumlah'],
                        'kondisi' 		=> $resulte['kondisi'],
                        'kontruksi' 	=> $resulte['konstruksi'],
                        'jenis' 		=> $resulte['jenis'],
                        'luas' 			=> $resulte['luas'],
                        'dsr_peroleh' 	=> $resulte['dsr_peroleh'],
                        'tgl_awal_kerja' => $resulte['tgl_awal_kerja'],
                        'status_tanah' 	=> $resulte['status_tanah'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'alamat1' 		=> $resulte['alamat1'],
                        'alamat2' 		=> $resulte['alamat2'],
                        'alamat3' 		=> $resulte['alamat3'],
                        'keterangan' 	=> $resulte['keterangan'],
                        'no_urut' 		=> $resulte['no_urut'],
                        'kd_unit' 		=> $resulte['kd_unit'], 
                        'milik' 		=> $resulte['milik'],
                        'wilayah' 		=> $resulte['wilayah'],
                        'tahun' 		=> $resulte['tahun'], 
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
						'lat' 			=> $resulte['lat'],
						'lon' 			=> $resulte['lon'],
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'riwayat'	 		=> $resulte['riwayat'],
                        'nm_skpd' 			=> $resulte['nm_lokasi'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	

    function trh_planbrg(){  
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_uskpd like '%' ";
        }else{
            $where1 = "where kd_uskpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(no_dokumen) like upper('%$kriteria%') or tgl_dokumen like '%$kriteria%' or upper(nm_uskpd) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_planbrg $where1 $where2 " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT TOP $rows * from trh_planbrg $where1 $where2 order by tgl_dokumen"; //
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'tgl_dokumen'   => $resulte['tgl_dokumen'],
                        'kd_unit'   	=> $resulte['kd_unit'],
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'nm_uskpd'      => $resulte['nm_uskpd'],
                        'tahun'         => $resulte['tahun'],
                        'total'         => $resulte['total']			                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
	}      
    
    function trd_planbrg(){
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('kode');
        //$skpd = $this->session->userdata('unit_skpd'); 

		$csql = "SELECT SUM(total) AS totalh from trd_planbrg 
		where no_dokumen = '$nomor' and kd_uskpd = '$skpd'";
        $rs   = $this->db->query($csql)->row() ; 
		
        $sql = "SELECT b.* FROM trh_planbrg a 
				INNER JOIN trd_planbrg b ON a.no_dokumen=b.no_dokumen 
				AND a.kd_uskpd=b.kd_uskpd 
				WHERE a.no_dokumen = '$nomor' AND a.kd_uskpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'idx'           => $ii,                                
                        'no_dokumen'    => $resulte['no_dokumen'],                      
                        'kd_brg'        => $resulte['kd_brg'],                     
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'totalh'         => $rs->totalh,
                        'total'         => $resulte['total'] ,                        
                        'ket'           => $resulte['ket'],                        
                        'satuan'        => $resulte['satuan'],                    
                        'no_urut'       => $resulte['no_urut'] 				
                        );
                        $ii++;
        }           
        echo json_encode($result);
    }

	
    function simpan_planbrg(){
        $tabel  = $this->input->post('tabel');
        $nomor  = $this->input->post('no');
        $tgl    = $this->input->post('tgl');
        $uskpd   = $this->input->post('uskpd');
        $lokasi   = $this->input->post('lokasi');
        $nmuskpd = $this->input->post('nmuskpd');
        $tahun    = $this->input->post('tahun');
        $total = $this->input->post('total');        
        $csql    = $this->input->post('sql'); 
        //data: ({tabel:'trh_planbrg',no:cno,tgl:ctgl,uskpd:cuskpd,nmuskpd:cnmuskpd,tahun:cthn,total:ctotal}),
        //$usernm     = $this->session->userdata('pcNama');    
        $usernm     = $this->session->userdata('nmuser');      
        $update     = date('Y-m-d');
		$msg        = array();
        
        if ($tabel == 'trh_planbrg') {
            $sql = "delete from trh_planbrg where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg  = $this->db->query($sql);
            //$asgx = $this->mdata->conn($sql);
			
            if ($asg){
                $sql = "insert into trh_planbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,total,tgl_update) 
                        values('$nomor','$tgl','$lokasi','$uskpd','$nmuskpd','$tahun','$usernm','$total','$update')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        } else if ($tabel == 'trd_planbrg') {
            
            // Simpan Detail //                       
                $sql = "delete from trd_planbrg where no_dokumen='$nomor'";
                $asg = $this->db->query($sql);
				//$asgx = $this->mdata->conn($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql  = "insert into trd_planbrg(no_dokumen,kd_brg,kd_rek5,kd_unit,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket,satuan)"; 
    
                    $asg  = $this->db->query($sql.$csql);
					//$asgx = $this->mdata->conn($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                                
        } 
    }
	
     function trd_plbrg(){
	
        $skpd 	= $this->session->userdata('unit_skpd');
        $unit 	= $this->input->post('unit');
        $csql 	= $this->input->post('sql');
        //$csql2 	= $this->input->post('sql2');
        $nodok 	= $this->input->post('nodok'); 
        $msg    = array(); 
        $sql1   = "delete from trd_planbrg where no_dokumen='$nodok'";
        $asg    = $this->db->query($sql1);  
        $sql  	= "insert into trd_planbrg(no_dokumen,kd_brg,kd_rek5,kd_unit,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket,satuan)"; 
        $asg  	= $this->db->query($sql.$csql);
        //$asgx  	= $this->mdata->conn($sql.$csql);
				/**************TRD ISIAN BARANG**************/    
				/* $sqlx  	= "INSERT INTO trd_isianbrg(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,jumlah,harga,total,keterangan)"; 
				$asgx  	= $this->db->query($sqlx.$csql2); */
				/*******************************************/
        if(!($asg)){
          /*$csqlx = "SELECT SUM(total) AS total from trd_planbrg where no_dokumen ='$nodok' and kd_unit='$unit' ";
          $rs 	 = $this->db->query($csqlx)->row() ;  
          if($rs){       
                $sql2 = "update trh_planbrg set total ='$rs->total' where no_dokumen='$nodok' and kd_unit='$unit'";
                $asg2 = $this->db->query($sql2);   
            }*/
            
           //echo number_format($rs->total);
           $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    //exit(); 
        }else{
           $msg = array('pesan'=>'1');
                   echo json_encode($msg);
                    //exit();
        } 
         
    }
	
	function hitung_total() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$nomor	  = $this->input->post('nomor');
		$kol_nomor= $this->input->post('kolnomor');
		$skpd 	  = $this->input->post('skpd');
		$kol_skpd = $this->input->post('kolskpd');
		$table	  = $this->input->post('table');
		$kolom	  = $this->input->post('kolom');
        $where 	  = '';
        if ($skpd <> ''){                               
            $where="where $kol_skpd ='$skpd' and $kol_nomor='$nomor'";            
        }
		
        $sql = "SELECT sum($kolom) AS total FROM $table $where";
        $query1 = $this->db->query($sql);  
        //SELECT SUM(total) AS total FROM trd_planbrg WHERE kd_uskpd='1.24.01.00' AND no_dokumen='0001'
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba= array(
                        'total' => $resulte['total']                                                                              
                        );
                        //$ii++;
        }
        
		
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
    
  function trd_plbrg_hapus(){
		$tahun = $this->session->userdata('ta_simbakda');
        $nomor = $this->input->post('nomor');
		$skpd  = $this->input->post('skpd');
        $kd    = $this->input->post('kd');
        $total = $this->input->post('ctotal');  
        $sql   = "delete from trd_planbrg where no_dokumen='$nomor' and kd_uskpd='$skpd' and kd_brg='$kd' and total='$total'";//no_dokumen='$nomor' and kd_brg='$kd' and 
        $asg   = $this->db->query($sql);
        //$asgx  = $this->mdata->conn($sql);
        if($asg){
            $sql2 = "update trh_planbrg set total ='$total' where no_dokumen='$nomor' and kd_uskpd='$skpd'";
                $asg2 = $this->db->query($sql2);
               // $asg2x= $this->mdata->conn($sql2);
        }
    }
    
	function noreg_trkib_a(){
        
        $no 	= $this->input->post('no');
        $tahun 	= $this->input->post('tahun');            
		
		$sql = $this->db->query("select count(no_reg) as max from trkib_a where kd_brg='$no' and tahun='$tahun'");
		$r = $sql->row();
		$jml = $r->max + 1;
		if(strlen($jml)==1) {
			$jmlh = '000'.$jml;
		} else if(strlen($jml)==2) {
			$jmlh = '00'.$jml;
		} else if(strlen($jml)==3) {
			$jmlh = '0'.$jml;
		}				
		       
        $msg[]=array('pesan'=>$jmlh);
        
        echo json_encode($msg);
    }
	
    function simpan_trkib_a(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
		
        $usernm     = '';
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();
        $sql1 		= "insert into $tabel $lcinsert values $lcvalue";
        $asg1 		= $this->db->query($sql1);
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_a='$urut',no_reg_a='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }          
            
        $sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg = '$kd_brg'";
        $asg2 		= $this->db->query($sql2);           
            //echo '1';
        if ($asg2){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
    }
    
    function update_trkib_a(){
        $query = $this->input->post('st_query');
        $msg        = array();
        $asg = $this->db->query($query);
        if ($asg){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
      
    }
    
	function simpan_trkib_a_kap(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $usernm     = $this->session->userdata('nmuser');;
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        $sql1 		= "insert into $tabel $lcinsert values $lcvalue";
        $asg1 		= $this->db->query($sql1);
        $sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg = '$kd_brg'";
        $asg2 		= $this->db->query($sql2);
                       
            echo '1';
    }
    
    function update_trkib_a_kap(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
    }

    function ambil_masa(){
        $kdbrg = $this->input->post('kdbrg');
        $pers  = $this->input->post('pers');
        $brg =$kdbrg;//"020602";substr($kdbrg,0,6);
        $sqq="
        select MAX(pers_2)as persenan 
            from ms_masa_umur a left join mkelompok b on a.nm_barang = b.nm_kelompok
            WHERE b.kelompok ='$brg' ";
        $sqly=$this->db->query($sqq);
        foreach($sqly->result() as $rows){
            $persenan=$rows->persenan;
        }
        if($pers<=$persenan){
            $sql="
            select b.kelompok as kd_barang,a.nm_barang,a.umur,a.jns_pelihara,a.persentase,a.pers_1,a.pers_2,a.masa_manfaat 
            from ms_masa_umur a left join mkelompok b on a.nm_barang = b.nm_kelompok
            WHERE b.kelompok ='$brg' AND '$pers' BETWEEN a.pers_1 AND a.pers_2";
            $sqlx=$this->db->query($sql);
            
            $result = array();
            $ii = 0;
            foreach($sqlx->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id'     => $ii,        
                            'pers1'  => $resulte['pers_1'],
                            'pers2'  =>$resulte['pers_2'],
                            'masa'  => $resulte['masa_manfaat']
                           
                            );
                            $ii++;
            }
        }else if($pers>$persenan){
            $sql="
            select '' as pers_1,'' as pers_2, max(masa_manfaat) as masa_manfaat
            from ms_masa_umur a left join mkelompok b on a.nm_barang = b.nm_kelompok
            WHERE b.kelompok ='$brg' ";
            $sqlx=$this->db->query($sql);
            
            $result = array();
            $ii = 0;
            foreach($sqlx->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id'     => $ii,        
                            'pers1'  => $resulte['pers_1'],
                            'pers2'  =>$resulte['pers_2'],
                            'masa'  => $resulte['masa_manfaat']
                           
                            );
                            $ii++;
            }
        }
        echo json_encode($result);
        $sqlx->free_result();
    }
	
    function simpan_trkib_b(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $usernm     = $this->session->userdata('nmuser');
        $kd_brg     = $this->input->post('kd_brg');		
        $brg        =substr($kd_brg,0,8);
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();
        $sql1 		= "insert into $tabel $lcinsert values $lcvalue";
        $asg1 		= $this->db->query($sql1);
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_b='$urut',no_reg_b='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);

          }
         
         /**/                     
         $strkd_brg = substr($kd_brg,0,6);
         
         $ceksqkp = $this->db->query("SELECT count(umur) as kd from mbarang_umur where kd_barang='$strkd_brg'")->row();
         $cekkd = $ceksqkp->kd;                      
        if($cekkd==0){
         $ceksqkp = $this->db->query("SELECT nm_kelompok from mkelompok where kelompok='$strkd_brg'")->row();
         $ceknm = $ceksqkp->nm_kelompok;  
         
         $sql        ="insert into mbarang_umur(kd_barang,nama,umur) values ('$strkd_brg','$ceknm','50')";
         $asg        = $this->db->query($sql);
         
         $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_b set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }else{
            $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_b set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }                                    
        /**/
        
		$sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kd_brg'";
		$asg2 		= $this->db->query($sql2);
            //echo '1';
        if ($asg2){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
        /*$unit 		= $this->input->post('kode');	
        $ruang  	= $this->input->post('ruang'); 
		$skpd 		= $this->input->post('skpd');    
        
        $qx = $this->db->query(" select count(nm_ruang) as nm_ruang from mruang where upper(rtrim(nm_ruang))='$ruang' ");
		//$aa	=0;
		$aab=0;
		foreach($qx->result() as $res){ 
			$aa = $res->nm_ruang;
			$aab++;
		}
		if($aa==0){
			$qx = $this->db->query(" select max(kd_ruang)+1 as kode from mruang ");
			foreach($qx->result_array() as $res){ 
				$kode_ruang =$res['kode'];
			}
		
			$qx = $this->db->query(" insert into mruang(kd_ruang,nm_ruang,kd_skpd,kd_unit) values('$kode_ruang','$ruang','$skpd','$unit') ");		
		}*/
    
	}
	
    function load_idmax() {
       if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$skpd 	  = $this->input->post('skpd');
		$table	  = $this->input->post('table');
		$kolom	  = $this->input->post('kolom');
		$kolom2	  = $this->input->post('kolom_skpd');
        $where 	  = '';
        if ($skpd <> ''){                               
            $where="where $kolom2 ='$skpd'";            
        }
        $rs = $this->db->query("select count(*) as tot FROM $table a");
        $trh = $rs->row();
        $sql = "SELECT IFNULL(MAX($kolom),0)+1 AS kode FROM $table $where";
        $query1 = $this->db->query($sql);  
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $coba= array(
                        'kode' 			=> $resulte['kode']                                                                              
                        );
                        //$ii++;
        }
        
        $result["total"] = $trh->tot; 
        $result["rows"] = $coba;   
        echo json_encode($result);
    	$query1->free_result();   
        }
	}
	
    function update_trkib_b(){
        $msg        = array();
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if ($asg){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
      
    }
    
	function simpan_trkib_b_kap(){
		$tabel 		= $this->input->post('tabel');
		$no			= $this->input->post('no');
		$lcinsert 	= $this->input->post('kolom');
		$lcvalue	= $this->input->post('lcvalues');
		$update		= date('y-m-d H:i:s');
		$msg		= array();
		$sql1		= "insert into $tabel $lcinsert values $lcvalue";
		$asg1		= $this->db->query($sql1);
		$sql2		= "update trd_isianbrg set invent='1' where no_dokumen='$no'";
		$asg2		= $this->db->query($sql2);
		echo '1';
	}

	function update_trkib_b_kap(){
		$query = $this->input->post('st_query');
		$asg   = $this->db->query($query);
	}

    function simpan_trkib_c_2(){
        $tabel  	= $this->input->post('tabel');
        $tabel2  	= $this->input->post('tabel2');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
        $brg        = substr($kd_brg,0,8);
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
		
        $id_barang 	= $this->input->post('id_barang');
        $kib_kdp 	= $this->input->post('kib_kdp');
        $id_kdp 	= $this->input->post('id_kdp');
        $tgl_kdp 	= $this->input->post('tgl_kdp');
		
        $usernm     = '';
        $update     = date('Y-m-d');      
        $msg        = array();
		//$sql1 		= "insert into $tabel $lcinsert $lcvalue";
		//$asg1 		= $this->db->query($sql1);
		
		$sql1 = "insert into trkib_c(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_golongan,kd_bidang,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,jumlah,total,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,alamat2,alamat3,konstruksi,konstruksi2,luas_lantai,kondisi,dasar,keterangan,kd_skpd,kd_unit,milik,wilayah,kd_tanah,username,tgl_update,tahun,no_urut,metode,masa_manfaat,nilai_sisa,kd_pemilik,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5)"; 
        $asg1 = $this->db->query($sql1.$lcvalue);
		
		if($asg1){
			
         /**/                     
         $strkd_brg = substr($kd_brg,0,6);
         
         $ceksqkp = $this->db->query("SELECT count(umur) as kd from mbarang_umur where kd_barang='$strkd_brg'")->row();
         $cekkd = $ceksqkp->kd;                      
        if($cekkd==0){
         $ceksqkp = $this->db->query("SELECT nm_kelompok from mkelompok where kelompok='$strkd_brg'")->row();
         $ceknm = $ceksqkp->nm_kelompok;  
         
         $sql        ="insert into mbarang_umur(kd_barang,nama,umur) values ('$strkd_brg','$ceknm','50')";
         $asg        = $this->db->query($sql);
         
         $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_c set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }else{
            $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_c set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }                                    
        /**/
			
			if($asg){
				$sql2       = "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kd_brg'";
				$asg2         = $this->db->query($sql2);	
				
				if ($asg2){
					$msg[]=array('pesan'=>'1');
				}else{
					$msg[]=array('pesan'=>'2');
				}
				
			}else{
				$msg[]=array('pesan'=>'2');
			}			
		
		}else{
			$msg[]=array('pesan'=>'2');
		}
		
        echo json_encode($msg);
    }
	
	
    function simpan_trkib_c(){
        $tabel  	= $this->input->post('tabel');
        $tabel2  	= $this->input->post('tabel2');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
        $brg        = substr($kd_brg,0,8);
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
		
        $id_barang 	= $this->input->post('id_barang');
        $kib_kdp 	= $this->input->post('kib_kdp');
        $id_kdp 	= $this->input->post('id_kdp');
        $tgl_kdp 	= $this->input->post('tgl_kdp');
		
        $usernm     = '';
        $update     = date('Y-m-d');      
        $msg        = array();
		//$sql1 		= "insert into $tabel $lcinsert $lcvalue";
		//$asg1 		= $this->db->query($sql1);
		
		$sql1 = "insert into trkib_c(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_golongan,kd_bidang,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,jumlah,total,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,alamat2,alamat3,konstruksi,konstruksi2,luas_lantai,kondisi,dasar,keterangan,kd_skpd,kd_unit,milik,wilayah,kd_tanah,username,tgl_update,tahun,no_urut,metode,masa_manfaat,nilai_sisa,kd_pemilik,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,mutasi_masuk,kd_skpd_asal)"; 
        $asg1 = $this->db->query($sql1.$lcvalue);
		
		if($asg1){
			
         /**/                     
         $strkd_brg = substr($kd_brg,0,6);
         
         $ceksqkp = $this->db->query("SELECT count(umur) as kd from mbarang_umur where kd_barang='$strkd_brg'")->row();
         $cekkd = $ceksqkp->kd;                      
        if($cekkd==0){
         $ceksqkp = $this->db->query("SELECT nm_kelompok from mkelompok where kelompok='$strkd_brg'")->row();
         $ceknm = $ceksqkp->nm_kelompok;  
         
         $sql        ="insert into mbarang_umur(kd_barang,nama,umur) values ('$strkd_brg','$ceknm','50')";
         $asg        = $this->db->query($sql);
         
         $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_c set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }else{
            $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_c set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }                                    
        /**/
			
			if($asg){
				$sql2       = "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kd_brg'";
				$asg2         = $this->db->query($sql2);	
				
				if ($asg2){
					$msg[]=array('pesan'=>'1');
				}else{
					$msg[]=array('pesan'=>'2');
				}
				
			}else{
				$msg[]=array('pesan'=>'2');
			}			
		
		}else{
			$msg[]=array('pesan'=>'2');
		}
		
        echo json_encode($msg);
    }
	
 function update_trkib_c(){
        $msg        = array();
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if ($asg){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
    }
    
    
    function simpan_trkib_d(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $kd_brg     = $this->input->post('kd_brg');
        $brg        =substr($kd_brg,0,8);
        $msg        = array();
        //$usernm     = '';
        //$update     = date('y-m-d H:i:s');      
        //$msg        = array();
		$sql1 		= "insert into $tabel $lcinsert values $lcvalue";
		$asg1 		= $this->db->query($sql1);
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_d='$urut',no_reg_d='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
        
       /**/                     
         $strkd_brg = substr($kd_brg,0,6);
         
         $ceksqkp = $this->db->query("SELECT count(umur) as kd from mbarang_umur where kd_barang='$strkd_brg'")->row();
         $cekkd = $ceksqkp->kd;                      
        if($cekkd==0){
         $ceksqkp = $this->db->query("SELECT nm_kelompok from mkelompok where kelompok='$strkd_brg'")->row();
         $ceknm = $ceksqkp->nm_kelompok;  
         
         $sql        ="insert into mbarang_umur(kd_barang,nama,umur) values ('$strkd_brg','$ceknm','50')";
         $asg        = $this->db->query($sql);
         
         $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_d set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }else{
            $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_d set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }                                    
        /**/
		
        $sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kd_brg'";
		$asg2 		= $this->db->query($sql2);
            //echo '1';
        if ($asg2){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
    }
    
	function simpan_trkib_d_kap(){
		$tabel		= $this->input->post('tabel');
		$no			= $this->input->post('no');
		$lcinsert	= $this->input->post('kolom');
		$lcvalue	= $this->input->post('values');
		$usernm		= '';
		$update		= date('y-m-d H:i:s');
		$msg		= array();
		$sql1		= "insert into $tabel $lcinsert values $lcvalue";			
	}
	
    function update_trkib_d(){
        $msg        = array();
        $query 		= $this->input->post('st_query');
        $asg 		= $this->db->query($query);
        if ($asg){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
    }
    
    function simpan_trkib_e(){
        $tabel  = $this->input->post('tabel');
        $no = $this->input->post('no');
        $lcinsert = $this->input->post('kolom');
        $lcvalue = $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $kdbrg      = $this->input->post('kdbrg');
        
      //  $usernm     = $this->session->userdata('pcNama');    
        $usernm     = '';
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();
        
      
           // $sql = "delete from $tabel where no_dokumen='$no' ";
//            $asg = $this->db->query($sql);
            
                $sql1 = "insert into $tabel $lcinsert values $lcvalue";
                $asg1 = $this->db->query($sql1);
				
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_e='$urut',no_reg_e='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
                $sql2 = "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kdbrg'";
                $asg2 = $this->db->query($sql2);
                       
           // echo '1';
        if ($asg2){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
            
     
    }
	
	     function update_trkib_e(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
    }

    
    function simpan_trkib_f(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $kd_brg     = $this->input->post('kd_brg');
        $brg        =substr($kd_brg,0,8);
        $msg        = array();
        //$usernm     = '';
        //$update     = date('y-m-d H:i:s');      
        //$msg        = array();
		$sql1 		= "insert into $tabel $lcinsert values $lcvalue";
		$asg1 		= $this->db->query($sql1);
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_d='$urut',no_reg_d='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
        
       /**/                     
         $strkd_brg = substr($kd_brg,0,6);
         
         $ceksqkp = $this->db->query("SELECT count(umur) as kd from mbarang_umur where kd_barang='$strkd_brg'")->row();
         $cekkd = $ceksqkp->kd;                      
        if($cekkd==0){
         $ceksqkp = $this->db->query("SELECT nm_kelompok from mkelompok where kelompok='$strkd_brg'")->row();
         $ceknm = $ceksqkp->nm_kelompok;  
         
         $sql        ="insert into mbarang_umur(kd_barang,nama,umur) values ('$strkd_brg','$ceknm','50')";
         $asg        = $this->db->query($sql);
         
         $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_f set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }else{
            $sqlu       =$this->db->query("SELECT umur from mbarang_umur where kd_barang='$strkd_brg'");
            foreach($sqlu->result_array() as $res){ 
                            $umur=$res['umur'];
                        }
                        
            $sql        ="update trkib_f set masa_manfaat='$umur' where no_dokumen='$no' and kd_brg='$kd_brg'";
            $asg        = $this->db->query($sql);
        }                                    
        /**/
		
        $sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg='$kd_brg'";
		$asg2 		= $this->db->query($sql2);
            //echo '1';
        if ($asg2){
            $msg[]=array('pesan'=>'1');
        }else{
            $msg[]=array('pesan'=>'2');
        }
        echo json_encode($msg);
    }
	
	    function simpan_trkib_g(){
        $tabel  = $this->input->post('tabel');
        $no = $this->input->post('no');
        $lcinsert = $this->input->post('kolom');
        $lcvalue = $this->input->post('lcvalues'); 
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
                $sql1 = "insert into $tabel $lcinsert values $lcvalue";
                $asg1 = $this->db->query($sql1);
                
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_f='$urut',no_reg_f='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
                $sql2 = "update trd_isianbrg set invent='1' where no_dokumen='$no'";
                $asg2 = $this->db->query($sql2);
                       
            echo '1';
    }
    
	
     function update_trkib_g(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
    }
    
    function hapus_trkib_a(){
        $nomor 	   = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_a where id_barang='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
	function hapus_trkib_a_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_a_kap where id_barang='$nomor'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
    function hapus_trkib_b(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $kdbrg     = $this->input->post('kdbrg');
        $sql = "delete from trkib_b where id_barang='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok' and kd_brg='$kdbrg'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
    function hapus_trkib_b_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_b_kap where no_dokumen='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
    function hapus_trkib_c(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_c where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
	function hapus_trkib_c_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $kdbrg  = $this->input->post('kdbrg');
        $sql = "delete from trkib_c_kap where no_dokumen ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok' and kd_brg='$kdbrg'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
    function hapus_trkib_d(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_d where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
	function hapus_trkib_d_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_d_kap where no_dokumen ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
    function hapus_trkib_e(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_e where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
	function hapus_trkib_e_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_e_kap where nomor_dok ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
    function hapus_trkib_f(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_f where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
	  function hapus_trkib_f_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_f_kap where no_dokumen ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
	    
    function hapus_trkib_g(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_f where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    
	  function hapus_trkib_g_kap(){
        $nomor = $this->input->post('no');
        $nomor_dok = $this->input->post('dok');
        $sql = "delete from trkib_f_kap where no_dokumen ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='0' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
       function hapus_planbrg(){
        $nomor = $this->input->post('no');
        $kode  = $this->input->post('skpd');
        $msg = array();
        $sql = "delete from trd_planbrg where no_dokumen='$nomor' and kd_uskpd='$kode'";
        $asg = $this->db->query($sql);
        //$asgx = $this->mdata->conn($sql);
        if ($asg){
            $sql = "delete from trh_planbrg where no_dokumen='$nomor' and kd_uskpd='$kode'";
            $asg = $this->db->query($sql);
            //$asgx = $this->mdata->conn($sql);
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
    //////////End Rencana Pengadaan barang/////////
    
    //////////Rencana Pemeliharaan Barang//////////
 function rencana_pemeliharaan_barang()
    {
        $data['page_title']= 'TRANSAKSI RENCANA Pemeliharaan BARANG';
        $this->template->set('title', 'TRANSAKSI RENCANA PEMELIHARAAN BARANG');   
        $this->template->load('index','transaksi/tr_pemeliharaan_barang',$data) ;         
    }
    
    function trh_treatbrg(){ 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_unit like '%' ";
        }else{
            $where1 = "where kd_uskpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="where (upper(no_dokumen) like upper('%$kriteria%') or tgl_dokumen like '%$kriteria%' or upper(nm_uskpd) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_treatbrg $where2" ;
		//$sql = "SELECT SUM(total) AS total from trd_treatbrg where no_dokumen = '$nomor' and kd_uskpd = '$skpd'" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "select * from trh_treatbrg $where1 $where2 order by tgl_dokumen,no_dokumen,kd_uskpd ";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'tgl_dokumen'   => $resulte['tgl_dokumen'],
                        'kd_unit'       => $resulte['kd_unit'],
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'nm_uskpd'      => $resulte['nm_uskpd'],
                        'tahun'         => $resulte['tahun'],
                        'total'         => $resulte['total']			                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
	}   

     function trh_pelihara_barang(){ 
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_uskpd like '%' ";
        }else{
            $where1 = "where kd_uskpd ='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(no_dokumen) like upper('%$kriteria%') or tgl_dokumen like '%$kriteria%' or upper(nm_uskpd) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_trpelihara $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select * from trh_trpelihara $where1 $where2 order by tgl_dokumen,no_dokumen,kd_uskpd";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                        
                        'no_dokumen'    => $resulte['no_dokumen'],
                        'no_reg'   		=> $resulte['no_reg'],
                        'tgl_dokumen'   => $resulte['tgl_dokumen'],
                        'kd_unit'       => $resulte['kd_unit'],
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'nm_uskpd'      => $resulte['nm_uskpd'],
                        'tahun'         => $resulte['tahun'],
                        'total'         => $resulte['total']			                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
	}       
	
    function trd_treatbrg(){
        $nomor = $this->input->post('no');
        $skpd = $this->session->userdata('skpd'); 
		
          $csql = "SELECT SUM(total) AS total from trd_treatbrg where no_dokumen = '$nomor' and kd_uskpd = '$skpd'";
          $rs   = $this->db->query($csql)->row() ;  
		  
        $sql = "SELECT b.* FROM trh_treatbrg a INNER JOIN trd_treatbrg b ON a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd and a.kd_unit=b.kd_unit
                WHERE a.no_dokumen = '$nomor' and b.kd_uskpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_dokumen'    => $resulte['no_dokumen'],                      
                        'kd_brg'        => $resulte['kd_brg'],                      
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'total'         => $resulte['total'], 
                        'kd_rek'        => $resulte['kd_rek'],
                        'biaya_pelihara'         => $resulte['biaya_pelihara'],
                        'uraian_pelihara'         => $resulte['uraian_pelihara'],                       
                        'ket'           => $resulte['ket'],
						'totalxx' 		=> $rs->total
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
	
	function ambil_trd_treatbrg(){
        $nomor = $this->input->post('no');
        $skpd = $this->session->userdata('skpd');                
        $sql = "SELECT * FROM trd_treatbrg where kd_uskpd = '$skpd' order by no_dokumen";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_dokumen'    	=> $resulte['no_dokumen'],                      
                        'kd_brg'        	=> $resulte['kd_brg'],                      
                        'kd_uskpd'      	=> $resulte['kd_uskpd'],
                        'nm_brg'        	=> $resulte['nm_brg'],
                        'merek'         	=> $resulte['merek'],
                        'jumlah'        	=> $resulte['jumlah'],
                        'harga'         	=> $resulte['harga'],
                        'total'         	=> $resulte['total'], 
                        'kd_rek'        	=> $resulte['kd_rek'],
                        'biaya_pelihara'    => $resulte['biaya_pelihara'],
                        'uraian_pelihara'   => $resulte['uraian_pelihara'],                       
                        'ket'           	=> $resulte['ket']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
	
        
	  function ambil_kib_aset($skontrak1='',$sskpd='',$skd_kegiatan='',$srek5=''){
        //$skontrak1='100.5231704'; $sskpd='1.20.12.01'; $srek5='5231704'; $skd_kegiatan='1.02.1.02.01.06.02.11';
        $skontrak2 = explode(".",$skontrak1);
        $skontrak = $skontrak2[0]."/".$skontrak2[1];
        $skpd = $this->session->userdata('skpd');                        
        $sqll = $this->db->query("select a.kd_brg,left(a.kd_brg,2) as gol,left(a.kd_brg,6) as kel,a.nm_brg,b.tahun from trd_isianbrg a
		left join trh_isianbrg b on b.no_dokumen = a.no_dokumen
		where b.no_dokumen='$skontrak' and b.kd_uskpd='$sskpd' and a.kd_rek5='$srek5' and a.kd_kegiatan='$skd_kegiatan'");
        foreach($sqll->result_array() as $resultess)
        {
            $kd_gol = $resultess['gol'];
            $kd_kel = $resultess['kel'];
            $kd_barang = $resultess['kd_brg'];
            $nm_barang = $resultess['nm_brg'];
            $tahun = $resultess['tahun'];
            
            $parrvar = "where no_dokumen='$skontrak' and kd_skpd='$sskpd' and kd_rek5='$srek5' and kd_kegiatan='$skd_kegiatan' and tahun='$tahun'";
            
            if($kd_gol=='01'){                                
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,'' as masa_manfaat,'' as pemeliharaan_ke,keterangan,tahun,kondisi from trkib_a $parrvar";
            }
            if($kd_gol=='02'){
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,(select umur from mbarang_umur where kd_barang='$kd_kel') as masa_manfaat,pemeliharaan_ke,keterangan,tahun,kondisi from trkib_b $parrvar";
            }                
            if($kd_gol=='03'){
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,(select umur from mbarang_umur where kd_barang='$kd_kel') as masa_manfaat,pemeliharaan_ke,keterangan,tahun,kondisi from trkib_c $parrvar";
            }
            if($kd_gol=='04'){
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,(select umur from mbarang_umur where kd_barang='$kd_kel') as masa_manfaat,pemeliharaan_ke,keterangan,tahun,kondisi from trkib_d $parrvar";
            }
            if($kd_gol=='05'){
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,'' as masa_manfaat,'' as pemeliharaan_ke,keterangan,tahun,kondisi from trkib_e $parrvar";
            }
            if($kd_gol=='06'){
                $parrsql = "select rtrim(kd_skpd)+'/'+tahun+'/'+kd_brg+'/'+no_reg as id_barang,no_reg,no_dokumen,kd_brg,kd_skpd,nm_brg,jumlah,nilai,kd_rek5,kd_kegiatan,'' as masa_manfaat,'' as pemeliharaan_ke,keterangan,tahun,kondisi from trkib_f $parrvar";
            }  
            
            
            $queryhasil = $this->db->query($parrsql);
            $result = array();
            $ii = 0;
            foreach($queryhasil->result_array() as $resulte)
            {
                  $total = $resulte['nilai'] * $resulte['jumlah'];  
                  if($resulte['pemeliharaan_ke']==""){
                    $pelihara = 0;
                  }else{
                    $pelihara = $resulte['pemeliharaan_ke'];
                  }
                  $brggg = $resulte['kd_brg'];
                  $bid = substr($brggg,0,4);
                  
                  $sqll = $this->db->query("select nm_bidang from mbidang where bidang='$bid'")->row();
                  $nm_bid = $sqll->nm_bidang; 
                  
                  $result[] = array(                                
                        'id_barang'    	=> $resulte['id_barang'],
                        'no_reg'    	=> $resulte['no_reg'],  
                        'no_dokumen'    	=> $resulte['no_dokumen'],                      
                        'kd_brg'        	=> $resulte['kd_brg'],
                        'nm_brg'        	=> $resulte['nm_brg'],                        
                        'kd_uskpd'      	=> $resulte['kd_skpd'],
                        'nm_brg'        	=> $resulte['nm_brg'],                        
                        'jumlah'        	=> $resulte['jumlah'],
                        'nilai'         	=> $resulte['nilai'],
                        'total'         	=> $total,
                        'nilai2'         	=> number_format($total),  
                        'kd_rek'        	=> $resulte['kd_rek5'],
                        'kd_keg'        	=> $resulte['kd_kegiatan'],
                        'masa_manfaat'      => $resulte['masa_manfaat'],
                        'pelihara'          => $pelihara,                       
                        'ket'           	=> $resulte['keterangan'],
                        'tahun'           	=> $resulte['tahun'],
                        'kondisi'           => $resulte['kondisi'], 
                        'kd_bid'           =>  $bid,
                        'nm_bid'           =>  $nm_bid,                                                                                                                                                           
                        );
                        $ii++;    
                
            }   
               
        }    
                         
        echo json_encode($result);
        $queryhasil->free_result();
    }
	
	
	
	
   function trd_trpelihara(){
        $nomor = $this->input->post('no');
        //$nomor = '0001/RSUD/2010';                    
        $sql = "SELECT b.* FROM trh_trpelihara a INNER JOIN trd_trpelihara b ON a.no_dokumen=b.no_dokumen
                WHERE a.no_dokumen = '$nomor' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                            'idx'               => $ii,
                            'no_dokumen'        => $resulte['no_dokumen'],   
                            'no_kontrak'        => $resulte['no_kontrak'],
                            'kd_uskpd'          => $resulte['kd_uskpd'], 
                            'kd_unit'           => $resulte['kd_unit'],
                            'id_barang'         => $resulte['id_barang'], 
                            'kd_golongan'       => $resulte['kd_golongan'],  
                            'nm_golongan'       => $resulte['nm_golongan'],  
                            'kd_bidang'         => $resulte['kd_bidang'], 
                            'nm_bidang'         => $resulte['nm_bidang'],
                            'kd_brg'            => $resulte['kd_brg'],
                            'nm_brg'            => $resulte['nm_brg'],
                            'thn_kib'           => $resulte['thn_kib'],
                            'pelihara'          => $resulte['pelihara'],
                            'kd_rek'            => $resulte['kd_rek'],
                            'total'             => $resulte['total'],
                            'umur'              => $resulte['umur'],
                            'uraian_pelihara'   => $resulte['uraian_pelihara'],
                            'ket'               => $resulte['ket'],
                            'biaya_pelihara'    => $resulte['biaya_pelihara'],
                            'harga'             => $resulte['harga']
                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function trd_trpelihara_det(){
        $nomor = $this->input->post('no');
        $idbrg = $this->input->post('idbrg');                    
        $sql = "SELECT a.uraian_pelihara,a.ket,b.* FROM trd_trpelihara a JOIN temp_pelihara b ON a.no_dokumen=b.no_dokumen AND a.id_barang=b.id_barang 
        WHERE a.no_dokumen='$nomor' AND a.id_barang='$idbrg' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                            'idx'               => $ii,
                            'uraian_pelihara'   => $resulte['uraian_pelihara'],
                            'ket'               => $resulte['ket'],
                            'no_dokumen'        => $resulte['no_dokumen'],
                            'no_kontrak'        => $resulte['no_kontrak'],
                            'kd_uskpd'          => $resulte['kd_uskpd'],
                            'kd_unit'           => $resulte['kd_unit'],
                            'id_barang'         => $resulte['id_barang'],
                            'kd_golongan'       => $resulte['kd_golongan'],
                            'nm_golongan'       => $resulte['nm_golongan'],
                            'kd_bidang'         => $resulte['kd_bidang'],
                            'nm_bidang'         => $resulte['nm_bidang'],
                            'kd_brg'            => $resulte['kd_brg'],
                            'nm_brg'            => $resulte['nm_brg'],
                            'kd_rek'            => $resulte['kd_rek'],
                            'thn_kib'           => $resulte['thn_kib'],
                            'pelihara'          => $resulte['pelihara'],
                            'umur_lama'         => $resulte['umur_lama'],
                            'umur_baru'         => $resulte['umur_baru'],
                            'nilai_oleh'        => $resulte['nilai_oleh'],
                            'nilai_pelihara'    => $resulte['nilai_pelihara']
                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
	function simpan_treatbrg(){
        $tabel  = $this->input->post('tabel');
        $nomor  = $this->input->post('no');
        $tgl    = $this->input->post('tgl');
        $uskpd   = $this->input->post('uskpd');
        $lokasi   = $this->input->post('lokasi');
        $nmuskpd = $this->input->post('nmuskpd');
        $tahun    = $this->input->post('tahun');
        $total = $this->input->post('total');        
        $csql    = $this->input->post('sql'); 
        //data: ({tabel:'trh_planbrg',no:cno,tgl:ctgl,uskpd:cuskpd,nmuskpd:cnmuskpd,tahun:cthn,total:ctotal}),
        //$usernm     = $this->session->userdata('pcNama');    
        $usernm     = $this->session->userdata('nmuser');      
        $update     = date('Y-m-d');
		$msg        = array();
        
        if ($tabel == 'trh_treatbrg') {
            $sql = "delete from trh_treatbrg where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg  = $this->db->query($sql);
            //$asgx = $this->mdata->conn($sql);
			
            if ($asg){
                $sql = "insert into trh_treatbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,total,tgl_update) 
                        values('$nomor','$tgl','$lokasi','$uskpd','$nmuskpd','$tahun','$usernm','$total','$update')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        } else if ($tabel == 'trd_treatbrg') {
            
            // Simpan Detail //                       
                $sql = "delete from trd_treatbrg where no_dokumen='$nomor'";
                $asg = $this->db->query($sql);
				//$asgx = $this->mdata->conn($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql  = "insert into trd_treatbrg(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,biaya_pelihara,harga,total,ket,satuan)"; 
                    $asg  = $this->db->query($sql.$csql);
					//$asgx = $this->mdata->conn($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                                
        } 
    }

	
	
  /*
  function simpan_treatbrg(){
        $tabel  	= $this->input->post('tabel');
        $nomor  	= $this->input->post('no');
        $tgl    	= $this->input->post('tgl');
        $uskpd   	= $this->input->post('uskpd');
        $unit   	= $this->input->post('mlokasi');
        $nmuskpd 	= $this->input->post('nmuskpd');
        $tahun    	= $this->input->post('tahun');
        $total 		= $this->input->post('total');        
        $csql    	= $this->input->post('sql'); 
     
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        
        if ($tabel == 'trh_treatbrg') {
            $sql = "delete from trh_treatbrg where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg  = $this->db->query($sql);
			//$asgx = $this->mdata->conn($sql);
            if ($asg){
                $sql = "insert into trh_treatbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,total) 
                        values('$nomor','$tgl','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$update','$total')";
                $asg  = $this->db->query($sql);
                //$asgx = $this->mdata->conn($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }  
    }*/
	
	
function simpan_peliharabrg(){
        $tabel  	= $this->input->post('tabel');
		$no_dok  	= $this->input->post('nomor_urut');
        $nomor  	= $this->input->post('no');
        $tgl    	= $this->input->post('tgl');
        $uskpd   	= $this->input->post('uskpd');
        $unit   	= $this->input->post('lokasi');
        $nmuskpd 	= $this->input->post('nmuskpd');
        $tahun    	= $this->input->post('tahun');
        $total 		= $this->input->post('total');        
        $csql    	= $this->input->post('sql'); 
        $cumur      = $this->input->post('h_umur');
        $idbrg      = $this->input->post('id_brg');
        $kd_brg      = $this->input->post('kd_brg');
        $jns        = $this->input->post('jns');
        $pl         = $this->input->post('pl');
        $usernm     = $this->session->userdata('nmuser');
        $update     = date('Y-m-d');      
        $msg        = array();						
		
        /*         
        $goll = substr($kd_brg,0,2); 
            
        if($goll=='02'){
            $tabel2='trkib_b';
        }else if($goll=='03'){
            $tabel2='trkib_c';
        }else if($goll=='04'){
            $tabel2='trkib_d';
        }
        	*/
		//trh	
		   $pelihara = $this->db->query("select max(left(no_dokumen,4)) as nomax from trh_trpelihara")->row();
		   $nor = $pelihara->nomax;
		   if($nor==null){				
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
			$gab = $no_urut .'/'. $uskpd;
			
		//trd	
		   /*$pelihara2 = $this->db->query("select max(RIGHT(no_dokumen,4)) as nomax from trd_trpelihara")->row();
		   $nor2 = $pelihara2->nomax;
		   if($nor2==null){				
				$no_urut2 = "0001";
			} else {
				$nor2 = $nor2 + 1;
				if(strlen($nor2)==1) {
					$no_urut2 = '000'.$nor2;	
				} else if(strlen($nor2)==2) {
					$no_urut2 = '00'.$nor2;
				} else if(strlen($nor2)==3) {
					$no_urut2 = '0'.$nor2;	
				} else if(strlen($nor2)==4) {
					$no_urut2 = ''.$nor2;	
				}		
			}		
			$gab2 = $no_urut2;	*/
		
        if ($tabel == 'trh_trpelihara') {		   		   
			
            $sql = "delete from trh_trpelihara where kd_uskpd='$uskpd' and no_dokumen='$gab'";
            $asg = $this->db->query($sql);
            if ($asg){ 
                $sql = "insert into trh_trpelihara(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,total) 
                        values('$gab','$tgl','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$update','$total')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        } else if($tabel== 'trd_trpelihara'){
            //$sql = "delete from trd_trpelihara where kd_uskpd='$uskpd' and no_dokumen='$gab2' and id_barang='$idbrg'";
            $sql = "delete from trd_trpelihara where kd_uskpd='$uskpd' and no_dokumen='$no_dok'";
            $asg = $this->db->query($sql);
            if ($asg){ 
                //asli $sql1 = "insert into trd_trpelihara(no_dokumen,no_kontrak,kd_uskpd,kd_unit,id_barang,kd_golongan,nm_golongan,kd_bidang,nm_bidang,kd_brg,nm_brg,thn_kib,pelihara,kd_rek,total,umur,uraian_pelihara,ket,biaya_pelihara,harga,kd_kegiatan)";
                $sql1 = "insert into trd_trpelihara(no_dokumen,kd_uskpd,id_barang,kd_brg,nm_brg,thn_kib,pelihara,kd_rek,total,umur,uraian_pelihara,ket,biaya_pelihara,harga)";
				$asg1 = $this->db->query($sql1.$csql);
				/*
				$sql  = "UPDATE trd_trpelihara SET no_dokumen='$gab2' where no_dokumen='$nomor'";
                $asg  = $this->db->query($sql);
				*/
				$jum_pl = $pl + 1;
                
                if($cumur=='50'){
                    $jum_cumur = '50';                    
                }
                if($cumur<'50'){
                    $jum_cumur = $cumur;                                        
                }
                if($cumur>'50'){
                    $jum_cumur = '50';                                        
                }                 
                                
                //$sql  = "UPDATE $tabel2 SET masa_manfaat='$jum_cumur',nilai='$total',pemeliharaan_ke='$jum_pl' WHERE id_barang='$idbrg' AND kd_skpd='$uskpd'";
                $asg  = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
        }else if($tabel=='temp_pelihara'){
            $sql = "delete from temp_pelihara where kd_uskpd='$uskpd' and no_dokumen='$gab' ";
            $asg = $this->db->query($sql);
            if ($asg){ 
                $sql = "insert into temp_pelihara (no_dokumen,kd_uskpd,kd_unit,id_barang,kd_brg,nm_brg,thn_kib,pelihara,kd_rek,nilai_oleh,umur_lama,nilai_pelihara,umur_baru)";
                $asg = $this->db->query($sql.$csql);
				
				$sql  = "UPDATE temp_pelihara SET no_dokumen='$gab' where no_dokumen='$no_dok'";
                $asg  = $this->db->query($sql);
                
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1','nomor_urut'=>$gab);
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
        } 
    }
	
    function save_treatbrg(){
        
        $csql  = $this->input->post('sql');
        $nodok = $this->input->post('nodok');  
        $lokasi = $this->input->post('lokasi');   
        $sql  = "insert into trd_treatbrg(no_dokumen,id_barang,kd_brg,kd_unit,kd_uskpd,nm_brg,harga,biaya_pelihara,kd_rek,jumlah,uraian_pelihara,total,ket,satuan)"; 
        $asg  = $this->db->query($sql.$csql);
        //$asgx  = $this->mdata->conn($sql.$csql);
        if($asg){
          $csql = "SELECT SUM(biaya_pelihara) AS total from trd_treatbrg where no_dokumen = '$nodok' and kd_unit='$lokasi' ";
          $rs  = $this->db->query($csql)->row() ;  
          $rsx = $this->mdata->conn($csql)->row() ;  
          if($rs){       
                $sql2 = "update trh_treatbrg set total ='$rs->total'  where no_dokumen='$nodok' and kd_unit='$lokasi' ";
                $asg2  = $this->db->query($sql2);  
                //$asg2x = $this->mdata->conn($sql2);   
            }
            
           echo number_format($rs->total); 
        }else{
           echo 'Data Tidak Tersimpan';
        } 
         
    }
  
  
    function save_peliharabrg(){
        $csql = $this->input->post('sql');
        $nodok = $this->input->post('nodok');  
        $unit = $this->input->post('lokasi');  
        $sql  = "insert into trd_trpelihara(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,harga,biaya_pelihara,kd_rek,jumlah,uraian_pelihara,total,ket)"; 
        $asg  = $this->db->query($sql.$csql);
        if($asg){
          $csql = "SELECT SUM(biaya_pelihara) AS total from trd_trpelihara where no_dokumen = '$nodok' and kd_unit='$unit'";
          $rs = $this->db->query($csql)->row() ;  
          if($rs){       
                $sql2 = "update trd_trpelihara set total ='$rs->total'  where no_dokumen='$nodok' and kd_unit='$unit'";
                $asg2 = $this->db->query($sql2);   
            }
            
           echo number_format($rs->total); 
        }else{
           echo 'Data Tidak Tersimpan';
        } 
         
    }    
    function hps_trd_treatbrg(){
        $nomor = $this->input->post('nomor');
        $kd    = $this->input->post('kd');
        $total = $this->input->post('ctotal');
                 
        $sql = "delete from trd_treatbrg where no_dokumen='$nomor' and kd_brg='$kd' ";
        $asg  = $this->db->query($sql);
        //$asgx = $this->mdata->conn($sql);
        if($asg){
            $sql2 = "update trh_treatbrg set total ='$total' where no_dokumen='$nomor' ";
                $asg2  = $this->db->query($sql2);
                //$asg2x = $this->mdata->conn($sql2);
         }
    }
	
    function hps_trd_trpelihara(){
        $nomor = $this->input->post('nomor');
        $kd    = $this->input->post('kd');
        $total = $this->input->post('ctotal');
                 
        $sql = "delete from trd_trpelihara where no_dokumen='$nomor' and kd_brg='$kd' ";
        $asg = $this->db->query($sql);
        if($asg){
            $sql2 = "update trh_trpelihara set total ='$total' where no_dokumen='$nomor' ";
                $asg2 = $this->db->query($sql2);
         }
    }    
              
     function hapus_treatbrg(){
		//$skpd = $this->session->userdata('unit_skpd');
        $skpd	= $this->session->userdata('skpd');

        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trd_treatbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
        $asg  = $this->db->query($sql);
        //$asgx = $this->mdata->conn($sql);
        if ($asg){
            $sql = "delete from trh_treatbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
            $asg  = $this->db->query($sql);
            //$asgx = $this->mdata->conn($sql);
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }    
    
     function hapus_peliharabrg(){
        $nomor  = $this->input->post('no');
        $skpd   = $this->input->post('skpd');
        $unit   = $this->input->post('unit');
        $tabel  ='';
        $msg    = array();

        $sql1 =$this->db->query("SELECT * FROM temp_pelihara WHERE no_dokumen='$nomor' AND kd_uskpd='$skpd' AND umur_lama!='0'");
        foreach($sql1->result_array() as $res){ 
                            $idbrg= $res['id_barang'];                            
                            $peli = $res['pelihara'];
                            
                            if($peli==0){
                               $peli = 0; 
                            }
                            if($peli>0){
                                $peli = $peli-1;
                            }
                            if($peli<0){
                                $peli = 0;
                            }
                            
                            $umur = $res['umur_lama'];
                            $nilai= $res['nilai_oleh'];
                            $bid  = $res['kd_bidang'];
                        }
        $gol = substr($bid,0,2);                
        if($gol=='02'){
            $tabel='trkib_b';
        }else if($gol=='03'){
            $tabel='trkib_c';
        }else if($gol=='04'){
            $tabel='trkib_d';
        }
        
        $sql = "UPDATE $tabel SET masa_manfaat='$umur',nilai='$nilai',pemeliharaan_ke='$peli' WHERE id_barang='$idbrg'";
        //$sql = "UPDATE $tabel SET masa_manfaat='$umur',pemeliharaan_ke='$peli' WHERE id_barang='$idbrg'";
        $asg  = $this->db->query($sql);
        if ($asg){
            $sql1 = "DELETE FROM trd_trpelihara WHERE no_dokumen='$nomor' AND kd_uskpd='$skpd' AND kd_unit='$unit' AND id_barang='$idbrg'";
            $asg1 = $this->db->query($sql1);

            $sql2 = "DELETE FROM trh_trpelihara WHERE no_dokumen='$nomor' AND kd_uskpd='$skpd' AND kd_unit='$unit'";
            $asg2 = $this->db->query($sql2);

            $sql = "DELETE FROM temp_pelihara WHERE no_dokumen='$nomor' AND kd_uskpd='$skpd' AND kd_unit='$unit' AND id_barang='$idbrg'";
            $asg = $this->db->query($sql);
            if (!($asg)){
              //$msg = array('pesan'=>'0');
              echo '0';//'json_encode($msg);
               exit();
            } 
        } else {
            //$msg = array('pesan'=>'0');
            //echo json_encode($msg);
            echo '0';
            exit();
        }
        //$msg = array('pesan'=>'1');
        //echo json_encode($msg);
        echo '1';
    }   
	
	function hps_trd_peliharabrg(){
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $kdbrg = $this->input->post('idbrg');
        $msg = array();
        $sql = "delete from temp_pelihara where no_dokumen='$nomor' and kd_uskpd='$skpd' and id_barang='$kdbrg'";
        $asg = $this->db->query($sql);
        /* if ($asg){
            $sql = "delete from trh_trpelihara where no_dokumen='$nomor' and kd_unit='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        } */
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    } 
    /// ----Penerimaan Barang--------------------------------------------------------------------------------------------
    
     function penerimaan_barang(){
        $data['page_title']= 'TRANSAKSI PENERIMAAN BARANG';
        $this->template->set('title', 'TRANSAKSI PENERIMAAN BARANG ');   
        $this->template->load('index','transaksi/tr_penerimaan_barang',$data) ;         
     }
    
     function trh_trmbrg(){
        
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_uskpd like '%' ";
        }else{
            $where1 = "where kd_uskpd ='$skpd'";
        }
        
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 = "and (upper(no_dokumen) like upper('%$kriteria%') or tgl_periksa like '%$kriteria%' or upper(nm_uskpd) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_terimabrg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "select * from trh_terimabrg $where1 $where2 order by tgl_periksa,no_dokumen,kd_uskpd limit $offset,$rows";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(     
                            'no_bap'       => $resulte['no_bap'], 
                        	'tgl_bap'      => $resulte['tgl_bap'], 
                        	'no_dokumen'   => $resulte['no_dokumen'], 
                        	'nip1'         => $resulte['nip1'], 
                        	'nip2'         => $resulte['nip2'], 
                        	'no_faktur'    => $resulte['no_faktur'], 
                        	'tgl_faktur'   => $resulte['tgl_faktur'], 
                        	'no_periksa'   => $resulte['no_periksa'], 
                        	'tgl_periksa'  => $resulte['tgl_periksa'], 
                        	'kd_unit'      => $resulte['kd_unit'],  
                        	'kd_uskpd'     => $resulte['kd_uskpd'], 
                        	'nm_uskpd'     => $resulte['nm_uskpd'], 
                        	'keterangan'   => $resulte['keterangan'], 
                        	'tahun'        => $resulte['tahun'], 
                        	'total'        => $resulte['nilai'] , 
                        	'username'     => $resulte['username'], 
                        	'tgl_update'   => $resulte['tgl_update']
                     	                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
        
     }
     
     function trd_trmbrg(){
        $nomor = $this->input->post('no');
        $kode  = $this->input->post('kode');        
		$sql = "SELECT b.* FROM trh_terimabrg a INNER JOIN trd_terimabrg b ON a.no_dokumen=b.no_dokumen AND a.`kd_uskpd`=b.`kd_uskpd`
				WHERE a.no_dokumen = '$nomor' and a.kd_uskpd='$kode'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_bap'        => $resulte['no_bap'],                      
                        'kd_brg'        => $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'total'         => $resulte['total'],                        
                        'ket'           => $resulte['ket']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    
    function simpan_trmbrg(){
        
        $tabel   = $this->input->post('tabel');
        $batrm   = $this->input->post('batrm');
        $thn     = $this->input->post('tahun');
        $tgltrm  = $this->input->post('tgltrm');
        $uskpd   = $this->input->post('uskpd');
        $lokasi  = $this->input->post('lokasi');
        $nmuskpd = $this->input->post('nmuskpd');
        $nodok   = $this->input->post('nodok');        
        $nip1    = $this->input->post('nip1');  
        $noprks  = $this->input->post('nopriksa');
        $nip2    = $this->input->post('nip2');
        $tglprks = $this->input->post('tglpriksa');
        $ket     = $this->input->post('ket');        
        $nofak   = $this->input->post('nofak'); 
        $tglfak  = $this->input->post('tglfak');
        $total   = $this->input->post('total');   
        $csql    = $this->input->post('sql');
        
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
      
      if ($tabel == 'trh_terimabrg') {
          //Simpan header //
            $sql = "delete from trh_terimabrg where kd_uskpd='$uskpd' and no_dokumen='$nodok'";
            $asg = $this->db->query($sql);
            if ($asg){
                    $sql = "insert into trh_terimabrg(no_bap,tgl_bap,no_dokumen,nip1,nip2,no_faktur,tgl_faktur,no_periksa,tgl_periksa,kd_unit,kd_uskpd,nm_uskpd,keterangan,tahun,nilai,username,tgl_update) 
                                            values('$batrm','$tgltrm','$nodok','$nip1','$nip2','$nofak','$tglfak','$noprks','$tglprks','$lokasi','$uskpd','$nmuskpd','$ket','$thn' ,'$total','$usernm','$update')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
          } 
    }
        
  function save_trmbrg(){
        
        $csql  = $this->input->post('sql');
        $nomor = $this->input->post('nomor');
        $skpd  = $this->input->post('skpd');
        $sql   = "insert into trd_terimabrg(no_bap,no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,merek,tahun,jumlah,harga,total,ket)"; 
        $asg   = $this->db->query($sql.$csql);
        if($asg){
          $csql = "SELECT SUM(total) AS total from trd_terimabrg where no_dokumen = '$nomor' and kd_uskpd='$skpd'";
          $rs = $this->db->query($csql)->row() ;  
          if($rs){       
                $sql2 = "update trh_terimabrg set nilai ='$rs->total' where no_dokumen='$nomor' and kd_uskpd='$skpd'";
                $asg2 = $this->db->query($sql2);   
            }
           echo number_format($rs->total); 
        }else{
           echo 'Data Tidak Tersimpan';
        } 
         
    }
    
     function hps_trd_trmbrg(){
        $nomor = $this->input->post('nomor');
        $kd    = $this->input->post('kd');
        $total = $this->input->post('ctotal');
        $unit = $this->input->post('unit');
                 
        $sql = "delete from trd_terimabrg where no_dokumen='$nomor' and kd_brg='$kd' and kd_unit='$unit'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql2 = "update trh_terimabrg set nilai ='$total' where no_dokumen='$nomor' and and kd_unit='$unit' ";
                $asg2 = $this->db->query($sql2);
         }
    }
    
    function hapus_trmbrg(){
        $nomor = $this->input->post('no');
        $unit = $this->input->post('unit');
        $msg = array();
        $sql = "delete from trd_terimabrg where no_dokumen ='$nomor' and kd_unit='$unit'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "delete from trh_terimabrg where no_dokumen ='$nomor' and kd_unit='$unit'";
            $asg = $this->db->query($sql);
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }   
    
    
    
    //----End penerimaan barang--------------------------------------------------------------------------------------------
    
    //  AYTKTM "Apapun Yang Terjadi Kami Tetap Mengaji" (@_@)  //
    
    ///----Pengeluaran Barang----------------------------------------------------------------------------------------------
    
    function pengeluaran_barang(){
        $data['page_title']='TRANSAKSI PENGELUARAN BARANG';
        $this->template->Set('title','TRANSAKSI PENGELUARANA BARANG');
        $this->template->Load('index','transaksi/tr_pengeluaran_barang');
    }
    
    function trh_klrbrg(){
        $skpd = $this->session->userdata('skpd');
        $unit = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where kd_uskpd like '%' ";
        }else{
            $where1 = "where kd_uskpd ='$skpd' and kd_unit='$unit' and tahun='$thn'";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and (upper(no_bak) like upper('%$kriteria%') or tgl_bak like '%$kriteria%') ";            
        }
        
        $sql = "SELECT count(*) as total from trh_keluarbrg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT * FROM trh_keluarbrg $where1 $where2 ORDER BY no_bak,tgl_bak limit $offset,$rows";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(     
                            'no_bak'    => $resulte['no_bak'], 
                           	'tgl_bak'   => $resulte['tgl_bak'], 
                           	'no_bap'    => $resulte['no_bap'],   
                           	'kd_uskpd'  => $resulte['kd_uskpd'], 
                            'nm_uskpd'  => $resulte['nm_uskpd'],
                           	'tahun'     => $resulte['tahun'] 
                                        			                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
        
     }
     
     function trd_klrbrg(){
        $nomor = $this->input->post('no');
                           
        $sql = "SELECT b.* FROM trh_keluarbrg a INNER JOIN trd_keluarbrg b ON a.no_bak=b.no_bak
                WHERE a.no_bak = '$nomor' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_bak'        => $resulte['no_bak'],                      
                        'kd_brg'        => $resulte['kd_brg'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'total'         => $resulte['total'],                        
                        'ket'           => $resulte['ket']                                                                                                                                                                                
                        
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
  function ambil_trmbrg(){
        $nomor = $this->input->post('no');
    
        $sql = "SELECT no_bap,no_dokumen,tgl_bap FROM trh_terimabrg WHERE no_bap like '%$nomor' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_bap'        => $resulte['no_bap'],                       
                        'no_dokumen'    => $resulte['no_dokumen'],                     
                        'tgl_bap'       => $resulte['tgl_bap']                                                                                                                                                                            
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function simpan_klrbrg(){
		 
        $tabel   = $this->input->post('tabel');
        $no      = $this->input->post('no');
        $batrm   = $this->input->post('batrm');
        $tgl     = $this->input->post('tgl');
        $uskpd   = $this->input->post('uskpd');
        $unit	 = $this->input->post('unit');
        $nmuskpd = $this->input->post('nmuskpd');
        $tahun   = $this->input->post('tahun');      
        $kd_brg  = $this->input->post('kd');      
        $nm_brg  = $this->input->post('nm');       
        $merk    = $this->input->post('merk');      
        $jumlah  = $this->input->post('jum');       
        $harga   = $this->input->post('hrg');          
        $total   = $this->input->post('total');      
        $ket     = $this->input->post('ket');     
        $tujuan  = $this->input->post('tujuan');    
    
       $csql    = $this->input->post('sql');
        
        $usernm     = $this->session->userdata('nmuser');
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
      
      if ($tabel == 'trh_keluarbrg') {
          //Simpan heider //
            //$sql = "delete from trh_keluarbrg where kd_unit='$unit' and no_bak='$no'";
			$sql = "select * from trh_keluarbrg where kd_unit='$unit' and no_bak='$no'";
            $asg = $this->db->query($sql);
            if ($asg){
                    $sql = "insert into trh_keluarbrg(no_bak,tgl_bak,no_bap,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,tujuan) 
                            values('$no','$tgl','$batrm','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$update','$tujuan')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
          
          } else if ($tabel == 'trd_keluarbrg') {
              
                //$sql = "delete from trd_keluarbrg where no_bak='$batrm' and kd_unit='$unit'";
				$sql = "select * from trd_keluarbrg where no_bak='$batrm' and kd_unit='$unit'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trd_keluarbrg(no_bak,kd_brg,kd_unit,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket)
					values('$no','$kd_brg','$unit','$uskpd','$nm_brg','$merk','$jumlah','$harga','$total','$ket')"; 
    
                    $asg = $this->db->query($sql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                                 
        }   
    }
    function hapus_klrbrg(){
        $nomor 	 = $this->input->post('no');
        $kd_unit = $this->input->post('kd_unit');
        $msg = array();
        $sql = "delete from trh_keluarbrg where no_bak ='$nomor' and kd_uskpd='$kd_unit'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "delete from trd_keluarbrg where no_bak ='$nomor'";
            $asg = $this->db->query($sql);
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }   
    
    
    
    //--End Pengeluaran barang----------------------------------------------------------------------------------------------
    
    //--Mutasi Barang-------------------------------------------------------------------------------------------------------
    
     function mutasi_barang(){
        $data['page_title']= 'TRANSAKSI MUTASI BARANG';
        $this->template->set('title', 'TRANSAKSI MUTASI BARANG ');   
        $this->template->load('index','transaksi/tr_mutasi_barang',$data) ;         
     }
	 
	function mutasi_barang_adm(){
        $data['page_title']= 'TRANSAKSI MUTASI BARANG';
        $this->template->set('title', 'TRANSAKSI MUTASI BARANG ');   
        $this->template->load('index','transaksi/tr_mutasi_barang_tetap',$data) ;         
     }

     function hapus_mutasi(){
    
            $cnid      = $this->input->post('cnid');
            $cid       = $this->input->post('cid');
            $skpd      = $this->input->post('skpd');
            $unit      = $this->input->post('unit');
            $skpd_lama = $this->input->post('skpd_lama');
            $unit_lama = $this->input->post('unit_lama');
            
            $sql = "delete from trd_mutasi where no_mutasi = '$cnid' and kd_skpd='$skpd' and kd_unit='$unit' and kd_skpd_lama='$skpd_lama' ";
            $casg  = $this->db->query($sql);
            if ($casg){
            $csql = "delete from trh_mutasi where no_mutasi = '$cnid' and kd_skpd='$skpd' and kd_unit='$unit' and kd_skpd_lama='$skpd_lama' ";
            $asg  = $this->db->query($csql);
            }
            if ($asg){
                    echo '1'; 
                } else{
                    echo '0';
                }
            
        }
        function hapus_detail(){
    
            $ctabel = $this->input->post('tabel');
            $cid    = $this->input->post('cid');
            $cnid   = $this->input->post('cnid');
            $id     = $this->input->post('id');
            $skpd   = $this->input->post('skpd');
            $urut   = $this->input->post('urut');
            $unit   = $this->input->post('unit');
            
            $csql = "delete from trd_mutasi where no_mutasi = '$cnid' 
            and id_barang='$id' and kd_skpd='$skpd' and kd_unit='$unit' and auto='$urut'";
            $asg  = $this->db->query($csql);
            if ($asg){
                echo '1'; 
            } else{
                echo '0';
            }
    
        }
     
    function ambil_kib()
	
    { 
        $lckib   = $this->input->post('gol');
        $kdskpd  = $this->input->post('kdskpd');
		$cari	 = $this->input->post('cari');
		$where	 = "";
		if($cari<>''){
		$where="and (c.nm_brg like '%$cari%' or a.tahun like '%$cari%')";
		}
        
        if($lckib == '01'){                   
        $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi 
		FROM trkib_a a LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg";
        }
        
        if($lckib == '02'){                   
        $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_b a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg ";
        }
        
        if($lckib == '03'){                   
        $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_c a
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg";
        }
                
        if($lckib == '04'){                   
        $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_d a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg";
        }
        
        if($lckib == '05'){                   
        $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_e a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg";
        }
        
        if($lckib == '06'){                   
        $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_f a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' $where order by a.kd_brg";
        }

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
		$totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
                        $totalx      = $resulte['nilai']+$totalx;  
            $result[] = array(  
                        'no'         => $ii,                    
                        'id_barang'  => $resulte['id_barang'],                           
                        'no_reg'     => $resulte['reg'],
                        'no_dokumen' => $resulte['no_dokumen'],                      
                        'kd_brg'     => $resulte['kd_brg'],
                        'nm_brg'     => $resulte['nm_brg'],
                        'tgl_reg'    => $resulte['tgl_reg'],
                        'tahun'      => $resulte['tahun'],
                        'kondisi'    => $resulte['kondisi'],
                        'nilai'      => $resulte['nilai'], 
						'totalx'	 => $totalx
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }  
    
	
	/*function ambil_mutasi()
	
    { 
        $lckib  = $this->input->post('gol');
        $kdskpd = $this->input->post('kdskpd');
        $cari   = $this->input->post('cari');
      		
		$where="";
		if($cari<>''){
		$where="and (a.nm_brg like '%$cari%' or a.tahun like '%$cari%')";
		}
		
        if($lckib == '01'){ 
			$sql="SELECT * FROM v_mts_a a WHERE a.kd_skpd ='$kdskpd' $where and a.no_mutasi is null order by a.kd_brg";
        }
        
        if($lckib == '02'){       
			$sql="SELECT * FROM v_mts_b a WHERE a.kd_skpd ='$kdskpd' $where and a.no_mutasi is null order by a.kd_brg";
        }
        
        if($lckib == '03'){ 
			$sql="SELECT * FROM v_mts_c a WHERE a.kd_skpd ='$kdskpd' $where and a.no_mutasi is null order by a.kd_brg";
        }
                
        if($lckib == '04'){   
			$sql="SELECT * FROM v_mts_d a WHERE a.kd_skpd ='$kdskpd' $where order by a.kd_brg and a.no_mutasi is null";
        }
        
        if($lckib == '05'){  
			$sql="SELECT * FROM v_mts_e a WHERE a.kd_skpd ='$kdskpd' $where and a.no_mutasi is null order by a.kd_brg";
        }
        
        if($lckib == '06'){        
			$sql="SELECT * FROM v_mts_f a WHERE a.kd_skpd ='$kdskpd' $where and a.no_mutasi is null order by a.kd_brg";
        }
        
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
		$totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
            $totalx      = $resulte['nilai']+$totalx;  
            $result[] = array(  
                        'no'         => $ii,                    
                        'id_barang'  => $resulte['id_barang'],                           
                        'no_reg'     => $resulte['reg'],
                        'no_dokumen' => $resulte['no_dokumen'],                      
                        'kd_brg'     => $resulte['kd_brg'],
                        'nm_brg'     => $resulte['nm_brg'],
                        'tgl_reg'    => $resulte['tgl_reg'],
                        'tahun'      => $resulte['tahun'],
                        'kondisi'    => $resulte['kondisi'],
                        'nilai'      => $resulte['nilai'], 
						'totalx'	 => $totalx
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    } */

	
    function ambil_mutasi_head(){
    
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
		$and1 = '';
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
			$and1 = "and a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd'";
			$and1 = "and a.kd_skpd like '%' ";
        }
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.merek) like upper('%$kriteria%')
					or upper(a.no_polisi) like upper('%$kriteria%')
					
					) ";
        }
        
        $sql = "SELECT count(*) as tot from trh_mutasi a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
 $sql = "SELECT a.*
                ,(CASE WHEN a.status='N' THEN 'DITOLAK' WHEN a.status='Y' THEN 'DISETUJUI' WHEN a.status IS NULL THEN 'MENUNGGU' END) AS sts
                from trh_mutasi a 
                $where1 $where2
                ORDER BY a.no_mutasi";
        								
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                                 
                            'no_mutasi'    => $resulte['no_mutasi'],
                            'tgl_mutasi'   => $resulte['tgl_mutasi'],
                            'kd_skpd'      => $resulte['kd_skpd'],
                            'kd_unit'      => $resulte['kd_unit'],
                            'baru'         => $resulte['nm_skpd'],
                            'kd_skpd_lama' => $resulte['kd_skpd_lama'],
                            'kd_unit_lama' => $resulte['kd_unit_lama'],
                            'lama'         => $resulte['nm_skpd_lama'],
                            'jumlah'       => $resulte['jumlah'],
                            'total'        => $resulte['total'],
                            'ket'          => $resulte['ket'],
                            'no_urut'      => $resulte['no_urut'],
                            'sts'          => $resulte['sts']
                    
                        );
                        $ii++;
        }   
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
	
	/* $oto    = $this->session->userdata('otori');
        $skpd   = "1.20.10.01";//$this->session->userdata('skpd');
        
          $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd_lama ='$skpd' and a.status IS NULL";
        }else{
            $where1 = "where a.kd_skpd_lama ='$skpd' AND (a.status IS NULL OR a.status='N' or a.status='Y')";
        }
        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and (upper(a.no_mutasi) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_mutasi a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
        

        $sql = "SELECT a.*
                ,(CASE WHEN a.status='N' THEN 'DITOLAK' WHEN a.status='Y' THEN 'DISETUJUI' WHEN a.status IS NULL THEN 'MENUNGGU' END) AS sts
                from trh_mutasi a 
                $where1 $where2
                ORDER BY a.no_mutasi";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                                 
                            'no_mutasi'    => $resulte['no_mutasi'],
                            'tgl_mutasi'   => $resulte['tgl_mutasi'],
                            'kd_skpd'      => $resulte['kd_skpd'],
                            'kd_unit'      => $resulte['kd_unit'],
                            'baru'         => $resulte['nm_skpd'],
                            'kd_skpd_lama' => $resulte['kd_skpd_lama'],
                            'kd_unit_lama' => $resulte['kd_unit_lama'],
                            'lama'         => $resulte['nm_skpd_lama'],
                            'jumlah'       => $resulte['jumlah'],
                            'total'        => $resulte['total'],
                            'ket'          => $resulte['ket'],
                            'no_urut'      => $resulte['no_urut'],
                            'sts'          => $resulte['sts']
                    
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        
    }
*/
    
      function ambil_mutasi_detail(){
        $skpd       = $this->input->post('skpd');
        $no_mutasi  = $this->input->post('nomor');
    
        $sql = "SELECT a.*
                FROM trd_mutasi a 
                inner join mbarang b on b.kd_brg=a.kd_brg 
                where a.kd_skpd_lama='$skpd' and no_mutasi='$no_mutasi'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_reg'            => $resulte['no_reg'],
                        'id_barang'         => $resulte['id_barang'],
                        'no'                => $resulte['no'],
                        'no_oleh'           => $resulte['no_oleh'],
                        'tgl_reg'           => $resulte['tgl_reg'],
                        'tgl_oleh'          => $resulte['tgl_oleh'],
                        'no_dokumen'        => $resulte['no_dokumen'],
                        'nm_brg'            => $resulte['nm_brg'],
                        'kd_brg'            => $resulte['kd_brg'],
                        'detail_brg'        => $resulte['detail_brg'],
                        'nilai'             => $resulte['nilai'],
                        'asal'              => $resulte['asal'],
                        'dsr_peroleh'       => $resulte['dsr_peroleh'],
                        'jumlah'            => $resulte['jumlah'],
                        'total'             => $resulte['total'],
                        'merek'             => $resulte['merek'],
                        'tipe'              => $resulte['tipe'],
                        'pabrik'            => $resulte['pabrik'],
                        'kd_warna'          => $resulte['kd_warna'],
                        'kd_bahan'          => $resulte['kd_bahan'],
                        'kd_satuan'         => $resulte['kd_satuan'],
                        'no_rangka'         => $resulte['no_rangka'],
                        'no_mesin'          => $resulte['no_mesin'],
                        'no_polisi'         => $resulte['no_polisi'],
                        'silinder'          => $resulte['silinder'],
                        'no_stnk'           => $resulte['no_stnk'],
                        'tgl_stnk'          => $resulte['tgl_stnk'],
                        'no_bpkb'           => $resulte['no_bpkb'],
                        'tgl_bpkb'          => $resulte['tgl_bpkb'],
                        'kondisi'           => $resulte['kondisi'],
                        'tahun_produksi'    => $resulte['tahun_produksi'],
                        'dasar'             => $resulte['dasar'],
                        'no_sk'             => $resulte['no_sk'],
                        'tgl_sk'            => $resulte['tgl_sk'],
                        'keterangan'        => $resulte['keterangan'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'        => $resulte['tgl_mutasi'],
                        'no_pindah'         => $resulte['no_pindah'],
                        'tgl_pindah'        => $resulte['tgl_pindah'],
                        'no_hapus'          => $resulte['no_hapus'],
                        'tgl_hapus'         => $resulte['tgl_hapus'],
                        'kd_ruang'          => $resulte['kd_ruang'],
                        'kd_lokasi2'        => $resulte['kd_lokasi2'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'kd_unit'           => $resulte['kd_unit'],
                        'kd_skpd_lama'      => $resulte['kd_skpd_lama'],
                        'milik'             => $resulte['milik'],
                        'wilayah'           => $resulte['wilayah'],
                        'username'          => $resulte['username'],
                        'tgl_update'        => $resulte['tgl_update'],
                        'tahun'             => $resulte['tahun'],
                        'foto'              => $resulte['foto'],
                        'foto2'             => $resulte['foto2'],
                        'foto3'             => $resulte['foto3'],
                        'foto4'             => $resulte['foto4'],
                        'foto5'             => $resulte['foto5'],
                        'no_urut'           => $resulte['no_urut'],
                        'metode'            => $resulte['metode'],
                        'masa_manfaat'      => $resulte['masa_manfaat'],
                        'nilai_sisa'        => $resulte['nilai_sisa'],
                        'kd_riwayat'        => $resulte['kd_riwayat'],
                        'tgl_riwayat'       => $resulte['tgl_riwayat'],
                        'detail_riwayat'    => $resulte['detail_riwayat'],
                        'status_tanah'      => $resulte['status_tanah'],
                        'no_sertifikat'     => $resulte['no_sertifikat'],
                        'tgl_sertifikat'    => $resulte['tgl_sertifikat'],
                        'luas'              => $resulte['luas'],
                        'penggunaan'        => $resulte['penggunaan'],
                        'alamat1'           => $resulte['alamat1'],
                        'alamat2'           => $resulte['alamat2'],
                        'alamat3'           => $resulte['alamat3'],
                        'lat'               => $resulte['lat'],
                        'lon'               => $resulte['lon'],
                        'luas_gedung'       => $resulte['luas_gedung'],
                        'jenis_gedung'      => $resulte['jenis_gedung'],
                        'luas_tanah'        => $resulte['luas_tanah'],
                        'konstruksi'        => $resulte['konstruksi'],
                        'konstruksi2'       => $resulte['konstruksi2'],
                        'luas_lantai'       => $resulte['luas_lantai'],
                        'kd_tanah'          => $resulte['kd_tanah'],
                        'hibah'             => $resulte['hibah'],
                        'panjang'           => $resulte['panjang'],
                        'lebar'             => $resulte['lebar'],
                        'perolehan'         => $resulte['perolehan'],
                        'judul'             => $resulte['judul'],
                        'spesifikasi'       => $resulte['spesifikasi'],
                        'cipta'             => $resulte['cipta'],
                        'tahun_terbit'      => $resulte['tahun_terbit'],
                        'penerbit'          => $resulte['penerbit'],
                        'jenis'             => $resulte['jenis'],
                        'bangunan'          => $resulte['bangunan'],
                        'tgl_awal_kerja'    => $resulte['tgl_awal_kerja'],
                        'nilai_kontrak'     => $resulte['nilai_kontrak'],
                        'auto'              => $resulte['auto'],
                        'pemeliharaan_ke'   => $resulte['pemeliharaan_ke'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang']
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function mutasi_kib(){
        $no_mutasi          = $this->input->post('nomut');
        $tgl_mut            = $this->input->post('tgl_mut');
        $riwayat            = $this->input->post('riwayat');
        $nmuskpdb           = $this->input->post('nmuskpdb');
        $no_reg             = $this->input->post('no_reg');
        $id_barang          = $this->input->post('id_barang');
        $no                 = $this->input->post('no');
        $no_oleh            = $this->input->post('no_oleh');
        $tgl_reg            = $this->input->post('tgl_reg');
        $tgl_oleh           = $this->input->post('tgl_oleh');
        $no_dokumen         = $this->input->post('no_dokumen');
        $kd_brg             = $this->input->post('kd_brg');
        $nm_brg             = $this->input->post('nm_brg');
        $detail_brg         = $this->input->post('detail_brg');
        $nilai              = $this->input->post('nilai');
        $asal               = $this->input->post('asal');
        $dsr_peroleh        = $this->input->post('dsr_peroleh');
        $jumlah             = $this->input->post('jumlah');
        $total              = $this->input->post('total');
        $merek              = $this->input->post('merek');
        $tipe               = $this->input->post('tipe');
        $pabrik             = $this->input->post('pabrik');
        $kd_warna           = $this->input->post('kd_warna');
        $kd_bahan           = $this->input->post('kd_bahan');
        $kd_satuan          = $this->input->post('kd_satuan');
        $no_rangka          = $this->input->post('no_rangka');
        $no_mesin           = $this->input->post('no_mesin');
        $no_polisi          = $this->input->post('no_polisi');
        $silinder           = $this->input->post('silinder');
        $no_stnk            = $this->input->post('no_stnk');
        $tgl_stnk           = $this->input->post('tgl_stnk');
        $no_bpkb            = $this->input->post('no_bpkb');
        $tgl_bpkb           = $this->input->post('tgl_bpkb');
        $kondisi            = $this->input->post('kondisi');
        $tahun_produksi     = $this->input->post('tahun_produksi');
        $dasar              = $this->input->post('dasar');
        $no_sk              = $this->input->post('no_sk');
        $tgl_sk             = $this->input->post('tgl_sk');
        $keterangan         = $this->input->post('keterangan');
        //$no_mutasi  = $this->input->post('no_mutasi');
        $tgl_mutasi         = $this->input->post('tgl_mutasi');
        $no_pindah          = $this->input->post('no_pindah');
        $tgl_pindah         = $this->input->post('tgl_pindah');
        $no_hapus           = $this->input->post('no_hapus');
        $tgl_hapus          = $this->input->post('tgl_hapus');
        $kd_ruang           = $this->input->post('kd_ruang');
        $kd_lokasi2         = $this->input->post('kd_lokasi2');
        $kd_skpd            = $this->input->post('kd_skpd');
        $kd_unit            = $this->input->post('kd_unit');
        $kd_skpd_lama       = $this->input->post('kd_skpd_lama');
        $milik              = $this->input->post('milik');
        $wilayah            = $this->input->post('wilayah');
        $username           = $this->session->userdata('nmuser');
        $tgl_update         = date('y-m-d H:i:s');
        $tahun              = $this->input->post('tahun');
        $foto               = $this->input->post('foto');
        $foto2              = $this->input->post('foto2');
        $foto3              = $this->input->post('foto3');
        $foto4              = $this->input->post('foto4');
        $foto5              = $this->input->post('foto5');
        $no_urut            = $this->input->post('no_urut');
        $metode             = $this->input->post('metode');
        $masa_manfaat       = $this->input->post('masa_manfaat');
        $nilai_sisa         = $this->input->post('nilai_sisa');
        $kd_riwayat         = $this->input->post('kd_riwayat');
        $tgl_riwayat        = $this->input->post('tgl_riwayat');
        $detail_riwayat     = $this->input->post('detail_riwayat');
        $status_tanah       = $this->input->post('status_tanah');
        $no_sertifikat      = $this->input->post('no_sertifikat');
        $tgl_sertifikat     = $this->input->post('tgl_sertifikat');
        $luas               = $this->input->post('luas');
        $penggunaan         = $this->input->post('penggunaan');
        $alamat1            = $this->input->post('alamat1');
        $alamat2            = $this->input->post('alamat2');
        $alamat3            = $this->input->post('alamat3');
        $lat                = $this->input->post('lat');
        $lon                = $this->input->post('lon');
        $luas_gedung        = $this->input->post('luas_gedung');
        $jenis_gedung       = $this->input->post('jenis_gedung');
        $luas_tanah         = $this->input->post('luas_tanah');
        $konstruksi         = $this->input->post('konstruksi');
        $konstruksi2        = $this->input->post('konstruksi2');
        $luas_lantai        = $this->input->post('luas_lantai');
        $kd_tanah           = $this->input->post('kd_tanah');
        $hibah              = $this->input->post('hibah');
        $panjang            = $this->input->post('panjang');
        $lebar              = $this->input->post('lebar');
        $perolehan          = $this->input->post('perolehan');
        $judul              = $this->input->post('judul');
        $spesifikasi        = $this->input->post('spesifikasi');
        $cipta              = $this->input->post('cipta');
        $tahun_terbit       = $this->input->post('tahun_terbit');
        $penerbit           = $this->input->post('penerbit');
        $jenis              = $this->input->post('jenis');
        $bangunan           = $this->input->post('bangunan');
        $tgl_awal_kerja     = $this->input->post('tgl_awal_kerja');
        $nilai_kontrak      = $this->input->post('nilai_kontrak');
        $kd_golongan        = $this->input->post('kd_golongan');
        $kd_bidang          = $this->input->post('kd_bidang');
        $pemeliharaan_ke    = $this->input->post('pemeliharaan_ke');

        $cno_reg            = explode('||',$no_reg);
        $cid_barang         = explode('||',$id_barang);
        $cno                = explode('||',$no);
        $cno_oleh           = explode('||',$no_oleh);
        $ctgl_reg           = explode('||',$tgl_reg);
        $ctgl_oleh          = explode('||',$tgl_oleh);
        $cno_dokumen        = explode('||',$no_dokumen);
        $ckd_brg            = explode('||',$kd_brg);
        $nm_brg             = explode('||',$nm_brg);
        $cdetail_brg        = explode('||',$detail_brg);
        $cnilai             = explode('||',$nilai);
        $casal              = explode('||',$asal);
        $cdsr_peroleh       = explode('||',$dsr_peroleh);
        $cjumlah            = explode('||',$jumlah);
        $ctotal             = explode('||',$total);
        $cmerek             = explode('||',$merek);
        $ctipe              = explode('||',$tipe);
        $cpabrik            = explode('||',$pabrik);
        $ckd_warna          = explode('||',$kd_warna);
        $ckd_bahan          = explode('||',$kd_bahan);
        $ckd_satuan         = explode('||',$kd_satuan);
        $cno_rangka         = explode('||',$no_rangka);
        $cno_mesin          = explode('||',$no_mesin);
        $cno_polisi         = explode('||',$no_polisi);
        $csilinder          = explode('||',$silinder);
        $cno_stnk           = explode('||',$no_stnk);
        $ctgl_stnk          = explode('||',$tgl_stnk);
        $cno_bpkb           = explode('||',$no_bpkb);
        $ctgl_bpkb          = explode('||',$tgl_bpkb);
        $ckondisi           = explode('||',$kondisi);
        $ctahun_produksi    = explode('||',$tahun_produksi);
        $cdasar             = explode('||',$dasar);
        $cno_sk             = explode('||',$no_sk);
        $ctgl_sk            = explode('||',$tgl_sk);
        $cketerangan        = explode('||',$keterangan);
        //$cno_mutasi  = explode('||',$no_mutasi);
        $ctgl_mutasi        = explode('||',$tgl_mutasi);
        $cno_pindah         = explode('||',$no_pindah);
        $ctgl_pindah        = explode('||',$tgl_pindah);
        $cno_hapus          = explode('||',$no_hapus);
        $ctgl_hapus         = explode('||',$tgl_hapus);
        $ckd_ruang          = explode('||',$kd_ruang);
        $ckd_lokasi2        = explode('||',$kd_lokasi2);
        /* $ckd_skpd  = explode('||',$kd_skpd);
        $ckd_unit  = explode('||',$kd_unit);
        $ckd_skpd_lama  = explode('||',$kd_skpd_lama); */
        $cmilik             = explode('||',$milik);
        $cwilayah           = explode('||',$wilayah);
        $cusername          = explode('||',$username);
        $ctgl_update        = explode('||',$tgl_update);
        $ctahun             = explode('||',$tahun);
        $cfoto              = explode('||',$foto);
        $cfoto2             = explode('||',$foto2);
        $cfoto3             = explode('||',$foto3);
        $cfoto4             = explode('||',$foto4);
        $cfoto5             = explode('||',$foto5);
        $cno_urut           = explode('||',$no_urut);
        $cmetode            = explode('||',$metode);
        $cmasa_manfaat      = explode('||',$masa_manfaat);
        $cnilai_sisa        = explode('||',$nilai_sisa);
        $ckd_riwayat        = explode('||',$kd_riwayat);
        $ctgl_riwayat       = explode('||',$tgl_riwayat);
        $cdetail_riwayat    = explode('||',$detail_riwayat);
        $cstatus_tanah      = explode('||',$status_tanah);
        $cno_sertifikat     = explode('||',$no_sertifikat);
        $ctgl_sertifikat    = explode('||',$tgl_sertifikat);
        $cluas              = explode('||',$luas);
        $cpenggunaan        = explode('||',$penggunaan);
        $calamat1           = explode('||',$alamat1);
        $calamat2           = explode('||',$alamat2);
        $calamat3           = explode('||',$alamat3);
        $clat               = explode('||',$lat);
        $clon               = explode('||',$lon);
        $cluas_gedung       = explode('||',$luas_gedung);
        $cjenis_gedung      = explode('||',$jenis_gedung);
        $cluas_tanah        = explode('||',$luas_tanah);
        $ckonstruksi        = explode('||',$konstruksi);
        $ckonstruksi2       = explode('||',$konstruksi2);
        $cluas_lantai       = explode('||',$luas_lantai);
        $ckd_tanah          = explode('||',$kd_tanah);
        $chibah             = explode('||',$hibah);
        $cpanjang           = explode('||',$panjang);
        $clebar             = explode('||',$lebar);
        $cperolehan         = explode('||',$perolehan);
        $cjudul             = explode('||',$judul);
        $cspesifikasi       = explode('||',$spesifikasi);
        $ccipta             = explode('||',$cipta);
        $ctahun_terbit      = explode('||',$tahun_terbit);
        $cpenerbit          = explode('||',$penerbit);
        $cjenis             = explode('||',$jenis);
        $cbangunan          = explode('||',$bangunan);
        $ctgl_awal_kerja    = explode('||',$tgl_awal_kerja);
        $cnilai_kontrak     = explode('||',$nilai_kontrak);
        $ckd_golongan       = explode('||',$kd_golongan);
        $ckd_bidang         = explode('||',$kd_bidang);
        $cpemeliharaan_ke   = explode('||',$pemeliharaan_ke);

              
        $pj=count($cno);
        
        /* Insert ke table mutasi_brg A-F  && mutasi di trkib A-F*/
            for($i=0;$i<$pj;$i++){
                if (trim($cno[$i])!=''){
                
                /*  $sql = "insert into mutasi_brg(no_mutasi,id_barang,no_urut,tgl_mutasi,no_reg,kd_brg,kd_unit,
                 kd_unitb,kd_skpdb,kondisi,asal,tahun_oleh,jumlah_awal,harga_awal,
                 jumlah_kurang,harga_kurang,jumlah_tambah,harga_tambah,keterangan,username,tgl_update,status) 
                         values('$no_mutasi','".$pid[$i]."','".$pno[$i]."','$tgl','".$pnoreg[$i]."','".$pkdbrg[$i]."','$uskpd',
                         '$uskpdb','$skpdb','".$pkondisi[$i]."','$uskpd','".$ptahun[$i]."','1','".$pharga[$i]."','','','','','$keterangan','','','')"; */
                //$sql ="delete from trd_mutasi where kd_brg='".$ckd_brg[$i]."' and kd_skpd='".$ckd_skpd[$i]."' and id_barang='".$cid_barang[$i]."' and nilai='".$cnilai[$i]."' ";
                
                //$asg = $this->db->query($sql);
                $kdbrg = substr($ckd_brg[$i],0,2);
                //$id_baru =($cid_barang[$i].".".$kd_skpd); (awal)
                $id_baru = ($cid_barang[$i].".".$kd_unit);
                $no_baru = ($cno[$i]."/".$kd_unit);
                //if($sql){
                    if($kdbrg=='01'){
                    //$this->db->query("UPDATE trkib_a SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,status_tanah,kondisi,asal,dsr_peroleh,no_sertifikat,tgl_sertifikat,luas,nilai,jumlah,total,penggunaan,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_lokasi2,milik,wilayah,kd_skpd,kd_unit,kd_skpd_lama,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','$no_baru','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cstatus_tanah[$i]."','".$ckondisi[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$cno_sertifikat[$i]."','".$ctgl_sertifikat[$i]."',
                                      '".$cluas[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$ctotal[$i]."',
                                      '".$cpenggunaan[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                                      '$no_mutasi','$tgl_mut',null,null,
                                      null,null,'".$cketerangan[$i]."','".$ckd_lokasi2[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','$kd_skpd','$kd_unit',
                                      '$kd_skpd_lama','$username','$tgl_update','".$ctahun[$i]."',
                                      '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."',
                                      '".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
                                      '$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");
                    }
                    if($kdbrg=='02'){
                    //$this->db->query("UPDATE trkib_b SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,dasar,no_sk,tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,foto3,foto4,foto5,no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','$no_baru','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                      '".$cjumlah[$i]."','".$ctotal[$i]."','".$cmerek[$i]."','".$ctipe[$i]."','".$cpabrik[$i]."',
                                      '".$ckd_warna[$i]."','".$ckd_bahan[$i]."','".$ckd_satuan[$i]."','".$cno_rangka[$i]."',
                                      '".$cno_mesin[$i]."','".$cno_polisi[$i]."','".$csilinder[$i]."','".$cno_stnk[$i]."',
                                      '".$ctgl_stnk[$i]."','".$cno_bpkb[$i]."','".$ctgl_bpkb[$i]."','".$ckondisi[$i]."',
                                      '".$ctahun_produksi[$i]."','".$cdasar[$i]."','".$cno_sk[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."','$no_mutasi','$tgl_mut',null,null,
                                      null,null,'".$ckd_ruang[$i]."',
                                      '".$ckd_lokasi2[$i]."','$kd_skpd','$kd_unit','$kd_skpd_lama',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                      '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."',
                                      '".$cfoto5[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                      '".$cnilai_sisa[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");
                    }
                    if($kdbrg=='03'){
                    //$this->db->query("UPDATE trkib_c SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,jumlah,asal,dsr_peroleh,total,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,konstruksi,konstruksi2,luas_lantai,kondisi,dasar,tgl_sk,keterangan,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,kd_tanah,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,hibah,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','$no_baru','".$cno_oleh[$i]."',
                                      '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$cluas_gedung[$i]."','".$cjenis_gedung[$i]."','".$cluas_tanah[$i]."','".$cstatus_tanah[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."','$no_mutasi',
                                      '$tgl_mut',null,null,
                                      null,null,'".$ckonstruksi[$i]."','".$ckonstruksi2[$i]."','".$cluas_lantai[$i]."',
                                      '".$ckondisi[$i]."','".$cdasar[$i]."','".$ctgl_sk[$i]."','".$cketerangan[$i]."',
                                      '".$ckd_lokasi2[$i]."','$kd_skpd','$kd_unit','$kd_skpd_lama',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_tanah[$i]."','$username',
                                      '$tgl_update','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."',
                                      '".$cfoto4[$i]."','".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$cmetode[$i]."',
                                      '".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."','".$chibah[$i]."','".$ckd_riwayat[$i]."',
                                      '".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                    
                                      }
                    if($kdbrg=='04'){
                    //$this->db->query("UPDATE trkib_d SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','$id_baru','$no_baru','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$cstatus_tanah[$i]."','".$cpanjang[$i]."',
                                      '".$cluas[$i]."','".$clebar[$i]."','".$ckonstruksi[$i]."','".$calamat1[$i]."','".$calamat2[$i]."',
                                      '".$calamat3[$i]."','$no_mutasi','$tgl_mut',null,null,
                                      null,null,'".$cperolehan[$i]."',
                                      '".$cdasar[$i]."','".$cjumlah[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','$kd_skpd_lama',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '".$cpenggunaan[$i]."','$username','$tgl_update','".$ctahun[$i]."',
                                      '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                      '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
                                      '".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                 
                                      }
                    if($kdbrg=='05'){
                    //$this->db->query("UPDATE trkib_e SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                    VALUES ('".$cno_reg[$i]."','$id_baru','$no_baru','".$cno_oleh[$i]."',
                                    '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                    '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                    '".$ctotal[$i]."','".$cjudul[$i]."','".$cspesifikasi[$i]."',
                                    '".$ccipta[$i]."','".$ctahun_terbit[$i]."','".$cpenerbit[$i]."','".$ckd_bahan[$i]."',
                                    '".$cjenis[$i]."','".$ctipe[$i]."','".$ckd_satuan[$i]."','".$cjumlah[$i]."',
                                    '".$ckondisi[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','$kd_skpd_lama',
                                    '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                    '$no_mutasi','$tgl_mut',null,null,
                                      null,null,
                                    '".$ckd_ruang[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                    '".$cfoto3[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                    '".$cnilai_sisa[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."',
                                    '".$ctgl_riwayat[$i]."','$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                  
                                      }
                    if($kdbrg=='06'){
                    //$this->db->query("UPDATE trkib_f SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("insert into trd_mutasi(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      values ('".$cno_reg[$i]."','".$cid_barang[$i]."','$no_baru','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$ckonstruksi[$i]."','".$cjenis[$i]."','".$cbangunan[$i]."','".$cluas[$i]."',
                                      '".$cjumlah[$i]."','".$ctgl_awal_kerja[$i]."','".$cstatus_tanah[$i]."','".$cnilai_kontrak[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."','$no_mutasi','$tgl_mut',
                                      null,null,null,null,
                                      '".$cketerangan[$i]."','$kd_skpd','$kd_unit','$kd_skpd_lama','".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '$username','$tgl_update','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                      '".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
                                      '$riwayat','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                
                                      }
                    }
                //}
            }
        }
    
    function ambil_mutasi(){

	
        $lckib    = $this->input->post('gol');
        $kdskpd   = $this->input->post('kdskpd');
        $kriteria = $this->input->post('cari');
        $unit     = $this->input->post('unit');
        
        $where="";
        if($kriteria<>''){
        $where="AND (UPPER(a.tahun) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.kondisi) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.keterangan) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.nilai) LIKE UPPER('%$kriteria%')
                    OR UPPER(a.nm_brg) LIKE UPPER('%$kriteria%') 
                    )";
					
					

		        $result = array();
		$row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
			
        }
        
        if($lckib == '01'){ 
        $sqlx = "SELECT count(*) as total from trkib_a a 
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
        

			$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,
                a.detail_brg,a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,
                '' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,
                '' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,
                '' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,
                a.no_hapus,a.tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,kd_unit,'' AS kd_skpd_lama,
                a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,
                no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,
                a.status_tanah,a.no_sertifikat,a.tgl_sertifikat,a.luas,penggunaan,a.alamat1,a.alamat2,
                a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,
                '' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
                '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,'' as pemeliharaan_ke FROM trkib_a a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        
        }

		

        if($lckib == '02'){ 
        $sqlx = "SELECT count(*) as total from trkib_b a 
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
        

			$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,
                a.detail_brg,a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,a.merek,a.tipe,a.pabrik,
                a.kd_warna,a.kd_bahan,a.kd_satuan,a.no_rangka,a.no_mesin,a.no_polisi,a.silinder,
                a.no_stnk,a.tgl_stnk,a.no_bpkb,a.tgl_bpkb,a.kondisi,a.tahun_produksi,a.dasar,
                a.no_sk,a.tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,
                a.no_hapus,a.tgl_hapus,a.kd_ruang,a.kd_lokasi2,a.kd_skpd,kd_unit,'' AS kd_skpd_lama,
                a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,a.foto4,a.foto5,
                a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,
                '' AS status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,'' AS penggunaan,'' AS alamat1,'' AS alamat2,
                '' AS alamat3,'' AS lat,'' AS lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,
                '' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
                '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,'' as pemeliharaan_ke FROM trkib_b a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='1.20.12.01' and a.kd_brg like 'limit' AND (a.no_mutasi IS NULL OR a.no_mutasi='') limit $offset,$rows" ;
        
        }

  
        
        if($lckib == '03'){ 
        $sqlx = "SELECT count(*) as total from trkib_c a INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
           
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,
        a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,
        '' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,
        dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,
        tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
        a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,
        a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
        '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,a.luas_gedung,a.jenis_gedung,a.luas_tanah,a.konstruksi,
        a.konstruksi2,a.luas_lantai,a.kd_tanah,a.hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
        '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
        c.nm_skpd FROM trkib_c a 
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='') 
        order by a.kd_brg limit $offset,$rows";*/

        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,
                a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,
                '' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,
                dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,
                tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
                a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,
                a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
                '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,a.luas_gedung,a.jenis_gedung,a.luas_tanah,a.konstruksi,
                a.konstruksi2,a.luas_lantai,a.kd_tanah,a.hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
                '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,a.pemeliharaan_ke FROM trkib_c a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        

        }
                
        if($lckib == '04'){  
        $sqlx = "SELECT count(*) as total from trkib_d a INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
             
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,
        a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,a.asal,'' AS dsr_peroleh,a.jumlah,a.total,'' AS merek,
        '' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,
        '' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,dasar,
        '' AS no_sk,'' AS tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,
        tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
        a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,
        a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,
        '' AS tgl_sertifikat,a.luas,a.penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,
        '' AS jenis_gedung,'' AS luas_tanah,konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,panjang,
        lebar,perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,
        '' AS nilai_kontrak,c.nm_skpd FROM trkib_d a 
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,
            a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,a.asal,'' AS dsr_peroleh,a.jumlah,a.total,'' AS merek,
            '' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,
            '' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,dasar,
            '' AS no_sk,'' AS tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,
            tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
            a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,
            a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,
            '' AS tgl_sertifikat,a.luas,a.penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,
            '' AS jenis_gedung,'' AS luas_tanah,konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,panjang,
            lebar,perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,
            '' AS nilai_kontrak,c.nm_skpd,a.pemeliharaan_ke FROM trkib_d a 
            INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)";
        

   }
        
        if($lckib == '05'){  
            $sqlx = "SELECT count(*) as total from trkib_e a INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)";
         $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
    /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_peroleh AS tgl_oleh,a.no_dokumen,a.kd_brg,a.nm_brg,a.detail_brg,
        a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,a.tipe,'' AS pabrik,'' AS kd_warna,a.kd_bahan,a.kd_satuan,
        '' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,'' AS kondisi,
        '' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,
        a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,
        a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,
        a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,
        '' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,'' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,lat,lon,'' AS luas_gedung,
        '' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,
        '' AS lebar,'' AS perolehan,a.judul,a.spesifikasi,a.cipta,a.tahun_terbit,a.penerbit,a.jenis,'' AS bangunan,'' AS tgl_awal_kerja,
        '' AS nilai_kontrak,c.nm_skpd FROM trkib_e a 
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_peroleh AS tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,
            a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,a.tipe,'' AS pabrik,'' AS kd_warna,a.kd_bahan,a.kd_satuan,
            '' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,'' AS kondisi,
            '' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,
            a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,
            a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,
            a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,
            '' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,'' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,lat,lon,'' AS luas_gedung,
            '' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,
            '' AS lebar,'' AS perolehan,a.judul,a.spesifikasi,a.cipta,a.tahun_terbit,a.penerbit,a.jenis,'' AS bangunan,'' AS tgl_awal_kerja,
            '' AS nilai_kontrak,c.nm_skpd,''as pemeliharaan_ke FROM trkib_e a 
            INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)";
 
        }
        
        if($lckib == '06'){        
        $sqlx = "SELECT count(*) as total from trkib_f a INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)" ;
                $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
    
    /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.nm_brg,a.detail_brg,
            a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,
            '' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,
            '' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,
            a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,
            a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
            foto2,'' AS foto3,'' AS foto4,'' AS foto5,no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,
            a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,luas,
            '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
            konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,
            '' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,a.bangunan,a.tgl_awal_kerja,
            a.nilai_kontrak,c.nm_skpd FROM trkib_f a 
            INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
            WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='') 
            order by a.kd_brg limit $offset,$rows";*/
            $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,'' AS kd_golongan,'' AS kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,
                a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,
                '' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,
                '' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,
                a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,
                a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
                foto2,'' AS foto3,'' AS foto4,'' AS foto5,no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,
                a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,luas,
                '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
                konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,
                '' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,a.bangunan,a.tgl_awal_kerja,
                a.nilai_kontrak,c.nm_skpd,''as pemeliharaan_ke FROM trkib_f a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_mutasi IS NULL OR a.no_mutasi='')
        AND (a.no_reg+'.'+a.kd_skpd+'.'+a.kd_brg+'.'+a.no_dokumen) NOT IN (SELECT (no_reg+'.'+kd_skpd_lama+'.'+kd_brg+'.'+no_dokumen) FROM trd_mutasi WHERE kd_skpd_lama=a.kd_skpd)
        ";

        }
        
        $query1 = $this->db->query($sql);  
        
        $ii = 1;
        $totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(  
                        'no'                => $ii, 
                        'no_reg'            => $resulte['no_reg'],
                        'id_barang'         => $resulte['id_barang'],
                        'nomor'             => $resulte['no'],
                        'no_oleh'           => $resulte['no_oleh'],
                        'tgl_reg'           => $resulte['tgl_reg'],
                        'tgl_oleh'          => $resulte['tgl_oleh'],
                        'no_dokumen'        => $resulte['no_dokumen'],
                        'kd_brg'            => $resulte['kd_brg'],
                        'nm_brg'            => $resulte['nm_brg'],
                        'detail_brg'        => $resulte['detail_brg'],
                        'nilai'             => $resulte['nilai'],
                        'asal'              => $resulte['asal'],
                        'dsr_peroleh'       => $resulte['dsr_peroleh'],
                        'jumlah'            => $resulte['jumlah'],
                        'total'             => $resulte['total'],
                        'merek'             => $resulte['merek'],
                        'tipe'              => $resulte['tipe'],
                        'pabrik'            => $resulte['pabrik'],
                        'kd_warna'          => $resulte['kd_warna'],
                        'kd_bahan'          => $resulte['kd_bahan'],
                        'kd_satuan'         => $resulte['kd_satuan'],
                        'no_rangka'         => $resulte['no_rangka'],
                        'no_mesin'          => $resulte['no_mesin'],
                        'no_polisi'         => $resulte['no_polisi'],
                        'silinder'          => $resulte['silinder'],
                        'no_stnk'           => $resulte['no_stnk'],
                        'tgl_stnk'          => $resulte['tgl_stnk'],
                        'no_bpkb'           => $resulte['no_bpkb'],
                        'tgl_bpkb'          => $resulte['tgl_bpkb'],
                        'kondisi'           => $resulte['kondisi'],
                        'tahun_produksi'    => $resulte['tahun_produksi'],
                        'dasar'             => $resulte['dasar'],
                        'no_sk'             => $resulte['no_sk'],
                        'tgl_sk'            => $resulte['tgl_sk'],
                        'keterangan'        => $resulte['keterangan'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'        => $resulte['tgl_mutasi'],
                        'no_pindah'         => $resulte['no_pindah'],
                        'tgl_pindah'        => $resulte['tgl_pindah'],
                        'no_hapus'          => $resulte['no_hapus'],
                        'tgl_hapus'         => $resulte['tgl_hapus'],
                        'kd_ruang'          => $resulte['kd_ruang'],
                        'kd_lokasi2'        => $resulte['kd_lokasi2'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'kd_unit'           => $resulte['kd_unit'],
                        'kd_skpd_lama'      => $resulte['kd_skpd_lama'],
                        'milik'             => $resulte['milik'],
                        'wilayah'           => $resulte['wilayah'],
                        'username'          => $resulte['username'],
                        'tgl_update'        => $resulte['tgl_update'],
                        'tahun'             => $resulte['tahun'],
                        'foto'              => $resulte['foto'],
                        'foto2'             => $resulte['foto2'],
                        'foto3'             => $resulte['foto3'],
                        'foto4'             => $resulte['foto4'],
                        'foto5'             => $resulte['foto5'],
                        'no_urut'           => $resulte['no_urut'],
                        'metode'            => $resulte['metode'],
                        'masa_manfaat'      => $resulte['masa_manfaat'],
                        'nilai_sisa'        => $resulte['nilai_sisa'],
                        'kd_riwayat'        => $resulte['kd_riwayat'],
                        'tgl_riwayat'       => $resulte['tgl_riwayat'],
                        'detail_riwayat'    => $resulte['detail_riwayat'],
                        'status_tanah'      => $resulte['status_tanah'],
                        'no_sertifikat'     => $resulte['no_sertifikat'],
                        'tgl_sertifikat'    => $resulte['tgl_sertifikat'],
                        'luas'              => $resulte['luas'],
                        'penggunaan'        => $resulte['penggunaan'],
                        'alamat1'           => $resulte['alamat1'],
                        'alamat2'           => $resulte['alamat2'],
                        'alamat3'           => $resulte['alamat3'],
                        'lat'               => $resulte['lat'],
                        'lon'               => $resulte['lon'],
                        'luas_gedung'       => $resulte['luas_gedung'],
                        'jenis_gedung'      => $resulte['jenis_gedung'],
                        'luas_tanah'        => $resulte['luas_tanah'],
                        'konstruksi'        => $resulte['konstruksi'],
                        'konstruksi2'       => $resulte['konstruksi2'],
                        'luas_lantai'       => $resulte['luas_lantai'],
                        'kd_tanah'          => $resulte['kd_tanah'],
                        'hibah'             => $resulte['hibah'],
                        'panjang'           => $resulte['panjang'],
                        'lebar'             => $resulte['lebar'],
                        'perolehan'         => $resulte['perolehan'],
                        'judul'             => $resulte['judul'],
                        'spesifikasi'       => $resulte['spesifikasi'],
                        'cipta'             => $resulte['cipta'],
                        'tahun_terbit'      => $resulte['tahun_terbit'],
                        'penerbit'          => $resulte['penerbit'],
                        'jenis'             => $resulte['jenis'],
                        'bangunan'          => $resulte['bangunan'],
                        'tgl_awal_kerja'    => $resulte['tgl_awal_kerja'],
                        'nilai_kontrak'     => $resulte['nilai_kontrak'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
                        'pemeliharaan_ke'   => $resulte['pemeliharaan_ke']

                        );
                        $ii++;
        }           
       
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
		exit();
	}
	
	/************************************************MURASI BARANG************************************************/
	function simpan_mts_adm(){
	 
        $id_brg   	= $this->input->post('cnom');
        $no		   	= $this->input->post('cid');
        $cuni    	= $this->input->post('cuni');
        $kd_unit    = $this->input->post('ckdu');
        $kd_skpd 	= $this->input->post('csku');
        $kd_awal 	= $this->input->post('cuskpd');
        $no_reg    	= $this->input->post('creg');
        $kd_brg    	= $this->input->post('ckd');
        $nm_brg		= $this->input->post('cnm');
        $nm_skpd   	= $this->input->post('ctuju');
        $nilai   	= $this->input->post('cnilai');
        $ket  		= $this->input->post('cket');        
        $ckode 		= $this->input->post('ckode');
        $tgl_oleh	= $this->input->post('ctgl_muts');  
        $tahun     	= $this->input->post('cthn'); 
        $kondisi   	= $this->input->post('ckds'); 
		
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		if($ckode=='01'){
			$updt	= "update trkib_a set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
			
			$updt1	= "update trkib_a set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 = $this->db->query($updt1);

			$sql 	= "INSERT INTO trkib_a(no_reg,id_barang,no,tgl_oleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg 	= $this->db->query($sql);
			
			$del	= "DELETE FROM mutasi_brg WHERE no_mutasi='$id_brg'";
			$cdel 	= $this->db->query($del);
		}
		if($ckode=='02'){
			$updt	= "update trkib_b set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
		
			$updt1	= "update trkib_b set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 = $this->db->query($updt1);

			$sql 	= "INSERT INTO trkib_b(no_reg,id_barang,no,tgl_oleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg 	= $this->db->query($sql);
			
			$del	= "DELETE FROM mutasi_brg WHERE no_mutasi='$id_brg'";
			$cdel 	= $this->db->query($del);

		}
		if($ckode=='03'){
			$updt	= "update trkib_c set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
			
			$updt1		= "update trkib_c set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 	= $this->db->query($updt1);

			$sql = "INSERT INTO trkib_c(no_reg,id_barang,no,tgl_oleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg = $this->db->query($sql);
		}
		if($ckode=='04'){
			$updt	= "update trkib_d set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
			
			$updt1	= "update trkib_d set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 = $this->db->query($updt1);

			$sql 	= "INSERT INTO trkib_d(no_reg,id_barang,no,tgl_oleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg 	= $this->db->query($sql);
			
			$del	= "DELETE FROM mutasi_brg WHERE no_mutasi='$id_brg'";
			$cdel 	= $this->db->query($del);
		}
		if($ckode=='05'){
			$updt	= "update trkib_e set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
			
			$updt1	= "update trkib_e set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 = $this->db->query($updt1);

			$sql 	= "INSERT INTO trkib_e(no_reg,id_barang,no,tgl_peroleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg 	= $this->db->query($sql);
			
			$del	= "DELETE FROM mutasi_brg WHERE no_mutasi='$id_brg'";
			$cdel 	= $this->db->query($del);
		}
		if($ckode=='06'){
			$updt	= "update trkib_f set no_mutasi='1' where kd_unit='$cuni' and id_barang='$no' and nilai='$nilai'";
			$cupdt 	= $this->db->query($updt);
			
			$updt1	= "update trkib_f set tgl_oleh='$tgl_oleh' where kd_unit='$cuni' and id_barang='$no'";
			$cupdt1 = $this->db->query($updt1);

			$sql 	= "INSERT INTO trkib_f(no_reg,id_barang,no,tgl_oleh,kd_brg, detail_brg, kondisi,nilai,keterangan,kd_skpd,kd_unit,username,tgl_update,tahun,detail_riwayat) 
					VALUES	('$no_reg','$id_brg','$no','$tgl_oleh','$kd_brg','$nm_brg','$kondisi','$nilai','$ket','$kd_skpd','$kd_unit','$nm_skpd','$update','$tahun','$kd_awal')";
			$asg 	= $this->db->query($sql);
			
			$del	= "DELETE FROM mutasi_brg WHERE no_mutasi='$id_brg'";
			$cdel 	= $this->db->query($del);
		}
            if($updt){   
				 $msg = array('pesan'=>'1');
                 echo json_encode($msg);
                 exit();      

            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
	}
	
	/*function simpan_mts_skpd(){
     
        $tabel   = $this->input->post('tabel');
        $no      = $this->input->post('no');
        $id_brg  = $this->input->post('id_brg');
        $urut    = $this->input->post('cno_urut');
        $tgl     = $this->input->post('tgl');
        $noreg   = $this->input->post('noreg');
        $kdbrg   = $this->input->post('kdbrg');
        $uskpd   = $this->input->post('uskpd');
        $skpdx   = $this->input->post('skpdx');
        $uskpdb  = $this->input->post('uskpdb');        
        $kondisi = $this->input->post('kondisi');
        $tahun   = $this->input->post('tahun');  
        $hrg     = $this->input->post('hrg'); 
        $ket     = $this->input->post('ket');
        
        $lcgol   = $this->input->post('lcgol');
           
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		$sql = "insert into mutasi_brg(no_mutasi,id_barang,no_urut,tgl_mutasi,no_reg, kd_brg, kd_unit,kd_unitb,kd_skpdb,kondisi,tahun_oleh,harga_awal,keterangan,username,tgl_update) 
                        values('$no','$id_brg','$urut','$tgl','$noreg','$kdbrg','$uskpd','$uskpdb','$skpdx','$kondisi','$tahun','$hrg','$ket','$usernm','$update')";
        $asg = $this->db->query($sql);

            if($asg){   
				 $msg = array('pesan'=>'1');
                 echo json_encode($msg);
                 exit();      

            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
	}*/

    function simpan_mts_skpd(){
        
        $tabel      = $this->input->post('tabel');
        $no_mutasi  = $this->input->post('no_mutasi');
        $tanggal    = $this->input->post('tanggal');
        $skpdb      = $this->input->post('skpdb');
        $unitb      = $this->input->post('unitb');
        $skpdl      = $this->input->post('skpdl');
        $ket        = $this->input->post('ket');
        $unit_lama  = $this->input->post('unit_lama');
        $nmskpd_br  = $this->input->post('nmskpd_br');
        $nmskpd_lm  = $this->input->post('nmskpd_lm');
        $username   = $this->session->userdata('nmuser');
        $tglupdate  = date('y-m-d H:i:s');      
        $msg        = array();
        $sqlh="delete from trh_mutasi where no_mutasi='$no_mutasi' and kd_skpd='$skpdb' and kd_skpd_lama='$skpdl'"; 
        $asgh = $this->db->query($sqlh);
        if($asgh){
        $sql = "insert into trh_mutasi(no_mutasi,tgl_mutasi,kd_skpd ,kd_unit ,nm_skpd,kd_skpd_lama,kd_unit_lama,nm_skpd_lama,ket,username,tgl_update) 
                                values('$no_mutasi','$tanggal','$skpdb','$unitb','$nmskpd_br','$skpdl','$unit_lama','$nmskpd_lm','$ket','$username','$tglupdate')";
        $asg = $this->db->query($sql);
        }
            if($asg){   
                 $msg = array('pesan'=>'1');
                 echo json_encode($msg);
                 exit();      

            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
    }
    function simpan_muts(){
     
        $tabel   = $this->input->post('tabel');
        $no      = $this->input->post('no');
        $id_brg  = $this->input->post('id_brg');
        $urut    = $this->input->post('cno_urut');
        $tgl     = $this->input->post('tgl');
        $noreg   = $this->input->post('noreg');
        $kdbrg   = $this->input->post('kdbrg');
        $uskpd   = $this->input->post('uskpd');
        $uskpdb  = $this->input->post('uskpdb');        
        $kondisi = $this->input->post('kondisi');
        $tahun   = $this->input->post('tahun');  
        $hrg     = $this->input->post('hrg'); 
        $ket     = $this->input->post('ket');
        
        $lcgol   = $this->input->post('lcgol');
           
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
    
                           
                $sql = "insert into mutasi_brg(no_mutasi,no_urut,tgl_mutasi,no_reg, kd_brg, kd_unit,kd_unitb,kondisi,tahun_oleh,harga_awal,keterangan,username,tgl_update) 
                        values('$no',$urut,'$tgl','$noreg','$kdbrg','$uskpd','$uskpdb','$kondisi','$tahun','$hrg','$ket','$usernm','$update')";
                $asg = $this->db->query($sql);
                
                if($asg){
                    if ($lcgol == '01'){
                
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_a where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } 
							
                      /*  $sql2 = "update trkib_a set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where no_reg='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                       $asg2 = $this->db->query($sql2);  */
					   $sql3 = "update trkib_a set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 

					   
                    }
                    if ($lcgol == '02'){
                        
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_b where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } 
                            /* 
                        $sql2 = "update trkib_b set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where RIGHT(no_reg,6)='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                        $asg2 = $this->db->query($sql2); */ 
					   $sql3 = "update trkib_b set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 
                    } 
                    if ($lcgol == '03'){
                        
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_c where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } /* 
                        $sql2 = "update trkib_c set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where RIGHT(no_reg,6)='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                        $asg2 = $this->db->query($sql2);  */
					   $sql3 = "update trkib_c set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 
                    } 
                    if ($lcgol == '04'){
                        
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_d where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } /* 
                        $sql2 = "update trkib_d set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where RIGHT(no_reg,6)='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                        $asg2 = $this->db->query($sql2);  */
					   $sql3 = "update trkib_d set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 
                    } 
                    if ($lcgol == '05'){
                        
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_e where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } 
                            /* 
                        $sql2 = "update trkib_e set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where RIGHT(no_reg,6)='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                        $asg2 = $this->db->query($sql2);  */
					   $sql3 = "update trkib_e set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 
                    } 
                    if ($lcgol == '06'){
                        
                            $nomutasi='000001';
                            $csql  ="select MAX(RIGHT(no_reg,6)) as no from trkib_f where kd_brg ='$kdbrg' and kd_unit='$uskpdb' ";
                            $query1 = $this->db->query($csql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nomutasi=$resulte['no'];
                                $nomutasi=($nomutasi)+1;
                      		
                             if(strlen($nomutasi)==1)$nomutasi='00000'.$nomutasi;
                             if(strlen($nomutasi)==2)$nomutasi='0000'.$nomutasi;
                             if(strlen($nomutasi)==3)$nomutasi='000'.$nomutasi;
                             if(strlen($nomutasi)==4)$nomutasi='00'.$nomutasi;
                             if(strlen($nomutasi)==5)$nomutasi='0'.$nomutasi;
                                
                             $noregb = $kdbrg.'.'.$nomutasi;  
                            } /* 
                        $sql2 = "update trkib_f set no_reg='$noregb',kd_unit='$uskpdb',no_mutasi='$no' where RIGHT(no_reg,6)='$noreg' and kd_brg = '$kdbrg' and kd_unit ='$uskpd' ";
                        $asg2 = $this->db->query($sql2);  */
					   $sql3 = "update trkib_f set no_mutasi='1' where id_barang='$id_brg' and kd_unit='$uskpd'";
                       $asg3 = $this->db->query($sql3); 
                    } 
                
                 $msg = array('pesan'=>'1');
                 echo json_encode($msg);
                 exit();            
            
            
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
    }
	
	  function load_mutasi() {
        $kriteria = $this->input->post('nokon');
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
		$where= "";
		
		if($oto <>'01'){
			$where="where a.kd_unit='$skpd'";		
			}
        
        $sql = "SELECT a.*,b.nm_brg from mutasi_brg a left join mbarang b on a.kd_brg=b.kd_brg $where";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,      
                        'kd_brg' => $resulte['kd_brg'],
                        'nm_brg' => $resulte['nm_brg'],
                        'tgl_mutasi' => $resulte['tgl_mutasi'],
                        'kd_unit'  =>  $resulte['kd_unit'],
                        'kd_unitb' =>  $resulte['kd_unitb']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

    function tolak_usulan_mutasi(){
     
        $nomor      = $this->input->post('no_mutasi');
        $skpd       = $this->input->post('skpdl');
        $skpdb      = $this->input->post('skpdb');
        $alasan     = $this->input->post('alasan');
        
        $updt1  = "update trh_mutasi set status='N',ket='$alasan' 
        where kd_skpd='$skpdb' and kd_skpd_lama='$skpd' and no_mutasi='$nomor'";
        $cupdt1 = $this->db->query($updt1);

          
    }

    function tetap_mutasi_kib(){
        
        $no_mut             = $this->input->post('nomut');
        $tgl_mut            = $this->input->post('tgl_mut');
        $rwyt_baru          = $this->input->post('rwyt_baru');
        $rwyt_lama          = $this->input->post('rwyt_lama');
        
        $no_reg             = $this->input->post('no_reg');
        $idbrg              = $this->input->post('idbrg');
        $id_barang          = $this->input->post('id_barang');
        $no                 = $this->input->post('no');
        $no_oleh            = $this->input->post('no_oleh');
        $tgl_reg            = $this->input->post('tgl_reg');
        $tgl_oleh           = $this->input->post('tgl_oleh');
        $no_dokumen         = $this->input->post('no_dokumen');
        $kd_brg             = $this->input->post('kd_brg');
        $nm_brg             = $this->input->post('nm_brg');
        $detail_brg         = $this->input->post('detail_brg');
        $nilai              = $this->input->post('nilai');
        $asal               = $this->input->post('asal');
        $dsr_peroleh        = $this->input->post('dsr_peroleh');
        $jumlah             = $this->input->post('jumlah');
        $total              = $this->input->post('total');
        $merek              = $this->input->post('merek');
        $tipe               = $this->input->post('tipe');
        $pabrik             = $this->input->post('pabrik');
        $kd_warna           = $this->input->post('kd_warna');
        $kd_bahan           = $this->input->post('kd_bahan');
        $kd_satuan          = $this->input->post('kd_satuan');
        $no_rangka          = $this->input->post('no_rangka');
        $no_mesin           = $this->input->post('no_mesin');
        $no_polisi          = $this->input->post('no_polisi');
        $silinder           = $this->input->post('silinder');
        $no_stnk            = $this->input->post('no_stnk');
        $tgl_stnk           = $this->input->post('tgl_stnk');
        $no_bpkb            = $this->input->post('no_bpkb');
        $tgl_bpkb           = $this->input->post('tgl_bpkb');
        $kondisi            = $this->input->post('kondisi');
        $tahun_produksi     = $this->input->post('tahun_produksi');
        $dasar              = $this->input->post('dasar');
        $no_sk              = $this->input->post('no_sk');
        $tgl_sk             = $this->input->post('tgl_sk');
        $keterangan         = $this->input->post('keterangan');
        $no_mutasi          = $this->input->post('no_mutasi');
        $tgl_mutasi         = $this->input->post('tgl_mutasi');
        $no_pindah          = $this->input->post('no_pindah');
        $tgl_pindah         = $this->input->post('tgl_pindah');
        $no_hapus           = $this->input->post('no_hapus');
        $tgl_hapus          = $this->input->post('tgl_hapus');
        $kd_ruang           = $this->input->post('kd_ruang');
        $kd_lokasi2         = $this->input->post('kd_lokasi2');
        $kd_skpd            = $this->input->post('kd_skpd');
        $kd_unit            = $this->input->post('kd_unit');
        $kd_skpd_lama       = $this->input->post('kd_skpd_lama');
        $milik              = $this->input->post('milik');
        $wilayah            = $this->input->post('wilayah');
        $username           = $this->session->userdata('nmuser');//$this->input->post('username');
        $tgl_update         = date('y-m-d H:i:s');//$this->input->post('tgl_update');
        $tahun              = $this->input->post('tahun');
        $foto               = $this->input->post('foto');
        $foto2              = $this->input->post('foto2');
        $foto3              = $this->input->post('foto3');
        $foto4              = $this->input->post('foto4');
        $foto5              = $this->input->post('foto5');
        $no_urut            = $this->input->post('no_urut');
        $metode             = $this->input->post('metode');
        $masa_manfaat       = $this->input->post('masa_manfaat');
        $nilai_sisa         = $this->input->post('nilai_sisa');
        $kd_riwayat         = $this->input->post('kd_riwayat');
        $tgl_riwayat        = $this->input->post('tgl_riwayat');
        $detail_riwayat     = $this->input->post('detail_riwayat');
        $status_tanah       = $this->input->post('status_tanah');
        $no_sertifikat      = $this->input->post('no_sertifikat');
        $tgl_sertifikat     = $this->input->post('tgl_sertifikat');
        $luas               = $this->input->post('luas');
        $penggunaan         = $this->input->post('penggunaan');
        $alamat1            = $this->input->post('alamat1');
        $alamat2            = $this->input->post('alamat2');
        $alamat3            = $this->input->post('alamat3');
        $lat                = $this->input->post('lat');
        $lon                = $this->input->post('lon');
        $luas_gedung        = $this->input->post('luas_gedung');
        $jenis_gedung       = $this->input->post('jenis_gedung');
        $luas_tanah         = $this->input->post('luas_tanah');
        $konstruksi         = $this->input->post('konstruksi');
        $konstruksi2        = $this->input->post('konstruksi2');
        $luas_lantai        = $this->input->post('luas_lantai');
        $kd_tanah           = $this->input->post('kd_tanah');
        $hibah              = $this->input->post('hibah');
        $panjang            = $this->input->post('panjang');
        $lebar              = $this->input->post('lebar');
        $perolehan          = $this->input->post('perolehan');
        $judul              = $this->input->post('judul');
        $spesifikasi        = $this->input->post('spesifikasi');
        $cipta              = $this->input->post('cipta');
        $tahun_terbit       = $this->input->post('tahun_terbit');
        $penerbit           = $this->input->post('penerbit');
        $jenis              = $this->input->post('jenis');
        $bangunan           = $this->input->post('bangunan');
        $tgl_awal_kerja     = $this->input->post('tgl_awal_kerja');
        $nilai_kontrak      = $this->input->post('nilai_kontrak');
        $kd_golongan        = $this->input->post('kd_golongan');
        $kd_bidang          = $this->input->post('kd_bidang');
        $pemeliharaan_ke    = $this->input->post('pemeliharaan_ke');

        $cno_reg            = explode('||',$no_reg);
        $cidbrg             = explode('||',$idbrg);
        $cid_barang         = explode('||',$id_barang);
        $cno                = explode('||',$no);
        $cno_oleh           = explode('||',$no_oleh);
        $ctgl_reg           = explode('||',$tgl_reg);
        $ctgl_oleh          = explode('||',$tgl_oleh);
        $cno_dokumen        = explode('||',$no_dokumen);
        $ckd_brg            = explode('||',$kd_brg);
        $nm_brg             = explode('||',$nm_brg);
        $cdetail_brg        = explode('||',$detail_brg);
        $cnilai             = explode('||',$nilai);
        $casal              = explode('||',$asal);
        $cdsr_peroleh       = explode('||',$dsr_peroleh);
        $cjumlah            = explode('||',$jumlah);
        $ctotal             = explode('||',$total);
        $cmerek             = explode('||',$merek);
        $ctipe              = explode('||',$tipe);
        $cpabrik            = explode('||',$pabrik);
        $ckd_warna          = explode('||',$kd_warna);
        $ckd_bahan          = explode('||',$kd_bahan);
        $ckd_satuan         = explode('||',$kd_satuan);
        $cno_rangka         = explode('||',$no_rangka);
        $cno_mesin          = explode('||',$no_mesin);
        $cno_polisi         = explode('||',$no_polisi);
        $csilinder          = explode('||',$silinder);
        $cno_stnk           = explode('||',$no_stnk);
        $ctgl_stnk          = explode('||',$tgl_stnk);
        $cno_bpkb           = explode('||',$no_bpkb);
        $ctgl_bpkb          = explode('||',$tgl_bpkb);
        $ckondisi           = explode('||',$kondisi);
        $ctahun_produksi    = explode('||',$tahun_produksi);
        $cdasar             = explode('||',$dasar);
        $cno_sk             = explode('||',$no_sk);
        $ctgl_sk            = explode('||',$tgl_sk);
        $cketerangan        = explode('||',$keterangan);
        $cno_mutasi         = explode('||',$no_mutasi);
        $ctgl_mutasi        = explode('||',$tgl_mutasi);
        $cno_pindah         = explode('||',$no_pindah);
        $ctgl_pindah        = explode('||',$tgl_pindah);
        $cno_hapus          = explode('||',$no_hapus);
        $ctgl_hapus         = explode('||',$tgl_hapus);
        $ckd_ruang          = explode('||',$kd_ruang);
        $ckd_lokasi2        = explode('||',$kd_lokasi2);
        $ckd_skpd           = explode('||',$kd_skpd);
        $ckd_unit           = explode('||',$kd_unit);
        $ckd_skpd_lama      = explode('||',$kd_skpd_lama);
        $cmilik             = explode('||',$milik);
        $cwilayah           = explode('||',$wilayah);
        $cusername          = explode('||',$username);
        $ctgl_update        = explode('||',$tgl_update);
        $ctahun             = explode('||',$tahun);
        $cfoto              = explode('||',$foto);
        $cfoto2             = explode('||',$foto2);
        $cfoto3             = explode('||',$foto3);
        $cfoto4             = explode('||',$foto4);
        $cfoto5             = explode('||',$foto5);
        $cno_urut           = explode('||',$no_urut);
        $cmetode            = explode('||',$metode);
        $cmasa_manfaat      = explode('||',$masa_manfaat);
        $cnilai_sisa        = explode('||',$nilai_sisa);
        $ckd_riwayat        = explode('||',$kd_riwayat);
        $ctgl_riwayat       = explode('||',$tgl_riwayat);
        $cdetail_riwayat    = explode('||',$detail_riwayat);
        $cstatus_tanah      = explode('||',$status_tanah);
        $cno_sertifikat     = explode('||',$no_sertifikat);
        $ctgl_sertifikat    = explode('||',$tgl_sertifikat);
        $cluas              = explode('||',$luas);
        $cpenggunaan        = explode('||',$penggunaan);
        $calamat1           = explode('||',$alamat1);
        $calamat2           = explode('||',$alamat2);
        $calamat3           = explode('||',$alamat3);
        $clat               = explode('||',$lat);
        $clon               = explode('||',$lon);
        $cluas_gedung       = explode('||',$luas_gedung);
        $cjenis_gedung      = explode('||',$jenis_gedung);
        $cluas_tanah        = explode('||',$luas_tanah);
        $ckonstruksi        = explode('||',$konstruksi);
        $ckonstruksi2       = explode('||',$konstruksi2);
        $cluas_lantai       = explode('||',$luas_lantai);
        $ckd_tanah          = explode('||',$kd_tanah);
        $chibah             = explode('||',$hibah);
        $cpanjang           = explode('||',$panjang);
        $clebar             = explode('||',$lebar);
        $cperolehan         = explode('||',$perolehan);
        $cjudul             = explode('||',$judul);
        $cspesifikasi       = explode('||',$spesifikasi);
        $ccipta             = explode('||',$cipta);
        $ctahun_terbit      = explode('||',$tahun_terbit);
        $cpenerbit          = explode('||',$penerbit);
        $cjenis             = explode('||',$jenis);
        $cbangunan          = explode('||',$bangunan);
        $ctgl_awal_kerja    = explode('||',$tgl_awal_kerja);
        $cnilai_kontrak     = explode('||',$nilai_kontrak);
        $ckd_golongan       = explode('||',$kd_golongan);
        $ckd_bidang         = explode('||',$kd_bidang);
        $cpemeliharaan_ke   = explode('||',$pemeliharaan_ke);
        $plonline           = $this->session->userdata('plonline');

        $pj=count($cno);
        
        /* Insert ke table mutasi_brg A-F  && mutasi di trkib A-F*/
            for($i=0;$i<$pj;$i++){
                if (trim($cno[$i])!=''){
                
                $kdbrg = substr($ckd_brg[$i],0,2);
                //$id_baru =($cid_barang[$i].".".$kd_skpd);
                    if($kdbrg=='01'){
                    $this->db->query("UPDATE trkib_a SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_a(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,
                    status_tanah,kondisi,asal,dsr_peroleh,no_sertifikat,tgl_sertifikat,luas,nilai,jumlah,total,penggunaan,
                    alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,
                    kd_lokasi2,milik,wilayah,kd_skpd,kd_unit,username,tgl_update,tahun,foto1,foto2,foto3,
                    foto4,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang)
                    VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                    '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                    '".$cdetail_brg[$i]."','".$cstatus_tanah[$i]."','".$ckondisi[$i]."','".$casal[$i]."',
                    '".$cdsr_peroleh[$i]."','".$cno_sertifikat[$i]."','".$ctgl_sertifikat[$i]."',
                    '".$cluas[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$ctotal[$i]."',
                    '".$cpenggunaan[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                    null,null,null,null,null,null,'".$cketerangan[$i]."','".$ckd_lokasi2[$i]."',
                    '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                    '$username','$tgl_update','".$ctahun[$i]."',
                    '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."',
                    '".$clat[$i]."','".$clon[$i]."',null,null,'$rwyt_baru','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."')");
                    }

                    if($kdbrg=='02'){
                    $this->db->query("UPDATE trkib_b SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_b(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,
                    detail_brg,nilai,asal,dsr_peroleh,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,
                    no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,dasar,no_sk,
                    tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,kd_lokasi2,
                    kd_skpd,kd_unit,milik,wilayah,username,tgl_update,tahun,foto,foto2,foto3,foto4,foto5,
                    no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                      '".$cjumlah[$i]."','".$ctotal[$i]."','".$cmerek[$i]."','".$ctipe[$i]."','".$cpabrik[$i]."',
                                      '".$ckd_warna[$i]."','".$ckd_bahan[$i]."','".$ckd_satuan[$i]."','".$cno_rangka[$i]."',
                                      '".$cno_mesin[$i]."','".$cno_polisi[$i]."','".$csilinder[$i]."','".$cno_stnk[$i]."',
                                      '".$ctgl_stnk[$i]."','".$cno_bpkb[$i]."','".$ctgl_bpkb[$i]."','".$ckondisi[$i]."',
                                      '".$ctahun_produksi[$i]."','".$cdasar[$i]."','".$cno_sk[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."',null,null,null,null,null,null,'".$ckd_ruang[$i]."',
                                      '".$ckd_lokasi2[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                      '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."',
                                      '".$cfoto5[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                      '".$cnilai_sisa[$i]."',null,null,'$rwyt_baru','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");
                    }

                    if($kdbrg=='03'){
                    $this->db->query("UPDATE trkib_c SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_c(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,
                    nilai,jumlah,asal,dsr_peroleh,total,no_dok,tgl_dok,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,
                    alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,konstruksi,konstruksi2,
                    luas_lantai,kondisi,dasar,tgl_sk,keterangan,kd_lokasi2,kd_skpd,kd_unit,milik,wilayah,
                    kd_tanah,username,tgl_update,tahun,foto1,foto2,foto3,foto4,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,
                    hibah,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$ctotal[$i]."','','',
                                      '".$cluas_gedung[$i]."','".$cjenis_gedung[$i]."','".$cluas_tanah[$i]."',
                                      '".$cstatus_tanah[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                                      null,null,null,null,null,null,'".$ckonstruksi[$i]."','".$ckonstruksi2[$i]."',
                                      '".$cluas_lantai[$i]."','".$ckondisi[$i]."','".$cdasar[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."','".$ckd_lokasi2[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_tanah[$i]."',
                                      '$username','$tgl_update','".$ctahun[$i]."','".$cfoto[$i]."',
                                      '".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                      '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
                                      '".$chibah[$i]."',null,null,'$rwyt_baru','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                  
                                      }

                    if($kdbrg=='04'){
                    $this->db->query("UPDATE trkib_d SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_d(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang,pemeliharaan_ke)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','$tgl_mut',
                                        '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                        '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$ctotal[$i]."',
                                        '".$ckondisi[$i]."','".$cstatus_tanah[$i]."','".$cpanjang[$i]."',
                                        '".$cluas[$i]."','".$clebar[$i]."','".$ckonstruksi[$i]."','".$calamat1[$i]."','".$calamat2[$i]."',
                                        '".$calamat3[$i]."',null,null,null,null,null,null,'".$cperolehan[$i]."',
                                        '".$cdasar[$i]."','".$cjumlah[$i]."','".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                        '".$cmilik[$i]."','".$cwilayah[$i]."',
                                        '".$cpenggunaan[$i]."','$username','$tgl_update','".$ctahun[$i]."',
                                        '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                        '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',null,null,'$rwyt_baru','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."','".$cpemeliharaan_ke[$i]."')");                   
                                      }

                    if($kdbrg=='05'){
                    $this->db->query("UPDATE trkib_e SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_e(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_peroleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,peroleh,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat,kd_golongan,kd_bidang)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                    '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
                                    '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                    '".$ctotal[$i]."','".$cjudul[$i]."','".$cspesifikasi[$i]."',
                                    '".$ccipta[$i]."','".$ctahun_terbit[$i]."','".$cpenerbit[$i]."','".$ckd_bahan[$i]."',
                                    '".$cjenis[$i]."','".$ctipe[$i]."','".$ckd_satuan[$i]."','".$cjumlah[$i]."',
                                    '".$ckondisi[$i]."','".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                    '".$cmilik[$i]."','".$cwilayah[$i]."','$username','$tgl_update',
                                    null,null,null,null,null,null,
                                    '".$ckd_ruang[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                    '".$cfoto3[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                    '".$cnilai_sisa[$i]."','".$clat[$i]."','".$clon[$i]."',null,null,'$rwyt_baru','".$ckd_golongan[$i]."','".$ckd_bidang[$i]."')");                   
                                      }

                    if($kdbrg=='06'){
                    $this->db->query("UPDATE trkib_f SET no_mutasi='$no_mut',tgl_mutasi='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='".$ckd_skpd_lama[$i]."' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
                    $this->db->query("INSERT INTO trkib_f(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','$tgl_mut',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$ckonstruksi[$i]."','".$cjenis[$i]."','".$cbangunan[$i]."','".$cluas[$i]."',
                                      '".$cjumlah[$i]."','".$ctgl_awal_kerja[$i]."','".$cstatus_tanah[$i]."','".$cnilai_kontrak[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,null,null,null,null,
                                      '".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."','".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '$username','$tgl_update','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                      '".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."',null,null,'$rwyt_baru')");                
                                      }
                    $this->db->query("UPDATE trh_mutasi SET status='Y' WHERE no_mutasi='$no_mutasi' AND kd_skpd='".$ckd_skpd[$i]."' AND kd_unit='".$ckd_unit[$i]."'");
                  
                    }
            }
			     if ($plonline == '1')
                 {
                    //echo $kd_brg;
                    $nomer_mutasi      = $no_mut.'/MUTASI/SIMBAKDA/A';
                    $nomer_mutasi_baru = $no_mut.'/MUTASI/SIMBAKDA/B';
                    $dbsimakda         = $this->load->database('simakda', TRUE);
                    $sqlmap            = "SELECT kd_reklo,nm_reklo,kd_rinci_objek,nm_rinci_objek FROM map_mutasi WHERE kd_barang='$kd_brg' ";
                    $asg2              = $this->db->query($sqlmap);
                    foreach($asg2->result() as $row)
                    {
                        $kd_reklo       =$row->kd_reklo;
                        $nm_reklo       =$row->nm_reklo;
                        $kd_rinci_objek =$row->kd_rinci_objek;
                        $nm_rinci_objek =$row->nm_rinci_objek;
                    }
                        if($kd_brg=='01')
                        {
                            //Belom
                        }
                        if($kd_brg=='02')
                        {
                            $sqlkibb   = "SELECT sum(nil_th_ini) as nil_akum,nilai from kibb_susut where id_barang='$idbrg' ";
                            $asg2      = $this->db->query($sqlkibb);
                            foreach($asg2->result() as $row)
                            {
                                $nilai_akum = $row->nil_akum;
                                $nilai      = $row->nilai;
                                $nil_buku   = $nilai - $nilai_akum;
                            }       
                            //JURNAL UNTUK SKPD YANG MEMBERI
                            $sqltrh = "INSERT into trhju_pkd (no_voucher,tgl_voucher,kd_skpd,nm_skpd,ket,total_d,total_k,tabel) 
                                       values ('$nomer_mutasi','$tgl_mut','$kd_skpd_lama','','$keterangan','$nilai','$nilai','101')";
                            $query1 = $dbsimakda->query($sqltrh);

                            $sqltrd1 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi','$kd_skpd_lama','3110101','Ekuitas','$nil_buku','0','D','0','1','1')";
                            $query2 = $dbsimakda->query($sqltrd1);                              

                            $sqltrd2 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi','$kd_skpd_lama','$kd_rinci_objek','$nm_rinci_objek','$nilai_akum','0','D','0','2','2')";
                            $query3 = $dbsimakda->query($sqltrd2);                              

                            $sqltrd3 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi','$kd_skpd_lama','$kd_reklo','$nm_reklo','0','$nilai','K','0','3','3')";
                            $query4 = $dbsimakda->query($sqltrd3);                              

                            //JURNAL UNTUK SKPD YANG MENERIMA
                            
                            $sqltrh = "INSERT into trhju_pkd (no_voucher,tgl_voucher,kd_skpd,nm_skpd,ket,total_d,total_k,tabel) 
                                       values ('$nomer_mutasi_baru','$tgl_mut','$kd_skpd','','$keterangan','$nilai','$nilai','102')";
                            $query1 = $dbsimakda->query($sqltrh);

                            $sqltrd3 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi_baru','$kd_skpd','$kd_reklo','$nm_reklo','$nilai','0','D','0','1','1')";
                            $query4 = $dbsimakda->query($sqltrd3);                                                          

                            $sqltrd2 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi_baru','$kd_skpd','$kd_rinci_objek','$nm_rinci_objek','0','$nilai_akum','K','0','2','2')";
                            $query3 = $dbsimakda->query
                            ($sqltrd2);                              

                            $sqltrd1 = "INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
                                       values ('$nomer_mutasi_baru','$kd_skpd','3110101','Ekuitas','0','$nil_buku','K','0','3','3')";
                            $query2 = $dbsimakda->query($sqltrd1);                                                         

                        }
                        if($kd_brg=='03')
                        {
                            //belom
                        }
                        if($kd_brg=='04')
                        {
                            //belom
                        }
                        if($kd_brg=='05')
                        {
                            //belom
                        }
                        if($kd_brg=='06')
                        {
                            //belom
                        }
                 }
			
        }
     
	 function ambil_listmutasi(){
        $kd_unit = $this->session->userdata('unit_skpd');
        //$skpd	 = $this->input->post('skpd');
		$cari	 = $this->input->post('cari');
		$where	 = "";
		if($cari<>''){
		$where	 = "where b.nm_brg like '%$cari%' or c.nm_lokasi like '%$cari%' or d.nm_lokasi like '%$cari%'";
		}
        $sql ="SELECT a.*,b.nm_brg,c.nm_lokasi AS asal,d.nm_lokasi AS tujuan FROM mutasi_brg a 
				LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
				LEFT JOIN mlokasi c ON c.kd_lokasi=a.kd_unit 
				LEFT JOIN mlokasi d ON d.kd_lokasi=a.kd_unitb
				$where"; 
				//WHERE a.kd_unit='$skpd'
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                         
                        'no_mutasi'      => $resulte['no_mutasi'],
                        'id_barang'      => $resulte['id_barang'],       
                        'no_reg'         => $resulte['no_reg'],
                        'kd_unit'     	 => $resulte['kd_unit'],  
                        'kd_unitb'     	 => $resulte['kd_unitb'],  
                        'kd_skpdb'     	 => $resulte['kd_skpdb'],   
                        'kd_brg'      	 => $resulte['kd_brg'], 
                        'nm_brg'     	 => $resulte['nm_brg'],      
                        'tgl_mutasi'   	 => $resulte['tgl_mutasi'],                     
                        'keterangan'     => $resulte['keterangan'],                    
                        'tahun'     	 => $resulte['tahun_oleh'],  
                        'kondisi'     	 => $resulte['kondisi'],                        
                        'harga_awal'     => $resulte['harga_awal'],                        
                        'asal'		     => $resulte['asal'],                     
                        'tujuan'  		 => $resulte['tujuan']                                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }	   
	/************************************************END MURASI BARANG************************************************/
    
    //--Penghapusan----------------------------------------------------------------------------------------------------------
    
     function penghapusan(){
        $data['page_title']= 'PENGHAPUSAN USULAN';
        $this->template->set('title', 'PENGHAPUSAN USULAN ');   
        $this->template->load('index','transaksi/penghapusan',$data) ;         
     }
	 
	 function penghapusan_tetap(){
        $data['page_title']= 'PENGHAPUSAN TETAP';
        $this->template->set('title', 'PENGHAPUSAN TETAP');   
        $this->template->load('index','transaksi/penghapusan_tetap',$data) ;         
     }

     function hapus_kib_tetap(){
     $kd_brg    = $this->input->post('kd_brg'); 
     //$pkdbrg    = explode('||',$kd_brg);
     //$kdbrg   = substr($kd_brg,0,2);
     $id_barang = $this->input->post('id_barang');
     
     if($kd_brg=='01'){ 
     $this->db->query("update trkib_a  set no_hapus ='1' where id_barang='$id_barang'");
     }if($kd_brg=='02'){ 
     $this->db->query("update trkib_b  set no_hapus ='1' where id_barang='$id_barang'");
     }if($kd_brg=='03'){ 
     $this->db->query("update trkib_c  set no_hapus ='1' where id_barang='$id_barang'");
     }if($kd_brg=='04'){ 
     $this->db->query("update trkib_d  set no_hapus ='1' where id_barang='$id_barang'");
     }if($kd_brg=='05'){ 
     $this->db->query("update trkib_e  set no_hapus ='1' where id_barang='$id_barang'");
     }if($kd_brg=='06'){ 
     $this->db->query("update trkib_f  set no_hapus ='1' where id_barang='$id_barang'");
     }
     }
     
           
    function ambil_listhapus(){
        $oto    = $this->session->userdata('otori');
        $skpd   = $this->session->userdata('skpd');
        
          $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' and a.status IS NULL";
        }else{
            $where1 = "where a.kd_skpd ='$skpd' AND (a.status IS NULL OR a.status='N' OR a.status='Y')";
        }
        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and (upper(a.no_hapus) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_penghapusan a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT a.*,b.nm_skpd 
                ,(CASE WHEN a.status='N' THEN 'DITOLAK' WHEN a.status='Y' THEN 'DISETUJUI' WHEN a.status IS NULL THEN 'MENUNGGU' END) AS sts
                FROM 
                trh_penghapusan a 
                INNER JOIN ms_skpd b ON b.kd_skpd=a.kd_skpd
                $where1 $where2
                ORDER BY a.no_hapus limit $offset,$rows";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                                 
                            'no_hapus'     => $resulte['no_hapus'],
                            'tgl_hapus'    => $resulte['tgl_hapus'],
                            'kd_unit'      => $resulte['kd_unit'],
                            'kd_skpd'      => $resulte['kd_skpd'],
                            'kd_skpd_asal' => $resulte['kd_skpd_lama'],
                            'nm_skpd'      => $resulte['nm_skpd'],
                            'jumlah'       => $resulte['jumlah'],
                            'total'        => $resulte['total'],
                            'ket'          => $resulte['ket'],
                            'no_urut'      => $resulte['no_urut'],
                            'sts'          => $resulte['sts']
                    
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        
    }

    function ambil_hapus_head(){
        $oto    = $this->session->userdata('otori');
        $skpd   = $this->session->userdata('skpd');
        
          $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' and a.status IS NULL";
        }else{
            $where1 = "where a.kd_skpd ='$skpd' AND (a.status IS NULL OR a.status='N' OR a.status='Y')";
        }
        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and (upper(a.no_hapus) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_penghapusan a $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT a.*,'' as lama,b.nm_skpd as baru
                ,(CASE WHEN a.status='N' THEN 'DITOLAK' WHEN a.status='Y' THEN 'DISETUJUI' WHEN a.status IS NULL THEN 'MENUNGGU' END) AS sts            
                FROM trh_penghapusan a 
                INNER JOIN ms_skpd b ON b.kd_skpd=a.kd_skpd
                $where1 $where2
                ORDER BY a.no_hapus limit $offset,$rows";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(                                 
                            'no_hapus'      => $resulte['no_hapus'],
                            'tgl_hapus'     => $resulte['tgl_hapus'],
                            'kd_unit'      => $resulte['kd_unit'],
                            'kd_skpd'      => $resulte['kd_skpd'],
                            'kd_skpd_asal' => $resulte['kd_skpd_lama'],
                            'lama'         => $resulte['lama'],
                            'baru'         => $resulte['baru'],
                            'jumlah'       => $resulte['jumlah'],
                            'total'        => $resulte['total'],
                            'ket'          => $resulte['ket'],
                            'no_urut'      => $resulte['no_urut'],
                            'sts'          => $resulte['sts']
                    
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        
    }
    
     function tetap_hapus_kib(){
        
        $no_mut         = $this->input->post('nomut');
        $tgl_mut        = $this->input->post('tgl_mut');
        $rwyt_baru      = $this->input->post('rwyt_baru');
        $rwyt_lama      = $this->input->post('rwyt_lama');
        $skpd           = $this->input->post('skpd');
        
        $no_reg         = $this->input->post('no_reg');
        $idbrg          = $this->input->post('idbrg');
        $id_barang      = $this->input->post('id_barang');
        $no             = $this->input->post('no');
        $no_oleh        = $this->input->post('no_oleh');
        $tgl_reg        = $this->input->post('tgl_reg');
        $tgl_oleh       = $this->input->post('tgl_oleh');
        $no_dokumen     = $this->input->post('no_dokumen');
        $kd_brg         = $this->input->post('kd_brg');
        $nm_brg         = $this->input->post('nm_brg');
        $detail_brg     = $this->input->post('detail_brg');
        $nilai          = $this->input->post('nilai');
        $asal           = $this->input->post('asal');
        $dsr_peroleh    = $this->input->post('dsr_peroleh');
        $jumlah         = $this->input->post('jumlah');
        $total          = $this->input->post('total');
        $merek          = $this->input->post('merek');
        $tipe           = $this->input->post('tipe');
        $pabrik         = $this->input->post('pabrik');
        $kd_warna       = $this->input->post('kd_warna');
        $kd_bahan       = $this->input->post('kd_bahan');
        $kd_satuan      = $this->input->post('kd_satuan');
        $no_rangka      = $this->input->post('no_rangka');
        $no_mesin       = $this->input->post('no_mesin');
        $no_polisi      = $this->input->post('no_polisi');
        $silinder       = $this->input->post('silinder');
        $no_stnk        = $this->input->post('no_stnk');
        $tgl_stnk       = $this->input->post('tgl_stnk');
        $no_bpkb        = $this->input->post('no_bpkb');
        $tgl_bpkb       = $this->input->post('tgl_bpkb');
        $kondisi        = $this->input->post('kondisi');
        $tahun_produksi = $this->input->post('tahun_produksi');
        $dasar          = $this->input->post('dasar');
        $no_sk          = $this->input->post('no_sk');
        $tgl_sk         = $this->input->post('tgl_sk');
        $keterangan     = $this->input->post('keterangan');
        $no_mutasi      = $this->input->post('no_mutasi');
        $tgl_mutasi     = $this->input->post('tgl_mutasi');
        $no_pindah      = $this->input->post('no_pindah');
        $tgl_pindah     = $this->input->post('tgl_pindah');
        $no_hapus       = $this->input->post('no_hapus');
        $tgl_hapus      = $this->input->post('tgl_hapus');
        $kd_ruang       = $this->input->post('kd_ruang');
        $kd_lokasi2     = $this->input->post('kd_lokasi2');
        $kd_skpd        = $this->input->post('kd_skpd');
        $kd_unit        = $this->input->post('kd_unit');
        $kd_skpd_lama   = $this->input->post('kd_skpd_lama');
        $milik          = $this->input->post('milik');
        $wilayah        = $this->input->post('wilayah');
        $username       = $this->input->post('username');
        $tgl_update     = $this->input->post('tgl_update');
        $tahun          = $this->input->post('tahun');
        $foto           = $this->input->post('foto');
        $foto2          = $this->input->post('foto2');
        $foto3          = $this->input->post('foto3');
        $foto4          = $this->input->post('foto4');
        $foto5          = $this->input->post('foto5');
        $no_urut        = $this->input->post('no_urut');
        $metode         = $this->input->post('metode');
        $masa_manfaat   = $this->input->post('masa_manfaat');
        $nilai_sisa     = $this->input->post('nilai_sisa');
        $kd_riwayat     = $this->input->post('kd_riwayat');
        $tgl_riwayat    = $this->input->post('tgl_riwayat');
        $detail_riwayat = $this->input->post('detail_riwayat');
        $status_tanah   = $this->input->post('status_tanah');
        $no_sertifikat  = $this->input->post('no_sertifikat');
        $tgl_sertifikat = $this->input->post('tgl_sertifikat');
        $luas           = $this->input->post('luas');
        $penggunaan     = $this->input->post('penggunaan');
        $alamat1        = $this->input->post('alamat1');
        $alamat2        = $this->input->post('alamat2');
        $alamat3        = $this->input->post('alamat3');
        $lat            = $this->input->post('lat');
        $lon            = $this->input->post('lon');
        $luas_gedung    = $this->input->post('luas_gedung');
        $jenis_gedung   = $this->input->post('jenis_gedung');
        $luas_tanah     = $this->input->post('luas_tanah');
        $konstruksi     = $this->input->post('konstruksi');
        $konstruksi2    = $this->input->post('konstruksi2');
        $luas_lantai    = $this->input->post('luas_lantai');
        $kd_tanah       = $this->input->post('kd_tanah');
        $hibah          = $this->input->post('hibah');
        $panjang        = $this->input->post('panjang');
        $lebar          = $this->input->post('lebar');
        $perolehan      = $this->input->post('perolehan');
        $judul          = $this->input->post('judul');
        $spesifikasi    = $this->input->post('spesifikasi');
        $cipta          = $this->input->post('cipta');
        $tahun_terbit   = $this->input->post('tahun_terbit');
        $penerbit       = $this->input->post('penerbit');
        $jenis          = $this->input->post('jenis');
        $bangunan       = $this->input->post('bangunan');
        $tgl_awal_kerja = $this->input->post('tgl_awal_kerja');
        $nilai_kontrak  = $this->input->post('nilai_kontrak');

        $cno_reg         = explode('||',$no_reg);
        $cidbrg          = explode('||',$idbrg);
        $cid_barang      = explode('||',$id_barang);
        $cno             = explode('||',$no);
        $cno_oleh        = explode('||',$no_oleh);
        $ctgl_reg        = explode('||',$tgl_reg);
        $ctgl_oleh       = explode('||',$tgl_oleh);
        $cno_dokumen     = explode('||',$no_dokumen);
        $ckd_brg         = explode('||',$kd_brg);
        $nm_brg          = explode('||',$nm_brg);
        $cdetail_brg     = explode('||',$detail_brg);
        $cnilai          = explode('||',$nilai);
        $casal           = explode('||',$asal);
        $cdsr_peroleh    = explode('||',$dsr_peroleh);
        $cjumlah         = explode('||',$jumlah);
        $ctotal          = explode('||',$total);
        $cmerek          = explode('||',$merek);
        $ctipe           = explode('||',$tipe);
        $cpabrik         = explode('||',$pabrik);
        $ckd_warna       = explode('||',$kd_warna);
        $ckd_bahan       = explode('||',$kd_bahan);
        $ckd_satuan      = explode('||',$kd_satuan);
        $cno_rangka      = explode('||',$no_rangka);
        $cno_mesin       = explode('||',$no_mesin);
        $cno_polisi      = explode('||',$no_polisi);
        $csilinder       = explode('||',$silinder);
        $cno_stnk        = explode('||',$no_stnk);
        $ctgl_stnk       = explode('||',$tgl_stnk);
        $cno_bpkb        = explode('||',$no_bpkb);
        $ctgl_bpkb       = explode('||',$tgl_bpkb);
        $ckondisi        = explode('||',$kondisi);
        $ctahun_produksi = explode('||',$tahun_produksi);
        $cdasar          = explode('||',$dasar);
        $cno_sk          = explode('||',$no_sk);
        $ctgl_sk         = explode('||',$tgl_sk);
        $cketerangan     = explode('||',$keterangan);
        $cno_mutasi      = explode('||',$no_mutasi);
        $ctgl_mutasi     = explode('||',$tgl_mutasi);
        $cno_pindah      = explode('||',$no_pindah);
        $ctgl_pindah     = explode('||',$tgl_pindah);
        $cno_hapus       = explode('||',$no_hapus);
        $ctgl_hapus      = explode('||',$tgl_hapus);
        $ckd_ruang       = explode('||',$kd_ruang);
        $ckd_lokasi2     = explode('||',$kd_lokasi2);
        $ckd_skpd        = explode('||',$kd_skpd);
        $ckd_unit        = explode('||',$kd_unit);
        $ckd_skpd_lama   = explode('||',$kd_skpd_lama);
        $cmilik          = explode('||',$milik);
        $cwilayah        = explode('||',$wilayah);
        $cusername       = explode('||',$username);
        $ctgl_update     = explode('||',$tgl_update);
        $ctahun          = explode('||',$tahun);
        $cfoto           = explode('||',$foto);
        $cfoto2          = explode('||',$foto2);
        $cfoto3          = explode('||',$foto3);
        $cfoto4          = explode('||',$foto4);
        $cfoto5          = explode('||',$foto5);
        $cno_urut        = explode('||',$no_urut);
        $cmetode         = explode('||',$metode);
        $cmasa_manfaat   = explode('||',$masa_manfaat);
        $cnilai_sisa     = explode('||',$nilai_sisa);
        $ckd_riwayat     = explode('||',$kd_riwayat);
        $ctgl_riwayat    = explode('||',$tgl_riwayat);
        $cdetail_riwayat = explode('||',$detail_riwayat);
        $cstatus_tanah   = explode('||',$status_tanah);
        $cno_sertifikat  = explode('||',$no_sertifikat);
        $ctgl_sertifikat = explode('||',$tgl_sertifikat);
        $cluas           = explode('||',$luas);
        $cpenggunaan     = explode('||',$penggunaan);
        $calamat1        = explode('||',$alamat1);
        $calamat2        = explode('||',$alamat2);
        $calamat3        = explode('||',$alamat3);
        $clat            = explode('||',$lat);
        $clon            = explode('||',$lon);
        $cluas_gedung    = explode('||',$luas_gedung);
        $cjenis_gedung   = explode('||',$jenis_gedung);
        $cluas_tanah     = explode('||',$luas_tanah);
        $ckonstruksi     = explode('||',$konstruksi);
        $ckonstruksi2    = explode('||',$konstruksi2);
        $cluas_lantai    = explode('||',$luas_lantai);
        $ckd_tanah       = explode('||',$kd_tanah);
        $chibah          = explode('||',$hibah);
        $cpanjang        = explode('||',$panjang);
        $clebar          = explode('||',$lebar);
        $cperolehan      = explode('||',$perolehan);
        $cjudul          = explode('||',$judul);
        $cspesifikasi    = explode('||',$spesifikasi);
        $ccipta          = explode('||',$cipta);
        $ctahun_terbit   = explode('||',$tahun_terbit);
        $cpenerbit       = explode('||',$penerbit);
        $cjenis          = explode('||',$jenis);
        $cbangunan       = explode('||',$bangunan);
        $ctgl_awal_kerja = explode('||',$tgl_awal_kerja);
        $cnilai_kontrak  = explode('||',$nilai_kontrak);
        $plonline		 = $this->session->userdata('plonline');
        $pj=count($cno);
        
        /* Insert ke table mutasi_brg A-F  && mutasi di trkib A-F*/
            for($i=0;$i<$pj;$i++){
                if (trim($cno[$i])!=''){
                
                $kdbrg = substr($ckd_brg[$i],0,2);
                    if($kdbrg=='01'){
                    $this->db->query("UPDATE trkib_a SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_a(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,
                    status_tanah,kondisi,asal,dsr_peroleh,no_sertifikat,tgl_sertifikat,luas,nilai,jumlah,total,penggunaan,
                    alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,
                    kd_lokasi2,milik,wilayah,kd_skpd,kd_unit,username,tgl_update,tahun,foto1,foto2,foto3,
                    foto4,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
                    VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                    '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."',
                    '".$cdetail_brg[$i]."','".$cstatus_tanah[$i]."','".$ckondisi[$i]."','".$casal[$i]."',
                    '".$cdsr_peroleh[$i]."','".$cno_sertifikat[$i]."','".$ctgl_sertifikat[$i]."',
                    '".$cluas[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$ctotal[$i]."',
                    '".$cpenggunaan[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                    null,null,'".$cno_pindah[$i]."','".$ctgl_pindah[$i]."',
                    '".$cno_hapus[$i]."','".$ctgl_hapus[$i]."','".$cketerangan[$i]."','".$ckd_lokasi2[$i]."',
                    '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                    '".$cusername[$i]."','".$ctgl_update[$i]."','".$ctahun[$i]."',
                    '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."',
                    '".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')"); */
                    }
                    if($kdbrg=='02'){
                    $this->db->query("UPDATE trkib_b SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_b(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,
                    detail_brg,nilai,asal,dsr_peroleh,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,
                    no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,dasar,no_sk,
                    tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,kd_lokasi2,
                    kd_skpd,kd_unit,milik,wilayah,username,tgl_update,tahun,foto,foto2,foto3,foto4,foto5,
                    no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                      '".$cjumlah[$i]."','".$ctotal[$i]."','".$cmerek[$i]."','".$ctipe[$i]."','".$cpabrik[$i]."',
                                      '".$ckd_warna[$i]."','".$ckd_bahan[$i]."','".$ckd_satuan[$i]."','".$cno_rangka[$i]."',
                                      '".$cno_mesin[$i]."','".$cno_polisi[$i]."','".$csilinder[$i]."','".$cno_stnk[$i]."',
                                      '".$ctgl_stnk[$i]."','".$cno_bpkb[$i]."','".$ctgl_bpkb[$i]."','".$ckondisi[$i]."',
                                      '".$ctahun_produksi[$i]."','".$cdasar[$i]."','".$cno_sk[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."',null,null,'".$cno_pindah[$i]."',
                                      '".$ctgl_pindah[$i]."','".$cno_hapus[$i]."','".$ctgl_hapus[$i]."','".$ckd_ruang[$i]."',
                                      '".$ckd_lokasi2[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','".$cusername[$i]."','".$ctgl_update[$i]."',
                                      '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."',
                                      '".$cfoto5[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                      '".$cnilai_sisa[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')"); */
                    }
                    if($kdbrg=='03'){
                    $this->db->query("UPDATE trkib_c SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_c(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,
                    nilai,jumlah,asal,dsr_peroleh,total,no_dok,tgl_dok,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,
                    alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,konstruksi,konstruksi2,
                    luas_lantai,kondisi,dasar,tgl_sk,keterangan,kd_lokasi2,kd_skpd,kd_unit,milik,wilayah,
                    kd_tanah,username,tgl_update,tahun,foto1,foto2,foto3,foto4,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,
                    hibah,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                      '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."',
                                      '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$casal[$i]."',
                                      '".$cdsr_peroleh[$i]."','".$ctotal[$i]."','".$cno_dok[$i]."','".$ctgl_dok[$i]."',
                                      '".$cluas_gedung[$i]."','".$cjenis_gedung[$i]."','".$cluas_tanah[$i]."',
                                      '".$cstatus_tanah[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
                                      null,null,'".$cno_pindah[$i]."','".$ctgl_pindah[$i]."',
                                      '".$cno_hapus[$i]."','".$ctgl_hapus[$i]."','".$ckonstruksi[$i]."','".$ckonstruksi2[$i]."',
                                      '".$cluas_lantai[$i]."','".$ckondisi[$i]."','".$cdasar[$i]."','".$ctgl_sk[$i]."',
                                      '".$cketerangan[$i]."','".$ckd_lokasi2[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                      '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_tanah[$i]."',
                                      '".$cusername[$i]."','".$ctgl_update[$i]."','".$ctahun[$i]."','".$cfoto[$i]."',
                                      '".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                      '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
                                      '".$chibah[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')");          */     
                                      }
                    if($kdbrg=='04'){
                    $this->db->query("UPDATE trkib_d SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_d(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','$tgl_mut',
                                        '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$cdetail_brg[$i]."',
                                        '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$ctotal[$i]."',
                                        '".$ckondisi[$i]."','".$cstatus_tanah[$i]."','".$cpanjang[$i]."',
                                        '".$cluas[$i]."','".$clebar[$i]."','".$ckonstruksi[$i]."','".$calamat1[$i]."','".$calamat2[$i]."',
                                        '".$calamat3[$i]."',null,null,'".$cno_pindah[$i]."',
                                        '".$ctgl_pindah[$i]."','".$cno_hapus[$i]."','".$ctgl_hapus[$i]."','".$cperolehan[$i]."',
                                        '".$cdasar[$i]."','".$cjumlah[$i]."','".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                        '".$cmilik[$i]."','".$cwilayah[$i]."',
                                        '".$cpenggunaan[$i]."','".$cusername[$i]."','".$ctgl_update[$i]."','".$ctahun[$i]."',
                                        '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
                                        '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
                                        '".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')");  */             
                                      }
                    if($kdbrg=='05'){
                    $this->db->query("UPDATE trkib_e SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_e(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_peroleh,no_dokumen,kd_brg,detail_brg,nilai,peroleh,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."',
                                    '$tgl_mut','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."',
                                    '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
                                    '".$ctotal[$i]."','".$cjudul[$i]."','".$cspesifikasi[$i]."',
                                    '".$ccipta[$i]."','".$ctahun_terbit[$i]."','".$cpenerbit[$i]."','".$ckd_bahan[$i]."',
                                    '".$cjenis[$i]."','".$ctipe[$i]."','".$ckd_satuan[$i]."','".$cjumlah[$i]."',
                                    '".$ckondisi[$i]."','".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."',
                                    '".$cmilik[$i]."','".$cwilayah[$i]."','".$cusername[$i]."','".$ctgl_update[$i]."',
                                    null,null,'".$cno_pindah[$i]."',
                                    '".$ctgl_pindah[$i]."','".$cno_hapus[$i]."','".$ctgl_hapus[$i]."',
                                    '".$ckd_ruang[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                    '".$cfoto3[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
                                    '".$cnilai_sisa[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."',
                                    '".$ctgl_riwayat[$i]."','$rwyt_baru')"); */                 
                                      }
                    if($kdbrg=='06'){
                    $this->db->query("UPDATE trkib_f SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/*                  $this->db->query("INSERT INTO trkib_f(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
                                      VALUES ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','$tgl_mut',
                                      '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$cdetail_brg[$i]."',
                                      '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
                                      '".$ckondisi[$i]."','".$ckonstruksi[$i]."','".$cjenis[$i]."','".$cbangunan[$i]."','".$cluas[$i]."',
                                      '".$cjumlah[$i]."','".$ctgl_awal_kerja[$i]."','".$cstatus_tanah[$i]."','".$cnilai_kontrak[$i]."',
                                      '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,
                                      '".$cno_pindah[$i]."','".$ctgl_pindah[$i]."','".$cno_hapus[$i]."','".$ctgl_hapus[$i]."',
                                      '".$cketerangan[$i]."','".$ckd_skpd[$i]."','".$ckd_unit[$i]."','".$cmilik[$i]."','".$cwilayah[$i]."',
                                      '".$cusername[$i]."','".$ctgl_update[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
                                      '".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."', 
                                      '$rwyt_baru')");              */
                                      }
                    $this->db->query("UPDATE trh_penghapusan SET status='Y' WHERE no_hapus='$no_mut' AND kd_skpd='$skpd'");//  AND kd_unit='".$ckd_unit[$i]."'
                  
                    }
            }
			if ($plonline=='1')
			{
                $no_mut_aset = $no_mut.'/TETAP-HAPUS/SIMBAKDA';
                $tahun = $this->session->userdata('ta_simbakda');

                // $csql ="SELECT a.kd_brg AS kode,b.nm_brg,a.merek,a.tahun,a.`nilai`,TRIM(c.umur) AS umur,
                //         if(a.tahun='$tahun',0,($tahun-a.tahun)) AS th_lalu,$tahun AS th_ini,a.tahun,
                //         (a.nilai/TRIM(c.umur)) AS penyusutan_pertahun,

                //         IF(a.tahun='$tahun',0,(CASE 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
                //         END)) AS tot_th_belum, 

                //         IF(a.tahun='$tahun',0,(CASE 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
                //         WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
                //         END)) AS nil_th_ini


                //         FROM trkib_b a
                //         LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
                //         LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
                //         WHERE kd_barang<>'' AND a.kd_brg='$kd_brg' AND a.id_barang='$idbrg' AND tahun<='$tahun' ";
                //         $hasil = $this->db->query($csql);
                //         foreach ($hasil->result() as $row) {
                //             $tot_th_belum = $row->tot_th_belum;
                //             $nil_th_ini   = $row->nil_th_ini;
                //             $akumulasi    = $tot_th_belum + $nil_th_ini;
                //             $nilai        = $row->nilai;
                //             $total_buku   = $nilai - $akumulasi;
                            
                //         }
                 $sqlkibb   = "SELECT sum(nil_th_ini) as nil_akum,nilai from kibb_susut where id_barang='$idbrg' ";
                 $asg2      = $this->db->query($sqlkibb);
                            foreach($asg2->result() as $row)
                            {
                                $nilai_akum = $row->nil_akum;
                                $nilai      = $row->nilai;
                                $nil_buku   = $nilai - $nilai_akum;
                            }     

				$dbsimakda = $this->load->database('simakda', TRUE);
                $sqlins    = " INSERT into trhju_pkd (no_voucher,tgl_voucher,kd_skpd,nm_skpd,ket,total_d,total_k,tabel) values 
                            ('$no_mut_aset','$tgl_mut','$skpd','','$keterangan','','','95') ";
                $query1 = $dbsimakda->query($sqlins);  

                $sqlins1    = " INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) values 
                            ('$no_mut_aset','$skpd','3130101','RK PPKD','$nil_buku','0','D','0','1','1') ";
                $query11 = $dbsimakda->query($sqlins1);  

                $sqlins2    = " INSERT into trdju_pkd (no_voucher,kd_skpd,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) values 
                            ('$no_mut_aset','$skpd','1540101','Aset Lain-lain','0','$nil_buku','K','0','2','2') ";
                $query12 = $dbsimakda->query($sqlins2);  

			}
        }


        
    function ambil_hapus_detail(){
        $skpd       = $this->input->post('skpd');
        $no_hapus   = $this->input->post('nomor');
    
        $sql = "SELECT a.*,
        SUBSTRING(a.id_barang,1,
        (LENGTH(TRIM(a.id_barang))-LENGTH(RIGHT(TRIM(a.id_barang),11)))) AS idbrg
        FROM trd_penghapusan a 
        where a.kd_skpd='$skpd' and a.no_hapus='$no_hapus'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_reg'            => $resulte['no_reg'],
                        'id_barang'         => $resulte['id_barang'],
                        'idbrg'             => $resulte['idbrg'],
                        'no'                => $resulte['no'],
                        'no_oleh'           => $resulte['no_oleh'],
                        'tgl_reg'           => $resulte['tgl_reg'],
                        'tgl_oleh'          => $resulte['tgl_oleh'],
                        'no_dokumen'        => $resulte['no_dokumen'],
                        'nm_brg'            => $resulte['nm_brg'],
                        'kd_brg'            => $resulte['kd_brg'],
                        'detail_brg'        => $resulte['detail_brg'],
                        'nilai'             => $resulte['nilai'],
                        'asal'              => $resulte['asal'],
                        'dsr_peroleh'       => $resulte['dsr_peroleh'],
                        'jumlah'            => $resulte['jumlah'],
                        'total'             => $resulte['total'],
                        'merek'             => $resulte['merek'],
                        'tipe'              => $resulte['tipe'],
                        'pabrik'            => $resulte['pabrik'],
                        'kd_warna'          => $resulte['kd_warna'],
                        'kd_bahan'          => $resulte['kd_bahan'],
                        'kd_satuan'         => $resulte['kd_satuan'],
                        'no_rangka'         => $resulte['no_rangka'],
                        'no_mesin'          => $resulte['no_mesin'],
                        'no_polisi'         => $resulte['no_polisi'],
                        'silinder'          => $resulte['silinder'],
                        'no_stnk'           => $resulte['no_stnk'],
                        'tgl_stnk'          => $resulte['tgl_stnk'],
                        'no_bpkb'           => $resulte['no_bpkb'],
                        'tgl_bpkb'          => $resulte['tgl_bpkb'],
                        'kondisi'           => $resulte['kondisi'],
                        'tahun_produksi'    => $resulte['tahun_produksi'],
                        'dasar'             => $resulte['dasar'],
                        'no_sk'             => $resulte['no_sk'],
                        'tgl_sk'            => $resulte['tgl_sk'],
                        'keterangan'        => $resulte['keterangan'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'        => $resulte['tgl_mutasi'],
                        'no_pindah'         => $resulte['no_pindah'],
                        'tgl_pindah'        => $resulte['tgl_pindah'],
                        'no_hapus'          => $resulte['no_hapus'],
                        'tgl_hapus'         => $resulte['tgl_hapus'],
                        'kd_ruang'          => $resulte['kd_ruang'],
                        'kd_lokasi2'        => $resulte['kd_lokasi2'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'kd_unit'           => $resulte['kd_unit'],
                        'kd_skpd_lama'      => $resulte['kd_skpd_lama'],
                        'milik'             => $resulte['milik'],
                        'wilayah'           => $resulte['wilayah'],
                        'username'          => $resulte['username'],
                        'tgl_update'        => $resulte['tgl_update'],
                        'tahun'             => $resulte['tahun'],
                        'foto'              => $resulte['foto'],
                        'foto2'             => $resulte['foto2'],
                        'foto3'             => $resulte['foto3'],
                        'foto4'             => $resulte['foto4'],
                        'foto5'             => $resulte['foto5'],
                        'no_urut'           => $resulte['no_urut'],
                        'metode'            => $resulte['metode'],
                        'masa_manfaat'      => $resulte['masa_manfaat'],
                        'nilai_sisa'        => $resulte['nilai_sisa'],
                        'kd_riwayat'        => $resulte['kd_riwayat'],
                        'tgl_riwayat'       => $resulte['tgl_riwayat'],
                        'detail_riwayat'    => $resulte['detail_riwayat'],
                        'status_tanah'      => $resulte['status_tanah'],
                        'no_sertifikat'     => $resulte['no_sertifikat'],
                        'tgl_sertifikat'    => $resulte['tgl_sertifikat'],
                        'luas'              => $resulte['luas'],
                        'penggunaan'        => $resulte['penggunaan'],
                        'alamat1'           => $resulte['alamat1'],
                        'alamat2'           => $resulte['alamat2'],
                        'alamat3'           => $resulte['alamat3'],
                        'lat'               => $resulte['lat'],
                        'lon'               => $resulte['lon'],
                        'luas_gedung'       => $resulte['luas_gedung'],
                        'jenis_gedung'      => $resulte['jenis_gedung'],
                        'luas_tanah'        => $resulte['luas_tanah'],
                        'konstruksi'        => $resulte['konstruksi'],
                        'konstruksi2'       => $resulte['konstruksi2'],
                        'luas_lantai'       => $resulte['luas_lantai'],
                        'kd_tanah'          => $resulte['kd_tanah'],
                        'hibah'             => $resulte['hibah'],
                        'panjang'           => $resulte['panjang'],
                        'lebar'             => $resulte['lebar'],
                        'perolehan'         => $resulte['perolehan'],
                        'judul'             => $resulte['judul'],
                        'spesifikasi'       => $resulte['spesifikasi'],
                        'cipta'             => $resulte['cipta'],
                        'tahun_terbit'      => $resulte['tahun_terbit'],
                        'penerbit'          => $resulte['penerbit'],
                        'jenis'             => $resulte['jenis'],
                        'bangunan'          => $resulte['bangunan'],
                        'tgl_awal_kerja'    => $resulte['tgl_awal_kerja'],
                        'nilai_kontrak'     => $resulte['nilai_kontrak'],
                        'auto'              => $resulte['auto'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
                        'pemeliharaan_ke'   => $resulte['pemeliharaan_ke']
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
	 
	 /*function hapus_kib_tetap(){
	 $kd_brg 	= $this->input->post('kd_brg'); 
     //$pkdbrg    = explode('||',$kd_brg);
	 //$kdbrg 	= substr($kd_brg,0,2);
	 $id_barang = $this->input->post('id_barang');
	 
	 if($kd_brg=='01'){ 
	 $this->db->query("update trkib_a  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_a where id_barang='$id_barang'");
	 }if($kd_brg=='02'){ 
	 $this->db->query("update trkib_b  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_b where id_barang='$id_barang'");
	 }if($kd_brg=='03'){ 
	 $this->db->query("update trkib_c  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_c where id_barang='$id_barang'");
	 }if($kd_brg=='04'){ 
	 $this->db->query("update trkib_d  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_d where id_barang='$id_barang'");
	 }if($kd_brg=='05'){ 
	 $this->db->query("update trkib_e  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_e where id_barang='$id_barang'");
	 }if($kd_brg=='06'){ 
	 $this->db->query("update trkib_f  set no_hapus ='1' where id_barang='$id_barang'");
	 $this->db->query("delete from trhapus_f where id_barang='$id_barang'");
	 }
	 }
	 
     function hapus_kib(){
        
        $id_barang  = $this->input->post('id_barang');
        $uskpd      = $this->input->post('uskpd');
        $tgl        = $this->input->post('tgl');
		$no         = $this->input->post('no');
        $nodoc      = $this->input->post('no_dokumen');
        $no_reg     = $this->input->post('no_reg');
        $kd_brg     = $this->input->post('kd_brg');
        $nm_brg     = $this->input->post('nm_brg');
        $tgl_reg    = $this->input->post('tgl_reg');
        $kondisi    = $this->input->post('kondisi'); 
        $tahun      = $this->input->post('tahun');
        $harga      = $this->input->post('harga');
       
        $pno        = explode('||',$no);
        $pnodoc     = explode('||',$nodoc);                
        $pnoreg     = explode('||',$no_reg); 
        $pkdbrg     = explode('||',$kd_brg); 
        $pnmbrg     = explode('||',$nm_brg); 
        $ptglbrg    = explode('||',$tgl_reg); 
        $pkondisi   = explode('||',$kondisi); 
        $ptahun     = explode('||',$tahun); 
        $pharga     = explode('||',$harga); 
        
		$pj=count($pno);
        
        // Insert ke table trhapus A-F  && Menghapus di trkib A-F
    		for($i=0;$i<$pj;$i++){
                if (trim($pno[$i])!=''){ 

    		      $kdbrg = substr($pkdbrg[$i],0,2);
                  	
                     if($kdbrg =='01'){ 	
                     
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_a where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_a(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_a  set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang'");
                                }                     
                            }
                            
                     }else if($kdbrg =='02'){
                        
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_b where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_b(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_b  set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang' ");
                                }
                            }
                            
                     }else if($kdbrg =='03'){
                            
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_c where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_c(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_c  set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang'");
                                }
                            }
                            
                     }else if($kdbrg =='04'){
                        
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_d where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_d(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_d  set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang' ");
                                }
                            }
                                      
                     }else if($kdbrg =='05'){
                        
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_e where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_e(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_e  set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang' ");
                                }
                            }
                        
                     }else if($kdbrg =='06'){
                        
                            $nohapus='0001';
                            $sql  ="select max(no_hapus) as no from trkib_f where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                                $sql = "insert into trhapus_f(no_reg,id_barang,tgl_reg,no_dokumen,kd_brg,nilai,no_hapus,keterangan,kd_unit,username,tgl_update,tahun) 
                                         values('".$pnoreg[$i]."','$id_barang','$tgl','".$pnodoc[$i]."','".$pkdbrg[$i]."','".$pharga[$i]."','$nohapus','','$uskpd','','','".$ptahun[$i]."')";
                                $asg = $this->db->query($sql);
                                if($sql){
                                    $this->db->query("update trkib_f set no_hapus ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' and id_barang='$id_barang' ");
                                }
                            }                
                         
                     }
                     		
    			}
    		}   
       
	}
       
    function ambil_listhapus(){
        $kd_unit = $this->session->userdata('unit_skpd');
        $sql ="SELECT * FROM  
		(SELECT a.id_barang,a.no_reg,a.kd_brg,b.nm_brg,a.keterangan,a.foto FROM trhapus_f a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
UNION
SELECT id_barang,no_reg,a.kd_brg,b.nm_brg,keterangan,a.foto  FROM trhapus_a a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
UNION 
SELECT id_barang,no_reg,a.kd_brg,b.nm_brg,keterangan,a.foto  FROM trhapus_b a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
UNION 
SELECT id_barang,no_reg,a.kd_brg,b.nm_brg,keterangan,a.foto  FROM trhapus_c a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
UNION 
SELECT id_barang,no_reg,a.kd_brg,b.nm_brg,keterangan,a.foto  FROM trhapus_d a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg
UNION 
SELECT id_barang,no_reg,a.kd_brg,b.nm_brg,keterangan,a.foto  FROM trhapus_e a
LEFT JOIN mbarang b ON a.kd_brg=b.kd_brg) 
faiz ORDER BY kd_brg,no_reg";//WHERE a.kd_unit='$kd_unit'
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'id_barang'      => $resulte['id_barang'],
                        'no_reg'         => $resulte['no_reg'], 
                        'kd_brg'      	 => $resulte['kd_brg'], 
                        'nm_brg'     	 => $resulte['nm_brg'],                       
                        'keterangan'     => $resulte['keterangan'],                       
                        'foto'  		 => $resulte['foto']                                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }	*/   
     
    //--End Penghapusan------------------------------------------------------------------------------------------------------
    
    /*--Hibah --------------------------------------------------------------------------------------------------------------- */
     function hibah(){
        $data['page_title']= 'HIBAH BARANG';
        $this->template->set('title', 'HIBAH BARANG ');   
        $this->template->load('index','transaksi/tr_hibah',$data) ;         
     }
 
    function update_hibah(){
        
        $uskpd      = $this->input->post('uskpd');
        $tgl        = $this->input->post('tgl');
		$no         = $this->input->post('no');
        $nodoc      = $this->input->post('no_dokumen');
        $no_reg     = $this->input->post('no_reg');
        $kd_brg     = $this->input->post('kd_brg');
        $nm_brg     = $this->input->post('nm_brg');
        $tgl_reg    = $this->input->post('tgl_reg');
        $kondisi    = $this->input->post('kondisi'); 
        $tahun      = $this->input->post('tahun');
        $harga      = $this->input->post('harga');
       
        $pno        = explode('||',$no);
        $pnodoc     = explode('||',$nodoc);                
        $pnoreg     = explode('||',$no_reg); 
        $pkdbrg     = explode('||',$kd_brg); 
        $pnmbrg     = explode('||',$nm_brg); 
        $ptglbrg    = explode('||',$tgl_reg); 
        $pkondisi   = explode('||',$kondisi); 
        $ptahun     = explode('||',$tahun); 
        $pharga     = explode('||',$harga); 
        
		$pj=count($pno);
        
        /* Insert ke table trhapus A-F  && Menghapus di trkib A-F*/
    		for($i=0;$i<$pj;$i++){
                if (trim($pno[$i])!=''){ 

    		      $kdbrg = substr($pkdbrg[$i],0,2);
                  	
                     if($kdbrg =='01'){ 	
                     
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_a where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                
                               $this->db->query("update trkib_a  set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                                           
                            }
                            
                     }else if($kdbrg =='02'){
                        
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_b where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                     
                                $this->db->query("update trkib_b  set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                            }
                            
                     }else if($kdbrg =='03'){
                            
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_c where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                             
                               $this->db->query("update trkib_c  set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                           }
                     }else if($kdbrg =='04'){
                        
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_d where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                             
                                $this->db->query("update trkib_d  set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                           }
                                      
                     }else if($kdbrg =='05'){
                        
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_e where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                 
                                    $this->db->query("update trkib_e  set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                                
                            }
                        
                     }else if($kdbrg =='06'){
                        
                            $nohapus='0001';
                            $sql  ="select max(hibah) as no from trkib_f where kd_brg ='".$pkdbrg[$i]."' and kd_unit='$uskpd' ";
                            $query1 = $this->db->query($sql);  
                            foreach($query1->result_array() as $resulte){ 
                            	$nohapus=$resulte['no'];
                                $nohapus=($nohapus)+1;
                      		
                             if(strlen($nohapus)==1)$nohapus='000'.$nohapus;
                             if(strlen($nohapus)==2)$nohapus='00'.$nohapus;
                             if(strlen($nohapus)==3)$nohapus='0'.$nohapus;
                                                 
                                $this->db->query("update trkib_f set hibah ='$nohapus' where no_reg ='".$pnoreg[$i]."' and kd_unit='$uskpd' ");
                            }                
                         
                     }
                     		
    			}
    		}   
       
	}
    
    /*--End Hibah------------------------------------------------------------------------------------------------------------ */
    
    function uploadfile(){
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
	
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
	
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
				$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
				move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/simbakda_bantaeng/data/'.$_FILES['fileToUpload']['name']);
				//for security reason, we force to remove all uploaded file
				@unlink($_FILES['fileToUpload']);		
		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";	
	}
    
    ////// END REncana PEmeliharaan Barang ///////////
    function do_upload()
	{	
		$config['upload_path'] = './data/';
		$config['allowed_types'] = 'gif|jpg|png|bmp';
		$config['max_size']	= '10000';
		$config['max_width']  = '50000';
		$config['max_height']  = '10000';
        $config['userfile'] = $this->input->post('cfile');
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			//$this->load->view('upload_form', $error);
            $data['page_title']= 'UPLOAD FOTO';
            $data['hasil']='0';
            $this->template->set('title', 'UPLOAD FOTO');   
            $this->template->load('index','upload_form',$data) ;
		}
		else
		{
		    $result = array();
            $result[] = array(                                
                            'hasil'    => 'berhasil'                                                                                                                                                                  
                            );
            echo json_encode($result);
		}
	}
    
    function scan(){

        $dir = opendir('./data');
        while ($entri=readdir($dir)){
            $nm=explode('.',$entri);
            if (strtoupper($nm[1])=='JPG'||strtoupper($nm[1])=='PNG'){
         
            $result[] = array(
                        'nm' => $nm[0].'.'.$nm[1]                                                                                                                                     
                        );               
            }
        }
    echo json_encode($result);
    }

 function tes() {
   
      //  $this->datadb = $this->load->database('alternate', TRUE);
        
        $sql = "SELECT sarana_id,nama,jumlah FROM sarana";

        $query1 = $this->db1->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'sarana_id' => $resulte['sarana_id'],
                        'nama' => $resulte['nama'],
                        'jumlah' => $resulte['jumlah'],
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
	
	function kode(){
		$this->load->library('ciqrcode');

		//$params['data'] = array(224,255,255);
		$params['data'] = 'http://localhost/simbakda_bantaeng/index.php/transaksi/kotak';
		$params['level'] = 'H';
		$params['size'] = 5;
		$params['savename'] = FCPATH.'tes.png';
		$this->ciqrcode->generate($params);

		echo '<img src="'.base_url().'tes.png" />';
		/*
		$this->load->library('ciqrcode');
		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']     = ''; //string, the default is application/cache/
		$config['errorlog']     = ''; //string, the default is application/logs/
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = ''; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);    */
		}

        function ambil_hapus()
    
    { 
        $lckib  = $this->input->post('gol');
        $kdskpd = $this->input->post('kdskpd');
        $kriteria   = $this->input->post('cari');
        $unit       = $this->input->post('unit');
            
        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;      
            
    
        $where="";
        if($kriteria<>''){
        $where="AND (UPPER(a.tahun) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.kondisi) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.keterangan) LIKE UPPER('%$kriteria%') 
                    OR UPPER(a.nilai) LIKE UPPER('%$kriteria%')
                    OR UPPER(b.nm_brg) LIKE UPPER('%$kriteria%') 
                    )";
        }
        
        if($lckib == '01'){ 
        //$sqlx = "SELECT count(*) as total from v_mts_a a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_a a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
        
/*      $sql="SELECT * FROM v_mts_a a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL 
        order by a.kd_brg limit $offset,$rows"; */
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,
a.detail_brg,a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,
'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,
'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,
'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,
a.no_hapus,a.tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,kd_unit,'' AS kd_skpd_lama,
a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,
no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,
a.status_tanah,a.no_sertifikat,a.tgl_sertifikat,a.luas,penggunaan,a.alamat1,a.alamat2,
a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,
'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
        
        b.nm_brg,c.nm_skpd FROM trkib_a a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
        order by a.kd_brg limit $offset,$rows";*/

        
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi 
        FROM trkib_a a LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */    $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,
                a.detail_brg,a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,
                '' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,
                '' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,
                '' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,
                a.no_hapus,a.tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,kd_unit,'' AS kd_skpd_lama,
                a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,
                no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,
                a.status_tanah,a.no_sertifikat,a.tgl_sertifikat,a.luas,penggunaan,a.alamat1,a.alamat2,
                a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,
                '' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
                '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,'' as pemeliharaan_ke FROM trkib_a a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
                AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
                order by a.id_barang limit $offset,$rows";   

        }
        
        if($lckib == '02'){  
        //$sqlx = "SELECT count(*) as total from v_mts_b a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_b a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
                 
        //$sql="SELECT * FROM v_mts_b a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_b a 
        LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg ";
 */        
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,a.merek,a.tipe,a.pabrik,a.kd_warna,a.kd_bahan,
a.kd_satuan,a.no_rangka,a.no_mesin,a.no_polisi,a.silinder,a.no_stnk,a.tgl_stnk,a.no_bpkb,
a.tgl_bpkb,a.kondisi,a.tahun_produksi,a.dasar,a.no_sk,a.tgl_sk,a.keterangan,a.no_mutasi,
a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,a.kd_lokasi2,
a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
a.foto2,a.foto3,a.foto4,a.foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,
a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
'' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,'' AS lat,'' AS lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
'' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,
'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
        
        b.nm_brg,c.nm_skpd FROM trkib_b a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') 
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,
                a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,a.merek,a.tipe,a.pabrik,a.kd_warna,a.kd_bahan,
                a.kd_satuan,a.no_rangka,a.no_mesin,a.no_polisi,a.silinder,a.no_stnk,a.tgl_stnk,a.no_bpkb,
                a.tgl_bpkb,a.kondisi,a.tahun_produksi,a.dasar,a.no_sk,a.tgl_sk,a.keterangan,a.no_mutasi,
                a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,a.kd_lokasi2,
                a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
                a.foto2,a.foto3,a.foto4,a.foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,
                a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
                '' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,'' AS lat,'' AS lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
                '' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,
                '' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,a.pemeliharaan_ke FROM trkib_b a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
                AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
                order by a.id_barang limit $offset,$rows";

        }
        
        if($lckib == '03'){ 
        //$sqlx = "SELECT count(*) as total from v_mts_c a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_c a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
            //$sql="SELECT * FROM v_mts_c a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_c a
        LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,a.nilai,
a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,
'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,
dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,
tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,
a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
'' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,a.luas_gedung,a.jenis_gedung,a.luas_tanah,a.konstruksi,
a.konstruksi2,a.luas_lantai,a.kd_tanah,a.hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
        
        b.nm_brg,c.nm_skpd FROM trkib_c a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') 
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,
                a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,
                '' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,
                dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,
                tgl_hapus,'' AS kd_ruang,a.kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
                a.tgl_update,a.tahun,'' AS foto,a.foto2,a.foto3,a.foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,a.nilai_sisa,
                a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,
                '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,a.luas_gedung,a.jenis_gedung,a.luas_tanah,a.konstruksi,
                a.konstruksi2,a.luas_lantai,a.kd_tanah,a.hibah,'' AS panjang,'' AS lebar,'' AS perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,
                '' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,'' AS nilai_kontrak,
                c.nm_skpd,a.pemeliharaan_ke FROM trkib_c a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
                AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
                order by a.id_barang limit $offset,$rows";

        }
                
        if($lckib == '04'){  
        //$sqlx = "SELECT count(*) as total from v_mts_d a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_d a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
             
        /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,
a.kd_brg,a.detail_brg,a.nilai,a.asal,'' AS dsr_peroleh,a.jumlah,a.total,'' AS merek,
'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,
'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,dasar,
'' AS no_sk,'' AS tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,
tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,
a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,
'' AS tgl_sertifikat,a.luas,a.penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,
'' AS jenis_gedung,'' AS luas_tanah,konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,panjang,
lebar,perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,
'' AS nilai_kontrak,
        
        b.nm_brg,c.nm_skpd FROM trkib_d a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') 
        order by a.kd_brg limit $offset,$rows";*/
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_d a 
        LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */    $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,
            a.kd_brg,a.nm_brg,a.detail_brg,a.nilai,a.asal,'' AS dsr_peroleh,a.jumlah,a.total,'' AS merek,
            '' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,
            '' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,dasar,
            '' AS no_sk,'' AS tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,
            tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,
            a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,a.masa_manfaat,
            a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,
            '' AS tgl_sertifikat,a.luas,a.penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,
            '' AS jenis_gedung,'' AS luas_tanah,konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,panjang,
            lebar,perolehan,'' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,'' AS bangunan,'' AS tgl_awal_kerja,
            '' AS nilai_kontrak,c.nm_skpd,a.pemeliharaan_ke FROM trkib_d a 
            INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
            AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
            order by a.id_barang limit $offset,$rows";    }
        
        if($lckib == '05'){  
        //$sqlx = "SELECT count(*) as total from v_mts_e a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_e a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
            //$sql="SELECT * FROM v_mts_e a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_e a 
        LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */                     /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_peroleh AS tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,a.tipe,'' AS pabrik,'' AS kd_warna,a.kd_bahan,a.kd_satuan,
'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,'' AS kondisi,
'' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,
a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,
a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,
a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,
'' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,'' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,lat,lon,'' AS luas_gedung,
'' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,
'' AS lebar,'' AS perolehan,a.judul,a.spesifikasi,a.cipta,a.tahun_terbit,a.penerbit,a.jenis,'' AS bangunan,'' AS tgl_awal_kerja,
'' AS nilai_kontrak,

        b.nm_brg,c.nm_skpd FROM trkib_e a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_peroleh AS tgl_oleh,a.no_dokumen,a.kd_golongan,a.kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,
            a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,a.tipe,'' AS pabrik,'' AS kd_warna,a.kd_bahan,a.kd_satuan,
            '' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,'' AS tgl_bpkb,'' AS kondisi,
            '' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,a.tgl_mutasi,a.no_pindah,
            a.tgl_pindah,a.no_hapus,a.tgl_hapus,a.kd_ruang,'' AS kd_lokasi2,a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,
            a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,a.foto2,a.foto3,'' AS foto4,'' AS foto5,a.no_urut,a.metode,
            a.masa_manfaat,a.nilai_sisa,a.kd_riwayat,a.tgl_riwayat,a.detail_riwayat,'' AS status_tanah,
            '' AS no_sertifikat,'' AS tgl_sertifikat,'' AS luas,'' AS penggunaan,'' AS alamat1,'' AS alamat2,'' AS alamat3,lat,lon,'' AS luas_gedung,
            '' AS jenis_gedung,'' AS luas_tanah,'' AS konstruksi,'' AS konstruksi2,'' AS luas_lantai,'' AS kd_tanah,'' AS hibah,'' AS panjang,
            '' AS lebar,'' AS perolehan,a.judul,a.spesifikasi,a.cipta,a.tahun_terbit,a.penerbit,a.jenis,'' AS bangunan,'' AS tgl_awal_kerja,
            '' AS nilai_kontrak,c.nm_skpd,''as pemeliharaan_ke FROM trkib_e a 
            INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
            AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
            order by a.id_barang limit $offset,$rows";
 
        }
        
        if($lckib == '06'){        
        //$sqlx = "SELECT count(*) as total from v_mts_f a WHERE a.kd_skpd ='$kdskpd' $where" ;
        $sqlx = "SELECT count(*) as total FROM trkib_f a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='') 
        AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result(); 
            
            //$sql="SELECT * FROM v_mts_f a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_f a 
        LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */                     /*$sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,
'' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,
'' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,
a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,
a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
foto2,'' AS foto3,'' AS foto4,'' AS foto5,no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,
a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,luas,
'' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,
'' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,a.bangunan,a.tgl_awal_kerja,
a.nilai_kontrak,

        b.nm_brg,c.nm_skpd FROM trkib_f a 
        INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
        INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
        WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
        order by a.kd_brg limit $offset,$rows";*/
        $sql="SELECT a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,'' AS kd_golongan,'' AS kd_bidang,a.kd_brg,a.nm_brg,a.detail_brg,
                a.nilai,a.asal,a.dsr_peroleh,a.jumlah,a.total,'' AS merek,'' AS tipe,'' AS pabrik,'' AS kd_warna,'' AS kd_bahan,
                '' AS kd_satuan,'' AS no_rangka,'' AS no_mesin,'' AS no_polisi,'' AS silinder,'' AS no_stnk,'' AS tgl_stnk,'' AS no_bpkb,
                '' AS tgl_bpkb,kondisi,'' AS tahun_produksi,'' AS dasar,'' AS no_sk,'' AS tgl_sk,a.keterangan,a.no_mutasi,
                a.tgl_mutasi,a.no_pindah,a.tgl_pindah,a.no_hapus,a.tgl_hapus,'' AS kd_ruang,'' AS kd_lokasi2,
                a.kd_skpd,a.kd_unit,'' AS kd_skpd_lama,a.milik,a.wilayah,a.username,a.tgl_update,a.tahun,a.foto,
                foto2,'' AS foto3,'' AS foto4,'' AS foto5,no_urut,'' AS metode,'' AS masa_manfaat,'' AS nilai_sisa,a.kd_riwayat,
                a.tgl_riwayat,a.detail_riwayat,a.status_tanah,'' AS no_sertifikat,'' AS tgl_sertifikat,luas,
                '' AS penggunaan,a.alamat1,a.alamat2,a.alamat3,a.lat,a.lon,'' AS luas_gedung,'' AS jenis_gedung,'' AS luas_tanah,
                konstruksi,'' AS konstruksi2,'' AS luas_lantai,kd_tanah,'' AS hibah,'' AS panjang,'' AS lebar,'' AS perolehan,
                '' AS judul,'' AS spesifikasi,'' AS cipta,'' AS tahun_terbit,'' AS penerbit,'' AS jenis,a.bangunan,a.tgl_awal_kerja,
                a.nilai_kontrak,c.nm_skpd,''as pemeliharaan_ke FROM trkib_f a 
                INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd WHERE a.kd_skpd ='$kdskpd' AND a.kd_unit='$unit' $where AND (a.no_hapus IS NULL OR a.no_hapus='') AND (a.no_mutasi is null or a.no_mutasi='')
                AND CONCAT(a.no_reg,'.',a.kd_skpd,'.',a.kd_brg,'.',a.no_dokumen) NOT IN (SELECT CONCAT(no_reg,'.',kd_skpd,'.',kd_brg,'.',no_dokumen) FROM trd_penghapusan WHERE kd_skpd=a.kd_skpd)
                order by a.id_barang limit $offset,$rows";

        }
        
        $query1 = $this->db->query($sql);  
        //$row = array();
        $ii = 1;
        $totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
            //$totalx      = $resulte['nilai']+$totalx;  
            $row[] = array(  
                        'no'                => $ii, 
                        'no_reg'            => $resulte['no_reg'],
                        'id_barang'         => $resulte['id_barang'],
                        'nomor'             => $resulte['no'],
                        'no_oleh'           => $resulte['no_oleh'],
                        'tgl_reg'           => $resulte['tgl_reg'],
                        'tgl_oleh'          => $resulte['tgl_oleh'],
                        'no_dokumen'        => $resulte['no_dokumen'],
                        'kd_brg'            => $resulte['kd_brg'],
                        'nm_brg'            => $resulte['nm_brg'],
                        'detail_brg'        => $resulte['detail_brg'],
                        'nilai'             => $resulte['nilai'],
                        'asal'              => $resulte['asal'],
                        'dsr_peroleh'       => $resulte['dsr_peroleh'],
                        'jumlah'            => $resulte['jumlah'],
                        'total'             => $resulte['total'],
                        'merek'             => $resulte['merek'],
                        'tipe'              => $resulte['tipe'],
                        'pabrik'            => $resulte['pabrik'],
                        'kd_warna'          => $resulte['kd_warna'],
                        'kd_bahan'          => $resulte['kd_bahan'],
                        'kd_satuan'         => $resulte['kd_satuan'],
                        'no_rangka'         => $resulte['no_rangka'],
                        'no_mesin'          => $resulte['no_mesin'],
                        'no_polisi'         => $resulte['no_polisi'],
                        'silinder'          => $resulte['silinder'],
                        'no_stnk'           => $resulte['no_stnk'],
                        'tgl_stnk'          => $resulte['tgl_stnk'],
                        'no_bpkb'           => $resulte['no_bpkb'],
                        'tgl_bpkb'          => $resulte['tgl_bpkb'],
                        'kondisi'           => $resulte['kondisi'],
                        'tahun_produksi'    => $resulte['tahun_produksi'],
                        'dasar'             => $resulte['dasar'],
                        'no_sk'             => $resulte['no_sk'],
                        'tgl_sk'            => $resulte['tgl_sk'],
                        'keterangan'        => $resulte['keterangan'],
                        'no_mutasi'         => $resulte['no_mutasi'],
                        'tgl_mutasi'        => $resulte['tgl_mutasi'],
                        'no_pindah'         => $resulte['no_pindah'],
                        'tgl_pindah'        => $resulte['tgl_pindah'],
                        'no_hapus'          => $resulte['no_hapus'],
                        'tgl_hapus'         => $resulte['tgl_hapus'],
                        'kd_ruang'          => $resulte['kd_ruang'],
                        'kd_lokasi2'        => $resulte['kd_lokasi2'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'kd_unit'           => $resulte['kd_unit'],
                        'kd_skpd_lama'      => $resulte['kd_skpd_lama'],
                        'milik'             => $resulte['milik'],
                        'wilayah'           => $resulte['wilayah'],
                        'username'          => $resulte['username'],
                        'tgl_update'        => $resulte['tgl_update'],
                        'tahun'             => $resulte['tahun'],
                        'foto'              => $resulte['foto'],
                        'foto2'             => $resulte['foto2'],
                        'foto3'             => $resulte['foto3'],
                        'foto4'             => $resulte['foto4'],
                        'foto5'             => $resulte['foto5'],
                        'no_urut'           => $resulte['no_urut'],
                        'metode'            => $resulte['metode'],
                        'masa_manfaat'      => $resulte['masa_manfaat'],
                        'nilai_sisa'        => $resulte['nilai_sisa'],
                        'kd_riwayat'        => $resulte['kd_riwayat'],
                        'tgl_riwayat'       => $resulte['tgl_riwayat'],
                        'detail_riwayat'    => $resulte['detail_riwayat'],
                        'status_tanah'      => $resulte['status_tanah'],
                        'no_sertifikat'     => $resulte['no_sertifikat'],
                        'tgl_sertifikat'    => $resulte['tgl_sertifikat'],
                        'luas'              => $resulte['luas'],
                        'penggunaan'        => $resulte['penggunaan'],
                        'alamat1'           => $resulte['alamat1'],
                        'alamat2'           => $resulte['alamat2'],
                        'alamat3'           => $resulte['alamat3'],
                        'lat'               => $resulte['lat'],
                        'lon'               => $resulte['lon'],
                        'luas_gedung'       => $resulte['luas_gedung'],
                        'jenis_gedung'      => $resulte['jenis_gedung'],
                        'luas_tanah'        => $resulte['luas_tanah'],
                        'konstruksi'        => $resulte['konstruksi'],
                        'konstruksi2'       => $resulte['konstruksi2'],
                        'luas_lantai'       => $resulte['luas_lantai'],
                        'kd_tanah'          => $resulte['kd_tanah'],
                        'hibah'             => $resulte['hibah'],
                        'panjang'           => $resulte['panjang'],
                        'lebar'             => $resulte['lebar'],
                        'perolehan'         => $resulte['perolehan'],
                        'judul'             => $resulte['judul'],
                        'spesifikasi'       => $resulte['spesifikasi'],
                        'cipta'             => $resulte['cipta'],
                        'tahun_terbit'      => $resulte['tahun_terbit'],
                        'penerbit'          => $resulte['penerbit'],
                        'jenis'             => $resulte['jenis'],
                        'bangunan'          => $resulte['bangunan'],
                        'tgl_awal_kerja'    => $resulte['tgl_awal_kerja'],
                        'nilai_kontrak'     => $resulte['nilai_kontrak'],
                        'kd_golongan'       => $resulte['kd_golongan'],
                        'kd_bidang'         => $resulte['kd_bidang'],
                        'pemeliharaan_ke'   => $resulte['pemeliharaan_ke']

                        );
                        $ii++;
        }           
       
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }

    function simpan_hps_skpd(){
        
        $tabel      = $this->input->post('tabel');
        $no_hapus   = $this->input->post('no_hapus');
        $no_aset    = $no_hapus.'/HAPUS/SIMBAKDA';
        $tanggal    = $this->input->post('tanggal');
        $skpdb      = $this->input->post('skpdb');
        $unitb      = $this->input->post('unit_lama');
        $cuskpd     = $this->input->post('cuskpd');
        $ket        = $this->input->post('ket');
        $username   = $this->session->userdata('nmuser');
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        $plonline   = $this->session->userdata('plonline');

        $sqlh="delete from trh_penghapusan where no_hapus='$no_hapus' and kd_skpd='$cuskpd' AND kd_unit='$unitb'"; 
        $asgh = $this->db->query($sqlh);
        if($asgh){
        $sql = "insert into trh_penghapusan(no_hapus,tgl_hapus,kd_unit,kd_skpd,kd_skpd_lama,ket,username,tgl_update) 
                                     values('$no_hapus','$tanggal','$unitb','$cuskpd','','$ket','$username','$update')";
        $asg = $this->db->query($sql);
        }
            if($asg){   
                 $msg = array('pesan'=>'1');
                 echo json_encode($msg);
                 //exit();      

            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                //exit();
            }
        if ($plonline=='1'){


        $dbsimakda=$this->load->database('simakda', TRUE);
        $sqldet = "SELECT sum(debet) as debet,sum(kredit) as kredit from trdju_pkd where no_voucher='$no_aset' ";
        $hasil = $dbsimakda->query($sqldet);  
        foreach ($hasil->result() as $row) {
            $debet  = $row->debet;
            $kredit = $row->kredit;
        }

        $sqltrh = "INSERT into trhju_pkd (no_voucher,tgl_voucher,kd_skpd,nm_skpd,ket,total_d,total_k,tabel) 
                   values ('$no_aset','$tanggal','$cuskpd','','$ket','$debet','$kredit','94')";
        $query1 = $dbsimakda->query($sqltrh);  

        exit();   
        
        }

    }

    function hapus_penghapusan(){
    
            $ctabel    = $this->input->post('tabel');
            $cid       = $this->input->post('cid');
            $cnid      = $this->input->post('cnid');
            $cnid_aset = $cnid.'/HAPUS/SIMBAKDA';
            $skpd      = $this->input->post('skpd');
            $unit      = $this->input->post('unit');
            $plonline  = $this->session->userdata('plonline');
            
            $sql = "delete from trd_penghapusan where no_hapus = '$cnid' and kd_skpd='$skpd' and kd_unit='$unit'";
            $casg  = $this->db->query($sql);
            if ($casg){
            $csql = "delete from trh_penghapusan where no_hapus = '$cnid' and kd_skpd='$skpd' and kd_unit='$unit'";
            $asg  = $this->db->query($csql);
            }
            if ($asg){
                    echo '1'; 
                } else{
                    echo '0';
                }
            if ($plonline =='1')
                $dbsimakda=$this->load->database('simakda', TRUE);
            {
                $sqldel = "DELETE from trhju_pkd where no_voucher='$cnid_aset' ";
                $query1 = $dbsimakda->query($sqldel);  

                $sqldel1 = "DELETE from trdju_pkd where no_voucher='$cnid_aset' ";
                $query1 = $dbsimakda->query($sqldel1);  

            }
            
        }

        function hapus_detail2(){
    
            $ctabel = $this->input->post('tabel');
            $cid    = $this->input->post('cid');
            $cnid   = $this->input->post('cnid');
            $id     = $this->input->post('id');
            $skpd   = $this->input->post('skpd');
            $urut   = $this->input->post('urut');
            $unit   = $this->input->post('unit');
            
            $csql = "delete from trd_penghapusan where no_hapus = '$cnid' 
            and id_barang='$id' and kd_skpd='$skpd' and auto='$urut'";
            $asg  = $this->db->query($csql);
            if ($asg){
                echo '1'; 
            } else{
                echo '0';
            }
    
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
   }