<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_kebijakan extends CI_Controller {
        
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

	function penyusutan_kibb()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN KIB B';
        $this->template->set('title', 'DAFTAR PENYUSUTAN KIB B');   
        $this->template->load('index','kebijakan/kibb',$data) ;
        } 
    }	
	
	     function  tanggal_indonesia($tgl)
    {
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
        
    }
	
	     function ambil_config(){

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
                         'logo' => $resulte['logo'],
                         'logo2' => $resulte['logo2']                                                                         					
                         );
        }
	   
       $query1->free_result(); 
	   return $resulte;        	
	}	
	
	
	
    function  getBulan($bln){
        switch  ($bln){
            case  1:
                return  "Januari";
                break;
            case  2:
                return  "Februari";
                break;
            case  3:
                return  "Maret";
                break;
            case  4:
                return  "Maret";
                break;
            case  5:
                return  "Mei";
                break;
            case  6:
                return  "Juni";
                break;
            case  7:
                return  "Juli";
                break;
            case  8:
                return  "Agustus";
                break;
            case  9:
                return  "September";
                break;
            case  10:
                return  "Oktober";
                break;
            case  11:
                return  "November";
                break;
            case  12:
                return  "Desember";
                break;
        }
    }
	
	function kibb(){
	$this->mlap->kibb();
	}

	function kibb_bulanan(){
	$this->mlap->kibb_bulanan();
	}
	
	function penyusutan_kibc()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN KIB C';
        $this->template->set('title', 'DAFTAR PENYUSUTAN KIB C');   
        $this->template->load('index','kebijakan/kibc',$data) ;
        } 
    }	
	
	function kibc(){
	$this->mlap->kibc();
	}

	function kibc_bulanan(){
	$this->mlap->kibc_bulanan();
	}
	function penyusutan_kibd()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN KIB D';
        $this->template->set('title', 'DAFTAR PENYUSUTAN KIB D');   
        $this->template->load('index','kebijakan/kibd',$data) ;
        } 
    }	
	
	function kibd(){
	$this->mlap->kibd();
	}	
	
	function kibd_bulanan(){
	$this->mlap->kibd_bulanan();
	}
	
	function penyusutan_kibe()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN KIB E';
        $this->template->set('title', 'DAFTAR PENYUSUTAN KIB E');   
        $this->template->load('index','kebijakan/kibe',$data) ;
        } 
    }	
	
	function kibe(){
	$this->mlap->kibe();
	}
	
	function kibe_bulanan(){
	$this->mlap->kibe_bulanan();
	}	
	
	function penyusutan_kibg()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN KIB G';
        $this->template->set('title', 'DAFTAR PENYUSUTAN KIB G');   
        $this->template->load('index','kebijakan/kibg',$data) ;
        } 
    }
	
	function kibb_lainnya(){
		$this->mlap->kibb_lainnya();
	}
	
	function kibc_lainnya(){
		$this->mlap->kibc_lainnya();
	}
	
	function kibd_lainnya(){
		$this->mlap->kibd_lainnya();
	}
	
	function kibe_lainnya(){
		$this->mlap->kibe_lainnya();
	}
		function penyusutan_kib_lain()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK DAFTAR PENYUSUTAN ASET LAINNYA';
        $this->template->set('title', 'DAFTAR PENYUSUTAN ASET LAINNYA');   
        $this->template->load('index','kebijakan/kib_lainnya',$data) ;
        } 
    }
	
	function kibg_bulanan(){
	$this->mlap->kibg_bulanan();
	}
	function penyusutan_rekap()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK REKAP DAFTAR PENYUSUTAN';
        $this->template->set('title', 'DAFTAR REKAP PENYUSUTAN');   
        $this->template->load('index','kebijakan/kib_rekap',$data) ;
        } 
    }		
	
	function penyusutan_rekap_all()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK REKAP DAFTAR PENYUSUTAN';
        $this->template->set('title', 'DAFTAR REKAP PENYUSUTAN');   
        $this->template->load('index','kebijakan/kib_rekap_all',$data) ;
        } 
    }	
	
	function penyusutan_akumulasi()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK REKAP DAFTAR AKUMULASI PENYUSUTAN';
        $this->template->set('title', 'DAFTAR REKAP AKUMULASI PENYUSUTAN');   
        $this->template->load('index','kebijakan/kib_akumulasi',$data) ;
        } 
    }	
	
		function penyusutan_rekap_skpd()
    {
        if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
        $data['page_title']= 'CETAK REKAP DAFTAR PENYUSUTAN';
        $this->template->set('title', 'DAFTAR REKAP PENYUSUTAN');   
        $this->template->load('index','kebijakan/kib_rekap_skpd',$data) ;
        } 
    }
	
	public function rekap_kib_penyusutan()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  = $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $kdbid 		= $_REQUEST['kd_skpd'];
        $cnm_skpd 	= $_REQUEST['nm_skpd'];
        $lctahu 	= $_REQUEST['tahu'];
        $lctgl 		= $_REQUEST['tgl'];
        $lctgl_akhir = $_REQUEST['tgl_akhir'];
        $tahun_ini	= $_REQUEST['tahun_ini'];
        $kib 		= $_REQUEST['kib'];
        $nmkib 		= $_REQUEST['nmkib'];
        $jenis 		= $_REQUEST['jenis'];
        $nmjenis	= $_REQUEST['nmjenis'];
        $trkib		= $_REQUEST['trkib'];
        $tahun		= $_REQUEST['ctahun'];
        $pnilai		= $_REQUEST['pnilai'];
        $iz 	  	= $_REQUEST['fa'];
		$nilai_eca	= "";
		
		if($pnilai=='1'){
			$fnilai="a.nilai";
			$lutji=" and a.nilai<>0";
		}else{
			$fnilai="a.total";
			$lutji="and a.total<>0";
		}
	/* 	if($trkib=='trkib_b'){
			$nilai_eca	= "and (nilai>=500000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_c'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_d'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}else{
			$nilai_eca	= "and (nilai>=150000 or kd_riwayat='9')";
		}
		 */
		
		$ckdskpd		="";
		if($kdbid<>''){
			$ckdskpd		="AND kd_skpd='$kdbid'";
		}
		$ctrkib		="";
		if($trkib<>''){
			$ctrkib		="$trkib";
		}
		$cjenis		="";
		if($jenis<>''){
			$cjenis		="AND LEFT(a.kd_brg,8)='$jenis'";
		}
		
		
		
        if($lctahu==''){
            $nm_tahu = '';
            $nip_tahu = '';
            $pkt_tahu = '';
            $jbt_tahu ='';
        }else{
        $csql1 = "SELECT a.*, b.nm_pangkat FROM ttd a LEFT JOIN mpangkat b 
                  ON a.kd_pangkat = b.kd_pangkat WHERE nip='$lctahu'";
        
        $rs = $this->db->query($csql1);
        $trh1 = $rs->row();
        $nm_tahu = $trh1->nama;
        $nip_tahu = $trh1->nip;
        $pkt_tahu = $trh1->nm_pangkat;
        $jbt_tahu = $trh1->jabatan;
        }
		
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI PENYUSUTAN ASET TETAP</b></td>
            </tr>";
			if($kib<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>".strtoupper($nmkib)."<br>Per TAHUN $tahun</b></td>
            </tr>";}
			if($jenis<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>( ".strtoupper($nmjenis)." )</b></td>
            </tr>";}
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten/Kota</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
			if($kdbid<>''){
            $cRet .= "<tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Satuan Kerja</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">:<b> $kdbid - $cnm_skpd </b></td>
            </tr>";
			}
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KODE JENIS/BARANG</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA JENIS/BARANG</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI PEROLEHAN</b></td>
                <td colspan=\"4\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>PENYUSUTAN</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI BUKU</b></td>
			</tr>
			<tr>	
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Masa Manfaat</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Akumulasi s/d Tahun Sebelumnya</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Tahun $tahun</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Per 31 Desember $tahun</b></td>
            </tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">1</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">2</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">3</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">4</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">5</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">6</td>
				<td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">7=5 + 6</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">8=4 - 7</td>
            </tr>
			</thead> ";
if($jenis==''){
	if($trkib=='trkib_b'){
		
$csql = "SELECT kode,nama,umur,SUM(nilai) AS tot,SUM(tot_th_belum) AS a
,SUM(nil_th_ini) AS b
FROM (

SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,if($pnilai='1',a.`nilai`,a.total) as nilai,
a.kd_brg,b.nm_brg,YEAR(a.tgl_oleh) as tahun,

IF(YEAR(a.tgl_oleh)='$tahun',0,(CASE 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))=1 THEN ($fnilai-($fnilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))<=0 THEN $fnilai 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))>1 THEN ($tahun-YEAR(a.tgl_oleh))*($fnilai/TRIM(c.umur))
END)) AS tot_th_belum,  

IF(YEAR(a.tgl_oleh)='$tahun',($fnilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))=1 THEN ($fnilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))>1 THEN ($fnilai/TRIM(c.umur))
END)) AS nil_th_ini


FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' 
AND kd_skpd='$kdbid' AND YEAR(a.tgl_oleh)<='$tahun' 
AND a.tgl_oleh<='$tahun-12-31'
and ($fnilai>=500000 or a.kd_riwayat='9') $lutji and a.kd_pemilik='12'
AND (a.kondisi<>'RB' AND a.kondisi<>'HB' AND a.kondisi<>'PM')
ORDER BY a.kd_brg,a.tahun

) fa GROUP BY kode";

	 $hasil = $this->db->query($csql);
	 
             $i 	= 1;
			 $nilai	=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
			 
			 
			 $n1	=0;
			 $n2	=0;
			 $n3	=0;
			 $n4	=0;
			 $n5	=0;
			 
	 foreach ($hasil->result() as $row){
			 $tot_th_ini = $row->a+$row->b;
			 $tot_buku  = $row->tot-$tot_th_ini;
			 
             /* $nilai 	= $nilai+$row->tot;
             $nilai2 	= $nilai2+$row->a;
             $nilai3 	= $nilai3+$row->b; */
             $nilai4 	= $row->a+$row->b;
             $nilai5 	= $row->tot-$nilai4;
			 
			 $n1	=$n1+$row->tot;
			 $n2	=$n2+$row->a;
			 $n3	=$n3+$row->b;
			 $n4	=$n4+$nilai4;
			 $n5	=$n5+$nilai5;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kode</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nama</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->a)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->b)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai5)."</td>
				</tr>";
            $i++;
	}		
			
	}elseif($trkib=='trkib_c'){
		$csql = "SELECT kode,nama,umur,SUM(nilai) AS tot,SUM(tot_th_belum) AS ax
,SUM(nil_th_ini) AS bx,(SUM(nilai)-SUM(tot_th_belum)) as buku_lalu,
(SUM(kap)) as kap
FROM (

SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,if($pnilai='1',a.`nilai`,a.total) as nilai,
a.kd_brg,b.nm_brg,YEAR(a.tgl_oleh) as tahun,
(SELECT if($pnilai='1',SUM(nilai),SUM(total)) FROM trkib_c_kap WHERE kd_skpd=a.kd_skpd and a.kd_unit=kd_unit 
and LEFT(a.kd_brg,8)=LEFT(kd_brg,8) and tgl_reg<='$tahun-12-31') as kap,

IF(YEAR(a.tgl_oleh)='$tahun',0,(CASE 
WHEN a.id_barang='07.01.01.01.2005.03.11.01.06.10.000047' THEN '34357240' 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))=1 THEN ($fnilai-($fnilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))<=0 THEN $fnilai 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))>1 THEN ($tahun-YEAR(a.tgl_oleh))*($fnilai/TRIM(c.umur))
END)) AS tot_th_belum,  

IF(YEAR(a.tgl_oleh)='$tahun',($fnilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))=1 THEN ($fnilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_oleh)))>1 THEN ($fnilai/TRIM(c.umur))
END)) AS nil_th_ini


FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' 
AND kd_skpd='$kdbid' AND YEAR(a.tgl_oleh)<='$tahun' 
AND a.tgl_oleh<='$tahun-12-31'
and ($fnilai>=20000000 or a.kd_riwayat='9') $lutji and a.kd_pemilik='12'
AND (a.kondisi<>'RB' AND a.kondisi<>'HB' AND a.kondisi<>'PM')
ORDER BY a.kd_brg,a.tahun

) iz GROUP BY kode";

	 $hasil = $this->db->query($csql);
	 
             $i 	= 1;
			 $nilai	=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
			 
			 $n1	=0;
			 $n2	=0;
			 $n3	=0;
			 $n4	=0;
			 $n5	=0;
			 
	 foreach ($hasil->result() as $row){
			 
			 /* if($kdbid=='1.02.01.00' && $row->id_barang=='07.01.01.01.2005.03.11.01.06.10.000047'){
			 $tot_th_belum = 34357240;
			 }else{
			 $tot_th_belum = $row->tot_th_belum;
			 } */
			 $tot		= $row->tot+$row->kap;
             $nilai4 	= $row->ax+$row->bx;
             $nilai5 	= $tot-$nilai4;
			 
			 $n1	=$n1+$tot;
			 $n2	=$n2+$row->ax;
			 $n3	=$n3+$row->bx;
			 $n4	=$n4+$nilai4;
			 $n5	=$n5+$nilai5;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kode</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nama</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->ax)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->bx)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai5)."</td>
				</tr>";
            $i++;
	 }
	}elseif($trkib=='trkib_d'){
		
	}else{
		$csql = "SELECT kode,nama,umur,SUM(nilai) AS tot,SUM(tot_th_belum) AS a
,SUM(nil_th_ini) AS b
FROM (

SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,if($pnilai='1',a.`nilai`,a.total) as nilai,
a.kd_brg,b.nm_brg,YEAR(a.tgl_peroleh) as tahun,

IF(YEAR(a.tgl_peroleh)='$tahun',0,(CASE 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))=1 THEN ($fnilai-($fnilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))<=0 THEN $fnilai 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))>1 THEN ($tahun-YEAR(a.tgl_peroleh))*($fnilai/TRIM(c.umur))
END)) AS tot_th_belum,  

IF(YEAR(a.tgl_peroleh)='$tahun',($fnilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))=1 THEN ($fnilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-YEAR(a.tgl_peroleh)))>1 THEN ($fnilai/TRIM(c.umur))
END)) AS nil_th_ini


FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' 
AND kd_skpd='$kdbid' AND YEAR(a.tgl_peroleh)<='$tahun' 
AND a.tgl_peroleh<='$tahun-12-31'
$lutji and a.kd_pemilik='12'
AND (a.kondisi<>'RB' AND a.kondisi<>'HB' AND a.kondisi<>'PM')
ORDER BY a.kd_brg,a.tahun

) nol GROUP BY kode";

	 $hasil = $this->db->query($csql);
	 
             $i 	= 1;
			 $nilai	=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
			 
			 
			 $n1	=0;
			 $n2	=0;
			 $n3	=0;
			 $n4	=0;
			 $n5	=0;
			 
	 foreach ($hasil->result() as $row){
			 $tot_th_ini = $row->a+$row->b;
			 $tot_buku  = $row->tot-$tot_th_ini;
			 
             /* $nilai 	= $nilai+$row->tot;
             $nilai2 	= $nilai2+$row->a;
             $nilai3 	= $nilai3+$row->b; */
             $nilai4 	= $row->a+$row->b;
             $nilai5 	= $row->tot-$nilai4;
			 
			 $n1	=$n1+$row->tot;
			 $n2	=$n2+$row->a;
			 $n3	=$n3+$row->b;
			 $n4	=$n4+$nilai4;
			 $n5	=$n5+$nilai5;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kode</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nama</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->a)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->b)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai5)."</td>
				</tr>";
            $i++;
	}
	}
	
 }else{
$csql = "SELECT c.`kd_barang` AS kode,c.`nama`,TRIM(c.umur) AS umur,a.`nilai`,
a.kd_brg,b.nm_brg,a.tahun,

if(a.tahun='$tahun',0,($tahun-a.tahun)) AS th_lalu,
(TRIM(c.umur)-($tahun-a.tahun)) AS masa_penyu,
(a.nilai/TRIM(c.umur)) AS penyusutan_pertahun,

if(a.tahun='$tahun',0,(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
END)) AS tot_th_belum, 

if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
END)) AS nil_th_ini

 
FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' $ckdskpd $cjenis AND tgl_reg<='$tahun-12-31' 
and kondisi<>'RB' and milik='12' $nilai_eca  ORDER BY kd_brg,tahun";
             $hasil = $this->db->query($csql);
             $i = 0;
			 $nilai=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
             foreach ($hasil->result() as $row){
			 $tot_th_ini	= $row->tot_th_belum+$row->nil_th_ini;
			 $tot_buku		= $row->nilai-$tot_th_ini;
			 $nilai=$nilai+$row->nilai;
			 $nilai2=$nilai2+$row->tot_th_belum;
			 $nilai3=$nilai3+$row->nil_th_ini;
			 $nilai4=$nilai4+$tot_th_ini;
			 $nilai5=$nilai5+$tot_buku;
				 
            $i++;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kd_brg</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nm_brg</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->nilai)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot_th_belum)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->nil_th_ini)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($tot_th_ini)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($tot_buku)."</td>
				</tr>";
             } 

}
			 
			$cRet .="
                 <tr>
                    <td colspan=\"3\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
                    <td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($n1)."</b></td>
                    <td align=\"center\" bgcolor=\"#ADFF2F\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($n2)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($n3)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($n4)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($n5)."</b></td>
				</tr>";
			 
            $cRet.="</table>";
                
				if($oto<>'01'){
            $cRet .="
			<br>
			<br>

			<table style=\"border-collapse:collapse;\" width=\"40%\" align=\"right\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
			     
                    <tr>
                        
                        <td colspan =\"4\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">
                        Bantaeng, ".$this->tanggal_indonesia($lctgl).",<br>KEPALA $cnm_skpd<br>&nbsp;<br>&nbsp;<br>
                        <u>$nm_tahu</u><br>Nip. $nip_tahu  
                        </td>
				</tr>";}
					
				$cRet .=" </table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PERALATAN DAN MESIN'); 
        $judul  = 'CETAK PENYUSUTAN PERALATAN DAN MESIN';	     
		switch($iz) {
        case 1;        
			 //$this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
              echo $cRet;//$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
         } 
	}
	
		public function rekap_kib_penyusutan_bln()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  = $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $kdbid 		= $_REQUEST['kd_skpd'];
        $cnm_skpd 	= $_REQUEST['nm_skpd'];
        $lctahu 	= $_REQUEST['tahu'];
        $lctgl 		= $_REQUEST['tgl'];
        $lctgl_akhir = $_REQUEST['tgl_akhir'];
        $tahun_ini	= $_REQUEST['tahun_ini'];
        $kib 		= $_REQUEST['kib'];
        $nmkib 		= $_REQUEST['nmkib'];
        $jenis 		= $_REQUEST['jenis'];
        $nmjenis	= $_REQUEST['nmjenis'];
        $trkib		= $_REQUEST['trkib'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
		$nilai_eca	= "";
		if($trkib=='trkib_b'){
			$nilai_eca	= "and (nilai>=500000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_c'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_d'){
			$nilai_eca	= "and (nilai>=0 or kd_riwayat='9')";
		}else{
			$nilai_eca	= "and (nilai>=0 or kd_riwayat='9')";
		}
		
		
		$ckdskpd		="";
		if($kdbid<>''){
			$ckdskpd		="AND kd_skpd='$kdbid'";
		}
		$ctrkib		="";
		if($trkib<>''){
			$ctrkib		="$trkib";
		}
		$cjenis		="";
		if($jenis<>''){
			$cjenis		="AND LEFT(a.kd_brg,8)='$jenis'";
		}
		
		
		
        if($lctahu==''){
            $nm_tahu = '';
            $nip_tahu = '';
            $pkt_tahu = '';
            $jbt_tahu ='';
        }else{
        $csql1 = "SELECT a.*, b.nm_pangkat FROM ttd a LEFT JOIN mpangkat b 
                  ON a.kd_pangkat = b.kd_pangkat WHERE nip='$lctahu'";
        
        $rs = $this->db->query($csql1);
        $trh1 = $rs->row();
        $nm_tahu = $trh1->nama;
        $nip_tahu = $trh1->nip;
        $pkt_tahu = $trh1->nm_pangkat;
        $jbt_tahu = $trh1->jabatan;
        }
		
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI PENYUSUTAN ASET TETAP</b></td>
            </tr>";
			if($kib<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>".strtoupper($nmkib)."<br>Per TAHUN $tahun</b></td>
            </tr>";}
			if($jenis<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>( ".strtoupper($nmjenis)." )</b></td>
            </tr>";}
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten/Kota</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
			if($kdbid<>''){
            $cRet .= "<tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Satuan Kerja</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">:<b> $kdbid - $cnm_skpd </b></td>
            </tr>";
			}
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KODE JENIS/BARANG</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA JENIS/BARANG</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI PEROLEHAN</b></td>
                <td colspan=\"4\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>PENYUSUTAN</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI BUKU</b></td>
			</tr>
			<tr>	
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Masa Manfaat</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Akumulasi s/d Tahun Sebelumnya</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Tahun $tahun</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Per 31 Desember $tahun</b></td>
            </tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">1</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">2</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">3</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">4</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">5</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">6</td>
				<td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">7=5 + 6</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">8=4 - 7</td>
            </tr>
			</thead> ";
             if($jenis==''){
$csql = "SELECT kode,nama,umur,SUM(nilai) AS tot,SUM(tot_th_belum) AS a
,SUM(nil_th_ini) AS b
FROM (

SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,a.nilai,
a.kd_brg,b.nm_brg,a.tahun,
if(a.tahun='$tahun',0,(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
END)) AS tot_th_belum, 

if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
END)) AS nil_th_ini


FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' 
AND kd_skpd='$kdbid' AND tgl_reg<='$tahun-12-31' 
and kondisi<>'RB' and milik='12' $nilai_eca ORDER BY a.kd_brg,a.tahun

) aa GROUP BY kode";
             $hasil = $this->db->query($csql);
             $i 	= 1;
			 $nilai	=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
             foreach ($hasil->result() as $row){
			 $c		= $row->a+$row->b;
			 $d		= $row->tot-$c;
			 $nilai	=$nilai+$row->tot;
			 $nilai2=$nilai2+$row->a;
			 $nilai3=$nilai3+$row->b;
			 $nilai4=$nilai4+$c;
			 $nilai5=$nilai5+$d;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kode</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nama</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->a)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->b)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($c)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($d)."</td>
				</tr>";
            $i++;
             }
			 }else{
$csql = "SELECT c.`kd_barang` AS kode,c.`nama`,TRIM(c.umur) AS umur,a.`nilai`,
a.kd_brg,b.nm_brg,a.tahun,

if(a.tahun='$tahun',0,($tahun-a.tahun)) AS th_lalu,
(TRIM(c.umur)-($tahun-a.tahun)) AS masa_penyu,
(a.nilai/TRIM(c.umur)) AS penyusutan_pertahun,

if(a.tahun='$tahun',0,(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
END)) AS tot_th_belum, 

if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
END)) AS nil_th_ini

 
FROM $ctrkib a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE kd_barang<>'' $ckdskpd $cjenis AND tgl_reg<='$tahun-12-31' 
and kondisi<>'RB' and milik='12' $nilai_eca  ORDER BY kd_brg,tahun";
             $hasil = $this->db->query($csql);
             $i = 0;
			 $nilai=0;
			 $nilai2=0;
			 $nilai3=0;
			 $nilai4=0;
			 $nilai5=0;
             foreach ($hasil->result() as $row){
			 $tot_th_ini	= $row->tot_th_belum+$row->nil_th_ini;
			 $tot_buku		= $row->nilai-$tot_th_ini;
			 $nilai=$nilai+$row->nilai;
			 $nilai2=$nilai2+$row->tot_th_belum;
			 $nilai3=$nilai3+$row->nil_th_ini;
			 $nilai4=$nilai4+$tot_th_ini;
			 $nilai5=$nilai5+$tot_buku;
				 
            $i++;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$row->kd_brg</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$row->nm_brg</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->nilai)."</td>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$row->umur</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot_th_belum)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->nil_th_ini)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($tot_th_ini)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($tot_buku)."</td>
				</tr>";
             } 
			 }
			 
			$cRet .="
                 <tr>
                    <td colspan=\"3\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
                    <td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai)."</b></td>
                    <td align=\"center\" bgcolor=\"#ADFF2F\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai2)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai3)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai4)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai5)."</b></td>
				</tr>";
			 
            $cRet.="</table>";
                
				if($oto<>'01'){
            $cRet .="
			<br>
			<br>

			<table style=\"border-collapse:collapse;\" width=\"40%\" align=\"right\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
			     
                    <tr>
                        
                        <td colspan =\"4\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">
                        Bantaeng, ".$this->tanggal_indonesia($lctgl).",<br>KEPALA $cnm_skpd<br>&nbsp;<br>&nbsp;<br>
                        <u>$nm_tahu</u><br>Nip. $nip_tahu  
                        </td>
				</tr>";}
					
				$cRet .=" </table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PERALATAN DAN MESIN'); 
        $judul  = 'CETAK PENYUSUTAN PERALATAN DAN MESIN';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
         } 
	}

		public function rekap_kib_penyusutan_all()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $lctgl_akhir = $_REQUEST['tgl_akhir'];
        $tahun_ini	= $_REQUEST['tahun_ini'];
        $kib 		= $_REQUEST['kib'];
        $nmkib 		= $_REQUEST['nmkib'];
        $trkib		= $_REQUEST['trkib'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
		$nilai_eca	= "";
		if($trkib=='trkib_b'){
			$nilai_eca	= "and (nilai>=500000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_c'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_d'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}else{
			$nilai_eca	= "and (nilai>=150000 or kd_riwayat='9')";
		}
	
		
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI PENYUSUTAN ASET TETAP SELURUH SKPD</b></td>
            </tr>";
			if($kib<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>( ".strtoupper($nmkib)." )<br>Per TAHUN $tahun</b></td>
            </tr>";}
			
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten/Kota</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
			
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KODE SKPD</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI PEROLEHAN</b></td>
                <td colspan=\"3\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>PENYUSUTAN</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI BUKU</b></td>
			</tr>
			<tr>	
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Akumulasi s/d Tahun Sebelumnya</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Tahun $tahun</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Per 31 Desember $tahun</b></td>
            </tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">1</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">2</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">3</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">4</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">5</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">6</td>
				<td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">7=5 + 6</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">8=4 - 7</td>
            </tr>
			</thead> ";
			$csql1 	= "select kd_skpd,nm_skpd from ms_skpd where kd_skpd<>'1.20.01.00' 
			and kd_skpd<>'1.20.02.00' and kd_skpd<>'1.20.23.00' order by kd_skpd";
$hasil1 	= $this->db->query($csql1);
$i 		= 1;
$tot_b	= 0;
$tot_c	= 0;
$tot_d	= 0;
$tot_e	= 0;
							 $nilai	=0;
							 $nilai2=0;
							 $nilai3=0;
							 $nilai4=0;
							 $nilai5=0;
foreach ($hasil1->result() as $row){
$skpd 	  	= $row->kd_skpd;
$nm_skpd 	= $row->nm_skpd;

					$csql = "SELECT SUM(nilai) AS tot,SUM(tot_th_belum) AS a
					,SUM(nil_th_ini) AS b
					FROM (

					SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,a.nilai,
					a.kd_brg,b.nm_brg,a.tahun,
					if(a.tahun='$tahun',0,(CASE 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
					END)) AS tot_th_belum, 

					if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
					END)) AS nil_th_ini


					FROM $trkib a
					LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
					LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
					WHERE kd_barang<>'' 
					AND kd_skpd='$skpd' AND tgl_reg<='$tahun-12-31' 
					and kondisi<>'RB' and milik='12' $nilai_eca

					) aa ";//GROUP BY kode
							 $hasil = $this->db->query($csql);
							 //$i 	= 1;
							 foreach ($hasil->result() as $row){
							 $c		= $row->a+$row->b;
							 $d		= $row->tot-$c;
							 $nilai	=$nilai+$row->tot;
							 $nilai2=$nilai2+$row->a;
							 $nilai3=$nilai3+$row->b;
							 $nilai4=$nilai4+$c;
							 $nilai5=$nilai5+$d;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$skpd</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->a)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->b)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($c)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($d)."</td>
				</tr>";
		}
            
				$i++;
		}
			$cRet .="
                 <tr>
                    <td colspan=\"3\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
                    <td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai2)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai3)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai4)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai5)."</b></td>
				</tr>";
				
		$cRet .=" </table>";
		//$cRet .=" </table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PERALATAN DAN MESIN'); 
        $judul  = 'CETAK PENYUSUTAN PERALATAN DAN MESIN';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
			 //echo $cRet;
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
         } 
	}
	
			public function rekap_kib_penyusutan_all_bln()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $lctgl_akhir = $_REQUEST['tgl_akhir'];
        $tahun_ini	= $_REQUEST['tahun_ini'];
        $kib 		= $_REQUEST['kib'];
        $nmkib 		= $_REQUEST['nmkib'];
        $trkib		= $_REQUEST['trkib'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
		$nilai_eca	= "";
		if($trkib=='trkib_b'){
			$nilai_eca	= "and (nilai>=500000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_c'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}elseif($trkib=='trkib_d'){
			$nilai_eca	= "and (nilai>=10000000 or kd_riwayat='9')";
		}else{
			$nilai_eca	= "and (nilai>=150000 or kd_riwayat='9')";
		}
	
		
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI PENYUSUTAN ASET TETAP SELURUH SKPD</b></td>
            </tr>";
			if($kib<>''){
            $cRet .= " <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>( ".strtoupper($nmkib)." )<br>Per TAHUN $tahun</b></td>
            </tr>";}
			
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten/Kota</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
			
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KODE SKPD</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI PEROLEHAN</b></td>
                <td colspan=\"3\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>PENYUSUTAN</b></td>
                <td rowspan=\"2\" align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NILAI BUKU</b></td>
			</tr>
			<tr>	
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Akumulasi s/d Tahun Sebelumnya</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Tahun $tahun</b></td>
				<td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>Per 31 Desember $tahun</b></td>
            </tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">1</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">2</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">3</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">4</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">5</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">6</td>
				<td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">7=5 + 6</td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\">8=4 - 7</td>
            </tr>
			</thead> ";
			$csql1 	= "select kd_skpd,nm_skpd from ms_skpd where kd_skpd<>'1.20.01.00' 
			and kd_skpd<>'1.20.02.00' and kd_skpd<>'1.20.23.00' order by kd_skpd";
$hasil1 	= $this->db->query($csql1);
$i 		= 1;
$tot_b	= 0;
$tot_c	= 0;
$tot_d	= 0;
$tot_e	= 0;
							 $nilai	=0;
							 $nilai2=0;
							 $nilai3=0;
							 $nilai4=0;
							 $nilai5=0;
foreach ($hasil1->result() as $row){
$skpd 	  	= $row->kd_skpd;
$nm_skpd 	= $row->nm_skpd;

					$csql = "SELECT SUM(nilai) AS tot,SUM(tot_th_belum) AS a
					,SUM(nil_th_ini) AS b
					FROM (

					SELECT c.kd_barang AS kode,c.nama,TRIM(c.umur) AS umur,a.nilai,
					a.kd_brg,b.nm_brg,a.tahun,
					if(a.tahun='$tahun',0,(CASE 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
					END)) AS tot_th_belum, 

					if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
					WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
					END)) AS nil_th_ini


					FROM $trkib a
					LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
					LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
					WHERE kd_barang<>'' 
					AND kd_skpd='$skpd' AND tgl_reg<='$tahun-12-31' 
					and kondisi<>'RB' and milik='12' $nilai_eca

					) aa ";//GROUP BY kode
							 $hasil = $this->db->query($csql);
							 //$i 	= 1;
							 foreach ($hasil->result() as $row){
							 $c		= $row->a+$row->b;
							 $d		= $row->tot-$c;
							 $nilai	=$nilai+$row->tot;
							 $nilai2=$nilai2+$row->a;
							 $nilai3=$nilai3+$row->b;
							 $nilai4=$nilai4+$c;
							 $nilai5=$nilai5+$d;
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"center\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">$skpd</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->tot)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->a)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($row->b)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($c)."</td>
					<td align=\"right\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($d)."</td>
				</tr>";
		}
            
				$i++;
		}
			$cRet .="
                 <tr>
                    <td colspan=\"3\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"2%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
                    <td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai2)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai3)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai4)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"10%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($nilai5)."</b></td>
				</tr>";
				
		$cRet .=" </table>";
		//$cRet .=" </table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PERALATAN DAN MESIN'); 
        $judul  = 'CETAK PENYUSUTAN PERALATAN DAN MESIN';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
			 //echo $cRet;
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
         } 
	}


	public function rekap_kib_akumulasi(){
		if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
 	
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI AKUMULASI PENYUSUTAN ASET TETAP <br/> Per TAHUN $tahun</b></td>
            </tr>";
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB B</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB C</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB D</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB E</b></td>
			</tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
            </tr>
			</thead> ";
					$csql 	= "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
					$hasil 	= $this->db->query($csql);
					$i 		= 1;
					$tot_b	= 0;
					$tot_c	= 0;
					$tot_d	= 0;
					$tot_e	= 0;
					foreach ($hasil->result() as $row){
					$skpd 	  	= $row->kd_skpd;
					$nm_skpd 	= $row->nm_skpd;

			 	$sqdetail1=" 
				select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini

				FROM trkib_b a
				LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
				WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun' 
				and (nilai>=500000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil1   = $this->db->query($sqdetail1);
				foreach ($hasil1->result() as $row)
				{
					
					$nilai1   = $row->nil_th_ini+$row->tot_th_belum;
					$tot_b   = $tot_b+$nilai1;
				}
				
				$sqdetail2="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_c a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun' 
							and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil2   = $this->db->query($sqdetail2);
				foreach ($hasil2->result() as $row)
				{
					$nilai2   	= $row->nil_th_ini+$row->tot_th_belum;
					$tot_c   	= $tot_c+$nilai2;
				}
				
				$sqdetail3="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_d a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun' 
							and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil3   = $this->db->query($sqdetail3);
				foreach ($hasil3->result() as $row)
				{
					$nilai3   = $row->nil_th_ini+$row->tot_th_belum;
					$tot_d    = $tot_d+$nilai3;
				}
				
				$sqdetail4="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN ($tahun-a.tahun)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',(a.nilai/TRIM(c.umur)),(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_e a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun' 
							and (nilai>=150000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil4   = $this->db->query($sqdetail4);
				foreach ($hasil4->result() as $row)
				{
					$nilai4   = $row->nil_th_ini+$row->tot_th_belum;
					$tot_e    = $tot_e+$nilai4;
				}
			 			 
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai1)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai2)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai3)."</td>
					<td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
				</tr>";
            $i++;
            
			 }
			 
			$cRet .="
                 <tr>
                    <td colspan=\"2\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"35%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_b)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_c)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_d)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_e)."</b></td>
				</tr>";
			 
        $cRet.="</table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PER SKPD'); 
        $judul  = 'CETAK PENYUSUTAN PER SKPD';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
		
         } 
	}

	public function rekap_kib_akumulasi_bln(){
		if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
 	
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI AKUMULASI PENYUSUTAN ASET TETAP <br/> Per TAHUN $tahun</b></td>
            </tr>";
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB B</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB C</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB D</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB E</b></td>
			</tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
            </tr>
			</thead> ";
					$csql 	= "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
					$hasil 	= $this->db->query($csql);
					$i 		= 1;
					$tot_b	= 0;
					$tot_c	= 0;
					$tot_d	= 0;
					$tot_e	= 0;
					foreach ($hasil->result() as $row){
					$skpd 	  	= $row->kd_skpd;
					$nm_skpd 	= $row->nm_skpd;
/*KIB B*/
	$csql1 ="SELECT sum(a.`nilai`) as nilai,sum(TRIM(c.umur*12)) AS umur,
year(a.tgl_oleh) as th,month(a.tgl_oleh) as bl,day(a.tgl_oleh) as hr,
sum(if(a.tahun='$tahun',0,($tahun-a.tahun))) AS th_lalu,
sum(a.nilai/TRIM(c.umur*12)) AS penyusutan_pertahun

FROM trkib_b a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE a.kd_brg<>'' 
AND a.kd_skpd='$skpd' AND tahun<='$tahun' and 
(a.nilai>=500000 or a.kd_riwayat='9') 
and a.kondisi<>'RB'"; 

             $hasil1 = $this->db->query($csql1);
			 
             foreach ($hasil1->result() as $row){
			 /*perhitungan bulanan*/
			if($row->hr >= 15){
				$bl_lalu 		= ($row->th_lalu*12)+(12-$row->bl);
				
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				 if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
				
				if($bl_lalu>$row->umur){
             $tot_bl_lalu 	= $row->nilai;
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 
		}else{
				 
             $bl_lalu 		= ($row->th_lalu*12)+((12-$row->bl)+1); 
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
			 if($bl_lalu>$row->umur){
			 $tot_bl_lalu 	= $row->nilai;	
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			    $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 }
			 
			 $nilai1   = $nil_bl_ini2+$tot_bl_lalu;
			 $tot_b    = $tot_b+$nilai1;
			 }
/*KIB C*/
	$csql2 ="SELECT sum(a.`nilai`) as nilai,sum(TRIM(c.umur*12)) AS umur,
year(a.tgl_oleh) as th,month(a.tgl_oleh) as bl,day(a.tgl_oleh) as hr,
sum(if(a.tahun='$tahun',0,($tahun-a.tahun))) AS th_lalu,
sum(a.nilai/TRIM(c.umur*12)) AS penyusutan_pertahun

FROM trkib_c a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE a.kd_brg<>'' 
AND a.kd_skpd='$skpd' AND tahun<='$tahun' and 
(a.nilai>=10000000 or a.kd_riwayat='9') 
and a.kondisi<>'RB'"; 

             $hasil2 = $this->db->query($csql2);
			 
             foreach ($hasil2->result() as $row){
			 /*perhitungan bulanan*/
			if($row->hr >= 15){
				$bl_lalu 		= ($row->th_lalu*12)+(12-$row->bl);
				
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				 if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
				
				if($bl_lalu>$row->umur){
             $tot_bl_lalu 	= $row->nilai;
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 
		}else{
				 
             $bl_lalu 		= ($row->th_lalu*12)+((12-$row->bl)+1); 
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
			 if($bl_lalu>$row->umur){
			 $tot_bl_lalu 	= $row->nilai;	
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			    $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 }
			 
			 
			 $nilai2   = $nil_bl_ini2+$tot_bl_lalu;
			 $tot_c    = $tot_c+$nilai2;
			 }

/*KIB D*/
	$csql3 ="SELECT sum(a.`nilai`) as nilai,sum(TRIM(c.umur*12)) AS umur,
year(a.tgl_oleh) as th,month(a.tgl_oleh) as bl,day(a.tgl_oleh) as hr,
sum(if(a.tahun='$tahun',0,($tahun-a.tahun))) AS th_lalu,
sum(a.nilai/TRIM(c.umur*12)) AS penyusutan_pertahun

FROM trkib_d a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE a.kd_brg<>'' 
AND a.kd_skpd='$skpd' AND tahun<='$tahun' and 
(a.nilai>=0 or a.kd_riwayat='9') 
and a.kondisi<>'RB'"; 

             $hasil3 = $this->db->query($csql3);
			 
             foreach ($hasil3->result() as $row){
			 /*perhitungan bulanan*/
			if($row->hr >= 15){
				$bl_lalu 		= ($row->th_lalu*12)+(12-$row->bl);
				
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				 if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
				
				if($bl_lalu>$row->umur){
             $tot_bl_lalu 	= $row->nilai;
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 
		}else{
				 
             $bl_lalu 		= ($row->th_lalu*12)+((12-$row->bl)+1); 
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
			 if($bl_lalu>$row->umur){
			 $tot_bl_lalu 	= $row->nilai;	
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			    $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 }
			 
			 
			 $nilai3   = $nil_bl_ini2+$tot_bl_lalu;
			 $tot_d    = $tot_d+$nilai3;
			 }

/*KIB E*/
	$csql4 ="SELECT sum(a.`nilai`) as nilai,sum(TRIM(c.umur*12)) AS umur,
year(a.tgl_oleh) as th,month(a.tgl_oleh) as bl,day(a.tgl_oleh) as hr,
sum(if(a.tahun='$tahun',0,($tahun-a.tahun))) AS th_lalu,
sum(a.nilai/TRIM(c.umur*12)) AS penyusutan_pertahun

FROM trkib_b a
LEFT JOIN mbarang b ON b.kd_brg=a.kd_brg 
LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
WHERE a.kd_brg<>'' 
AND a.kd_skpd='$skpd' AND tahun<='$tahun' and 
(a.nilai>=0 or a.kd_riwayat='9') 
and a.kondisi<>'RB'"; 

             $hasil4 = $this->db->query($csql4);
			 
             foreach ($hasil4->result() as $row){
			 /*perhitungan bulanan*/
			if($row->hr >= 15){
				$bl_lalu 		= ($row->th_lalu*12)+(12-$row->bl);
				
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				 if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
				
				if($bl_lalu>$row->umur){
             $tot_bl_lalu 	= $row->nilai;
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 
		}else{
				 
             $bl_lalu 		= ($row->th_lalu*12)+((12-$row->bl)+1); 
				if($bl_lalu==$row->umur){
			    $tot_bl_lalu2 	= 0;	
				}elseif($bl_lalu<$row->umur){
				$tot_bl_lalu2 	= ($row->penyusutan_pertahun*$bl_lalu);	
				}else{
				$tot_bl_lalu2 	= 0;	
				}
				
				if((12-$row->bl)<>0){
				 $nil_bl_ini 	= ((12-$row->bl)*$row->penyusutan_pertahun);
				 }else{
				 $nil_bl_ini 	= ($row->bl*$row->penyusutan_pertahun);
				 }
				 
			 if($bl_lalu>$row->umur){
			 $tot_bl_lalu 	= $row->nilai;	
				$tot_buku 		= 0;
				$nil_bl_ini2 	= 0;
			 $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				}else{
             $tot_bl_lalu 	= ($row->penyusutan_pertahun*$bl_lalu);
				$nil_bl_ini2 	= $nil_bl_ini;
			    $tot_bl_ini	= $tot_bl_lalu2+$nil_bl_ini2;
				$tot_buku 		= $row->nilai-$tot_bl_ini;
				}
			 }
			 
			 
			 $nilai4   = $nil_bl_ini2+$tot_bl_lalu;
			 $tot_e    = $tot_e+$nilai4;
			 }	
			 
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">$nilai1</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">$nilai2</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">$nilai3</td>
					<td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">$nilai4</td>
				</tr>";
            $i++;
            
			 }
			 
			$cRet .="
                 <tr>
                    <td colspan=\"2\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"35%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>$tot_b</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>$tot_c</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>$tot_d</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>$tot_e</b></td>
				</tr>";
			 
        $cRet.="</table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PER SKPD'); 
        $judul  = 'CETAK PENYUSUTAN PER SKPD';	     
		switch($iz) {
        case 1; 
			echo $cRet;		
			 //$this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
		
         } 
	}

	
	public function rekap_kib_penyusutan_skpd()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
						
 	
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI NILAI ASET TETAP BERSIH <br/> Per TAHUN $tahun</b></td>
            </tr>";
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB B</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB C</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB D</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB E</b></td>
			</tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
            </tr>
			</thead> ";
$csql 	= "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
$hasil 	= $this->db->query($csql);
$i 		= 1;
$tot_b	= 0;
$tot_c	= 0;
$tot_d	= 0;
$tot_e	= 0;
foreach ($hasil->result() as $row){
$skpd 	  	= $row->kd_skpd;
$nm_skpd 	= $row->nm_skpd;

			 	$sqdetail1=" 
				select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini

							FROM trkib_b a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							and (nilai>=500000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil1   = $this->db->query($sqdetail1);
				foreach ($hasil1->result() as $row)
				{
					
					$nilai1   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_b   = $tot_b+$nilai1;
				}
				
				$sqdetail2="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_c a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil2   = $this->db->query($sqdetail2);
				foreach ($hasil2->result() as $row)
				{
					$nilai2   	= ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_c   	= $tot_c+$nilai2;
				}
				
				$sqdetail3="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_d a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil3   = $this->db->query($sqdetail3);
				foreach ($hasil3->result() as $row)
				{
					$nilai3   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_d    = $tot_d+$nilai3;
				}
				
				$sqdetail4="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_e a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=500000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil4   = $this->db->query($sqdetail4);
				foreach ($hasil4->result() as $row)
				{
					$nilai4   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_e    = $tot_e+$nilai4;
				}
			 			 
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai1)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai2)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai3)."</td>
					<td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
				</tr>";
            $i++;
            
			 }
			 
			$cRet .="
                 <tr>
                    <td colspan=\"2\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"35%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_b)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_c)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_d)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_e)."</b></td>
				</tr>";
			 
        $cRet.="</table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PER SKPD'); 
        $judul  = 'CETAK PENYUSUTAN PER SKPD';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
		
         } 
	}
	
		public function rekap_kib_penyusutan_skpd_bln()
	{
	if($this->auth->is_logged_in() == false){
           	redirect(site_url().'/welcome/login');
      	}else{
		$oto		= $this->session->userdata('otori');
		$thn  		= $this->session->userdata('ta_simbakda');
        $konfig   	= $this->ambil_config();
		$kota  	  	= strtoupper($konfig['kota']);
		$nm_client  = strtoupper($konfig['nm_client']);
        $lctgl 		= $_REQUEST['tgl'];
        $tahun		= $_REQUEST['ctahun'];
        $iz 	  	= $_REQUEST['fa'];
						
 	
		$cRet ="";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"2\" style=\"font-size:14px; font-family:tahoma;\"><b>REKAPITULASI NILAI ASET TETAP BERSIH <br/> Per TAHUN $tahun</b></td>
            </tr>";
            $cRet .= "<tr>
                <td width =\"15%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Provinsi</td>
                <td width =\"85%\" align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $nm_client</td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">&ensp;Kabupaten</td>
                <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: $kota</td>
            </tr>";
            $cRet .= "<tr>
                <td align=\"left\"  colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&ensp;</td>
            </tr>
            
            </table>
			
        <table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NO</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>NAMA SKPD</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB B</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB C</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB D</b></td>
                <td align=\"center\" bgcolor=\"#ADFF2F\" style=\"font-size:10px; font-family:tahoma;\"><b>KIB E</b></td>
			</tr>
            <tr>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
                <td align=\"center\" style=\"font-size:10px; font-family:tahoma;\"></td>
            </tr>
			</thead> ";
$csql 	= "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
$hasil 	= $this->db->query($csql);
$i 		= 1;
$tot_b	= 0;
$tot_c	= 0;
$tot_d	= 0;
$tot_e	= 0;
foreach ($hasil->result() as $row){
$skpd 	  	= $row->kd_skpd;
$nm_skpd 	= $row->nm_skpd;

			 	$sqdetail1=" 
				select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini

							FROM trkib_b a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							and (nilai>=500000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil1   = $this->db->query($sqdetail1);
				foreach ($hasil1->result() as $row)
				{
					
					$nilai1   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_b   = $tot_b+$nilai1;
				}
				
				$sqdetail2="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_c a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil2   = $this->db->query($sqdetail2);
				foreach ($hasil2->result() as $row)
				{
					$nilai2   	= ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_c   	= $tot_c+$nilai2;
				}
				
				$sqdetail3="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_d a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=10000000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil3   = $this->db->query($sqdetail3);
				foreach ($hasil3->result() as $row)
				{
					$nilai3   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_d    = $tot_d+$nilai3;
				}
				
				$sqdetail4="select sum(a.nilai) as nilai,sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai-(a.nilai/TRIM(c.umur))) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN a.nilai 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN ($tahun-a.tahun-1)*(a.nilai/TRIM(c.umur))
				END))) AS tot_th_belum, 

				sum(if(a.tahun='$tahun',0,(CASE 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))=1 THEN (a.nilai/TRIM(c.umur)) 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))<=0 THEN 0 
				WHEN (TRIM(c.umur)-($tahun-a.tahun-1))>1 THEN (a.nilai/TRIM(c.umur))
				END))) AS nil_th_ini


							FROM trkib_e a
							LEFT JOIN mbarang_umur c ON c.kd_barang=LEFT(a.kd_brg,8) 
							WHERE kd_barang<>'' AND kd_skpd='$skpd' AND tahun<='$tahun'
							 and (nilai>=500000 or kd_riwayat='9') and kondisi<>'RB'"; 
				$hasil4   = $this->db->query($sqdetail4);
				foreach ($hasil4->result() as $row)
				{
					$nilai4   = ($row->nilai-($row->nil_th_ini+$row->tot_th_belum));
					$tot_e    = $tot_e+$nilai4;
				}
			 			 
              $cRet .="
                 <tr>
                    <td align=\"center\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\">$i</td>
                    <td align=\"left\" width =\"30%\" style=\"font-size:10px; font-family:tahoma;\">$nm_skpd</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai1)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai2)."</td>
                    <td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai3)."</td>
					<td align=\"right\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\">".number_format($nilai4)."</td>
				</tr>";
            $i++;
            
			 }
			 
			$cRet .="
                 <tr>
                    <td colspan=\"2\" bgcolor=\"#ADFF2F\" align=\"center\" width =\"35%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL NILAI</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_b)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_c)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_d)."</b></td>
					<td align=\"right\" bgcolor=\"#ADFF2F\" width =\"15%\" style=\"font-size:10px; font-family:tahoma;\"><b>".number_format($tot_e)."</b></td>
				</tr>";
			 
        $cRet.="</table>";
		$data['prev']= $cRet;
		$kertas='A4';  
        $this->template->set('title', 'CETAK PENYUSUTAN PER SKPD'); 
        $judul  = 'CETAK PENYUSUTAN PER SKPD';	     
		switch($iz) {
        case 1;        
			 $this->mdata->_mpdf3('',$cRet,10,10,10,2,$kertas,3);      
             //$this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            $this->load->view('transaksi/excel', $data);
        break;
		} 
		
         } 
	}

	
	
}