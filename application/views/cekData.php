<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<!-- <table boder="1">
		<tr>
			<td>Id</td>
			<td>Bucket</td>
			<td>Nama</td>
			<td>Detail</td>
			<td>Tlp</td>
			<td>Plat</td>
			<td>Tahun</td>
			<td>Status</td>
			<td>Is</td>
			<td>Categorys</td>
		</tr>
	</table>
	<table border="1" cellpadding="10">
        <tr>
            <td bgcolor="yellow">Baris 1 kolom 1</td>
            <td>baris 1 kolom 2</td>
        </tr>
        <tr bgcolor="#00ff80">
            <td>Baris 2 kolom 1</td>
            <td>baris 2 kolom 2</td>
        </tr>
    </table> -->

	<table border="1" >
		
		<thead>
			<tr>
				<!-- <td>No</td> -->
				<td>Id </td>
				<td>Loan</td>
				<td>Paket</td>
				<td>Rate</td>
				<td>Premium</td>
			
			</tr>
		</thead>
		<tbody>
       <tr>
       
	 <?php
	  
	 $i=1;
	 foreach ($hasil->result_array() as $bicycle){
			$paket = $this->db->query("select * from ref_package where ref_package_name = '".$bicycle['cust_cf_package']."'");
			$hasilPaket = $paket->row_array();
		 ?>
	 
			 <!-- <td> <?php echo $i; ?> </td> -->
			<td> <?php echo $bicycle['cust_cf_id']; ?> </td>
			<td> <?php echo $bicycle['cust_cf_loan']; ?> </td>
			<td> <?php echo $bicycle['cust_cf_package']; ?> </td>
			<td> <?php echo $hasilPaket['ref_package_rate']; ?> </td>
			<td> <?php echo $bicycle['cust_cf_premium']; ?> </td>
			
			</tr>
			<?php $i++;} ?>
		</tbody>
	</table>
</body>
</html>