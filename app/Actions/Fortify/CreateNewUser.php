<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use Hotash\Authable\Registrar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Features;
use Laravel\Jetstream\HasTeams;
use Laravel\Jetstream\Jetstream;
use Symfony\Component\HttpFoundation\Response;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return Model
     */
    public function create(array $input)
    {
        $model = Registrar::model();

        abort_unless(in_array(Features::registration(), Registrar::features('fortify')), Response::HTTP_NOT_FOUND);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique((new $model)->getTable())],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input, &$model) {
            return tap($model::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function ($user) {
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  Model  $user
     * @return void
     */
    protected function createTeam($user)
    {
        if (! in_array(HasTeams::class, class_uses($user))) {
            return;
        }

        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
