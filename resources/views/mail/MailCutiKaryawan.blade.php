<style type="text/css">
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	@if($hari['cuti_accept']->status == 'v')
	<p>
		Hello Sinergy,
		<br>Hore Cuti Kamu di Approve! Berikut Detail cuti kamu:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$name_cuti->name}}</td>
		</tr>
		<tr>
			<th>Lama Cuti</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->days}} hari</td>
		</tr>
		<tr>
			<th>Tanggal Off cuti <b style="color: green">(approved)</b></th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@foreach($ardetil as $data)
				<ul>
					
					<li>
						{{date('d-M-Y', strtotime($data))}}
					</li>
					
				</ul>
				@endforeach
			</td>
		</tr>
		<tr>
			<th>Tanggal Off cuti <b style="color: red">(rejected)<b></th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@if($ardetil_after != null)
					@foreach($ardetil_after as $data)
					<ul>
						
						<li>
							{{date('d-M-Y', strtotime($data))}}
						</li>
						
					</ul>
					@endforeach
				@else
				-
				@endif				
			</td>
		</tr>
		<tr>
			<th>
				Alasan reject cuti
			</th>
			<th> : </th>
			<td>{{$hari['cuti_reject']->decline_reason}}</td>
		</tr>
		<tr>
			<th>Tanggal Request Cuti</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($hari['cuti_reject']->date_req))}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->reason_leave}}</td>
		</tr>
	</table>
	<br>
	@elseif($hari['cuti_accept']->status == 'd')
	<p>
		Hello Sinergy,
		<br>Sorry Cuti Kamu di Decline sama Bos! Berikut Detail cuti kamu:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$name_cuti->name}}</td>
		</tr>
		<tr>
			<th>Lama Cuti</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->days}} hari</td>
		</tr>
		<tr>
			<th>Tanggal Off cuti sbb</th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@foreach($ardetil as $data)
				<ul>
					
					<li>
						{{date('d-M-Y', strtotime($data))}}
					</li>
					
				</ul>
				@endforeach
			</td>
		</tr>
		<tr>
			<th>Tanggal Request Cuti</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->reason_leave}}</td>
		</tr>
		<tr>
			<th>Decline Reason</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->decline_reason}}</td>
		</tr>
	</table>
	<br>
	@elseif($hari['cuti_accept']->status == 'R')
	<p>
		Hai Bos,
		<br>Berikut Re-schedule Perizinan Cuti oleh:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$name_cuti->name}}</td>
		</tr>
		<tr>
			<th>Lama Cuti</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->days}} hari</td>
		</tr>
		<tr>
			<th>Tanggal Off cuti sebelumnya sbb</th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@foreach($ardetil as $data)
				<ul>
					
					<li>
						{{date('d-M-Y', strtotime($data))}}
					</li>
					
				</ul>
				@endforeach
			</td>
		</tr>
		@if($ardetil_after != "")
		<tr>
			<th>Tanggal Off cuti re-schedule sbb</th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@foreach($ardetil_after as $data)
				<ul>
					
					<li>
						{{date('d-M-Y', strtotime($data))}}
					</li>
					
				</ul>
				@endforeach
			</td>
		</tr>
		@endif
		<tr>
			<th>Tanggal Request Cuti</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
		</tr>
	</table>
	<br>
	@else
	<p>
		Hai Bos,
		<br>Berikut Perizinan Cuti oleh:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$name_cuti->name}}</td>
		</tr>
		<tr>
			<th>Lama Cuti</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->days}} hari</td>
		</tr>
		<tr>
			<th>Tanggal Off cuti sbb</th>
			<th> : </th>
		</tr>
		<tr>
			<td>
				@foreach($ardetil as $data)
				<ul>
					
					<li>
						{{date('d-M-Y', strtotime($data))}}
					</li>
					
				</ul>
				@endforeach
			</td>
		</tr>
		<tr>
			<th>Tanggal Request Cuti</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{{$hari['cuti_accept']->reason_leave}}</td>
		</tr>
	</table>
	<br>
	@endif
	Silahkan klik link berikut ini untuk melihat Detail Cuti.<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url('/show_cuti')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Leaving Permite.
							</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
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