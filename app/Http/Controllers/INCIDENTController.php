<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;
use Auth;
use DB;
use PDF;
use Excel;

class INCIDENTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $datas = DB::table('dvg_im')
                        ->select('no','date','chase','user','division','status','solution','time','impact')
                        ->get();

        if (Auth::User()->id_position == 'ADMIN') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'ADMIN')
                            ->get();
        } elseif (Auth::User()->id_position == 'HR MANAGER') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'HRD')
                            ->get();
        } elseif (Auth::User()->id_division == 'FINANCE') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        return view('DVG/im/incident',compact('datas', 'notifClaim'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $month_im = substr($request['date'],5,2);
         $year_im = substr($request['date'],0,4);

         $array_bln = array('01' => "I" ,
                            '02' => "II",
                            '03' => "III",
                            '04' => "IV",
                            '05' => "V",
                            '06' => "VI",
                            '07' => "VII",
                            '08' => "VIII",
                            '09' => "IX",
                            '10' => "X",
                            '11' => "XI",
                            '12' => "XII");
         $bln = $array_bln[$month_im];

         $inc = DB::table('dvg_im')
                    ->select('no')
                    ->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
         }elseif($nomor > 9 && $nomor < 100){
            $nomor = '0' . $nomor;
         }elseif($nomor >= 100){
            $nomor = $nomor;
         }

        $division = $request['division'];
        $no_im = $nomor.'/'. $division .'/'. 'IM' .'/'. $bln .'/'. $year_im;

        $tambah = new Incident();
        $tambah->no = $no_im;
        $tambah->date = $request['date'];
        $tambah->chase = $request['chase'];
        $tambah->user = $request['user'];
        $tambah->division = $request['division'];
        $tambah->status = $request['status'];
        $tambah->solution = $request['solution'];
        $tambah->time = $request['time'];
        $tambah->impact = $request['impact'];
        $tambah->save();

        return redirect()->to('/incident_management');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $no = $request['edit_no'];

        $update = Incident::where('no', $no)->first();
        $update->no = $request['edit_no'];
        $update->date = $request['edit_date'];
        $update->chase = $request['edit_chase'];
        $update->user = $request['edit_user'];
        $update->division = $request['edit_division'];
        $update->status = $request['edit_status'];
        $update->solution = $request['edit_solution'];
        $update->time = $request['edit_time'];
        $update->impact = $request['edit_impact'];
        $update->update();

        return redirect('incident_management');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($no)
    {
        $hapus = Incident::find($no);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function downloadPDF()
    {
        $datas = DB::table('dvg_im')
                        ->select('no','date','chase','user','division','status','solution','time','impact')
                        ->get();

        $pdf = PDF::loadView('DVG.im.im_pdf', compact('datas'));
        return $pdf->download('report_incident_management'.date("d-m-Y").'.pdf');
    }

     public function exportExcelIM(Request $request)
    {
        $nama = 'Incident Management'.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Rekap Case', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:J1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('RECAP CASE'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });


        $datas = Incident::select('no','date','chase','user','division','status','solution','time','impact')
                    ->get();

        // $produks = pamProduk::select('id_pam','name_product','qty')
        //         ->get();

            // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO","DATE", "CASE", "USER", "DIVISION", "STATUS", "SOLUTION",  "TIME", "IMPACT");
             $i=1;

            foreach ($datas as $data) {
                       // $sheet->appendrow($data);
                foreach ($datas as $data) {
                    if($data->no == $data->no)
                     $datasheet[$i] = array($i,


                        $data['date'],
                        $data['chase'],
                        $data['user'],
                        $data['division'],
                        $data['status'],
                        $data['solution'],
                        $data['time'],
                        $data['impact'],
                    );
                  $i++;
                }        
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}