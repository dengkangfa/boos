<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Transformers\ContactTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactsController extends ApiController
{
    public function show(Request $request, $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->first();

        if (!$contact) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($contact, new ContactTransformer(true));
    }
}
