<?php

use App\Livewire\ConferenceSignUpPage;
use Illuminate\Support\Facades\Route;

Route::get('/conference-sign-up/{conference}', ConferenceSignUpPage::class)->name('conference-sign-up');
