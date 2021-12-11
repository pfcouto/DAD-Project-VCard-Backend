<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    public function show (Contact $contact){
        return new ContactResource($contact);
    }

    public function store(StoreUpdateContactRequest $request)
    {
        $validated_data = $request->validated();
        $new_contact = Contact::create($validated_data);

        return new ContactResource($new_contact);
    }
    
    public function update(StoreUpdateContactRequest $request, Contact $contact)
    {
        $validated_data = $request->validated();

        $contact->contact = $validated_data["contact"];
        $contact->name = $validated_data["name"];
        $contact->save();
        
        return new ContactResource($contact);
    }
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return new ContactResource($contact);
    }
}
