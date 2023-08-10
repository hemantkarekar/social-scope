<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function test()
    {
        Mail::to('hemant@sociomark.in')->send(new TestMail());
    }
}
