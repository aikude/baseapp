<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    function __construct(){
        //add any data that should be accessible on all pages
        $this->pagedata = [];
    }
    
    public function index(){
        $this->pagedata['title'] = 'Welcome home!';
        $this->pagedata['intro'] = 'This is a cool app that meets all your needs.';
        return view('pages.index')->with($this->pagedata);
    }

    public function about(){
        $this->pagedata['title'] = 'About us';
        $this->pagedata['intro'] = 'Info about our company.';
        return view('pages.about')->with($this->pagedata);
    }

    public function services(){
        $this->pagedata['services'] = ['service 1', 'service 2', 'service 3'];
        $this->pagedata['title'] = 'Services Page';
        $this->pagedata['text'] = '';
        return view('pages.services')->with($this->pagedata);
    }
}
