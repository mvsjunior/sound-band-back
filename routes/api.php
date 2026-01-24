<?php

use App\Domains\Musical\Actions\Playlist\PlaylistListAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/sandbox", function(){
    $action = new PlaylistListAction();
    return response()->json($action->execute());
});
require base_path('app/Domains/Auth/Routes/api.php');
require base_path('app/Domains/Musical/Routes/api.php');

