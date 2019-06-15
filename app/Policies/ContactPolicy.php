<?php

namespace App\Policies;

use App\User;
use App\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;


    /*
     * INTERCETTARE CHIAMATE AI METODI, QUESTO METODO VIENE CHIAMATO PRIMA DI TUTTI GLI ALTRI
     * (HOOK CHE VIENE CHIAMATO OGNI VOLTA CHE SI CHIAMA UN GATE)
     * Utile per permettere ad esempio ad un admin di fare qualsiasi cosa
     */
    public function before($user, $ability)
    {
        // NB: $ability è l'abilità che stiamo chiamando così posso fare dei controlli personalizzati in base alla abilità

        //if($user->isAdmin()){
        //    return true;
        //}
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @param  \App\User  $user
     * @param  \App\Contact  $contact
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        return $user->id === $contact->user_id;
    }

    /**
     * Determine whether the user can create contacts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param  \App\User  $user
     * @param  \App\Contact  $contact
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        return $user->id === $contact->user_id;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param  \App\User  $user
     * @param  \App\Contact  $contact
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->id === $contact->user_id;
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @param  \App\User  $user
     * @param  \App\Contact  $contact
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        return $user->id === $contact->user_id;
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @param  \App\User  $user
     * @param  \App\Contact  $contact
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        return $user->id === $contact->user_id;
    }
}
