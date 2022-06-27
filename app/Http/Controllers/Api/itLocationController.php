<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\it_location;
use App\it_asset_hardware;

class itLocationController extends Controller
{
    public function index(){
        $locations = it_location::orderBy('it_locat_name')->get();
        return response()->json($locations);
    }

    public function store(Request $request){

        $location = new it_location([
                'it_locat_id' => $request->get('locat_id'),
                'it_locat_name' => $request->get('locat_name')
            ]);
        
        $result = $location->save();
        return response()->json($result);

    }

    public function edit($id){
        $locations = it_location::where('it_locat_id',$id)->get();
        $result = $locations[0];
        return response()->json($result);
    }

    public function update(Request $request, $id){

        $result = it_location::where('it_locat_id',$id)
            ->update([ 'it_locat_name' => $request->get('locat_name') ]);

        return response()->json($result);

    }

    public function destroy($id){

        if( $this->checkExistLocationInAssetHw($id) > 0 ){

            return response()->json([
                'Status' => false,
                'ErrMessage' => 'ระบบไม่สามารถลบข้อมูล Location ได้เนื่องจากมีข้อมูลการถือครองทรัพย์สินอยู่'
            ]);

        }
        else{
            $result = it_location::where('it_locat_id',$id)->delete();
            return response()->json([ 'Status' => $result ]);
        }

    }

    private function checkExistLocationInAssetHw($id){
        $result = it_asset_hardware::where('it_locat_id',$id)->count();
        return $result;
    }

}
