<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $id_counter = 10;

    public function index()
    {
        $result = array();

        $contents = "http://admin:admin@localhost:8984/rest/AB2/students.xml";
        $data = simplexml_load_string(file_get_contents($contents));

        foreach($data as $d) {
            $r = [
                'id' => ((array) $d->attributes()->id)[0],
                'name' => ((array) $d->name)[0],
                'birth' => ((array) $d->birth)[0],
                'city' => ((array) $d->city)[0],
                'address' => ((array) $d->address)[0],
                'phone' => ((array) $d->phone)[0],
                'classId' => ((array) $d->classId)[0]
            ];
            array_push($result, $r);
        }

        return response()->json(['data' => $result]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->data;
        $id = $this->id_counter++;

        $query = 'let%20$up%20:=%20<student%20id="'.$id.'"><name>'.$data['name'].'</name><birth>'.$data['birth'].'</birth><city>'.$data['city'].'</city><address>'.$data['address'].'</address><phone>'.$data['phone'].'</phone><classId>'.$data['classId'].'</classId></student>%20return%20insert%20node%20$up%20as%20last%20into%20doc(%27AB2/students.xml%27)/students';
        $contents = "http://admin:admin@localhost:8984/rest?query=".$query;
        file_get_contents($contents);

        return response()->json(['data' => 'Success!']);
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

    public function getByCity($city)
    {
        $result = array();

        $query = '<students>{for%20$s%20in%20//students/student%20where%20$s/city[contains(.,%20"'.$city.'")]%20return%20$s}</students>';
        $contents = "http://admin:admin@localhost:8984/rest/AB2/students.xml?query=".$query;
        $data = simplexml_load_string(file_get_contents($contents));

        foreach($data as $d) {
            $r = [
                'id' => ((array) $d->attributes()->id)[0],
                'name' => ((array) $d->name)[0],
                'birth' => ((array) $d->birth)[0],
                'city' => ((array) $d->city)[0],
                'address' => ((array) $d->address)[0],
                'phone' => ((array) $d->phone)[0],
                'classId' => ((array) $d->classId)[0]
            ];
            array_push($result, $r);
        }

        return response()->json(['data' => $result]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = 'let%20$up%20:=%20for%20$s%20in%20//students/student%20where%20$s/@id='.$id.'%20return%20$s%20return%20delete%20node%20$up';
        $contents = "http://admin:admin@localhost:8984/rest/AB2/students.xml?query=".$query;
        file_get_contents($contents);

        return response()->json(['data' => 'Success!']);
    }
}
