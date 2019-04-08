<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Session;
use App\Query;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contents = "http://admin:admin@localhost:8984/rest/AB2/classes.xml";
        $result = simplexml_load_string(file_get_contents($contents));
        foreach($result as $r) {
            $t = $r->domainName->asXML();
            print_r($t);
        }

        $target = htmlspecialchars($_POST['target']);
        $url = 'http://localhost:8080/rest/db/repertorium/xquery/searchBibl.xql';
        $data = array('type' => 'input', 'target' => $target);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        try{
            $session = new Session("localhost", 1984, "admin", "admin");

            // create new database
            $session->execute("create db database");
            //print $session->info();

            // add document
            $session->add("classes.xml", "<classes>
                                                        <class id=\"01\">
                                                            <domainName>Ciência da Computação</domainName>
                                                            <grade>8º período</grade>
                                                            <year>2018</year>
                                                        </class>
                                                        <class id=\"02\">
                                                            <domainName>Matemática</domainName>
                                                            <grade>1º período</grade>
                                                            <year>2017</year>
                                                        </class>
                                                        <class id=\"03\">
                                                            <domainName>Secretariado Bilingue</domainName>
                                                            <grade>4º período</grade>
                                                            <year>2017</year>
                                                        </class>
                                                      </classes>");
            //print "<br/>".$session->info();

            // add document
            $session->add("students.xml", "<students>
                                                            <student id=\"11112222\">
                                                                <name>Anthony</name>
                                                                <age>22</age>
                                                                <address>casa do júlio</address>
                                                                <phone>8299999999</phone>
                                                                <classId>01</classId>
                                                            </student>
                                                            <student id=\"22223333\">
                                                                <name>Júlio</name>
                                                                <age>22</age>
                                                                <address>Casa</address>
                                                                <phone>8299999998</phone>
                                                                <classId>02</classId>
                                                            </student>
                                                            <student id=\"33334444\">
                                                                <name>Myron</name>
                                                                <age>22</age>
                                                                <address>Loteamento Acauã</address>
                                                                <phone>8299999997</phone>
                                                                <classId>03</classId>
                                                            </student>
                                                            <student id=\"44445555\">
                                                                <name>João</name>
                                                                <age>22</age>
                                                                <address>Residencial Tabuleiro dos Martins</address>
                                                                <phone>8299999996</phone>
                                                                <classId>01</classId>
                                                            </student>
                                                            <student id=\"55556666\">
                                                                <name>Drogado</name>
                                                                <age>20</age>
                                                                <address>Maceió</address>
                                                                <phone>8299999995</phone>
                                                                <classId>02</classId>
                                                            </student>
                                                            <student id=\"66667777\">
                                                                <name>Douglas</name>
                                                                <age>22</age>
                                                                <address>Hiper Bompreço</address>
                                                                <phone>8299999994</phone>
                                                                <classId>03</classId>
                                                            </student>
                                                        </students>");
            //print "<br/>".$session->info();

            // run query on database
            //print "<br/>".$session->execute("xquery <classes>{ for \$c in //classes/class where \$c/year=2017 return \$c }</classes>");

            print $session->execute("xquery <classes>{ //classes//domainName }</classes>")."<br/>";

            // drop database
            $session->execute("drop db database");
            // close session
            $session->close();
        } catch (Exception $e) {
            // print exception
            print $e->getMessage();
        }
        print_r($session);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
