<?php

namespace App\Http\Controllers;

use App\Collect;
use App\Inventory;
use App\Local;
use App\Patrimony;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
//use Yajra\Datatables\Facades\Datatables;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Inventory $inventory)
    {
        return view('local.new')
            ->with('inventory', $inventory);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventory = Inventory::find($request->input('inventory_id'));
        $local = new Local();

        $local->value = $request->input('value');

        $local->inventory_id = $request->input('inventory_id');

        $local->save();

        $permission = Permission::create(['name' => 'collect '.$local->value.' - '.$inventory->year]);

        return redirect('inventory/'.$local->inventory_id)
            ->with('success','Inventário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function show(Local $local)
    {
        return view('local.show')
            ->with('local', $local);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function edit(Local $local)
    {
        return view('local.edit')
            ->with('local',$local);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Local $local)
    {
        $local->value = $request->input('value');

        $local->save();

        return redirect('inventory/'.$local->inventory_id)
            ->with('success','Inventário alterado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function destroy(Local $local)
    {
        Collect::where('local_id','=',$local->id)->delete();
        Patrimony::where('local_id','=',$local->id)->delete();
        Local::where('id','=',$local->id)->delete();

        return redirect('inventory/'.$local->inventory_id)
            ->with('success','Inventário removido com sucesso!');
    }

    public function listPatrimoniesCollecteds($id)
    {
        $collects = Collect::query()
            ->leftJoin('patrimonies','patrimonies.id','=','collects.patrimony_id')
            ->leftJoin('responsibles','responsibles.id','=','collects.responsible_id')
            ->leftJoin('responsibles as responsibles_patrimonies','responsibles_patrimonies.id','=','patrimonies.responsible_id')
            ->leftJoin('locals','locals.id','=','collects.local_id')
            ->leftJoin('locals as local_old','local_old.id','=','patrimonies.local_id')
            ->join('users','users.id','=','collects.user_id')
            ->where('collects.local_id','=',$id)
            ->select(DB::raw('
                if(patrimonies.id is not null, patrimonies.tombo,IF(collects.tombo is not null, collects.tombo,"")) as tombo,
                collects.tombo_old as tombo_old,
                if(local_old.id is not null, local_old.value,"") as local,
                if(patrimonies.id is not null, patrimonies.description,IF(collects.description is not null, collects.description,"")) as description,
                if(responsibles.id is not null, responsibles.value, IF(responsibles_patrimonies.id is not null, responsibles_patrimonies.value,"")) as responsible,
                CONCAT(locals.value," - ",collects.created_at," - ",users.name) as collect
                '));

        return Datatables::of($collects)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsibles.value like ?", ["%$keyword%"])
                    ->orWhereRaw("responsibles_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("local_old.value like ?", ["%$keyword%"])
                    ->orWhereRaw("locals.value like ?", ["%$keyword%"]);
            })
//            ->addColumn('collect', function ($patrimony) {
//                $collects = $patrimony->collects()->get();
//                $qtd = count($collects);
//                if($qtd >= 1)
//                {
//                    $string = '';
//                    $cont = 0;
//                    foreach($collects as $collect)
//                    {
//                        $string .= $collect->local()->first()->value . ' - ' . Carbon::createFromFormat('Y-m-d H:i:s', $collect->created_at)->format('d/m/Y H:i') . ' - ' . $collect->user->name;
//                        $cont++;
//                        if($cont!=$qtd)
//                            $string .= ' | ';
//                    }
//                    return $string;
//                }
//                else
//                    return 'Nao coletado';
//            })
            ->make(true);

    }

    public function listPatrimonies($id)
    {
        $local = Local::find($id);
        $patrimonies = $local->patrimonies()->get();
        $patrimonies = Patrimony::query()
            ->join('locals','patrimonies.local_id','=','locals.id')
            ->join('responsibles','patrimonies.responsible_id','=','responsibles.id')
            ->where('patrimonies.local_id','=',$id)
            ->select('locals.value as locals','responsibles.value as responsibles','patrimonies.*');

        return Datatables::of($patrimonies)
            ->filterColumn('responsibles', function ($query, $keyword) {
                $query->whereRaw("responsibles.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('locals', function ($query, $keyword) {
                $query->whereRaw("locals.value like ?", ["%$keyword%"]);
            })
            ->addColumn('collect', function ($patrimony) {
                $collects = $patrimony->collects()->get();
                $qtd = count($collects);
                if($qtd >= 1)
                {
                    $string = '';
                    $cont = 0;
                    foreach($collects as $collect)
                    {
                        $string .= $collect->local()->first()->value . ' - ' . Carbon::createFromFormat('Y-m-d H:i:s', $collect->created_at)->format('d/m/Y H:i') . ' - ' . $collect->user->name;
                        $cont++;
                        if($cont!=$qtd)
                            $string .= ' | ';
                    }
                    return $string;
                }
                else
                    return 'Nao coletado';
            })->make(true);

    }
}
