<?php

namespace App\Livewire;

use App\Models\Attendee;
use App\Models\Conference;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Conference $conference;
    public int $price = 50000;

    public function mount(Conference $conference)
    {
        $this->conference = $conference;
    }

    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->form([
                Repeater::make('attendees')
                    ->schema(Attendee::getFormSchema()),
            ])
            ->slideOver()
            ;
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
