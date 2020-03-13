@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  .modalIconsubject input[type=text]{
      padding-left:115px;
    }

    .modalIconsubject.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIconsubject.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIconsubject{
      position:relative;
    }

   .modalIconsubject i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }
</style>
<section class="content">

  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
  @endif

  @if (session('danger'))
    <div class="alert alert-danger" id="alert">
        {{ session('danger') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-primary" id="alert">
      {{ session('success') }}
    </div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success" id="alert">
      {{ session('alert') }}
    </div>
  @endif

  @if ($message = Session::get('qty-done'))
  <div class="alert alert-danger alert-block" id="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong style="">{{ $message }}</strong>
  </div>
  @endif

  <div class="box">
    <div class="box-body">
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="kategori" aria-selected="false">List Asset</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#peminjaman_asset" role="tab" aria-controls="home" aria-selected="true">Request ATK</a>
          </li>
          @if(Auth::User()->id_division == 'HR')
          <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#request_pr" role="tab" aria-controls="home" aria-selected="true">Request PR</a>
          </li>
          @endif
          @if(Auth::User()->id_division == 'HR')
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"> </i>&nbsp Add Asset</button>
          @else
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#peminjaman_modal" style="width: 150px;"><i class="fa fa-plus"> </i>&nbsp Request ATK</button>
          @endif
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered nowrap " id="data_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Description</th>
                    @if(Auth::User()->id_division == 'HR')
                    <th>Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" hidden></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->unit}}</td>
                    <td>{{$data->description}}</td>
                    @if(Auth::User()->id_division == 'HR')
                    <td>
                      @if($data->status == 'NN')
                      <a href="{{url('/asset_atk/detail_asset_atk', $data->id_barang) }}"><button class="btn btn-xs" style="width: 80px; height: 25px; background-color: black;color: white ">Detail</button></a>
                      @else
                      <button class="btn btn-xs" disabled style="width: 80px; height: 25px; background-color: black;color: white ">Detail</button>
                      @endif
                      <button class="btn btn-xs btn-primary" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#modaledit" onclick="edit_asset('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->description}}')">Edit</button>
                      <button class="btn btn-xs btn-warning" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#modalrestock" onclick="update_stok('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->qty}}', '{{$data->description}}')">Restock</button>
                    </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @if(Auth::User()->id_division == 'HR')
          <div class="tab-pane fade" id="peminjaman_asset" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Nama</th>
                    <th>Tgl Request</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($assetsd as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>
                      @if($data->qty_akhir != null)
                      {{$data->qty_akhir}}
                      @else 
                      0
                      @endif
                    </td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open" style="width: 90px">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'PROSES')
                        <label class="status-sd" style="width: 90px">PROSES PR</label>
                      @elseif($data->status == 'DONE')
                        <label class="status-tp" style="width: 90px">DONE</label>
                      @endif
                    </td>
                    <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-xs btn-success" id="btn_accept" name="btn_accept" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}')">ACCEPT</button>
                      <button class="btn btn-xs btn-danger" id="btn_reject" name="btn_reject" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}')">REJECT</button>
                      @elseif($data->status == 'PROSES')
                      <button class="btn btn-xs btn-primary" id="btn-done" data-target="#done_modal" data-toggle="modal" name="btn_done" value="{{$data->id_transaction}}" style="width: 90px; height: 25px" onclick="update_done_pr('{{$data->id_transaction}}', '{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_request}}', '{{$data->nama_barang}}')">DONE</button>
                      @elseif($data->status == 'DONE')
                      <button class="btn btn-xs btn-primary disabled" style="width: 90px; height: 25px">DONE</button>
                      @else
                      <button class="btn btn-xs btn-success disabled" style="width: 90px; height: 25px;">ACCEPT</button>
                      <button class="btn btn-xs btn-danger disabled" style="width: 90px; height: 25px;">REJECT</button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @else 
          <div class="tab-pane fade" id="peminjaman_asset" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Qty Available</th>
                    <th>Qty Request</th>
                    <th>Description</th>
                    <th>Tgl Request</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($pinjaman as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>
                      @if($data->status == 'PROSES' || $data->status == 'DONE')
                      {{$data->qty_awal}}
                      @elseif($data->status == 'PENDING' || $data->status == 'ACCEPT' || $data->status == 'REJECT')
                      {{$data->qty_akhir}}
                      @elseif($data->qty_awal == 0) 
                      0
                      @endif
                    </td>
                    <td>
                      @if($data->qty_request != null)
                      {{$data->qty_request}}
                      @else
                      0
                      @endif
                    </td>
                    <td>{{$data->keterangan}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'PROSES')
                        <label class="status-sd" style="width: 90px">PROSES PR</label>
                      @elseif($data->status == 'DONE')
                        <label class="status-tp" style="width: 90px">DONE</label>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @endif
          @if(Auth::User()->id_division == 'HR')
          <div class="tab-pane fade" id="request_pr" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="pr_request" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Qty Request</th>
                    <th>Description</th>
                    <th>Nama</th>
                    <th>Tgl Request</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($pr_request as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>
                      @if($data->qty_request != null)
                      {{$data->qty_request}}
                      @else 
                      0
                      @endif
                    </td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open" style="width: 90px">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'PROSES')
                        <label class="status-sd" style="width: 90px">PROSES PR</label>
                      @elseif($data->status == 'DONE')
                        <label class="status-tp" style="width: 90px">DONE</label>
                      @endif
                    </td>
                    <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-xs btn-success" id="btn_accept" name="btn_accept" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}')">ACCEPT</button>
                      <button class="btn btn-xs btn-danger" id="btn_reject" name="btn_reject" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}')">REJECT</button>
                      @elseif($data->status == 'PROSES')
                      <button class="btn btn-xs btn-primary" id="btn-done" data-target="#done_modal" data-toggle="modal" name="btn_done" value="{{$data->id_transaction}}" style="width: 90px; height: 25px" onclick="update_done_pr('{{$data->id_transaction}}', '{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_request}}', '{{$data->nama_barang}}')">DONE</button>
                      @elseif($data->status == 'DONE')
                      <button class="btn btn-xs btn-primary disabled" style="width: 90px; height: 25px">DONE</button>
                      @else
                      <button class="btn btn-xs btn-success disabled" style="width: 90px; height: 25px;">ACCEPT</button>
                      <button class="btn btn-xs btn-danger disabled" style="width: 90px; height: 25px;">REJECT</button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<style type="text/css">
   .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }

</style>

<!--add asset-->
<div class="modal fade" id="add_asset" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/store_asset_atk')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="sow">Qty</label>
            <input name="qty" id="qty" type="number" class="form-control" required="">
          </div>
          <div class="form-group">
            <label>Unit</label>
            <select class="form-control" name="unit" id="unit">
              <option value="">Select Unit</option>
              @foreach($unit_assets as $unit_asset)
              <option value="{{$unit_asset->unit}}">{{$unit_asset->unit}}</option>
              @endforeach
            </select>
            <label class="hover-biru" style="color:#002280;">Unit belum ada?</label>
          </div>
          <div class="form-group">
            <label for="sow">Deskripsi</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit asset-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/edit_atk')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" name="id_barang_edit" id="id_barang_edit" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_edit" id="nama_barang_edit" class="form-control">
          </div>
          <div class="form-group">
            <label for="sow">Deskripsi</label>
            <textarea name="deskripsi_edit" id="deskripsi_edit" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-warning" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modalrestock" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Stok Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/update_stok')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" name="id_barang_restok" id="id_barang_restok" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_restok" id="nama_barang_restok" class="form-control" readonly>
          </div>
          <div class="form-group">
           <label>Qty Awal</label>
            <input type="text" name="qty_awal_restok" id="qty_awal_restok" class="form-control" readonly>
          </div>
          <div class="form-group">
           <label>Qty Masuk</label>
            <input type="number" name="qty_masuk_restok" id="qty_masuk_restok" class="form-control">
          </div>
          <div class="form-group">
            <label for="sow">Deskripsi</label>
            <textarea name="deskripsi_restok" id="deskripsi_restok" class="form-control" readonly></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-warning" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="peminjaman_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Request ATK</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/request_atk')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <select name="atk" id="atk" class="form-control" style="width: 270px;" required >
              <option>Select Name</option>
              @foreach($atk as $atk)
              <option value="{{$atk->id_barang}}">{{$atk->nama_barang}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="sow">Jumlah Stock</label>
            <input name="qty" type="number" class="form-control qty" readonly>
          </div>
          <div class="form-group margin-left-right">
              @if ($message = Session::get('warning'))
              <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{{ $message }}</strong>
              </div>
              @endif
            </div>
          <div class="form-group">
            <label>Masukkan kebutuhan</label><br>
            <input type="text" name="qtys" id="qtys" class="qtys" hidden>
            <input type='number' name='quantity' id="quantity" value='0' class="form-control" style="width: 270px;" />
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control" name="keterangan"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--modal accept-->
<div class="modal fade" id="accept_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/accept_request')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_update" id="id_barang_update" hidden>
          <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
          <input type="" name="qty_awal_accept" id="qty_awal_accept" hidden>
          <input type="" name="qty_akhir_accept" id="qty_akhir_accept" hidden>
          <div class="form-group">
          	<h4 style="text-align: center;"><b>Are you sure to accept?</b></h4>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp YES</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--REJECT-->
<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/reject_request')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject" id="id_barang_reject" hidden>
          <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
          <input type="" name="qty_awal_reject" id="qty_awal_reject" hidden>
          <input type="" name="qty_akhir_reject" id="qty_akhir_reject" hidden>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>Are you sure to reject?</b></h3>
          </div>
          <div class="form-group">
          	<label>Note</label>
          	<textarea class="form-control" name="note_reject" id="note_reject" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" class="btn btn-xs btn-danger" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp YES</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reject_note_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_transaction_reject2" id="id_transaction_reject2" hidden>
          <div class="form-group">
          	<label>Note</label>
          	<textarea class="form-control" name="note_reject" id="note_reject2" readonly></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="done_modal" role="dialog">
 <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <form method="POST" action="{{url('asset_atk/done_request_pr')}}" id="modalProgress" name="modalProgress">
          @csrf
        <input type="text" name="id_barang_done" id="id_barang_done" hidden>
        <input type="text" name="id_transaction_done" id="id_transaction_done" hidden>
        <input type="text" name="qty_done" id="qty_done" hidden> 
        <input type="text" name="qty_request_done" id="qty_request_done" hidden>
        <div class="form-group">
          <h4 style="text-align: center;"><b>PR Done?</b></h4>
        </div>
        <div class="form-group"> 
          <label>Nama Barang</label>
          <input name="nama_barang_done" id="nama_barang_done" class="form-control" readonly>
        </div>
        <div class="form-group"> 
          <label>Qty Request</label>
          <input name="qty_request_done_field" class="form-control" id="qty_request_done_field" readonly>
        </div>
        <div class="form-group">
          <label>Qty Now</label>
          <input name="qty_now_pr" id="qty_now_pr" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Qty Restock</label>
          <input type="number" name="qty_restock_pr" id="qty_restock_pr" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
          <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp YES</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">

  	function reject_note(id_transaction,note) {
  		$('#id_transaction_reject2').val(id_transaction);
  		$('#note_reject2').val(note);
  	}

    function id_accept_update(id_transaction,id_barang,qty,qty_akhir){
      $('#id_transaction_update').val(id_transaction);
      $('#id_barang_update').val(id_barang);
      $('#qty_awal_accept').val(qty);
      $('#qty_akhir_accept').val(qty_akhir);
    }

    function update_done_pr(id_transaction,id_barang,qty,qty_request,nama_barang) {
      $('#id_transaction_done').val(id_transaction);
      $('#id_barang_done').val(id_barang);
      $('#qty_done').val(qty);
      $('#qty_request_done').val(qty_request);
      $('#nama_barang_done').val(nama_barang);
      $('#qty_request_done_field').val(qty_request);
      $('#qty_now_pr').val(qty);
    }

    $('#atk').select2();


    /*$(document).on('keyup keydown', "input[id^='quantity']", function(e){
      var qty_before  = $(".qty").val();
      console.log(qty_before);
          if ($(this).val() > parseFloat(qty_before)
              && e.keyCode != 46
              && e.keyCode != 8 
             ) {
             e.preventDefault();     
             $(this).val(qty_before);
          }
    });*/

    $('.hover-biru').click(function(){
      var new_unit = prompt("Enter unit :");
      $("#unit").append($('<option>', { value: new_unit, text: new_unit, selected:true }));
    })

    $(".detail-product").select2({
      closeOnSelect : false,
    });

    $('#data_table').DataTable({
    });

    $('#datatable').DataTable({
    });

    $('#pr_request').DataTable({
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $(document).on('change',"select[id^='atk']",function(e) {
      var atk = $('#atk').val();

         $.ajax({
          type:"GET",
          url:'asset_atk/get_qty_atk',
          data:{
            atk:this.value,
          },
          success: function(result,qty){
            $.each(result[0], function(key, value){
              $(".qty").val(value.qty);
            });
          }
        });
    });

    function id_reject_update(id_transaction,id_barang,qty,qty_akhir){
      $('#id_transaction_reject').val(id_transaction);
      $('#id_barang_reject').val(id_barang);
      $('#qty_awal_reject').val(qty);
      $('#qty_akhir_reject').val(qty_akhir);
    }

    function edit_asset(id_barang,nama_barang,description){
      $('#id_barang_edit').val(id_barang);
      $('#nama_barang_edit').val(nama_barang);
      $('#deskripsi_edit').val(description);
    }

    function update_stok(id_barang,nama_barang,qty,description){
      $('#id_barang_restok').val(id_barang);
      $('#nama_barang_restok').val(nama_barang);
      $('#qty_awal_restok').val(qty);
      $('#deskripsi_restok').val(description);
    }

    $('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');
  </script>
@endsection