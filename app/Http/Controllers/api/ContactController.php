<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function show (Contact $contact){
        return new ContactResource($contact);
    }

    public function store(StoreUpdateContactRequest $request)
    {
        $validated_data = $request->validated();

        if (Auth::user()->username != $validated_data['phone_number']){
            return Response("", 403);
        }

        $sameContactValidator = Validator::make(
            $validated_data,
            ['contact' => [function ($attribute, $value, $fail) use ($validated_data) {
                if (Contact::where('contact', $value)->where('phone_number', $validated_data['phone_number'])->first()) {
                    $fail('A contact with this phone number already exists');
                }
            }]]
        );
        $sameContactValidator->validate();
        
        $new_contact = new Contact($validated_data);

        $new_contact->save();

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
