<?php

namespace App\Http\Controllers;

use App\Collect;
use App\Inventory;
use App\Local;
use Auth;
use App\Patrimony;
use App\Responsible;
use App\State;
use Illuminate\Http\Request;

class CollectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Local $local)
    {
        $inventory = $local->inventory()->first();
        $states = State::get();
        $responsibles = $inventory->responsibles()->get();
        return view('collect.collect', ['local' => $local, 'responsibles' => $responsibles, 'states' => $states]);
    }

    public function archive(Inventory $inventory)
    {
        $locals = $inventory->locals()->get();
        $states = State::get();
        $responsibles = $inventory->responsibles()->get();
        return view('collect.archive', ['locals' => $locals, 'inventory' => $inventory, 'responsibles' => $responsibles, 'states' => $states]);
    }

    public function archive_old(Inventory $inventory)
    {
        $locals = $inventory->locals()->get();
        $states = State::get();
        $responsibles = $inventory->responsibles()->get();
        return view('collect.archive_old', ['locals' => $locals, 'inventory' => $inventory, 'responsibles' => $responsibles, 'states' => $states]);
    }

    public function storeArchive(Request $request, Inventory $inventory)
    {
        request()->validate([
            'image' => 'required',
        ]);

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();

        request()->image->move(public_path('uploads'), $imageName);

        $filename = 'uploads/' . $imageName;

        $csv = file_get_contents($filename);

        $array = array_map(function ($v) {
            return str_getcsv($v, ";");
        }, explode("\n", $csv));

        $local = Local::find($request->input('local'));
        $responsible = Responsible::find($request->input('responsible'));
        $state = State::find($request->input('state'));

        $cont = 0;
        foreach ($array as $item) {
            if($item[0] != null){
                $patrimony = $inventory->patrimonies()->where('tombo', '=', $item[0])->first();
//                Patrimony::where('tombo', '=', $item[0])->where('inventory_id', $inventory->id)->first();
                if($patrimony){
                    $collect = new Collect();
                    $collect->tombo = $item[0];
                    $collect->local_id = $local->id;
                    $collect->inventory_id = $inventory->id;
                    $collect->patrimony_id = $patrimony->id;
                    $collect->user_id = Auth::user()->id;
                    $collect->responsible_id = $responsible->id;
                    $collect->state_id = $state->id;
                    $collect->save();
                    $cont++;
                    $patrimony->collected = 1;
                    $patrimony->save();
                }
            }
        }

        return view('inventory.show')
            ->with('inventory', $inventory)
            ->with('success', $cont.' itens importados.');
    }

    public function storeArchiveTomboAntigo(Request $request, Inventory $inventory)
    {
        request()->validate([
            'image' => 'required',
        ]);

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();

        request()->image->move(public_path('uploads'), $imageName);

        $filename = 'uploads/' . $imageName;

        $csv = file_get_contents($filename);

        $array = array_map(function ($v) {
            return str_getcsv($v, ";");
        }, explode("\n", $csv));

        $local = Local::find($request->input('local'));
        $responsible = Responsible::find($request->input('responsible'));
        $state = State::find($request->input('state'));

        $cont = 0;
        foreach ($array as $item) {
            if($item[0] != null){
                $patrimony = $inventory->patrimonies()->where('tombo_old', '=', $item[0])->first();
//                Patrimony::where('tombo', '=', $item[0])->where('inventory_id', $inventory->id)->first();
                if($patrimony){
                    $collect = new Collect();
                    $collect->tombo_old = $item[0];
                    $collect->local_id = $local->id;
                    $collect->inventory_id = $inventory->id;
                    $collect->patrimony_id = $patrimony->id;
                    $collect->user_id = Auth::user()->id;
                    $collect->responsible_id = $responsible->id;
                    $collect->state_id = $state->id;
                    $collect->save();
                    $cont++;
                    $patrimony->collected = 1;
                    $patrimony->save();
                }
            }
        }

        return view('inventory.show')
            ->with('inventory', $inventory)
            ->with('success', $cont.' itens importados.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $collect = Collect::find($request->input('id'));

        if ($collect) {
            $collect->tombo = $request->input('tombo');
            $collect->tombo_old = $request->input('tombo_old');
            $collect->description = $request->input('description');
            $collect->observation = $request->input('observation');
            $collect->responsible_id = $request->input('responsible');
            $collect->state_id = $request->input('state');
        } else {
            if($request->input('tombo_proep')){
                $collect = new Collect();
                $local = Local::find($request->input('local_id_proep'));
                $collect->local_id = $local->id;
                $collect->inventory_id = $local->inventory()->first()->id;
                $collect->user_id = Auth::user()->id;
                $collect->tombo_proep = $request->input('tombo_proep');
                $collect->description = ($request->input('description_proep')? $request->input('description_proep') : null);
                $collect->observation = ($request->input('observation_proep')? $request->input('observation_proep')." - Item PROEP" : " - Item PROEP");
                $collect->responsible_id = ($request->input('responsible_proep')? $request->input('responsible_proep') : null);
                $collect->state_id = ($request->input('state_proep')? $request->input('state_proep') : null);
                $collect->save();
            }
            else if($request->input('description_sem_pat')){
                $collect = new Collect();
                $local = Local::find($request->input('local_id_sem_pat'));
                $collect->local_id = $local->id;
                $collect->inventory_id = $local->inventory()->first()->id;
                $collect->user_id = Auth::user()->id;
                $collect->description = ($request->input('description_sem_pat')? $request->input('description_sem_pat') : null);
                $collect->observation = ($request->input('observation_sem_pat')? $request->input('observation_sem_pat')." - Item Sem Patrimônio" : " - Item Sem Patrimônio");
                $collect->responsible_id = ($request->input('responsible_sem_pat')? $request->input('responsible_sem_pat') : null);
                $collect->state_id = ($request->input('state_sem_pat')? $request->input('state_sem_pat') : null);
                $collect->save();
            }else {
                $collect = new Collect();
                $local = Local::find($request->input('local_id'));
                $collect->local_id = $local->id;
                $collect->inventory_id = $local->inventory()->first()->id;
                $collect->user_id = Auth::user()->id;
                $collect->tombo_old = $request->input('tombo_old');
                $collect->description = $request->input('description');
                $collect->observation = $request->input('observation');
                $collect->responsible_id = $request->input('responsible');
                $collect->state_id = $request->input('state');
                $collect->save();
            }
        }
        $collect->save();

        return redirect(url()->previous());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Collect $collect
     * @return \Illuminate\Http\Response
     */
    public function show(Collect $collect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Collect $collect
     * @return \Illuminate\Http\Response
     */
    public function edit(Collect $collect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Collect $collect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collect $collect)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Collect $collect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collect $collect)
    {
        //
    }

    public function ajax(Request $request)
    {
//        dd($request);
        $tombo = $request->input('tombo');

        $local_id = $request->input('local_id');

        $antigo = $request->input('select');

        $local = Local::all()->find($local_id);

        $inventory = $local->inventory;

        if($antigo == "true"){
            $patrimony = $inventory->patrimonies()->where('tombo_old', '=', $tombo)->first();
        }
        else{
            $patrimony = $inventory->patrimonies()->where('tombo', '=', $tombo)->first();
        }

        $collectedhere = false;

        foreach ($patrimony->collects()->get() as $collect) {
            if($collect->local_id == $local_id)
                $collectedhere = true;
        }

        $responsible = $patrimony->responsible;

        $state = $patrimony->state;

        $collect = null;
        if (!$collectedhere) {
            $collect = new Collect();
            $collect->local_id = $local_id;
            $collect->inventory_id = $patrimony->inventory_id;
            $collect->tombo_old = $patrimony->tombo_old;
            $collect->patrimony_id = $patrimony->id;
            $collect->user_id = Auth::user()->id;
            $collect->observation = null;
            $collect->save();
        } else {
            $collect = Collect::where('patrimony_id', '=', $patrimony->id)->first();
        }

        $ret = array([
            'collect_id' => $collect->id,
            'tombo' => $patrimony->tombo,
            'tombo_old' => $collect->tombo_old == null ? $patrimony->tombo_old : $collect->tombo_old,
            'description' => $collect->description == null ? $patrimony->description : $collect->description,
            'observation' => $collect->observation,
            'collected' => $patrimony->collected,
            'collects' => $patrimony->collects()->get(),
            'responsible' => $collect->responsible()->first() ? json_decode(json_encode($collect->responsible()->first())) : json_decode(json_encode($responsible)),
            'state' => $collect->state()->first() ? json_decode(json_encode($collect->state()->first())) : json_decode(json_encode($state)),
            'collectedhere' => $collectedhere
        ]);
//        dd($collect->responsible,$responsible,$ret);

        $patrimony->collected = 1;

        $patrimony->save();

        return json_encode($ret);
    }

    public function ajaxDualCollect(Request $request)
    {
        $tombo = $request->input('tombo');

        $local_id = $request->input('local_id');

        $antigo = $request->input('select');

        $local = Local::all()->find($local_id);

        $inventory = $local->inventory;

        if($antigo == "true"){
            $patrimony = $inventory->patrimonies()->where('tombo_old', '=', $tombo)->first();
        }
        else{
            $patrimony = $inventory->patrimonies()->where('tombo', '=', $tombo)->first();
        }

        $responsible = $patrimony->responsible()->first();

        $collect = new Collect();
        $collect->local_id = $local_id;
        $collect->inventory_id = $patrimony->inventory_id;
        $collect->patrimony_id = $patrimony->id;
        $collect->user_id = Auth::user()->id;
        $collect->save();

        $ret = array([
            'collect_id' => $collect->id,
            'tombo' => $patrimony->tombo,
            'tombo_old' => $patrimony->tombo_old,
            'description' => $patrimony->description,
            'observation' => $collect->observation,
            'collected' => $patrimony->collected,
            'local' => json_decode(json_encode($local)),
            'responsible' => json_decode(json_encode($responsible))
        ]);

        return json_encode($ret);
    }
}
