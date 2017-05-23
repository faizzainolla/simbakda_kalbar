<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi extends CI_Controller {
        
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
    function fomulir_pengadaan_barang()
    {
   	    if($this->auth->is_logged_in() == false) {
        	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'TRANSAKSI FOMULIR PENGADAAN BARANG';
        $this->template->set('title', 'TRANSAKSI FOMULIR PENGADAAN BARANG');   
        $this->template->load('index','transaksi/tr_isian_barang',$data) ;    
        }     
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
    
     
    function trh_isianbrg(){
		$skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_uskpd like '%' ";
        }else{
            $where1 = "and a.kd_uskpd ='$skpd'";
        }
        $result 	= array();
        $row 		= array();
      	$page 		= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset 	= ($page-1)*$rows;        
        $kriteria 	= $this->input->post('cari');
        $where 		='';
        if ($kriteria <> ''){                               
            $where="where (upper(a.no_dokumen) like upper('%$kriteria%') or a.tgl_dokumen like '%$kriteria%' or upper(a.kd_comp) like upper('%$kriteria%')) ";            
        }
        		
        $sql = "SELECT count(*) as total from trh_isianbrg a" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select top $rows a.*,b.nm_comp,c.nm_milik 
				 from trh_isianbrg a left join mcompany b on a.kd_comp=b.kd_comp 
                 left join mmilik c on a.kd_milik = c.kd_milik where a.no_dokumen not in ( select top $offset no_dokumen from trh_isianbrg) $where1 $where 
				 order by tgl_dokumen,no_dokumen,a.kd_comp";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
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
                        'nilai_apbd'   => $resulte['nilai_apbd'],                        
                        'total'        => $resulte['total'],
                        'kd_cr_oleh'   => $resulte['kd_cr_oleh']	                        			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();                 	   
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
		$csql = "SELECT SUM(total) AS total from trd_isianbrg 
		where no_dokumen = '$nomor' and kd_uskpd = '$skpd'"; 
        $rs   = $this->db->query($csql)->row() ;
		
        $sql = "SELECT a.no_dokumen,jns,c.nm_golongan,kd_brg,a.kd_unit,a.kd_uskpd,nm_brg,a.s_dana,no_sp2d,tgl_sp2d,nilai_sp2d,a.nilai_kontrak,a.kd_kegiatan,
                kd_rek5,jumlah,harga,ppn,a.total,keterangan,invent FROM trd_isianbrg a INNER JOIN trh_isianbrg b ON 
                a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd and a.kd_unit=b.kd_unit LEFT JOIN mgolongan c ON a.jns=c.golongan $where1 and b.no_dokumen = '$nomor'";                //a.no_dokumen=b.no_dokumen LEFT JOIN mgolongan c ON a.jns=c.golongan WHERE a.no_dokumen = '$nomor' and b.kd_uskpd='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_dokumen'    => $resulte['no_dokumen'],  
                        'jns'           => $resulte['jns'],    
                        'nm_golongan'   => $resulte['nm_golongan'],                                         
                        'kd_brg'        => $resulte['kd_brg'],                                         
                        'kd_unit'       => $resulte['kd_unit'],                                         
                        'kd_uskpd'      => $resulte['kd_uskpd'],
                        'nm_brg'        => $resulte['nm_brg'],
                        's_dana'        => $resulte['s_dana'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'tgl_sp2d'      => $resulte['tgl_sp2d'],
                        'nilai_sp2d'    => $resulte['nilai_sp2d'],
                        'nilai_kontrak' => $resulte['nilai_kontrak'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'ppn'           => $resulte['ppn'],
                        'total'         => $rs->total,//$resulte['total'],
                        'keterangan'    => $resulte['keterangan'],
                        'invent'        => $resulte['invent']                                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function nomor(){
        $tabel 	=$this->input->post('tabel');      
        $kdskpd =$this->input->post('kd_unit');                    
        $sql = "SELECT coalesce((MAX(no_urut)+1),1) as no_urut,coalesce(MAX(no_reg),0) as no_reg FROM $tabel WHERE kd_unit='$kdskpd'";
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
        $sql 	= "SELECT IFNULL((MAX($urut)+1),1) as no_urut,IFNULL((MAX($reg)),0) as no_reg 
				   FROM $tabel WHERE kd_unit='$kdskpd' and kd_brg='$kd_brg'";
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
        $tabel  = $this->input->post('tabel');
        $nomor  = $this->input->post('no'); 
        $kdbrg  = $this->input->post('kdbrg'); 
        $unit	= $this->input->post('kd_unit'); 
        //$kolom  = $this->input->post('rows');         
        $csql   = $this->input->post('sql'); 
        $username   = '';
        $tglupdate  = date('y-m-d');      
        $msg        = array();
        if ($tabel == 'trh_isianbrg') {
            $sql = "delete from trh_isianbrg where no_dokumen='$nomor' and kd_uskpd='$unit'";
            $asg = $this->db->query($sql);
            if ($asg){
                $sql = "insert into trh_isianbrg ".$csql;                        
                $asg = $this->db->query($sql);
                /*  if ($asg){
                        $msg[]=array('pesan'=>'Data Header Berhasil di Simpan');
                    }else{
                        $msg[]=array('pesan'=>'Penyimpanan Data Header Gagal');
                    }
					if (!($asg)){
					   $msg = array('pesan'=>'0');
					   echo json_encode($msg);
					   exit();
					} else {
						$msg = array('pesan'=>'1');
						echo json_encode($msg);
					} */      
                       
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        } else if ($tabel == 'trd_isianbrg') {
            
            // Simpan Detail //                       
                $sql = "delete from trd_isianbrg where no_dokumen='$nomor' 
				and kd_brg='$kdbrg' and kd_uskpd='$unit'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    if($csql!=''){
                        $sql = "insert into trd_isianbrg"; 
                        $asg = $this->db->query($sql.$csql);
                          if($asg){
								  $csql = "SELECT SUM(total) AS total from trd_isianbrg where no_dokumen = '$nomor' and kd_uskpd='$unit' ";
								  $rs 	= $this->db->query($csql)->row() ;  
								  if($rs){       
										$sqlx = "update trh_isianbrg set total ='$rs->total' where no_dokumen='$nomor' and kd_uskpd='$unit'";
										$asgx = $this->db->query($sqlx);   
									}
								   echo number_format($rs->total); 
								}else{
								   echo 'Data Tidak Tersimpan';
								} 
										   /*  if (!($asg)){
											   $msg = array('pesan'=>'0');
												echo json_encode($msg);
												exit();
											}  else {
											   $msg = array('pesan'=>'1');
												echo json_encode($msg);
											} */
                    }
                }                                                                 
			} 
		echo json_encode($msg);  
        
    } 

		/* 	if(count($_POST) > 0) {
					foreach($_POST['kolom'] as $row){
					$insRow = array(  */ 
		//alert("A"+"--"+no+"','"+kdbrg+"','"+kd_unit+"','"+kd_uskpd+"','"+nmbrg+"','"+kdrek5+"',
		//'"+jml+"','"+hrg+"','"+tot+"','"+nosp2d+"','"+tglsp2d+"','"+nilsp2d+"','"+ket+"','"+invt+"',
		//'"+cppn+"','0','"+sdana+"','"+kdgiat+"','"+cjns+"','"+nilkont);
					
                        //'no_dokumen'   	=> $row['no_dokumen']/* ,                     
                        /* 'kode'       	=> $row['kode'],                      
                        'kodegiat'   	 	=> $row['kodegiat'],
                        'no'     	     	=> $row['no'],                         
                        'unit_skpd'    	 	=> $_POST['ckd_skpd'],                   
                        'uraian'    	 	=> $row['uraian'],
                        'satuan'     	 	=> $row['satuan'],
                        'vol'        	 	=> $row['vol'],
                        'harga'      	    => $row['harga'],
                        'harga_hps'       	=> $row['harga_hps'],
                        'harga_tawar'     	=> $row['harga_tawar'],
                        'harga_akhir'     	=> $row['harga_akhir'],
                        'jumlah'     	    => $row['jumlah'],
                        //'total_jml'	    	=> $row['total_jml'],
                        'jml_hps'	    	=> $row['total_hps'],  
                        'jml_tawar'	    	=> $row['total_twr'],  
                        'jml_akhir'     	=> $row['total_akhir']   */
						/*);
						$query = $this->db->insert('trd_isianbrg', $insRow);
							}
						} */
						
     function hapus_isianbrg(){
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $msg = array();
        $sql = "delete from trd_isianbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "delete from trh_isianbrg where no_dokumen='$nomor' and kd_uskpd='$skpd'";
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
  
    
    ///////////Rencana Pengadaan Barang/////////
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
        $data['page_title']= 'TRANSAKSI RENCANA PEMELIHARAAN BARANG';
        $this->template->set('title', 'TRANSAKSI RENCANA PEMELIHARAAN BARANG');   
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
            $where1 = "where LEFT(a.kd_brg,2)='01' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='01' and b.kd_uskpd ='$skpd' ";
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
    
    function ambil_dok_b() {
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='02' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='02' and b.kd_uskpd ='$skpd' ";
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
    
    function ambil_dok_c(){
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='03' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='03' and b.kd_uskpd ='$skpd' ";
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
    
    function ambil_dok_d() {
     
		$skpd = $this->session->userdata('skpd');
		$oto  = $this->session->userdata('otori');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where LEFT(a.kd_brg,2)='04' and b.kd_uskpd like '%' ";
        }else{
            $where1 = "where LEFT(a.kd_brg,2)='04' and b.kd_uskpd ='$skpd' ";
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
		$cari="and (upper(b.nm_brg) like upper('%$lccr%') 
		or upper(a.total) like upper('%$lccr%') 
		or upper(a.keterangan) like upper('%$lccr%')
		or upper(a.alamat1) like upper('%$lccr%'))";
		}
        $sql = "SELECT a.kd_brg,b.nm_brg,a.total as nilai,
		a.alamat1,a.keterangan from trkib_a a 
		left join mbarang b on a.kd_brg=b.kd_brg 
		WHERE a.kd_unit='$skpd' and a.total<>'0' 
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
                        'alamat' => $resulte['alamat1'],    
                        'keterangan' => $resulte['keterangan'],  
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
    
    
  function ambil_kib_a() {
         
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
        }    
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;   
        $kriteria = $this->input->post('cari');
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(d.nm_brg) like upper('%$kriteria%')
					or upper(a.no_sertifikat) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%') 
					or upper(a.penggunaan) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT top $rows a.*,d.nm_brg FROM trkib_a a 
		left JOIN mbarang d on d.kd_brg = a.kd_brg where a.id_barang not in (select top $offset id_barang from trkib_a) $where1 $where2 "; // 
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
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
    
  function ambil_kib_a_k() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $skp  = $this->session->userdata('skpd');
		$kode = substr($skpd,9,10);
 
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
        $where2 ="";
		
        if ($kriteria <> ''){                                  
		$where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(d.nm_brg) like upper('%$kriteria%')
					or upper(a.no_sertifikat) like upper('%$kriteria%') 
					or upper(a.luas) like upper('%$kriteria%') 
					or upper(a.penggunaan) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";             
        }
        $sql = "SELECT count(*) as tot from trkib_a a left join mbarang d on d.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql1 = "SELECT a.*,d.nm_brg FROM trkib_a a 
		left JOIN mbarang d on d.kd_brg = a.kd_brg $where1 $where2 order by a.tahun,a.kd_brg limit $offset,$rows"; // 
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
                        'detail_riwayat'	=> $resulte['detail_riwayat']
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
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
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
	
    function ambil_kib_b() {
        
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as tot from trkib_b a left join mbarang b on b.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT top $rows a.*,b.nm_brg FROM trkib_b a left join
				mbarang b on b.kd_brg = a.kd_brg 
				where a.id_barang not in (select top $offset id_barang from trkib_b) $where1 $where2";
   
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
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'kd_unit' 		=> $resulte['kd_unit'],
                        'metode' 		=> $resulte['metode'],
                        'masa_manfaat' 	=> $resulte['masa_manfaat'],
                        'nilai_sisa' 	=> $resulte['nilai_sisa'],
                        'foto' 			=> $resulte['foto'],
                        'foto2' 		=> $resulte['foto2'],
                        'foto3' 		=> $resulte['foto3'],
                        'foto4' 		=> $resulte['foto4'],
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
        
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
					or upper(a.luas_gedung) like upper('%$kriteria%') 
					or upper(a.luas_tanah) like upper('%$kriteria%')
					or upper(a.luas_lantai) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT top $rows a.*,b.nm_brg FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg where a.id_barang not in (select top $offset id_barang from trkib_c) $where1 $where2";

        
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
    
	function ambil_kib_c_k() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $skp  = $this->session->userdata('skpd');
		$kode = substr($skpd,9,10);
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }elseif($kode=='01'){
            $where1 = "where a.kd_skpd ='$skp'";
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
            $where2="and (upper(a.tahun) like upper('%$kriteria%') 
					or upper(a.kondisi) like upper('%$kriteria%') 
					or upper(a.keterangan) like upper('%$kriteria%') 
					or upper(a.nilai) like upper('%$kriteria%')
					or upper(b.nm_brg) like upper('%$kriteria%') 
					or upper(a.asal) like upper('%$kriteria%') 
					or upper(a.luas_gedung) like upper('%$kriteria%') 
					or upper(a.luas_tanah) like upper('%$kriteria%')
					or upper(a.luas_lantai) like upper('%$kriteria%')
					or upper(a.alamat1) like upper('%$kriteria%')
					) ";            
        }
        
        $sql = "SELECT count(*) as tot from trkib_c a left join mbarang b on b.kd_brg = a.kd_brg $where1 $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_brg FROM trkib_c a left JOIN mbarang b ON b.kd_brg = a.kd_brg $where1 $where2 order by a.kd_brg,a.tahun";

        
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
                        'kd_riwayat' 		=> $resulte['kd_riwayat'],
                        'tgl_riwayat' 		=> $resulte['tgl_riwayat'],
                        'detail_riwayat'	=> $resulte['detail_riwayat']/* ,
                        'sts'				=> $resulte['sts'] */
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
        
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as tot from trkib_d a left join mbarang b on b.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT top $rows a.*,b.nm_brg FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg where a.id_barang not in (select top $offset id_barang from trkib_d) $where1 $where2";

        
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
                        'detail_riwayat'	=> $resulte['detail_riwayat']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    	   
	}
         function ambil_kib_d_k() {
        
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $skp  = $this->session->userdata('skpd');
		$kode = substr($skpd,9,10);
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_unit like '%' ";
        }elseif($kode=='01'){
            $where1 = "where a.kd_skpd ='$skp'";
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
        
        $sql = "SELECT a.*,b.nm_brg FROM trkib_d a left JOIN mbarang b ON 
				a.kd_brg = b.kd_brg $where1 $where2 order by a.kd_brg,a.tahun";

        
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
                        'detail_riwayat'	=> $resulte['detail_riwayat']
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
        
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as tot from trkib_e a left join mbarang b on b.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT top $rows a.*,b.nm_brg from trkib_e a inner join mbarang b on b.kd_brg = a.kd_brg
		where a.id_barang not in (select top $offset id_barang from trkib_e) $where1 $where2";
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
                        'kd_ruang' 		=> $resulte['kd_ruang'],
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
                        'detail_riwayat'	=> $resulte['detail_riwayat'] 
                        
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
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as tot FROM trkib_f a LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT a.*,b.nm_brg FROM trkib_f a 
		LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg  
		where a.id_barang not in (select top $offset id_barang from trkib_f) $where1 $where2";

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
	
	
	function ambil_kib_g() {
		$skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_unit like '%' ";
        }else{
            $where1 = "and a.kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as tot from trkib_g a left join mbarang b on b.kd_brg = a.kd_brg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT top $rows a.*,b.nm_brg from trkib_g a left join mbarang b on b.kd_brg=a.kd_brg where a.id_barang not in (select top $offset id_barang from trkib_g)  $where1 $where2";

        
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
	
	    
	function ambil_rincian_kap() {
        $skpd = $this->session->userdata('skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
        $idkdp = $this->input->post('gol');
        $tabel = $this->input->post('tabel');
        $tabel2 = $this->input->post('tabel2');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "where a.kd_skpd like '%' ";
        }else{
            $where1 = "where a.kd_skpd ='$skpd' and a.id_barang='$idkdp'";
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
        $sql = "SELECT a.no_reg,a.id_barang,a.tgl_reg,a.kd_brg,a.nilai,a.tahun,
				a.kd_unit,a.kd_skpd,b.nm_brg
				from $tabel a 
				INNER JOIN $tabel2 c 
				ON c.kd_skpd=a.kd_skpd 
				AND c.kd_unit=a.kd_unit AND a.id_barang=c.id_barang
				INNER JOIN mbarang b ON b.kd_brg=c.kd_brg
				$where1
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
                        'tgl_reg' 		=> $resulte['tgl_reg'], 
                        'kd_brg' 		=> $resulte['kd_brg'],
                        'nm_brg' 		=> $resulte['nm_brg'],
                        'nilai' 		=> $resulte['nilai'],
                        'tahun' 		=> $resulte['tahun'], 
                        'kd_unit'   	=> $resulte['kd_unit'],
                        'kd_skpd'		=> $resulte['kd_skpd']
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
            $where1 = "and kd_uskpd like '%' ";
        }else{
            $where1 = "and kd_uskpd ='$skpd'";
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
        
        $sql = "SELECT count(*) as total from trh_planbrg " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "select top $rows * from trh_planbrg where no_dokumen not in (select top $offset no_dokumen from trh_planbrg) $where1 $where2"; //
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

		$csql = "SELECT SUM(total) AS total from trd_planbrg 
		where no_dokumen = '$nomor' and kd_uskpd = '$skpd'";
        $rs   = $this->db->query($csql)->row() ; 
		
        $sql = "SELECT b.* FROM trh_planbrg a 
				INNER JOIN trd_planbrg b ON a.no_dokumen=b.no_dokumen 
				AND a.kd_uskpd=b.kd_uskpd AND a.kd_unit=b.kd_unit
				WHERE a.no_dokumen = '$nomor' AND a.kd_uskpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
                        'no_dokumen'    => $resulte['no_dokumen'],                      
                        'kd_brg'        => $resulte['kd_brg'],                     
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'total'         => $rs->total,
                        //'total'         => $resulte['total'] ,                        
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
        $usernm     = '';
        $update     = date('Y-m-d');      
        $msg        = array();
        
        if ($tabel == 'trh_planbrg') {
            $sql = "delete from trh_planbrg where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg  = $this->db->query($sql);
            //$asgx = $this->mdata->conn($sql);
			
            if ($asg){
                $sql = "insert into trh_planbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,total) 
                        values('$nomor','$tgl','$lokasi','$uskpd','$nmuskpd','$tahun','$usernm','$update','$total')";
                $asg = $this->db->query($sql);
				//$asgx = $this->mdata->conn($sql);
					/*********formulir pengadaan barang********//* 
                $sql2 = "INSERT INTO trh_isianbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,tahun,username,tgl_update) 
						 VALUES('$nomor','$tgl','$lokasi','$uskpd','$tahun','$usernm','$update')";
                $asg2 = $this->db->query($sql2); */
					/*****************************************/
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
                    $sql  = "insert into trd_planbrg(no_dokumen,kd_brg,nm_brg,merek,jumlah,harga,total,ket)"; 
    
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
        $csql2 	= $this->input->post('sql2');
        $nodok 	= $this->input->post('nodok');    
        $sql  	= "insert into trd_planbrg(no_dokumen,kd_brg,kd_rek5,kd_unit,kd_uskpd,nm_brg,merek,jumlah,harga,total,ket,satuan)"; 
        $asg  	= $this->db->query($sql.$csql);
        //$asgx  	= $this->mdata->conn($sql.$csql);
				/**************TRD ISIAN BARANG**************/    
				/* $sqlx  	= "INSERT INTO trd_isianbrg(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,jumlah,harga,total,keterangan)"; 
				$asgx  	= $this->db->query($sqlx.$csql2); */
				/*******************************************/
        if($asg){
          $csqlx = "SELECT SUM(total) AS total from trd_planbrg where no_dokumen ='$nodok' and kd_unit='$unit' ";
          $rs 	 = $this->db->query($csqlx)->row() ;  
          if($rs){       
                $sql2 = "update trh_planbrg set total ='$rs->total' where no_dokumen='$nodok' and kd_unit='$unit'";
                $asg2 = $this->db->query($sql2);   
            }
            
           echo number_format($rs->total); 
        }else{
           echo 'Data Tidak Tersimpan';
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
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        $sql1 		= "insert into $tabel $lcinsert values $lcvalue";
        $asg1 		= $this->db->query($sql1);
				/* if($asg1){
					$msg = array('pesan'=>'1');
					echo json_encode($msg);
				}else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				} */
			
		
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_a='$urut',no_reg_a='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }  
        $sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no' and kd_brg = '$kd_brg'";
        $asg2 		= $this->db->query($sql2);           
            echo '1';
    }
    
    function update_trkib_a(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
    }
    
	function simpan_trkib_a_kap(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $usernm     = '';
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
	
    function simpan_trkib_b(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        $sql1 		= "insert into $tabel $lcinsert values $lcvalue";
        $asg1 		= $this->db->query($sql1);
		if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_b='$urut',no_reg_b='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }  
		$sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no'";
		$asg2 		= $this->db->query($sql2);
            echo '1';
        $unit 		= $this->input->post('kode');	
        $ruang  	= $this->input->post('ruang'); 
		$skpd 		= $this->input->post('skpd');    
        
        /* $qx = $this->db->query(" select count(nm_ruang) as nm_ruang from mruang where upper(rtrim(nm_ruang))='$ruang' ");
		//$aa	=0;
		$aab=0;
		foreach($qx->result_array() as $res){ 
			$aa = $res->nm_ruang;
			$aab++;
		} */
		/* if($aa==0){
			$qx = $this->db->query(" select max(kd_ruang)+1 as kode from mruang ");
			foreach($qx->result_array() as $res){ 
				$kode_ruang =$res['kode'];
			}
		
			$qx = $this->db->query(" insert into mruang(kd_ruang,nm_ruang,kd_skpd,kd_unit) values('$kode_ruang','$ruang','$skpd','$unit') ");		
		} */
    
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
        $sql = "SELECT COALESCE(MAX($kolom),0)+1 AS kode FROM $table $where";
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
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
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
	
    function simpan_trkib_c(){
        $tabel  	= $this->input->post('tabel');
        $tabel2  	= $this->input->post('tabel2');
        $no 		= $this->input->post('no');
        $kd_brg 	= $this->input->post('lkd_brg');
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
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
		$sql1 		= "insert into $tabel $lcinsert values $lcvalue";
		$asg1 		= $this->db->query($sql1);
		/* $sql2 		= "update $tabel2 set kd_riwayat='9' where id_barang='$id_barang'"; // and kd_unit='$unit'
		$asg2 		= $this->db->query($sql2); */
		
		/* if($asg1){
			$sqlx 		= "update trkib_f set kd_riwayat='$id_barang',tgl_riwayat='$tgl_kdp' where id_barang='$id_kdp'";
			$asgx 		= $this->db->query($sqlx);
          } */
		  
        if ($asg2){
            $msg[]=array('pesan'=>'Data Berhasil di Simpan');
        }else{
            $msg[]=array('pesan'=>'Penyimpanan Data Gagal');
        }
        echo json_encode($msg);
    }
	
 function update_trkib_c(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
    }
    
    
    function simpan_trkib_d(){
        $tabel  	= $this->input->post('tabel');
        //$no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
        //$usernm     = '';
        //$update     = date('y-m-d H:i:s');      
        //$msg        = array();
		$sql1 		= "insert into $tabel $lcinsert values $lcvalue";
		$asg1 		= $this->db->query($sql1);
		/* if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_d='$urut',no_reg_d='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          } */
		//$sql2 		= "update trd_isianbrg set invent='1' where no_dokumen='$no'";
		//$asg2 		= $this->db->query($sql2);
            echo '1';
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
        $query 		= $this->input->post('st_query');
        $asg 		= $this->db->query($query);
    }
    
    function simpan_trkib_e(){
        $tabel  	= $this->input->post('tabel');
        $no 		= $this->input->post('no');
        $lcinsert 	= $this->input->post('kolom');
        $lcvalue 	= $this->input->post('lcvalues');
        $urut	 	= $this->input->post('urut');
        $reg	 	= $this->input->post('reg');
        $unit	 	= $this->input->post('unit');
	
		$usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

                $sql1 = "insert into $tabel $lcinsert values $lcvalue";
                $asg1 = $this->db->query($sql1);
                
		/* if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_e='$urut',no_reg_e='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          } */
                       
            echo '1';
            
     
    }
    
     function update_trkib_e(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
    }
    
    function simpan_trkib_f(){
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
                
		/* if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_f='$urut',no_reg_f='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
                $sql2 = "update trd_isianbrg set invent='1' where no_dokumen='$no'";
                $asg2 = $this->db->query($sql2); */
                       
            echo '1';
    }
    
	
     function update_trkib_f(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
      
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
                
		/* if($asg1){
			$sqlx 		= "update mlokasi_urut set no_urut_f='$urut',no_reg_f='$reg' where kd_lokasi='$unit'";
			$asgx 		= $this->db->query($sqlx);
          }
                $sql2 = "update trd_isianbrg set invent='1' where no_dokumen='$no'";
                $asg2 = $this->db->query($sql2); */
                       
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql = "delete from trkib_b where id_barang='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql = "delete from trkib_c_kap where no_dokumen ='$nomor_dok'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
                $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
    }
	
    function hapus_trkib_e(){
        $nomor = $this->input->post('no');
        $kd_skpd = $this->input->post('kd_skpd');
        $nomor_dok = $this->input->post('dok');
        $no_urut   = $this->input->post('no_urut');
        $sql = "delete from trkib_e where id_barang ='$nomor' and kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql = "delete from trkib_g where id_barang ='$nomor' and no_urut='$no_urut'";
        $asg = $this->db->query($sql);
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $sql2 = "update trd_isianbrg set invent='2' where no_dokumen='$nomor_dok'";
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
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and kd_unit like '%' ";
        }else{
            $where1 = "and kd_unit ='$skpd'";
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
        
        $sql = "SELECT count(*) as total from trh_treatbrg" ;
		//$sql = "SELECT SUM(total) AS total from trd_treatbrg where no_dokumen = '$nomor' and kd_uskpd = '$skpd'" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select top $rows * from trh_treatbrg where no_dokumen not in (select top $offset no_dokumen from trh_treatbrg) $where1 $where2 order by tgl_dokumen,no_dokumen,kd_uskpd";
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
            $where1 = "and kd_uskpd like '%' ";
        }else{
            $where1 = "and kd_uskpd ='$skpd'";
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
        
        $sql = "SELECT count(*) as total from trh_trpelihara" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = " select top $rows * from trh_trpelihara where no_dokumen not in (select top $offset no_dokumen from trh_trpelihara ) $where1 $where2 ";
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
		
          $csql = "SELECT SUM(total) AS total from trd_treatbrg where no_dokumen = '$nomor'"; //and kd_uskpd = '$skpd'
          $rs   = $this->db->query($csql)->row() ;  
		  
        $sql = "SELECT b.* FROM trh_treatbrg a INNER JOIN trd_treatbrg b ON a.no_dokumen=b.no_dokumen and a.kd_uskpd=b.kd_uskpd and a.kd_unit=b.kd_unit
                WHERE a.no_dokumen = '$nomor'"; //and b.kd_uskpd = '$skpd'
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
        $sql = "SELECT * FROM trd_treatbrg order by no_dokumen"; //where kd_uskpd = '$skpd'
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
                        'no_dokumen'    => $resulte['no_dokumen'],                      
                        'kd_brg'        => $resulte['kd_brg'],                     
                        'kd_rek'        => $resulte['kd_rek'],
                        'nm_brg'        => $resulte['nm_brg'],
                        'merek'         => $resulte['merek'],
                        'jumlah'        => $resulte['jumlah'],
                        'harga'         => $resulte['harga'],
                        'biaya_pelihara'=> $resulte['biaya_pelihara'],
                        'total'         => $resulte['total'],                        
                        'ket'           => $resulte['ket']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
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
        $update     = date('y-m-d');      
        $msg        = array();
        
        if ($tabel == 'trh_treatbrg') {
            $sql = "delete from trh_treatbrg where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg  = $this->db->query($sql);
			//$asgx = $this->mdata->conn($sql);
            if ($asg){
                $sql = "insert into trh_treatbrg(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,total) 
                        values('$nomor','$tgl','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$tgl','$total')";
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
    }
function simpan_peliharabrg(){
        $tabel  	= $this->input->post('tabel');
        $nomor  	= $this->input->post('no');
        $tgl    	= $this->input->post('tgl');
        $uskpd   	= $this->input->post('uskpd');
        $unit   	= $this->input->post('lokasi');
        $nmuskpd 	= $this->input->post('nmuskpd');
        $tahun    	= $this->input->post('tahun');
        $total 		= $this->input->post('total');        
        $csql    	= $this->input->post('sql'); 
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
        
        if ($tabel == 'trh_trpelihara') {
            $sql = "delete from trh_trpelihara where kd_uskpd='$uskpd' and no_dokumen='$nomor'";
            $asg = $this->db->query($sql);
            if ($asg){ 
                $sql = "insert into trh_trpelihara(no_dokumen,tgl_dokumen,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,total) 
                        values('$nomor','$tgl','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$tgl','$total')";
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
	
    function save_treatbrg(){
        
        $csql  = $this->input->post('sql');
        $nodok = $this->input->post('nodok');  
        $lokasi = $this->input->post('lokasi');   
        $sql  = "insert into trd_treatbrg(no_dokumen,id_barang,kd_brg,kd_unit,kd_uskpd,nm_brg,harga,jumlah,kd_rek,biaya_pelihara,uraian_pelihara,total,ket,satuan)"; 
        $asg  = $this->db->query($sql.$csql);
        //$asgx  = $this->mdata->conn($sql.$csql);
        if($asg){
          $csql = "SELECT SUM(biaya_pelihara) AS total from trd_treatbrg where no_dokumen = '$nodok' and kd_unit='$lokasi' ";
          $rs  = $this->db->query($csql)->row() ;  
          //$rsx = $this->mdata->conn($csql)->row() ;  
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
        $sql  = "insert into trd_trpelihara(no_dokumen,kd_brg,kd_unit,kd_uskpd,nm_brg,harga,jumlah,kd_rek,biaya_pelihara,uraian_pelihara,total,ket)"; 
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
        $skpd = $this->input->post('skpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trd_treatbrg where no_dokumen='$nomor' and kd_unit='$skpd'";
        $asg  = $this->db->query($sql);
        //$asgx = $this->mdata->conn($sql);
        if ($asg){
            $sql = "delete from trh_treatbrg where no_dokumen='$nomor' and kd_unit='$skpd'";
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
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $msg = array();
        $sql = "delete from trd_trpelihara where no_dokumen='$nomor' and kd_unit='$skpd'";
        $asg = $this->db->query($sql);
        if ($asg){
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
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }   
	
	function hps_trd_peliharabrg(){
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('kd_unit');
        $kdbrg = $this->input->post('kd');
        $msg = array();
        $sql = "delete from trd_trpelihara where no_dokumen='$nomor' and kd_unit='$skpd' and kd_brg='$kdbrg'";
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
            $where1 = "and kd_uskpd like '%' ";
        }else{
            $where1 = "and kd_uskpd ='$skpd'";
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
        
        $sql = "SELECT count(*) as total from trh_terimabrg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "select top $rows * from trh_terimabrg where no_bap not in (select top $offset no_bap from trh_terimabrg) $where1 $where2 order by tgl_periksa,no_dokumen,kd_uskpd";
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
                                            values('$batrm','$tgltrm','$nodok','$nip1','$nip2','$nofak','$tglfak','$noprks','$tglprks','$lokasi','$uskpd','$nmuskpd','$ket','$thn' ,'$total','$usernm','$tgltrm')";
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
        $skpd = $this->session->userdata('unit_skpd');
        $oto  = $this->session->userdata('otori');
        $thn  = $this->session->userdata('ta_simbakda');
 
        $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and kd_uskpd like '%' ";
        }else{
            $where1 = "and kd_uskpd ='$skpd' and tahun='$thn'";
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
        
        $sql = "SELECT count(*) as total from trh_keluarbrg" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT top $rows * FROM trh_keluarbrg 
		where no_bak not in (select top $offset no_bak from trh_keluarbrg) 
		$where1 $where2 ORDER BY no_bak,tgl_bak";
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
        
        $usernm     = '';
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
      
      if ($tabel == 'trh_keluarbrg') {
          //Simpan heider //
            //$sql = "delete from trh_keluarbrg where kd_unit='$unit' and no_bak='$no'";
			$sql = "select * from trh_keluarbrg where kd_unit='$unit' and no_bak='$no'";
            $asg = $this->db->query($sql);
            if ($asg){
                    $sql = "insert into trh_keluarbrg(no_bak,tgl_bak,no_bap,kd_unit,kd_uskpd,nm_uskpd,tahun,username,tgl_update,tujuan) 
                            values('$no','$tgl','$batrm','$unit','$uskpd','$nmuskpd','$tahun','$usernm','$tgl','$tujuan')";
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
    
	
	function ambil_mutasi()
	
    { 
        $lckib  = $this->input->post('gol');
        $kdskpd = $this->input->post('kdskpd');
        $cari   = $this->input->post('cari');
      	$result = array();
        $row 	= array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows; 
		$where="";
		
		
		if($cari<>''){
		$where="and (a.nm_brg like '%$cari%' or a.tahun like '%$cari%')";
		}
		
        if($lckib == '01'){ 
			$sql="SELECT top $rows * FROM v_mts_a a WHERE a.kd_unit ='$kdskpd' and id_barang not in (select top $offset id_barang from trkib_b) $where and a.no_mutasi is null order by a.kd_brg";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi 
		FROM trkib_a a LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
        
        if($lckib == '02'){      
			$sql="SELECT top $rows * FROM v_mts_b a WHERE a.kd_unit ='$kdskpd' and id_barang not in (select top $offset id_barang from trkib_b) $where and a.no_mutasi is null order by a.kd_brg";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_b a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg ";
 */        }
        
        if($lckib == '03'){ 
			$sql="SELECT top $rows * FROM v_mts_c a WHERE a.kd_unit ='$kdskpd'  and id_barang not in (select top $offset id_barang from trkib_c) $where and a.no_mutasi is null order by a.kd_brg";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_c a
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
                
        if($lckib == '04'){   
			$sql="SELECT top $rows * FROM v_mts_d a WHERE a.kd_unit ='$kdskpd'  and id_barang not in (select top $offset id_barang from trkib_d) $where order by a.kd_brg and a.no_mutasi is null";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_d a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
        
        if($lckib == '05'){  
			$sql="SELECT top $rows * FROM v_mts_e a WHERE a.kd_unit ='$kdskpd'  and id_barang not in (select top $offset id_barang from trkib_e) $where and a.no_mutasi is null order by a.kd_brg";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_e a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
        
        if($lckib == '06'){        
			$sql="SELECT top $rows  * FROM v_mts_f a WHERE a.kd_unit ='$kdskpd'  and id_barang not in (select top $offset id_barang from trkib_f) $where and a.no_mutasi is null order by a.kd_brg";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_f a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
        
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 1;
		$totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
            $totalx      = $resulte['nilai']+$totalx;  
            $row[] = array(  
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
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
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
	
	function simpan_mts_skpd(){
     
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
        $data['page_title']= 'PENGHAPUSAN BARANG';
        $this->template->set('title', 'PENGHAPUSAN BARANG ');   
        $this->template->load('index','transaksi/penghapusan',$data) ;         
     }
	 
	 function penghapusan_tetap(){
        $data['page_title']= 'PENGHAPUSAN BARANG';
        $this->template->set('title', 'PENGHAPUSAN BARANG ');   
        $this->template->load('index','transaksi/penghapusan_tetap',$data) ;         
     }
	 	function ambil_no_uhapus() {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		
        $skpd 		= $this->input->post('kduskpd');
        $sql 	= "select no_hapus,tgl_hapus from trh_penghapusan where kd_skpd='$skpd' order by tgl_hapus";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no' => $resulte['no_hapus'],  
                        'tgl'=> $resulte['tgl_hapus']
                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        }
    	   
	}
	
	function ambil_hapus_detail(){
        $skpd 		= $this->input->post('skpd');
        $no_hapus 	= $this->input->post('nomor');
    
        $sql = "SELECT a.*, SUBSTRING(a.id_barang,1, (len(RTRIM(a.id_barang))-len(RIGHT(RTRIM(a.id_barang),11)))) AS idbrg 
			FROM trd_penghapusan a  
		where a.kd_skpd='$skpd' and a.no_hapus='$no_hapus'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(                                
						'no_reg'        	=> $resulte['no_reg'],
						'id_barang'        	=> $resulte['id_barang'],
						'idbrg'        		=> $resulte['idbrg'],
						'no'        		=> $resulte['no'],
						'no_oleh'        	=> $resulte['no_oleh'],
						'tgl_reg'        	=> $resulte['tgl_reg'],
						'tgl_oleh'        	=> $resulte['tgl_oleh'],
						'no_dokumen'       	=> $resulte['no_dokumen'],
						'nm_brg'        	=> $resulte['nm_brg'],
						'kd_brg'        	=> $resulte['kd_brg'],
						'detail_brg'        => $resulte['detail_brg'],
						'nilai'        		=> $resulte['nilai'],
						'asal'        		=> $resulte['asal'],
						'dsr_peroleh'       => $resulte['dsr_peroleh'],
						'jumlah'        	=> $resulte['jumlah'],
						'total'        		=> $resulte['total'],
						'merek'        		=> $resulte['merek'],
						'tipe'        		=> $resulte['tipe'],
						'pabrik'        	=> $resulte['pabrik'],
						'kd_warna'        	=> $resulte['kd_warna'],
						'kd_bahan'        	=> $resulte['kd_bahan'],
						'kd_satuan'        	=> $resulte['kd_satuan'],
						'no_rangka'        	=> $resulte['no_rangka'],
						'no_mesin'        	=> $resulte['no_mesin'],
						'no_polisi'        	=> $resulte['no_polisi'],
						'silinder'        	=> $resulte['silinder'],
						'no_stnk'        	=> $resulte['no_stnk'],
						'tgl_stnk'        	=> $resulte['tgl_stnk'],
						'no_bpkb'        	=> $resulte['no_bpkb'],
						'tgl_bpkb'        	=> $resulte['tgl_bpkb'],
						'kondisi'        	=> $resulte['kondisi'],
						'tahun_produksi'    => $resulte['tahun_produksi'],
						'dasar'        		=> $resulte['dasar'],
						'no_sk'        		=> $resulte['no_sk'],
						'tgl_sk'        	=> $resulte['tgl_sk'],
						'keterangan'       	=> $resulte['keterangan'],
						'no_mutasi'        	=> $resulte['no_mutasi'],
						'tgl_mutasi'        => $resulte['tgl_mutasi'],
						'no_pindah'        	=> $resulte['no_pindah'],
						'tgl_pindah'        => $resulte['tgl_pindah'],
						'no_hapus'        	=> $resulte['no_hapus'],
						'tgl_hapus'        	=> $resulte['tgl_hapus'],
						'kd_ruang'        	=> $resulte['kd_ruang'],
						'kd_lokasi2'        => $resulte['kd_lokasi2'],
						'kd_skpd'        	=> $resulte['kd_skpd'],
						'kd_unit'        	=> $resulte['kd_unit'],
						'kd_skpd_lama'      => $resulte['kd_skpd_lama'],
						'milik'        		=> $resulte['milik'],
						'wilayah'        	=> $resulte['wilayah'],
						'username'        	=> $resulte['username'],
						'tgl_update'       	=> $resulte['tgl_update'],
						'tahun'        		=> $resulte['tahun'],
						'foto'        		=> $resulte['foto'],
						'foto2'        		=> $resulte['foto2'],
						'foto3'        		=> $resulte['foto3'],
						'foto4'        		=> $resulte['foto4'],
						'foto5'        		=> $resulte['foto5'],
						'no_urut'        	=> $resulte['no_urut'],
						'metode'        	=> $resulte['metode'],
						'masa_manfaat'      => $resulte['masa_manfaat'],
						'nilai_sisa'        => $resulte['nilai_sisa'],
						'kd_riwayat'        => $resulte['kd_riwayat'],
						'tgl_riwayat'       => $resulte['tgl_riwayat'],
						'detail_riwayat'    => $resulte['detail_riwayat'],
						'status_tanah'      => $resulte['status_tanah'],
						'no_sertifikat'     => $resulte['no_sertifikat'],
						'tgl_sertifikat'    => $resulte['tgl_sertifikat'],
						'luas'        		=> $resulte['luas'],
						'penggunaan'        => $resulte['penggunaan'],
						'alamat1'        	=> $resulte['alamat1'],
						'alamat2'        	=> $resulte['alamat2'],
						'alamat3'        	=> $resulte['alamat3'],
						'lat'        		=> $resulte['lat'],
						'lon'        		=> $resulte['lon'],
						'luas_gedung'       => $resulte['luas_gedung'],
						'jenis_gedung'      => $resulte['jenis_gedung'],
						'luas_tanah'        => $resulte['luas_tanah'],
						'konstruksi'        => $resulte['konstruksi'],
						'konstruksi2'       => $resulte['konstruksi2'],
						'luas_lantai'       => $resulte['luas_lantai'],
						'kd_tanah'        	=> $resulte['kd_tanah'],
						'hibah'        		=> $resulte['hibah'],
						'panjang'        	=> $resulte['panjang'],
						'lebar'        		=> $resulte['lebar'],
						'perolehan'        	=> $resulte['perolehan'],
						'judul'        		=> $resulte['judul'],
						'spesifikasi'       => $resulte['spesifikasi'],
						'cipta'        		=> $resulte['cipta'],
						'tahun_terbit'      => $resulte['tahun_terbit'],
						'penerbit'        	=> $resulte['penerbit'],
						'jenis'        		=> $resulte['jenis'],
						'bangunan'        	=> $resulte['bangunan'],
						'tgl_awal_kerja'    => $resulte['tgl_awal_kerja'],
						'nilai_kontrak'     => $resulte['nilai_kontrak'],
						'auto'     			=> $resulte['auto']
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

	     function tetap_hapus_kib(){
        
        $no_mut  	= $this->input->post('nomut');
        $tgl_mut  	= $this->input->post('tgl_mut');
        $rwyt_baru 	= $this->input->post('rwyt_baru');
        $rwyt_lama 	= $this->input->post('rwyt_lama');
        $skpd 	= $this->input->post('skpd');
		
		$no_reg  	= $this->input->post('no_reg');
		$idbrg  	= $this->input->post('idbrg');
		$id_barang  = $this->input->post('id_barang');
		$no  		= $this->input->post('no');
		$no_oleh  	= $this->input->post('no_oleh');
		$tgl_reg  	= $this->input->post('tgl_reg');
		$tgl_oleh  	= $this->input->post('tgl_oleh');
		$no_dokumen = $this->input->post('no_dokumen');
		$kd_brg  	= $this->input->post('kd_brg');
		$nm_brg  	= $this->input->post('nm_brg');
		$detail_brg = $this->input->post('detail_brg');
		$nilai  	= $this->input->post('nilai');
		$asal  		= $this->input->post('asal');
		$dsr_peroleh= $this->input->post('dsr_peroleh');
		$jumlah  	= $this->input->post('jumlah');
		$total  	= $this->input->post('total');
		$merek  	= $this->input->post('merek');
		$tipe  		= $this->input->post('tipe');
		$pabrik  	= $this->input->post('pabrik');
		$kd_warna  	= $this->input->post('kd_warna');
		$kd_bahan  	= $this->input->post('kd_bahan');
		$kd_satuan  = $this->input->post('kd_satuan');
		$no_rangka  = $this->input->post('no_rangka');
		$no_mesin  	= $this->input->post('no_mesin');
		$no_polisi  = $this->input->post('no_polisi');
		$silinder  	= $this->input->post('silinder');
		$no_stnk  	= $this->input->post('no_stnk');
		$tgl_stnk  	= $this->input->post('tgl_stnk');
		$no_bpkb  	= $this->input->post('no_bpkb');
		$tgl_bpkb  	= $this->input->post('tgl_bpkb');
		$kondisi  	= $this->input->post('kondisi');
		$tahun_produksi  = $this->input->post('tahun_produksi');
		$dasar  	= $this->input->post('dasar');
		$no_sk  	= $this->input->post('no_sk');
		$tgl_sk  	= $this->input->post('tgl_sk');
		$keterangan = $this->input->post('keterangan');
		$no_mutasi  = $this->input->post('no_mutasi');
		$tgl_mutasi = $this->input->post('tgl_mutasi');
		$no_pindah  = $this->input->post('no_pindah');
		$tgl_pindah = $this->input->post('tgl_pindah');
		$no_hapus  	= $this->input->post('no_hapus');
		$tgl_hapus  = $this->input->post('tgl_hapus');
		$kd_ruang  	= $this->input->post('kd_ruang');
		$kd_lokasi2 = $this->input->post('kd_lokasi2');
		$kd_skpd  	= $this->input->post('kd_skpd');
		$kd_unit  	= $this->input->post('kd_unit');
		$kd_skpd_lama  = $this->input->post('kd_skpd_lama');
		$milik  	= $this->input->post('milik');
		$wilayah  	= $this->input->post('wilayah');
		$username  	= $this->input->post('username');
		$tgl_update = $this->input->post('tgl_update');
		$tahun  	= $this->input->post('tahun');
		$foto  		= $this->input->post('foto');
		$foto2  	= $this->input->post('foto2');
		$foto3  	= $this->input->post('foto3');
		$foto4  	= $this->input->post('foto4');
		$foto5  	= $this->input->post('foto5');
		$no_urut  	= $this->input->post('no_urut');
		$metode  	= $this->input->post('metode');
		$masa_manfaat  = $this->input->post('masa_manfaat');
		$nilai_sisa = $this->input->post('nilai_sisa');
		$kd_riwayat = $this->input->post('kd_riwayat');
		$tgl_riwayat= $this->input->post('tgl_riwayat');
		$detail_riwayat= $this->input->post('detail_riwayat');
		$status_tanah  = $this->input->post('status_tanah');
		$no_sertifikat = $this->input->post('no_sertifikat');
		$tgl_sertifikat= $this->input->post('tgl_sertifikat');
		$luas  		= $this->input->post('luas');
		$penggunaan = $this->input->post('penggunaan');
		$alamat1  	= $this->input->post('alamat1');
		$alamat2  	= $this->input->post('alamat2');
		$alamat3  	= $this->input->post('alamat3');
		$lat  		= $this->input->post('lat');
		$lon  		= $this->input->post('lon');
		$luas_gedung  = $this->input->post('luas_gedung');
		$jenis_gedung = $this->input->post('jenis_gedung');
		$luas_tanah   = $this->input->post('luas_tanah');
		$konstruksi   = $this->input->post('konstruksi');
		$konstruksi2  = $this->input->post('konstruksi2');
		$luas_lantai  = $this->input->post('luas_lantai');
		$kd_tanah  	= $this->input->post('kd_tanah');
		$hibah  	= $this->input->post('hibah');
		$panjang  	= $this->input->post('panjang');
		$lebar  	= $this->input->post('lebar');
		$perolehan  = $this->input->post('perolehan');
		$judul  	= $this->input->post('judul');
		$spesifikasi  = $this->input->post('spesifikasi');
		$cipta  	= $this->input->post('cipta');
		$tahun_terbit = $this->input->post('tahun_terbit');
		$penerbit  	= $this->input->post('penerbit');
		$jenis  	= $this->input->post('jenis');
		$bangunan  	= $this->input->post('bangunan');
		$tgl_awal_kerja= $this->input->post('tgl_awal_kerja');
		$nilai_kontrak = $this->input->post('nilai_kontrak');

		$cno_reg  = explode('||',$no_reg);
		$cidbrg  = explode('||',$idbrg);
		$cid_barang  = explode('||',$id_barang);
		$cno  = explode('||',$no);
		$cno_oleh  = explode('||',$no_oleh);
		$ctgl_reg  = explode('||',$tgl_reg);
		$ctgl_oleh  = explode('||',$tgl_oleh);
		$cno_dokumen  = explode('||',$no_dokumen);
		$ckd_brg  = explode('||',$kd_brg);
		$nm_brg  = explode('||',$nm_brg);
		$cdetail_brg  = explode('||',$detail_brg);
		$cnilai  = explode('||',$nilai);
		$casal  = explode('||',$asal);
		$cdsr_peroleh  = explode('||',$dsr_peroleh);
		$cjumlah  = explode('||',$jumlah);
		$ctotal  = explode('||',$total);
		$cmerek  = explode('||',$merek);
		$ctipe  = explode('||',$tipe);
		$cpabrik  = explode('||',$pabrik);
		$ckd_warna  = explode('||',$kd_warna);
		$ckd_bahan  = explode('||',$kd_bahan);
		$ckd_satuan  = explode('||',$kd_satuan);
		$cno_rangka  = explode('||',$no_rangka);
		$cno_mesin  = explode('||',$no_mesin);
		$cno_polisi  = explode('||',$no_polisi);
		$csilinder  = explode('||',$silinder);
		$cno_stnk  = explode('||',$no_stnk);
		$ctgl_stnk  = explode('||',$tgl_stnk);
		$cno_bpkb  = explode('||',$no_bpkb);
		$ctgl_bpkb  = explode('||',$tgl_bpkb);
		$ckondisi  = explode('||',$kondisi);
		$ctahun_produksi  = explode('||',$tahun_produksi);
		$cdasar  = explode('||',$dasar);
		$cno_sk  = explode('||',$no_sk);
		$ctgl_sk  = explode('||',$tgl_sk);
		$cketerangan  = explode('||',$keterangan);
		$cno_mutasi  = explode('||',$no_mutasi);
		$ctgl_mutasi  = explode('||',$tgl_mutasi);
		$cno_pindah  = explode('||',$no_pindah);
		$ctgl_pindah  = explode('||',$tgl_pindah);
		$cno_hapus  = explode('||',$no_hapus);
		$ctgl_hapus  = explode('||',$tgl_hapus);
		$ckd_ruang  = explode('||',$kd_ruang);
		$ckd_lokasi2  = explode('||',$kd_lokasi2);
		$ckd_skpd  = explode('||',$kd_skpd);
		$ckd_unit  = explode('||',$kd_unit);
		$ckd_skpd_lama  = explode('||',$kd_skpd_lama);
		$cmilik  = explode('||',$milik);
		$cwilayah  = explode('||',$wilayah);
		$cusername  = explode('||',$username);
		$ctgl_update  = explode('||',$tgl_update);
		$ctahun  = explode('||',$tahun);
		$cfoto  = explode('||',$foto);
		$cfoto2  = explode('||',$foto2);
		$cfoto3  = explode('||',$foto3);
		$cfoto4  = explode('||',$foto4);
		$cfoto5  = explode('||',$foto5);
		$cno_urut  = explode('||',$no_urut);
		$cmetode  = explode('||',$metode);
		$cmasa_manfaat  = explode('||',$masa_manfaat);
		$cnilai_sisa  = explode('||',$nilai_sisa);
		$ckd_riwayat  = explode('||',$kd_riwayat);
		$ctgl_riwayat  = explode('||',$tgl_riwayat);
		$cdetail_riwayat  = explode('||',$detail_riwayat);
		$cstatus_tanah  = explode('||',$status_tanah);
		$cno_sertifikat  = explode('||',$no_sertifikat);
		$ctgl_sertifikat  = explode('||',$tgl_sertifikat);
		$cluas  = explode('||',$luas);
		$cpenggunaan  = explode('||',$penggunaan);
		$calamat1  = explode('||',$alamat1);
		$calamat2  = explode('||',$alamat2);
		$calamat3  = explode('||',$alamat3);
		$clat  = explode('||',$lat);
		$clon  = explode('||',$lon);
		$cluas_gedung  = explode('||',$luas_gedung);
		$cjenis_gedung  = explode('||',$jenis_gedung);
		$cluas_tanah  = explode('||',$luas_tanah);
		$ckonstruksi  = explode('||',$konstruksi);
		$ckonstruksi2  = explode('||',$konstruksi2);
		$cluas_lantai  = explode('||',$luas_lantai);
		$ckd_tanah  = explode('||',$kd_tanah);
		$chibah  = explode('||',$hibah);
		$cpanjang  = explode('||',$panjang);
		$clebar  = explode('||',$lebar);
		$cperolehan  = explode('||',$perolehan);
		$cjudul  = explode('||',$judul);
		$cspesifikasi  = explode('||',$spesifikasi);
		$ccipta  = explode('||',$cipta);
		$ctahun_terbit  = explode('||',$tahun_terbit);
		$cpenerbit  = explode('||',$penerbit);
		$cjenis  = explode('||',$jenis);
		$cbangunan  = explode('||',$bangunan);
		$ctgl_awal_kerja  = explode('||',$tgl_awal_kerja);
		$cnilai_kontrak  = explode('||',$nilai_kontrak);
        
		$pj=count($cno);
        
        /* Insert ke table mutasi_brg A-F  && mutasi di trkib A-F*/
    		for($i=0;$i<$pj;$i++){
                if (trim($cno[$i])!=''){
				
    		    $kdbrg = substr($ckd_brg[$i],0,2);
					if($kdbrg=='01'){
					$this->db->query("UPDATE trkib_a SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/* 					$this->db->query("INSERT INTO trkib_a(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,
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
/* 					$this->db->query("INSERT INTO trkib_b(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,
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
/* 					$this->db->query("INSERT INTO trkib_c(no_reg,id_barang,NO,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,
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
									  '".$chibah[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')");			 */		
									  }
					if($kdbrg=='04'){
					$this->db->query("UPDATE trkib_d SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/* 					$this->db->query("INSERT INTO trkib_d(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat)
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
										'".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$rwyt_baru')");	 */				
									  }
					if($kdbrg=='05'){
					$this->db->query("UPDATE trkib_e SET no_hapus='$no_mut',tgl_hapus='$tgl_mut',detail_riwayat='$rwyt_lama' WHERE id_barang='".$cidbrg[$i]."' AND kd_skpd='$skpd' AND kd_brg='".$ckd_brg[$i]."'");// AND no_urut='".$cno_urut[$i]."'
/* 					$this->db->query("INSERT INTO trkib_e(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_peroleh,no_dokumen,kd_brg,detail_brg,nilai,peroleh,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
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
/* 					$this->db->query("INSERT INTO trkib_f(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
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
									  '$rwyt_baru')");				*/
									  }
					$this->db->query("UPDATE trh_penghapusan SET status='Y' WHERE no_hapus='$no_mut' AND kd_skpd='$skpd'");//  AND kd_unit='".$ckd_unit[$i]."'
				  
					}
			}
		}

		     function tolak_usulan_hapus(){
	 
        $nomor   	= $this->input->post('no_mutasi');
        $skpdb	   	= $this->input->post('skpdb');
        $alasan	   	= $this->input->post('alasan');
		
		$updt1	= "update trh_penghapusan set status='N',ket='$alasan' 
		where kd_skpd='$skpdb' and no_hapus='$nomor'";
		$cupdt1 = $this->db->query($updt1);

          
	}
		function ambil_hapus()
	
    { 
        $lckib  = $this->input->post('gol');
        $kdskpd = $this->input->post('kdskpd');
        $kriteria   = $this->input->post('cari');
      		
		$result = array();
        $row 	= array();
      	$page 	= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
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
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	
		
/* 		$sql="SELECT * FROM v_mts_a a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL 
		order by a.kd_brg limit $offset,$rows"; */
		$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,
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
		WHERE a.kd_skpd ='$kdskpd' and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)
		order by a.kd_brg";

		
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi 
		FROM trkib_a a LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */       

		}
        
        if($lckib == '02'){  
		//$sqlx = "SELECT count(*) as total from v_mts_b a WHERE a.kd_skpd ='$kdskpd' $where" ;
		$sqlx = "SELECT count(*) as total FROM trkib_b a 
		INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
		INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
        $query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	     
		//$sql="SELECT * FROM v_mts_b a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_b a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg ";
 */        
		$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
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
		WHERE a.kd_skpd ='$kdskpd'  and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null) 
		order by a.kd_brg";

		}
        
        if($lckib == '03'){ 
		//$sqlx = "SELECT count(*) as total from v_mts_c a WHERE a.kd_skpd ='$kdskpd' $where" ;
		$sqlx = "SELECT count(*) as total FROM trkib_c a 
		INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
		INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
		$query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	
			//$sql="SELECT * FROM v_mts_c a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_c a
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        
		$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,a.nilai,
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
		WHERE a.kd_skpd ='$kdskpd'  and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null) 
		order by a.kd_brg";

		}
                
        if($lckib == '04'){  
		//$sqlx = "SELECT count(*) as total from v_mts_d a WHERE a.kd_skpd ='$kdskpd' $where" ;
		$sqlx = "SELECT count(*) as total FROM trkib_d a 
		INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
		INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
		$query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	 
		$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,
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
		WHERE a.kd_skpd ='$kdskpd'  and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null) 
		order by a.kd_brg";
/*         $sql =  "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_d a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        }
        
        if($lckib == '05'){  
		//$sqlx = "SELECT count(*) as total from v_mts_e a WHERE a.kd_skpd ='$kdskpd' $where" ;
		$sqlx = "SELECT count(*) as total FROM trkib_e a 
		INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
		INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
		$query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	
			//$sql="SELECT * FROM v_mts_e a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_e a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        				$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_peroleh AS tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
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
		WHERE a.kd_skpd ='$kdskpd'  and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)
		order by a.kd_brg";
 
		}
        
        if($lckib == '06'){        
		//$sqlx = "SELECT count(*) as total from v_mts_f a WHERE a.kd_skpd ='$kdskpd' $where" ;
		$sqlx = "SELECT count(*) as total FROM trkib_f a 
		INNER JOIN mbarang b ON b.kd_brg=a.kd_brg
		INNER JOIN ms_skpd c ON c.kd_skpd=a.kd_skpd
		WHERE a.kd_skpd ='$kdskpd' $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)" ;
		$query1 = $this->db->query($sqlx);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        	
			//$sql="SELECT * FROM v_mts_f a WHERE a.kd_skpd ='$kdskpd' $where AND a.no_mutasi IS NULL order by a.kd_brg limit $offset,$rows";
/*         $sql = "SELECT a.id_barang,a.no_reg AS reg,a.kd_brg,c.nm_brg,a.no_dokumen,a.tgl_reg,a.tahun,a.nilai,a.kondisi FROM trkib_f a 
		LEFT JOIN mbarang c ON a.kd_brg = c.kd_brg  WHERE a.kd_unit ='$kdskpd' and a.no_mutasi is null order by a.kd_brg";
 */        				$sql="SELECT top $rows a.no_reg,a.id_barang,a.no,a.no_oleh,a.tgl_reg,a.tgl_oleh,a.no_dokumen,a.kd_brg,a.detail_brg,
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
		WHERE a.kd_skpd ='$kdskpd'  and a.id_barang not in (select top $offset id_barang from trkib_a) $where AND (a.no_hapus IS NULL OR a.no_hapus='')
		and (a.kondisi='RB' or a.kd_riwayat is not null)
		order by a.kd_brg";

		}
        
        $query1 = $this->db->query($sql);  
        //$row = array();
        $ii = 1;
		$totalx=0;
        foreach($query1->result_array() as $resulte)
        {            
            //$totalx      = $resulte['nilai']+$totalx;  
            $row[] = array(  
                        'no'         => $ii, 
						
						'no_reg'  => $resulte['no_reg'],
						'id_barang'  => $resulte['id_barang'],
						'nomor'  => $resulte['no'],
						'no_oleh'  => $resulte['no_oleh'],
						'tgl_reg'  => $resulte['tgl_reg'],
						'tgl_oleh'  => $resulte['tgl_oleh'],
						'no_dokumen'  => $resulte['no_dokumen'],
						'kd_brg'  => $resulte['kd_brg'],
						'nm_brg'  => $resulte['nm_brg'],
						'detail_brg'  => $resulte['detail_brg'],
						'nilai'  => $resulte['nilai'],
						'asal'  => $resulte['asal'],
						'dsr_peroleh'  => $resulte['dsr_peroleh'],
						'jumlah'  => $resulte['jumlah'],
						'total'  => $resulte['total'],
						'merek'  => $resulte['merek'],
						'tipe'  => $resulte['tipe'],
						'pabrik'  => $resulte['pabrik'],
						'kd_warna'  => $resulte['kd_warna'],
						'kd_bahan'  => $resulte['kd_bahan'],
						'kd_satuan'  => $resulte['kd_satuan'],
						'no_rangka'  => $resulte['no_rangka'],
						'no_mesin'  => $resulte['no_mesin'],
						'no_polisi'  => $resulte['no_polisi'],
						'silinder'  => $resulte['silinder'],
						'no_stnk'  => $resulte['no_stnk'],
						'tgl_stnk'  => $resulte['tgl_stnk'],
						'no_bpkb'  => $resulte['no_bpkb'],
						'tgl_bpkb'  => $resulte['tgl_bpkb'],
						'kondisi'  => $resulte['kondisi'],
						'tahun_produksi'  => $resulte['tahun_produksi'],
						'dasar'  => $resulte['dasar'],
						'no_sk'  => $resulte['no_sk'],
						'tgl_sk'  => $resulte['tgl_sk'],
						'keterangan'  => $resulte['keterangan'],
						'no_mutasi'  => $resulte['no_mutasi'],
						'tgl_mutasi'  => $resulte['tgl_mutasi'],
						'no_pindah'  => $resulte['no_pindah'],
						'tgl_pindah'  => $resulte['tgl_pindah'],
						'no_hapus'  => $resulte['no_hapus'],
						'tgl_hapus'  => $resulte['tgl_hapus'],
						'kd_ruang'  => $resulte['kd_ruang'],
						'kd_lokasi2'  => $resulte['kd_lokasi2'],
						'kd_skpd'  => $resulte['kd_skpd'],
						'kd_unit'  => $resulte['kd_unit'],
						'kd_skpd_lama'  => $resulte['kd_skpd_lama'],
						'milik'  => $resulte['milik'],
						'wilayah'  => $resulte['wilayah'],
						'username'  => $resulte['username'],
						'tgl_update'  => $resulte['tgl_update'],
						'tahun'  => $resulte['tahun'],
						'foto'  => $resulte['foto'],
						'foto2'  => $resulte['foto2'],
						'foto3'  => $resulte['foto3'],
						'foto4'  => $resulte['foto4'],
						'foto5'  => $resulte['foto5'],
						'no_urut'  => $resulte['no_urut'],
						'metode'  => $resulte['metode'],
						'masa_manfaat'  => $resulte['masa_manfaat'],
						'nilai_sisa'  => $resulte['nilai_sisa'],
						'kd_riwayat'  => $resulte['kd_riwayat'],
						'tgl_riwayat'  => $resulte['tgl_riwayat'],
						'detail_riwayat'  => $resulte['detail_riwayat'],
						'status_tanah'  => $resulte['status_tanah'],
						'no_sertifikat'  => $resulte['no_sertifikat'],
						'tgl_sertifikat'  => $resulte['tgl_sertifikat'],
						'luas'  => $resulte['luas'],
						'penggunaan'  => $resulte['penggunaan'],
						'alamat1'  => $resulte['alamat1'],
						'alamat2'  => $resulte['alamat2'],
						'alamat3'  => $resulte['alamat3'],
						'lat'  => $resulte['lat'],
						'lon'  => $resulte['lon'],
						'luas_gedung'  => $resulte['luas_gedung'],
						'jenis_gedung'  => $resulte['jenis_gedung'],
						'luas_tanah'  => $resulte['luas_tanah'],
						'konstruksi'  => $resulte['konstruksi'],
						'konstruksi2'  => $resulte['konstruksi2'],
						'luas_lantai'  => $resulte['luas_lantai'],
						'kd_tanah'  => $resulte['kd_tanah'],
						'hibah'  => $resulte['hibah'],
						'panjang'  => $resulte['panjang'],
						'lebar'  => $resulte['lebar'],
						'perolehan'  => $resulte['perolehan'],
						'judul'  => $resulte['judul'],
						'spesifikasi'  => $resulte['spesifikasi'],
						'cipta'  => $resulte['cipta'],
						'tahun_terbit'  => $resulte['tahun_terbit'],
						'penerbit'  => $resulte['penerbit'],
						'jenis'  => $resulte['jenis'],
						'bangunan'  => $resulte['bangunan'],
						'tgl_awal_kerja'  => $resulte['tgl_awal_kerja'],
						'nilai_kontrak'  => $resulte['nilai_kontrak']

                        );
                        $ii++;
        }           
       
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    } 

		function simpan_hps_skpd(){
		
        $tabel   	= $this->input->post('tabel');
        $no_hapus   = $this->input->post('no_hapus');
        $tanggal   	= $this->input->post('tanggal');
        $skpdb     	= $this->input->post('skpdb');
        $unitb   	= $this->input->post('unit_lama');
        $cuskpd   	= $this->input->post('cuskpd');
        $ket     	= $this->input->post('ket');
        
        $update     = date('y-m-d H:i:s');      
        $msg        = array();
		$sqlh="delete from trh_penghapusan where no_hapus='$no_hapus' and kd_skpd='$cuskpd'"; 
        $asgh = $this->db->query($sqlh);
		if($asgh){
		$sql = "insert into trh_penghapusan(no_hapus,tgl_hapus,kd_unit,kd_skpd,kd_skpd_lama,ket) 
                values('$no_hapus','$tanggal','$unitb','$cuskpd','','$ket')";
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
	 
	 function hapus_kib_tetap(){
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
        $no_mutasi  = $this->input->post('nomut');
        $tgl_mut  	= $this->input->post('tgl_mut');
        $riwayat  	= $this->input->post('riwayat');
        $nmuskpdb  	= $this->input->post('nmuskpdb');
		
		$no_reg  = $this->input->post('no_reg');
		$id_barang  = $this->input->post('id_barang');
		$no  = $this->input->post('no');
		$no_oleh  = $this->input->post('no_oleh');
		$tgl_reg  = $this->input->post('tgl_reg');
		$tgl_oleh  = $this->input->post('tgl_oleh');
		$no_dokumen  = $this->input->post('no_dokumen');
		$kd_brg  = $this->input->post('kd_brg');
		$nm_brg  = $this->input->post('nm_brg');
		$detail_brg  = $this->input->post('detail_brg');
		$nilai  = $this->input->post('nilai');
		$asal  = $this->input->post('asal');
		$dsr_peroleh  = $this->input->post('dsr_peroleh');
		$jumlah  = $this->input->post('jumlah');
		$total  = $this->input->post('total');
		$merek  = $this->input->post('merek');
		$tipe  = $this->input->post('tipe');
		$pabrik  = $this->input->post('pabrik');
		$kd_warna  = $this->input->post('kd_warna');
		$kd_bahan  = $this->input->post('kd_bahan');
		$kd_satuan  = $this->input->post('kd_satuan');
		$no_rangka  = $this->input->post('no_rangka');
		$no_mesin  = $this->input->post('no_mesin');
		$no_polisi  = $this->input->post('no_polisi');
		$silinder  = $this->input->post('silinder');
		$no_stnk  = $this->input->post('no_stnk');
		$tgl_stnk  = $this->input->post('tgl_stnk');
		$no_bpkb  = $this->input->post('no_bpkb');
		$tgl_bpkb  = $this->input->post('tgl_bpkb');
		$kondisi  = $this->input->post('kondisi');
		$tahun_produksi  = $this->input->post('tahun_produksi');
		$dasar  = $this->input->post('dasar');
		$no_sk  = $this->input->post('no_sk');
		$tgl_sk  = $this->input->post('tgl_sk');
		$keterangan  = $this->input->post('keterangan');
		//$no_mutasi  = $this->input->post('no_mutasi');
		$tgl_mutasi  = $this->input->post('tgl_mutasi');
		$no_pindah  = $this->input->post('no_pindah');
		$tgl_pindah  = $this->input->post('tgl_pindah');
		$no_hapus  = $this->input->post('no_hapus');
		$tgl_hapus  = $this->input->post('tgl_hapus');
		$kd_ruang  = $this->input->post('kd_ruang');
		$kd_lokasi2  = $this->input->post('kd_lokasi2');
		$kd_skpd  = $this->input->post('kd_skpd');
		$kd_unit  = $this->input->post('kd_unit');
		$kd_skpd_lama  = $this->input->post('kd_skpd_lama');
		$milik  = $this->input->post('milik');
		$wilayah  = $this->input->post('wilayah');
		$username  = $this->input->post('username');
		$tgl_update  = $this->input->post('tgl_update');
		$tahun  = $this->input->post('tahun');
		$foto  = $this->input->post('foto');
		$foto2  = $this->input->post('foto2');
		$foto3  = $this->input->post('foto3');
		$foto4  = $this->input->post('foto4');
		$foto5  = $this->input->post('foto5');
		$no_urut  = $this->input->post('no_urut');
		$metode  = $this->input->post('metode');
		$masa_manfaat  = $this->input->post('masa_manfaat');
		$nilai_sisa  = $this->input->post('nilai_sisa');
		$kd_riwayat  = $this->input->post('kd_riwayat');
		$tgl_riwayat  = $this->input->post('tgl_riwayat');
		$detail_riwayat  = $this->input->post('detail_riwayat');
		$status_tanah  = $this->input->post('status_tanah');
		$no_sertifikat  = $this->input->post('no_sertifikat');
		$tgl_sertifikat  = $this->input->post('tgl_sertifikat');
		$luas  = $this->input->post('luas');
		$penggunaan  = $this->input->post('penggunaan');
		$alamat1  = $this->input->post('alamat1');
		$alamat2  = $this->input->post('alamat2');
		$alamat3  = $this->input->post('alamat3');
		$lat  = $this->input->post('lat');
		$lon  = $this->input->post('lon');
		$luas_gedung  = $this->input->post('luas_gedung');
		$jenis_gedung  = $this->input->post('jenis_gedung');
		$luas_tanah  = $this->input->post('luas_tanah');
		$konstruksi  = $this->input->post('konstruksi');
		$konstruksi2  = $this->input->post('konstruksi2');
		$luas_lantai  = $this->input->post('luas_lantai');
		$kd_tanah  = $this->input->post('kd_tanah');
		$hibah  = $this->input->post('hibah');
		$panjang  = $this->input->post('panjang');
		$lebar  = $this->input->post('lebar');
		$perolehan  = $this->input->post('perolehan');
		$judul  = $this->input->post('judul');
		$spesifikasi  = $this->input->post('spesifikasi');
		$cipta  = $this->input->post('cipta');
		$tahun_terbit  = $this->input->post('tahun_terbit');
		$penerbit  = $this->input->post('penerbit');
		$jenis  = $this->input->post('jenis');
		$bangunan  = $this->input->post('bangunan');
		$tgl_awal_kerja  = $this->input->post('tgl_awal_kerja');
		$nilai_kontrak  = $this->input->post('nilai_kontrak');

		$cno_reg  = explode('||',$no_reg);
		$cid_barang  = explode('||',$id_barang);
		$cno  = explode('||',$no);
		$cno_oleh  = explode('||',$no_oleh);
		$ctgl_reg  = explode('||',$tgl_reg);
		$ctgl_oleh  = explode('||',$tgl_oleh);
		$cno_dokumen  = explode('||',$no_dokumen);
		$ckd_brg  = explode('||',$kd_brg);
		$nm_brg  = explode('||',$nm_brg);
		$cdetail_brg  = explode('||',$detail_brg);
		$cnilai  = explode('||',$nilai);
		$casal  = explode('||',$asal);
		$cdsr_peroleh  = explode('||',$dsr_peroleh);
		$cjumlah  = explode('||',$jumlah);
		$ctotal  = explode('||',$total);
		$cmerek  = explode('||',$merek);
		$ctipe  = explode('||',$tipe);
		$cpabrik  = explode('||',$pabrik);
		$ckd_warna  = explode('||',$kd_warna);
		$ckd_bahan  = explode('||',$kd_bahan);
		$ckd_satuan  = explode('||',$kd_satuan);
		$cno_rangka  = explode('||',$no_rangka);
		$cno_mesin  = explode('||',$no_mesin);
		$cno_polisi  = explode('||',$no_polisi);
		$csilinder  = explode('||',$silinder);
		$cno_stnk  = explode('||',$no_stnk);
		$ctgl_stnk  = explode('||',$tgl_stnk);
		$cno_bpkb  = explode('||',$no_bpkb);
		$ctgl_bpkb  = explode('||',$tgl_bpkb);
		$ckondisi  = explode('||',$kondisi);
		$ctahun_produksi  = explode('||',$tahun_produksi);
		$cdasar  = explode('||',$dasar);
		$cno_sk  = explode('||',$no_sk);
		$ctgl_sk  = explode('||',$tgl_sk);
		$cketerangan  = explode('||',$keterangan);
		$cno_mutasi  = explode('||',$no_mutasi);
		$ctgl_mutasi  = explode('||',$tgl_mutasi);
		$cno_pindah  = explode('||',$no_pindah);
		$ctgl_pindah  = explode('||',$tgl_pindah);
		$cno_hapus  = explode('||',$no_hapus);
		$ctgl_hapus  = explode('||',$tgl_hapus);
		$ckd_ruang  = explode('||',$kd_ruang);
		$ckd_lokasi2  = explode('||',$kd_lokasi2);
		/* $ckd_skpd  = explode('||',$kd_skpd);
		$ckd_unit  = explode('||',$kd_unit);
		$ckd_skpd_lama  = explode('||',$kd_skpd_lama); */
		$cmilik  = explode('||',$milik);
		$cwilayah  = explode('||',$wilayah);
		$cusername  = explode('||',$username);
		$ctgl_update  = explode('||',$tgl_update);
		$ctahun  = explode('||',$tahun);
		$cfoto  = explode('||',$foto);
		$cfoto2  = explode('||',$foto2);
		$cfoto3  = explode('||',$foto3);
		$cfoto4  = explode('||',$foto4);
		$cfoto5  = explode('||',$foto5);
		$cno_urut  = explode('||',$no_urut);
		$cmetode  = explode('||',$metode);
		$cmasa_manfaat  = explode('||',$masa_manfaat);
		$cnilai_sisa  = explode('||',$nilai_sisa);
		$ckd_riwayat  = explode('||',$kd_riwayat);
		$ctgl_riwayat  = explode('||',$tgl_riwayat);
		$cdetail_riwayat  = explode('||',$detail_riwayat);
		$cstatus_tanah  = explode('||',$status_tanah);
		$cno_sertifikat  = explode('||',$no_sertifikat);
		$ctgl_sertifikat  = explode('||',$tgl_sertifikat);
		$cluas  = explode('||',$luas);
		$cpenggunaan  = explode('||',$penggunaan);
		$calamat1  = explode('||',$alamat1);
		$calamat2  = explode('||',$alamat2);
		$calamat3  = explode('||',$alamat3);
		$clat  = explode('||',$lat);
		$clon  = explode('||',$lon);
		$cluas_gedung  = explode('||',$luas_gedung);
		$cjenis_gedung  = explode('||',$jenis_gedung);
		$cluas_tanah  = explode('||',$luas_tanah);
		$ckonstruksi  = explode('||',$konstruksi);
		$ckonstruksi2  = explode('||',$konstruksi2);
		$cluas_lantai  = explode('||',$luas_lantai);
		$ckd_tanah  = explode('||',$kd_tanah);
		$chibah  = explode('||',$hibah);
		$cpanjang  = explode('||',$panjang);
		$clebar  = explode('||',$lebar);
		$cperolehan  = explode('||',$perolehan);
		$cjudul  = explode('||',$judul);
		$cspesifikasi  = explode('||',$spesifikasi);
		$ccipta  = explode('||',$cipta);
		$ctahun_terbit  = explode('||',$tahun_terbit);
		$cpenerbit  = explode('||',$penerbit);
		$cjenis  = explode('||',$jenis);
		$cbangunan  = explode('||',$bangunan);
		$ctgl_awal_kerja  = explode('||',$tgl_awal_kerja);
		$cnilai_kontrak  = explode('||',$nilai_kontrak);
        
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
					$this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,status_tanah,kondisi,asal,dsr_peroleh,no_sertifikat,tgl_sertifikat,luas,nilai,jumlah,total,penggunaan,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_lokasi2,milik,wilayah,kd_skpd,kd_unit,kd_skpd_lama,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
									  values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
									  '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
									  '".$cdetail_brg[$i]."','".$cstatus_tanah[$i]."','".$ckondisi[$i]."','".$casal[$i]."',
									  '".$cdsr_peroleh[$i]."','".$cno_sertifikat[$i]."','".$ctgl_sertifikat[$i]."',
									  '".$cluas[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$ctotal[$i]."',
									  '".$cpenggunaan[$i]."','".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',
										null,null,null,null,
									  '$no_mutasi','$tgl_mut','".$cketerangan[$i]."','".$ckd_lokasi2[$i]."',
									  '".$cmilik[$i]."','".$cwilayah[$i]."','$kd_skpd','$kd_unit',
									  '$kd_skpd_lama','$nmuskpdb','".$ctgl_update[$i]."','".$ctahun[$i]."',
									  '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."','".$cno_urut[$i]."',
									  '".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
									  '$riwayat')");
					}
					if($kdbrg=='02'){
					//$this->db->query("UPDATE trkib_b SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
					$this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,jumlah,total,merek,tipe,pabrik,kd_warna,kd_bahan,kd_satuan,no_rangka,no_mesin,no_polisi,silinder,no_stnk,tgl_stnk,no_bpkb,tgl_bpkb,kondisi,tahun_produksi,dasar,no_sk,tgl_sk,keterangan,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,foto3,foto4,foto5,no_urut,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat)
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
									  '".$cmilik[$i]."','".$cwilayah[$i]."','$nmuskpdb','".$ctgl_update[$i]."',
									  '".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cfoto4[$i]."',
									  '".$cfoto5[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
									  '".$cnilai_sisa[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat')");
					}
					if($kdbrg=='03'){
					//$this->db->query("UPDATE trkib_c SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
					$this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,jumlah,asal,dsr_peroleh,total,luas_gedung,jenis_gedung,luas_tanah,status_tanah,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,konstruksi,konstruksi2,luas_lantai,kondisi,dasar,tgl_sk,keterangan,kd_lokasi2,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,kd_tanah,username,tgl_update,tahun,foto,foto2,foto3,foto4,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,hibah,kd_riwayat,tgl_riwayat,detail_riwayat)
									  values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
									  '".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
									  '".$cdetail_brg[$i]."','".$cnilai[$i]."','".$cjumlah[$i]."','".$casal[$i]."',
									  '".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
									  '".$cluas_gedung[$i]."','".$cjenis_gedung[$i]."','".$cluas_tanah[$i]."','".$cstatus_tanah[$i]."',
									  '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut','".$ckonstruksi[$i]."',
									  '".$ckonstruksi2[$i]."','".$cluas_lantai[$i]."',
									  '".$ckondisi[$i]."','".$cdasar[$i]."','".$ctgl_sk[$i]."','".$cketerangan[$i]."',
									  '".$ckd_lokasi2[$i]."','$kd_skpd','$kd_unit','',
									  '".$cmilik[$i]."','".$cwilayah[$i]."','".$ckd_tanah[$i]."','$nmuskpdb',
									  '".$ctgl_update[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."',
									  '".$cfoto4[$i]."','".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$cmetode[$i]."',
									  '".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."','".$chibah[$i]."','".$ckd_riwayat[$i]."',
									  '".$ctgl_riwayat[$i]."','$riwayat')");					
									  }
					if($kdbrg=='04'){
					//$this->db->query("UPDATE trkib_d SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
					$this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,total,kondisi,status_tanah,panjang,luas,lebar,konstruksi,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,perolehan,dasar,jumlah,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,penggunaan,username,tgl_update,tahun,foto,foto2,foto3,no_urut,lat,lon,metode,masa_manfaat,nilai_sisa,kd_riwayat,tgl_riwayat,detail_riwayat)
									  values ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
									  '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
									  '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$ctotal[$i]."',
									  '".$ckondisi[$i]."','".$cstatus_tanah[$i]."','".$cpanjang[$i]."',
									  '".$cluas[$i]."','".$clebar[$i]."','".$ckonstruksi[$i]."','".$calamat1[$i]."','".$calamat2[$i]."',
									  '".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut','".$cperolehan[$i]."',
									  '".$cdasar[$i]."','".$cjumlah[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','',
									  '".$cmilik[$i]."','".$cwilayah[$i]."',
									  '".$cpenggunaan[$i]."','$nmuskpdb','".$ctgl_update[$i]."','".$ctahun[$i]."',
									  '".$cfoto[$i]."','".$cfoto2[$i]."','".$cfoto3[$i]."','".$cno_urut[$i]."','".$clat[$i]."',
									  '".$clon[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."','".$cnilai_sisa[$i]."',
									  '".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."','$riwayat')");					
									  }
					if($kdbrg=='05'){
					//$this->db->query("UPDATE trkib_e SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
					$this->db->query("INSERT INTO trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,nilai,asal,dsr_peroleh,total,judul,spesifikasi,cipta,tahun_terbit,penerbit,kd_bahan,jenis,tipe,kd_satuan,jumlah,kondisi,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,kd_ruang,tahun,foto,foto2,foto3,no_urut,metode,masa_manfaat,nilai_sisa,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
									VALUES ('".$cno_reg[$i]."','$id_baru','".$cno[$i]."','".$cno_oleh[$i]."',
									'".$ctgl_reg[$i]."','".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."',
									'".$cdetail_brg[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."',
									'".$ctotal[$i]."','".$cjudul[$i]."','".$cspesifikasi[$i]."',
									'".$ccipta[$i]."','".$ctahun_terbit[$i]."','".$cpenerbit[$i]."','".$ckd_bahan[$i]."',
									'".$cjenis[$i]."','".$ctipe[$i]."','".$ckd_satuan[$i]."','".$cjumlah[$i]."',
									'".$ckondisi[$i]."','".$cketerangan[$i]."','$kd_skpd','$kd_unit','',
									'".$cmilik[$i]."','".$cwilayah[$i]."','$nmuskpdb','".$ctgl_update[$i]."',
									null,null,null,null,'$no_mutasi','$tgl_mut',
									'".$ckd_ruang[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
									'".$cfoto3[$i]."','".$cno_urut[$i]."','".$cmetode[$i]."','".$cmasa_manfaat[$i]."',
									'".$cnilai_sisa[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."',
									'".$ctgl_riwayat[$i]."','$riwayat')");					
									  }
					if($kdbrg=='06'){
					//$this->db->query("UPDATE trkib_f SET no_mutasi='$no_mutasi' WHERE id_barang='".$cid_barang[$i]."' AND kd_skpd='$kd_skpd_lama' AND kd_brg='".$ckd_brg[$i]."' AND no_urut='".$cno_urut[$i]."'");
					$this->db->query("insert into trd_penghapusan(no_reg,id_barang,no,no_oleh,tgl_reg,tgl_oleh,no_dokumen,kd_brg,nm_brg,detail_brg,kd_tanah,nilai,asal,dsr_peroleh,total,kondisi,konstruksi,jenis,bangunan,luas,jumlah,tgl_awal_kerja,status_tanah,nilai_kontrak,alamat1,alamat2,alamat3,no_mutasi,tgl_mutasi,no_pindah,tgl_pindah,no_hapus,tgl_hapus,keterangan,kd_skpd,kd_unit,kd_skpd_lama,milik,wilayah,username,tgl_update,tahun,foto,foto2,no_urut,lat,lon,kd_riwayat,tgl_riwayat,detail_riwayat)
									  values ('".$cno_reg[$i]."','".$cid_barang[$i]."','".$cno[$i]."','".$cno_oleh[$i]."','".$ctgl_reg[$i]."',
									  '".$ctgl_oleh[$i]."','".$cno_dokumen[$i]."','".$ckd_brg[$i]."','".$nm_brg[$i]."','".$cdetail_brg[$i]."',
									  '".$ckd_tanah[$i]."','".$cnilai[$i]."','".$casal[$i]."','".$cdsr_peroleh[$i]."','".$ctotal[$i]."',
									  '".$ckondisi[$i]."','".$ckonstruksi[$i]."','".$cjenis[$i]."','".$cbangunan[$i]."','".$cluas[$i]."',
									  '".$cjumlah[$i]."','".$ctgl_awal_kerja[$i]."','".$cstatus_tanah[$i]."','".$cnilai_kontrak[$i]."',
									  '".$calamat1[$i]."','".$calamat2[$i]."','".$calamat3[$i]."',null,null,null,null,'$no_mutasi','$tgl_mut',
									  '".$cketerangan[$i]."','$kd_skpd','$kd_unit','','".$cmilik[$i]."','".$cwilayah[$i]."',
									  '$nmuskpdb','".$ctgl_update[$i]."','".$ctahun[$i]."','".$cfoto[$i]."','".$cfoto2[$i]."',
									  '".$cno_urut[$i]."','".$clat[$i]."','".$clon[$i]."','".$ckd_riwayat[$i]."','".$ctgl_riwayat[$i]."',
									  '$riwayat')");				
									  }
					}
				//}
			}
		}

       		function hapus_penghapusan(){
	
			$ctabel = $this->input->post('tabel');
			$cid 	= $this->input->post('cid');
			$cnid 	= $this->input->post('cnid');
			$skpd 	= $this->input->post('skpd');
			
			$sql = "delete from trd_penghapusan where no_hapus = '$cnid' and kd_skpd='$skpd'";
			$casg  = $this->db->query($sql);
			if ($casg){
			$csql = "delete from trh_penghapusan where no_hapus = '$cnid' and kd_skpd='$skpd'";
			$asg  = $this->db->query($csql);
			}
			if ($asg){
					echo '1'; 
				} else{
					echo '0';
				}
			
		}
	   
	   		function hapus_detail2(){
	
			$ctabel = $this->input->post('tabel');
			$cid 	= $this->input->post('cid');
			$cnid 	= $this->input->post('cnid');
			$id 	= $this->input->post('id');
			$skpd 	= $this->input->post('skpd');
			$urut 	= $this->input->post('urut');
			
			$csql = "delete from trd_penghapusan where no_hapus = '$cnid' 
			and id_barang='$id' and kd_skpd='$skpd' and auto='$urut'";
			$asg  = $this->db->query($csql);
			if ($asg){
				echo '1'; 
			} else{
				echo '0';
			}
	
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
        $row 	= array();
      	$page 	= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
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
                            'no_hapus'    	=> $resulte['no_hapus'],
							'tgl_hapus'   	=> $resulte['tgl_hapus'],
							'kd_unit'      => $resulte['kd_unit'],
							'kd_skpd'      => $resulte['kd_skpd'],
							'kd_skpd_baru' => $resulte['kd_skpd_lama'],
							'lama' 		   => $resulte['lama'],
							'baru' 		   => $resulte['baru'],
							'jumlah'       => $resulte['jumlah'],
							'total'    	   => $resulte['total'],
							'ket'    	   => $resulte['ket'],
							'no_urut'      => $resulte['no_urut'],
							'sts'    	   => $resulte['sts']
           			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
		
	}

	   
    function ambil_listhapus(){
        $oto    = $this->session->userdata('otori');
        $skpd   = $this->session->userdata('skpd');
		
		  $where1 = '';       
        if($oto == '01'){ 
            $where1 = "and a.kd_skpd like '%' and a.status IS NULL";
        }else{
            $where1 = "and a.kd_skpd ='$skpd' AND (a.status IS NULL OR a.status='N' OR a.status='Y')";
        }
        $result = array();
        $row 	= array();
      	$page 	= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows 	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where2 ='';
        if ($kriteria <> ''){                               
            $where2 ="and (upper(a.no_hapus) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trh_penghapusan a" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result(); 
        
        $sql = "SELECT top $rows a.*,b.nm_skpd 
				,(CASE WHEN a.status='N' THEN 'DITOLAK' WHEN a.status='Y' THEN 'DISETUJUI' WHEN a.status IS NULL THEN 'MENUNGGU' END) AS sts
				FROM 
				trh_penghapusan a 
				INNER JOIN ms_skpd b ON b.kd_skpd=a.kd_skpd where no_hapus not in (select top $offset no_hapus from trh_penghapusan)
				$where1 $where2
				ORDER BY a.no_hapus";
        $query1 = $this->db->query($sql);          
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $row[] = array(     							
                            'no_hapus'     => $resulte['no_hapus'],
							'tgl_hapus'    => $resulte['tgl_hapus'],
							'kd_unit'      => $resulte['kd_unit'],
							'kd_skpd'      => $resulte['kd_skpd'],
							'kd_skpd_baru' => $resulte['kd_skpd_lama'],
							'nm_skpd' 	   => $resulte['nm_skpd'],
							'jumlah'       => $resulte['jumlah'],
							'total'    	   => $resulte['total'],
							'ket'    	   => $resulte['ket'],
							'no_urut'      => $resulte['no_urut'],
							'sts'    	   => $resulte['sts']
           			
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
		
    }

     
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
	 
    }

