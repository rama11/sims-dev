<style type="text/css">
	.tableBarang{
		border-collapse: collapse;
		width: 100%;
	}
	.tableBarang tr th{
		border: 1px solid #ddd;;
		text-align:left;
		padding: 5px
	}

	.tableBarang tr td{
		border: 1px solid #ddd;;
		text-align:left;
		padding: 5px
	}
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	@if($status == 'peminjaman')
		<p>
			Hello {{$users->name}},
		</p>		
		@if($req_asset->status == 'REJECT')
		<b><i>Rejecting Peminjaman Asset</i></b>, berikut rinciannya:
		<table style="text-align: left;margin: 5px;">
			<tr>
				<th>Name</th>
				<th> : </th>
				<td>{{$req_asset->nama_barang}}</td>
			</tr>
			<tr>
				<th>Description</th>
				<th> : </th>
				<td>{{$req_asset->description}}</td>
			</tr>
			<tr>
				<th>Tanggal Peminjaman</th>
				<th> : </th>
				<td>{{date('d-M-Y', strtotime($req_asset->created_at))}}</td>
			</tr>
			<tr>
				<th>Note</th>
				<th> : </th>
				<td> Peminjaman asset ditolak </td>
			</tr>
		</table>
		@else
		<b><i>Accepting Peminjaman Asset</i></b>, berikut rinciannya:
		<table style="text-align: left;margin: 5px;">
			<tr>
				<th>Name</th>
				<th> : </th>
				<td>{{$req_asset->nama_barang}}</td>
			</tr>
			<tr>
				<th>Description</th>
				<th> : </th>
				<td>{{$req_asset->description}}</td>
			</tr>
			<tr>
				<th>Request By</th>
				<th> : </th>
				<td>{{$req_asset->name}}</td>
			</tr>
			<tr>
				<th>Tanggal Peminjaman</th>
				<th> : </th>
				<td>{{date('d-M-Y', strtotime($req_asset->created_at))}}</td>
			</tr>
			<tr>
				<th>Note</th>
				<th> : </th>
				<td>{{$req_asset->keterangan}}</td>
			</tr>
		</table>
		@endif
	@elseif($status == 'proses')
		<p>
			Hello {{$users->name}},
		</p>
		@if($req_asset->status == 'PENDING')
			<b>Request Asset sedang Diproses</b>, berikut rinciannya:
			<table style="text-align: left;margin: 5px;">
				<tr>
					<th>Tanggal Acc</th>
					<th> : </th>
					<!-- <td>2021-02-14</td> -->
					<td>{{date('d-M-Y', strtotime($req_asset->updated_at))}}</td>
				</tr>
				<tr>
					<th>Status</th>
					<th> : </th>
					<!-- <td>2021-02-14</td> -->
					<td><label style="padding: 5px;background-color: #f39c12;color: white">{{$req_asset->status}}</label></td>
				</tr>
			</table>
			<table style="text-align: left;margin: 5px;" class="tableBarang">
				<tr style="border: solid 1px">
					<th width="40%">Name / Merk</th>
					<th width="5%">Qty</th>
					<th width="55%">Note(link)</th>			
				</tr>
				<tr>
					<td>{{$req_asset->nama}} {{$req_asset->merk}}</td>
					<td>{{$req_asset->qty}}</td>
					<td style="color: blue">{!!substr($req_asset->link,0,35)!!}...</td>
				</tr>
			</table>
		@elseif($req_asset->status == 'ACCEPT')
			<b>Barang Sudah datang</b>, berikut rinciannya:
			<table style="text-align: left;margin: 5px;">
				<tr>
					<th>Tanggal sampai</th>
					<th> : </th>
					<!-- <td>2021-02-14</td> -->
					<td>{{date('d-M-Y', strtotime($req_asset->updated_at))}}</td>
				</tr>
				<tr>
					<th>Status</th>
					<th> : </th>
					<td><label style="padding: 5px;background-color: #008d4c;color: white">{{$req_asset->status}}</label></td>
				</tr>
			</table>
			<table style="text-align: left;margin: 5px;" class="tableBarang">
				<tr style="border: solid 1px">
					<th width="20%">Name / Merk</th>
					<th width="5%">Qty</th>
					<th width="25%">Note(link)</th>			
				</tr>
				<tr>
					<td>{{$req_asset->nama}} {{$req_asset->merk}}</td>
					<td>{{$req_asset->qty}}</td>
					<td style="color: blue">{!!substr($req_asset->link,0,35)!!}...</td>
				</tr>
			</table>
		@endif	
	@endif	
	<br>
	<p>
		Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Tech - Dev</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 – Indonesia |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div>