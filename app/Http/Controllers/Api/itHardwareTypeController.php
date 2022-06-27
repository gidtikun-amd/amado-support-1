<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\it_hardware_type;
use App\it_hardware;

class itHardwareTypeController extends Controller
{
    public function index(){
        $assettype = it_hardware_type::orderBy('it_hw_type_name')->get();
        return response()->json($assettype);
    }

    public function store(Request $request){

        $assettype = new it_hardware_type([
                'it_hw_type_name' => $request->get('type_name')
            ]);
        
        $result = $assettype->save();
        return response()->json($result);

    }

    public function edit($id){
        $assettype = it_hardware_type::where('it_hw_type_id',$id)->get();
        $result = $assettype[0];
        return response()->json($result);
    }

    public function update(Request $request, $id){

        $assettype = it_hardware_type::where('it_hw_type_id',$id)
            ->update([
                'it_hw_type_name' => $request->get('type_name')
            ]);
        
        $result = $assettype;
        return response()->json($result);

    }

    public function destroy($id){

        if( $this->checkExistTypeInHardware($id) > 0 ){

            return response()->json([
                'Status' => false,
                'ErrMessage' => 'ระบบไม่สามารถลบประเภททรัพย์สิน เนื่องจากในประเภททรัพย์สินมีข้อมูลทรัพย์สินอยู่'
            ]);

        }
        else{
            $result = it_hardware_type::where('it_hw_type_id',$id)->delete();
            return response()->json([ 'Status' => $result ]);
        }

    }

    private function checkExistTypeInHardware($id){
        $count = it_hardware::where('it_hw_type_id',$id)->count();
        return $count;
    }

}
