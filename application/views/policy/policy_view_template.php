<!DOCTYPE html>
<html>
<head>
	<title>Polis Pinduit</title>
	<meta charset="utf-8">
	<style type="text/css">

		.page_break { page-break-before: always; }

		.tablborderHeader {
			border-collapse: collapse;
		}

		.tableborder {
			border: 1px solid black;
		}

		.tableborder tr td {
			border: 1px solid black;
		}

		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 8px;
		}

		hr {
			border: none;
		    height: 1px;
		    /* Set the hr color */
		    color: #333; /* old IE */
		    background-color: #333; /* Modern Browsers */
		}


		.oriWrapper{
			position: relative;
			right: 0;
			margin-left: 150px;
		}

		.ori {
			border: 3px solid black;
			padding: 5px;
			padding-left: 20px;
			padding-right: 20px;
			width: 100px;
			position: center right;
			text-align: center;
			float: right;
		}
		/*@page { margin: 100px 25px; }
	    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
	    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }*/
	</style>
</head>
<body>
	<?php
		$imagepath = FCPATH;
		// $imagepath = base_url();
	?>
	<img src="<?php echo $imagepath; ?>assets/policy/header_polis_adira.png"  width="100%">
	<table width="100%">
		<tr>
			<td align="right" width="100%">
				<table width="100%">
					<tr>
						<td width="50%">&nbsp;</td>
						<td width="50%">
							<div class="oriWrapper">
								
								<div class="ori">ORIGINAL</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<center style="font-size:13px;">SERTIFIKAT</center>
			</td>
		</tr>
		<tr>
			<td>
				<center style="font-size:13px;">ASURANSI PEMBIAYAAN</center>
				<br><br>
			</td>
		</tr>
		<tr>
			<td>
				Sertifikat ini merupakan bagian tak terpisahkan dari Polis Induk yang disebutkan di bawah ini dan merupakan ringkasan dari objek yang dipertanggungkan.<br>Persyaratan Polis Induk ini berlaku untuk objek yang dipertanggungkan di bawah ini:
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px"> - No. Polis </td>
						<td> : </td>
						<td><?= $nomorPolis; ?></td>
					</tr>
					<tr>
						<td> - No. Sertifikat </td>
						<td> : </td>
						<td><?= $nomorSertifikat; ?></td>
					</tr>
					<tr>
						<td> - No. Kontrak/No. </td>
						<td> : </td>
						<td><?= $nomorKontrak; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px"> - Pemegang Polis </td>
						<td> : </td>
						<td><?= $pemegangPolis; ?></td>
					</tr>
					<tr>
						<td> - Alamat Pemegang Polis </td>
						<td> : </td>
						<td><?= $alamatPemegangPolis; ?></td>
					</tr>
					<tr>
						<td> - Nama Tertanggung </td>
						<td> : </td>
						<td><?= $namaTertanggung; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px"> - Jangka Waktu Pertanggungan </td>
						<td> : </td>
						<td>Dari <br>
							<u>Pertanggungan dimulai dan berakhir pukul 12.00 siang waktu setempat dimana objek pertanggungan berada</u>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px"> - Objek Pertanggungan </td>
						<td> : </td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> NAMA DEBITUR </td>
						<td> : </td>
						<td><?= $namaDebitur; ?></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> ALAMAT DEBITUR </td>
						<td> : </td>
						<td><?= $alamatDebitur; ?></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> NOMOR INVOICE </td>
						<td> : </td>
						<td><?= $nomorInvoice; ?></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> NAMA PAYOR </td>
						<td> : </td>
						<td><?= $namaPayor; ?></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> PROJECT </td>
						<td> : </td>
						<td><?= $project; ?></td>
					</tr>
					<tr>
						<td style="padding-left:10px;"> JATUH TEMPO </td>
						<td> : </td>
						<td><?= $jatuhTempo; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px"> - Harga Petanggungan </td>
						<td> : </td>
						<td width="250px"> Loan</td>
						<td><?= $periode; ?></td>
						<td width="100px" align="right">IDR</td>
						<td width="50px" align="right"><?= $tsi; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="150px" style="word-wrap:break-word"> - Jenis Pertanggungan & Pehitungan Premi </td>
						<td> : </td>
						<td></td>
					</tr>
				</table>
				<table>
					<tr>
						<td width="150px" style="text-align:center;"> <u>Periode</u></td>
						<td width="130px" style="text-align:center;"> <u>Jenis Pertanggungan</u></td>
						<td width="220px" style="text-align:center;"> <u>Perhitungan Premi</u></td>
						<td colspan="2" width="180px" style="text-align:center;padding-left: 30px;"> <u>Premi</u></td>
					</tr>
					<tr valign="top">
						<td align="center"><?php echo $periode;?></td>
						<td> Gagal bayar lebih dari <?= $jenisPertanggungan; ?> hari sejak Jatuh Tempo</td>
						<td style="padding-left: 50px;"><?= $rate; ?> x <?= $tsi; ?></td>
						<td width="70px" align="right">IDR</td>
						<td width="20px" align="right" style="padding-right: 40px;"><?= $premi; ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="padding-left: 50px;"></td>
						<td colspan="2" width="70px" align="right" style="padding-left: 70px;padding-right:20px;"><hr></td>
						
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="padding-left: 50px;"</td>
						<td width="70px" align="right">IDR</td>
						<td width="20px" align="right" style="padding-right: 40px;"><?= $premi; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				* Perhitungan Premi = Rate x Harga Pertanggungan<br>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr valign="top">
						<td width="150px"> - Pembayaran Premi </td>
						<td width="5px"> : </td>
						<td align="justify"> Premi   asuransi   sudah   harus   dibayarkan   dan   diterima   oleh   PT   Asuransi    Adira    Dinamika    paling lambat   tanggal    1    Desember    2018.    Tidak    dibayarkan    premi    asuransi    dalam    jangka    waktu tersebut   menyebabkan   batalnya   polis   secara    otomatis    dan    pembayaran    premi    asuransi    untuk   masa   yang   sudah   berjalan   sesuai   dengan   ketentuan-ketentuan   dalam    Polis    Standar    Asuransi Kendaraan Bermotor Indonesia.
						</td> 
					</tr>
					<tr>
						<td width="150px"> - Risiko Sendiri </td>
						<td> : </td>
						<td>- Loan : 20 % dari nilai klaim yang dapat dibayarkan</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				*a.o.a = anyone accident<br>
				<hr>
			</td>
		</tr>
		<tr>
			<td align="right">
				Jakarta, <?= date("d M Y"); ?><br>
				PT Asuransi Adira Dinamika
				<br><br><br><br>
			</td>
		</tr>
		<tr>
			<td  width="auto">
				<table  width="auto">
					<tr>
						<td width="400px;">
							WebPS00000000 v4.2.0 - CTC0200005 v2.0.0 - <?= $nomorPolis; ?> - 000019
						</td>
						<td width="320px;" align="right">
							Page 1 of 1
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<center>
	<img src="<?php echo $imagepath; ?>assets/policy/footer_polis_adira.png" width="25%"></center>
</body>
</html>