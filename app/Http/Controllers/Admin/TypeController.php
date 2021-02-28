<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Solo;
use App\Models\SoloType;
use App\Models\Team;
use App\Models\TeamType;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:solos.index|solos.create|solos.edit|solos.delete|teams.index|teams.create|teams.edit|teams.delete']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSolo(Game $game, Solo $solo)
    {
        $not_include = [];
        foreach($game->solo->types as $type ) {
            $not_include[] = $type->id;
        }
        
        $types = Type::latest()->whereNotIn('id', $not_include)->get();
        return view('admin.type.createSolo', compact('game','solo','types'));
    }

     /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeSolo(Request $request, Game $game, Solo $solo)
    {
        $this->validate($request,[
            'name'   => 'required|unique:types',
            'description' => 'required',
            'min_player' => 'required|min:0',
            'max_player' => 'required|gte:min_player',
            'rules' => 'required',
            'crown_price' => 'required|min:0'
        ]);

        $type = Type::create([
            'name'        => $request->input('name'),
            'description'        => $request->input('description'),
            'min_player'        => $request->input('min_player'),
            'max_player'        => $request->input('max_player'),
            'rules'        => $request->input('rules'),
            'crown_price'        => $request->input('crown_price'),
        ]);


        if($type){
            SoloType::create([
                'solo_id' => $solo->id,
                'type_id' => $type->id
            ]);
            //redirect dengan pesan sukses
            return redirect()->route('admin.game.solo', $game->id)->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.game.solo', $game->id)->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Store mass type to solo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function storeMassSolo(Request $request, Game $game ,Solo $solo)
    {
        if($request->input('types')) {
            $solo->types()->attach($request->input('types'));
            $solo->save();
            return redirect()->route('admin.game.solo', $game->id)->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.solo.create', ['game'=>$game->id,'solo'=>$solo->id])->with(['error' => 'Harus pilih salah satu template type!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroySolo(Request $request)
    {
        //  dd($request->id);
        //  dd($request->game_id);
        $soloType = SoloType::findOrFail($request->id);
        $soloType->delete();
        if($soloType) {
            return redirect()->route('admin.game.solo', $request->game_id)->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('admin.game.solo',  $request->game_id)->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    // TEAM

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTeam(Game $game, Team $team)
    {
        $not_include = [];
        foreach($game->team->types as $type ) {
            $not_include[] = $type->id;
        }
        
        $types = Type::latest()->whereNotIn('id', $not_include)->get();
        return view('admin.type.createTeam', compact('game','team','types'));
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeTeam(Request $request, Game $game, Team $team)
    {
        $this->validate($request,[
            'name'   => 'required|unique:types',
            'description' => 'required',
            'min_player' => 'required|min:0',
            'max_player' => 'required|gte:min_player',
            'rules' => 'required',
            'crown_price' => 'required|min:0',
        ]);

        $type = Type::create([
            'name'        => $request->input('name'),
            'description'        => $request->input('description'),
            'min_player'        => $request->input('min_player'),
            'max_player'        => $request->input('max_player'),
            'rules'        => $request->input('rules'),
            'crown_price'        => $request->input('crown_price'),
        ]);


        if($type){
            TeamType::create([
                'team_id' => $team->id,
                'type_id' => $type->id
            ]);
            //redirect dengan pesan sukses
            return redirect()->route('admin.game.team', $game->id)->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.game.team', $game->id)->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Store mass type to solo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function storeMassTeam(Request $request, Game $game ,Team $team)
    {
        if($request->input('types')) {
            $team->types()->attach($request->input('types'));
            $team->save();
            return redirect()->route('admin.game.team', $game->id)->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.team.create', ['game'=>$game->id,'team'=>$team->id])->with(['error' => 'Harus pilih salah satu template type!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroyTeam(Request $request)
    {
        //  dd($request->id);
        //  dd($request->game_id);
        $teamType = TeamType::findOrFail($request->id);
        $teamType->delete();
        if($teamType) {
            return redirect()->route('admin.game.team', $request->game_id)->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('admin.game.team',  $request->game_id)->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    /**
     * get all types
     */
    public function index()
    {
        $types = Type::latest()->get();
        return view('admin.type.index', compact('types'));
    }

    /**
     * show create form
     */
    public function create()
    {
        return view('admin.type.create');
    }

    /**
     * store create form
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'   => 'required|unique:types',
            'description' => 'required',
            'min_player' => 'required|min:0',
            'max_player' => 'required|gte:min_player',
            'rules' => 'required',
            'crown_price' => 'required|min:0',
        ]);

        $type = Type::create([
            'name'         => $request->input('name'),
            'description'  => $request->input('description'),
            'min_player'   => $request->input('min_player'),
            'max_player'   => $request->input('max_player'),
            'rules'        => $request->input('rules'),
            'crown_price'  => $request->input('crown_price'),
        ]);


        if($type){

            //redirect dengan pesan sukses
            return redirect()->route('admin.type.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.type.team')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit form
     */

     public function edit(Type $type)
     {
        return view('admin.type.edit', compact('type'));
     }

    /**
     * update type
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request,[
            'name'   => 'required|unique:types,name,' . $type->id,
            'description' => 'required',
            'min_player' => 'required|min:0',
            'max_player' => 'required|gte:min_player',
            'rules' => 'required',
            'crown_price' => 'required|min:0',
        ]);

        $type->name = $request->input('name');
        $type->description = $request->input('description');
        $type->min_player = $request->input('min_player');
        $type->max_player = $request->input('max_player');
        $type->rules = $request->input('rules');
        $type->crown_price = $request->input('crown_price');
        $type->save();

        return redirect()->route('admin.type.index')->with(['success' => 'Data Berhasil Diupdate!']);

    }
}
