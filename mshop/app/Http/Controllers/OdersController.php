<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Oders;
use App\Oders_detail;
use DB;

class OdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function getlist()
    {
    	$data = Oders::paginate(10);
    	return view('back-end.oders.list',['data'=>$data]);
    }

    public function getdetail($id)
    {
    	$oder = Oders::where('id',$id)->first();
    	$data = DB::table('oders_detail')
    			 	->join('products', 'products.id', '=', 'oders_detail.pro_id')
    			 	->groupBy('oders_detail.id')
    			 	->where('o_id',$id)
    			 	->get();
    	return view('back-end.oders.detail',['data'=>$data,'oder'=>$oder]);
    }
    public function postdetail($id, Request $rq)
    {
    	$oder = Oders::find($id);
    	$oder->status = $rq->sltlevel;
    	$oder->save();
    	return redirect('admin/donhang')
      	->with(['flash_level'=>'result_msg','flash_massage'=>' Đã xác nhận đơn hàng thành công !']);    	

    }
     public function getdel($id)
    {       
    	$oder = Oders::where('id',$id)->first();
    	if ($oder->status !=0) {
    		return redirect()->back()
    		->with(['flash_level'=>'result_msg','flash_massage'=>'Không thể hủy đơn hàng số: '.$id.' vì đã được xác nhận hoặc đã thực hiện xong!']);
    	} else {
    		$oder = Oders::find($id);
        	$oder->delete();
        	return redirect('admin/donhang')
         	->with(['flash_level'=>'result_msg','flash_massage'=>'Đã hủy bỏ thành công đơn hàng số:  '.$id.' !']);
     	}
    }
}
