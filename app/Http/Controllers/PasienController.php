<?php

namespace App\Http\Controllers;

use App\Pasien;
use DataTables;
use Illuminate\Http\Request;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\ConditionalMessage;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\MessageToTopic;
use Kreait\Firebase\Util\JSON;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
            $firebase = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                        ->create();
    
            $database = $firebase->getDatabase();
            $createPost = $database->getReference('Pasien')->getvalue();     
    
            // Proses
            foreach($createPost as $subject)
            {
                $subjectArray[] = $subject;
            }
            
            return DataTables::of($subjectArray)
                                ->addIndexColumn()
                                ->addColumn('aksi', function($subjectArray){
                                    return '<button type="button" onclick="editData()" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i><b>Edit Data</b></button>';
                                })
                                ->rawColumns(['aksi'])
                                ->make(true);
        }

        return view('Pasien.index');
    }


    public function location()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                    ->create();

        $database = $firebase->getDatabase();
        $tracklog = $database->getReference('Tracklog')->getvalue();      

        // Proses
        foreach($tracklog as $subject)
        {
            $subjectTracklog[] = $subject;
        }
        
        // return $subjecttracklog;
        return view('Maps.maps', compact('subjectTracklog'));
    }

    public function getLocation()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                    ->create();

        $database = $firebase->getDatabase();
        $lokasi = $database->getReference('Lokasi')->getvalue();      

        return $lokasi;
    }

    public function testNotif()
    {
        // Api Key
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();

        $messaging = $firebase->getMessaging();
        
        // $message = CloudMessage::withTarget('topic', 'topic')
        //     // ->withNotification(Notification::create('Title', 'Body'))
        //     ->withNotification(['Title' => 'judul', 'Body' => 'judul'])
        //     ->withData(['coba' => 'value']);
    
        // return $messaging;
        $data = ['foo' => 'bar'];

        $notification = [
            'title' => 'Notification title',
            'body' => 'Notification body',
        ];

        $deviceRegistrationToken = '...';

        $messages = [
            MessageToTopic::create('my-topic')
                ->withData($data)
                ->withNotification($notification),

            [
                'topic' => 'another-topic',
                'data' => $data,
                'notification' => $notification,
            ],

            MessageToRegistrationToken::create($deviceRegistrationToken)
                ->withData($data)
                ->withNotification($notification),

            [
                'token' => $deviceRegistrationToken,
                'data' => $data,
                'notification' => $notification,
            ],

            ConditionalMessage::create("'dogs' in topics || 'cats' in topics")
                ->withData($data)
                ->withNotification($notification),

            [
                'condition' => "'dogs' in topics || 'cats' in topics",
                'data' => $data,
                'notification' => $notification,
            ],
        ];

        array_map(function ($message) use ($messaging) {
            try {
                echo 'Sending '.PHP_EOL.JSON::encode($message).PHP_EOL;
                $messaging->send($message);
                echo 'SUCCESS'.PHP_EOL;
            } catch (MessagingException $e) {
                echo 'ERROR: '.$e->getMessage();
            }
        }, $messages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'ID_Pasien' => 'required|string',
            'Nama_Pasien' => 'required|string',
            'No_Telp' => 'required|numeric',
        ]);

        // Proses
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                        ->create();

        $database = $firebase->getDatabase();
        $createPost = $database
                        ->getReference('Pasien')
                        ->push([
                            // Ganti dengan Request;
                            'ID_Pasien' => $request->ID_Pasien,
                            'Nama_Pasien' => $request->Nama_Pasien,
                            'Jenis_Kelamin' => $request->Jenis_Kelamin,
                            'Nomor_Telp' => $request->No_Telp,
                        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function show(Pasien $pasien)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                    ->create();

        $database = $firebase->getDatabase(); 
        // $createPost = $database->getReference('Pasien')->getChild('ID_Pasien')->getValue();     
        // $createPost = $database->getReference('Pasien')->getSnapshot()->getValue();    
        // $createPost = $database->getReference('Pasien')->getChild('ID_Pasien')->getKey();    
        $createPost = $database->getReference('Pasien')->getChild('ID_Pasien')->getKey();    

        // Proses
        // foreach($createPost as $subject)
        // {
        //     $subjectArray[] = $subject;
        // }

        // return $subjectArray;
        return $createPost;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pasien $pasien)
    {
        // Validasi
        $request->validate([
            'ID_Pasien' => 'required|string',
            'Nama_Pasien' => 'required|string',
            'No_Telp' => 'required|numeric',
        ]);

        // Proses
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://lokasipasien-d02da.firebaseio.com')
                        ->create();

        $database = $firebase->getDatabase();
        $createPost = $database
                        ->getReference('Pasien', '')
                        ->set([
                            // Ganti dengan Request;
                            'ID_Pasien' => $request->ID_Pasien,
                            'Nama_Pasien' => $request->Nama_Pasien,
                            'Jenis_Kelamin' => $request->Jenis_Kelamin,
                            'Nomor_Telp' => $request->No_Telp,
                        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pasien $pasien)
    {
        //
    }
}
