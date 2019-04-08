<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Session;
use App\Query;


class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $id_counter = 4;

    public function index()
    {
        $result = array();

        $contents = "http://admin:admin@localhost:8984/rest/AB2/classes.xml";
        $data = simplexml_load_string(file_get_contents($contents));

        foreach($data as $d) {
            $r = [
                'id' => ((array) $d->attributes()->id)[0],
                'domainName' => ((array) $d->domainName)[0],
                'grade' => ((array) $d->grade)[0],
                'year' => ((array) $d->year)[0]
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

        $query = 'let%20$up%20:=%20<class%20id="'.$id.'"><domainName>'.$data['domainName'].'</domainName><grade>'.$data['grade'].'</grade><year>'.$data['year'].'</year></class>%20return%20insert%20node%20$up%20as%20last%20into%20doc(%27AB2/classes.xml%27)/classes';
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

    public function getByYear($year)
    {
        $result = array();

        $query = '<classes>{%20for%20$c%20in%20//classes/class%20where%20$c/year='.$year.'%20return%20$c%20}</classes>';
        $contents = "http://admin:admin@localhost:8984/rest/AB2/classes.xml?query=".$query;
        $data = simplexml_load_string(file_get_contents($contents));

        foreach($data as $d) {
            $r = [
                'id' => ((array) $d->attributes()->id)[0],
                'domainName' => ((array) $d->domainName)[0],
                'grade' => ((array) $d->grade)[0],
                'year' => ((array) $d->year)[0]
            ];
            array_push($result, $r);
        }

        return response()->json(['data' => $result]);
    }

    public function getStudents($id)
    {
        $result = array();

        $query = '<students>{%20for%20$s%20in%20//students/student%20where%20$s/classId='.$id.'%20return%20$s%20}</students>';
        $contents = "http://admin:admin@localhost:8984/rest/AB2?query=".$query;
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
        $query = 'let%20$up%20:=%20for%20$c%20in%20//classes/class%20where%20$c/@id='.$id.'%20return%20$c%20return%20delete%20node%20$up';
        $contents = "http://admin:admin@localhost:8984/rest/AB2/classes.xml?query=".$query;
        file_get_contents($contents);

        return response()->json(['data' => 'Success!']);
    }
}
