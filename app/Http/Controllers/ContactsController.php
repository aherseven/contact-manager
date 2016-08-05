<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Contact;
use App\Group;


class ContactsController extends Controller
{

	private $rules =[
    		'name' => ['required', 'min:3'],
    		'email' => ['required', 'email'],
            'company' => ['required'],
            'photo' => ['mimes:jpg,jpeg,png,gif,bmp']
    		
    	];

    private $upload_dir;

    public function __construct()
    {
        $this->upload_dir = base_path() . '/public/uploads';
    }
/**
 * [index description]
 * @param  Request $request [description]
 * @return [type]           [description]
 */

    public function index(Request $request)
    {

        $contacts = Contact::where(function($query) use ($request){
            //Filter by selected group
            if(($group_id = $request->get('group_id') ) ){
                $query->where('group_id', $group_id);
            }

            if(($term = $request->get('term'))){
                $query->orWhere('name', 'like', '%' . $term . '%');
                $query->orWhere('email', 'like', '%' . $term . '%');
                $query->orWhere('company', 'like', '%' . $term . '%');
            }
        })
        ->latest()
        ->paginate(5);

        

    	return view('contacts.index', compact('contacts'));
    }
    

    public function autocomplete(Request $request)
    {

        //prevent this method called by non ajax
        if($request->ajax()){
            $contacts = Contact::where(function($query) use ($request){
            //Filter by keyword entered

            if(($term = $request->get('term'))){
                $query->orWhere('name', 'like', '%' . $term . '%');
                $query->orWhere('email', 'like', '%' . $term . '%');
                $query->orWhere('company', 'like', '%' . $term . '%');
            }
        })
        ->latest()
        ->take(5)
        ->get();

        //convert to json
        $results = [];
        foreach ($contacts as $contact) {
            $results[] = ['id' => $contact->id, 'value' => $contact->name ];
        }

        return response()->json($results);
        }
        
    }
    
    private function get_request(Request $request)
    {

       $data =  $request->all();

        if($request->hasFile('photo'))
        {
        //get file name
        $photo = rand(1, 99999).''.$request->file('photo')->getClientOriginalName();
        // move file to server
        $destination = base_path() . '/public/uploads';

        $request->file('photo')->move($destination,$photo);

        $data['photo'] = $photo;
       }

       return $data;
    }

/**
 * [create description]
 * @return [type] [description]
 */

    public function create()
    {
    	$groups = Group::pluck('name','id');	
    	return view('contacts.create', compact('groups'));
    }

    public function store(Request $request)
    {
    	
    	
        $this->validate($request, $this->rules);

        $data = $this->get_request($request);


       
    	Contact::create($data);

    	return redirect('contacts')->with('message', 'Contact Saved!');
    }

    
    public function edit($id)
    {
    	$groups = Group::pluck('name','id');
    	$contact = Contact::find($id);
    	return view('contacts.edit', compact('groups','contact'));
    }

    public function update($id, Request $request)
    {
    	$this->validate($request, $this->rules);

    	$contact = Contact::find($id);

    	$contact->update($request->all());

    	return redirect('contacts')->with('message', 'Contact Updated!');
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if(!is_null($contact->photo)){
            $file_path = $this->upload_dir . '/' . $contact->photo;
            if(file_exists($file_path)) unlink($file_path);
        }

        $contact->delete();

     return redirect('contacts')->with('message', 'Contact Deleted!');           
    }
}
