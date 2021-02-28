<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\Award;
use App\Models\PlayerSchedule;
use App\Models\Solo;
use App\Models\Team;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:games.index|games.create|games.edit|games.delete']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::latest()->get();

        return view('admin.game.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.game.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:3000',
            'background'    => 'required|image|mimes:jpeg,jpg,png|max:10000',
            'name'          => 'required|unique:games'
        ]);

        //upload image
        $image = $request->file('image');
        $background = $request->file('background');
        $image->storeAs('public/games', $image->hashName());
        $background->storeAs('public/gameBackgrounds', $background->hashName());

        $game = Game::create([
            'image'       => $image->hashName(),
            'background'  => $background->hashName(),
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('name'), '-')
        ]);


        if($game){
            Solo::create([
                'game_id' => $game->id
            ]);
            Team::create([
                'game_id' => $game->id
            ]);
            //redirect dengan pesan sukses
            return redirect()->route('admin.game.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.game.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('admin.game.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $this->validate($request,[
            'image'         => 'image|mimes:jpeg,jpg,png|max:3000',
            'background'    => 'image|mimes:jpeg,jpg,png|max:10000',
            'name'          => 'required|unique:games,name,'.$game->id 
        ]);


        if ($request->file('image') == "" && $request->file('background') == "") {
        
            $game = Game::findOrFail($game->id);
            $game->update([
                'name'        => $request->input('name'),
                'slug'        => Str::slug($request->input('name'), '-')
            ]);

        } else if ($request->file('background') == "" && $request->file('image') != "") {
            //remove old image
            $image_name = basename($game->image).PHP_EOL;
            Storage::disk('local')->delete('public/games/'. $image_name);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/games', $image->hashName());

            $game = Game::findOrFail($game->id);
            $game->update([
                'image'       => $image->hashName(),
                'name'        => $request->input('name'),
                'slug'        => Str::slug($request->input('name'), '-')
            ]);

        } else if ($request->file('background') != "" && $request->file('image') == "") {
            //remove old background
            $background_name = basename($game->background).PHP_EOL;
            Storage::disk('local')->delete('public/gameBackgrounds/'. $background_name);
            //upload new background
            $background = $request->file('background');
            $background->storeAs('public/gameBackgrounds', $background->hashName());

            $game = Game::findOrFail($game->id);
            $game->update([
                'background'  => $background->hashName(),
                'name'        => $request->input('name'),
                'slug'        => Str::slug($request->input('name'), '-')
            ]);
        } else {
            //remove old image
            $image_name = basename($game->image).PHP_EOL;
            Storage::disk('local')->delete('public/games/'. $image_name);

            //remove old background
            $background_name = basename($game->background).PHP_EOL;
            Storage::disk('local')->delete('public/gameBackgrounds/'. $background_name);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/games', $image->hashName());
            //upload new background
            $background = $request->file('background');
            $background->storeAs('public/gameBackgrounds', $background->hashName());


            $game = Game::findOrFail($game->id);
            $game->update([
                'image'       => $image->hashName(),
                'background'  => $background->hashName(),
                'name'        => $request->input('name'),
                'slug'        => Str::slug($request->input('name'), '-')
            ]);

        }

        if($game){
            //redirect dengan pesan sukses
            return redirect()->route('admin.game.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.game.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $image_name = basename($game->image).PHP_EOL;
        Storage::disk('local')->delete('public/games/'. $image_name);
        $background_name = basename($game->background).PHP_EOL;
        Storage::disk('local')->delete('public/gameBackgrounds/'. $background_name);
        $game->delete();
        // hapus aktivasi dan schedule
        $activations = Activation::where('game_id',$id);
        $activations->delete();
        $player_schedules = PlayerSchedule::where('game_id',$id);
        $player_schedules->delete();
        if($game){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    /**
     * Show game populate with solo data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showSolo(Game $game)
    {
        // $types = 
        return view('admin.game.solo', compact('game'));
    }

    /**
     * Show game populate with team data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showTeam(Game $game)
    {
        // $types = 
        return view('admin.game.team', compact('game'));
    }


    /**
     * Show awards in type
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showAward(Game $game, Type $type)
    {
        return view('admin.game.award', compact('game','type'));
    }

    /**
     * Create awards in type
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function storeAward(Game $game, Type $type, Request $request)
    {
        $this->validate($request,['nominal' => 'required']);
        $award = Award::create([
            'nominal' => $request->nominal,
            'type_id' => $type->id
        ]);
        if($award){
            //redirect dengan pesan sukses
            return redirect()->route('admin.award.show', ['game' => $game->id, 'type' => $type->id])->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.award.show', ['game' => $game->id, 'type' => $type->id])->with(['error' => 'Data Gagal Diupdate!']);
        }
    
    }

    /**
     * delete awards in type
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteAward(Game $game, Type $type, $id)
    {
        $award = Award::findOrFail($id);
        
        $award->delete();

        if($award){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        } 
    
    }
}
