<?php

namespace App\Http\Controllers;

use App\Collect;
use App\Inventory;
use App\Local;
use App\Patrimony;
use App\Responsible;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
    public function create()
    {
        return view('inventory.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
         * image é o nome do campo que faz upload do CSV
         */
        request()->validate([
            'image' => 'required',
        ]);

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();

        request()->image->move(public_path('uploads'), $imageName);

        $inventory = new Inventory();

        $inventory->year = $request->input('year');

        $inventory->filename = 'uploads/' . $imageName;

        $inventory->final_prevision = $request->input('final_prevision');

        $inventory->description = $request->input('description');

        $inventory->observation = $request->input('observation');

        $inventory->finished = false;

        $inventory->final_filename = "";

        $inventory->save();

        $this->importCSV($inventory);

        return redirect('home')
            ->with('success', 'Inventário cadastrado com sucesso!');
    }

    /**
     * Função que faz a importação do CSV enviado pelo usuário
     *
     * @param string $filename
     * @return boolean with result of import
     */

    private function importCSV(Inventory $inventory)
    {
        $csv = file_get_contents($inventory->filename);

        $array = array_map(function ($v) {
            return str_getcsv($v, ";");
        }, explode("\n", $csv));

        /**
         * 0 -> LOCAL
         * 1 ->
         * 2 -> TOMBO
         * 3 -> TOMBO_OLD
         * 4 -> RESPONSAVEL
         * 5 -> DESCRIÇÃO DO ITEM
         */
        foreach ($array as $item) {
            if (count($item) < 6)
                break;
            /**
             * Procura a referencia do responsável pelo item, caso não encontre, cria um novo
             */

            $responsible = null;

            if (Responsible::where('value', $item[4])->count()) {
                $responsible = Responsible::where('value', $item[4])->get();
            } else {
                $responsible = new Responsible();
                $responsible->value = $item[4];
                $responsible->save();
            }

            /**
             * Procura a referencia do local do item, caso não encontre, cria um novo
             */
            $local = null;

            if (Local::where('value', $item[0])->where('inventory_id', $inventory->id)->count()) {
                $local = Local::where('value', $item[0])->where('inventory_id', $inventory->id)->get();
            } else {
                $local = new Local();
                $local->value = $item[0];
                $local->inventory_id = $inventory->id;
                $local->save();
            }

            /**
             * Procura a referencia do estado do item, caso não encontre, cria um novo
             */
            /**
             * ATUALIZAÇÃO
             * 11/07/2019 recebi um csv sem campo para estado do item
             * Será considerado BOM
             */
            $state = null;

            if(State::where('value','BOM')->count()){
                $state = State::where('value','BOM')->get();
            }else{
                $state = State::create([
                        'value' => 'BOM'
                    ]);
            }

        }
        foreach ($array as $item) {
            if (count($item) < 6)
                break;

            /**
             * Procura a referencia do estado do item, caso não encontre, cria um novo
             */
            $patrimony = null;
            $state = State::where('value', 'BOM')->get();
            $local = Local::where('value', $item[0])->where('inventory_id', $inventory->id)->get();
	    $responsible = Responsible::where('value', $item[4])->get();

            if (Patrimony::where('tombo', $item[2])->where('inventory_id', $inventory->id)->count()) {
                $patrimony = Patrimony::where('tombo', $item[2])->where('inventory_id', $inventory->id)->get();
            } else {
                $patrimony = new Patrimony();
                $patrimony->tombo = $item[2];
                $patrimony->tombo_old = $item[3] != '' ? $item[3] : null;
                $patrimony->description = substr($item[5], 0, 500);
                $patrimony->state_id = $state->first()->id;
                $patrimony->local_id = $local->first()->id;
                $patrimony->responsible_id = $responsible->first()->id;
                $patrimony->inventory_id = $inventory->id;
                $patrimony->save();
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Inventory $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        return view('inventory.show')
            ->with('inventory', $inventory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Inventory $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        return view('inventory.edit')
            ->with('inventory', $inventory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Inventory $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        $inventory->year = $request->input('year');

        $inventory->final_prevision = $request->input('final_prevision');

        $inventory->description = $request->input('description');

        $inventory->observation = $request->input('observation');

        $inventory->finished = $request->input('finished') == 'on' ? 1 : 0;

        $inventory->save();

        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Inventory $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        /**
         * Caso seja necessário excluir os registros, descomentar as linhas abaixo
         */
        /*Collect::where('inventory_id', '=', $inventory->id)->delete();
        Patrimony::where('inventory_id', '=', $inventory->id)->delete();
        Local::where('inventory_id', '=', $inventory->id)->delete();
        */Inventory::where('id', '=', $inventory->id)->delete();

        return redirect('home')
            ->with('success', 'Inventário removido com sucesso!');
    }

    public function relatories(Inventory $inventory)
    {
        return view('inventory.relatories')
            ->with('inventory', $inventory);
    }

    public function relatoryFinal(Inventory $inventory)
    {

        return view('inventory.relatory.final')
            ->with('route', 'inventory.relatory.listFinal')
            ->with('title', 'Relatório Final')
            ->with('inventory', $inventory);
    }

    public function listFinal(Inventory $inventory)
    {
        $collects = Collect::query()
            ->leftJoin('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
            ->leftJoin('responsibles as responsible_collects', function ($join) {
                $join->on('collects.responsible_id', '=', 'responsible_collects.id');
            })
            ->leftJoin('responsibles as responsible_patrimonies', function ($join) {
                $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
            })
            ->leftJoin('states as state_collects', function ($join) {
                $join->on('collects.state_id', '=', 'state_collects.id');
            })
            ->leftJoin('states as state_patrimonies', function ($join) {
                $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
            })
            ->leftJoin('users', 'collects.user_id', '=', 'users.id')
//            ->join('locals', 'collects.local_id', '=', 'locals.id')
            ->join('locals as local_antigo', 'patrimonies.local_id', '=', 'local_antigo.id')
            ->join('locals as local_novo', 'collects.local_id', '=', 'local_novo.id')
            ->select('collects.observation as observation', 'local_antigo.value as local_antigo', 'local_novo.value as local_novo', 'collects.created_at as data', 'users.name as coletor')
            ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
            ->selectRaw('if( collects.tombo_old != 0 , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
            ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
            ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
            ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
            ->where('collects.inventory_id', '=', $inventory->id);

        return Datatables::of($collects)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsible_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("responsible_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo', function ($query, $keyword) {
                $query->whereRaw("collects.tombo like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo_old', function ($query, $keyword) {
                $query->whereRaw("collects.tombo_old like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("collects.description like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.description like ?", ["%$keyword%"]);
            })
            ->filterColumn('estado', function ($query, $keyword) {
                $query->whereRaw("state_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("state_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('observation', function ($query, $keyword) {
                $query->whereRaw("collects.observation like ?", ["%$keyword%"]);
            })
            ->filterColumn('local_antigo', function ($query, $keyword) {
                $query->whereRaw("local_antigo.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('local_novo', function ($query, $keyword) {
                $query->whereRaw("local_novo.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('coletor', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function relatoryDuplicado(Inventory $inventory)
    {

        return view('inventory.relatory.duplicado')
            ->with('route', 'inventory.relatory.listDuplicado')
            ->with('title', 'Relatório de itens duplicados')
            ->with('inventory', $inventory);
    }

    public function listDuplicado(Inventory $inventory)
    {
        $collects = Collect::query()
            ->groupBy('patrimony_id')
            ->select('patrimony_id')
            ->selectRaw('count(*) as count')
            ->selectRaw('\'\' as tombo')
            ->where('patrimony_id', '!=', null)
            ->get();

        $duplicados = \collect();
        foreach ($collects as $duplicity) {
            $duplicado = \collect();
            if ($duplicity->count > 1) {
                $item = Patrimony::where('id', '=', $duplicity->patrimony_id)->first();
                $duplicity->tombo = $item->tombo;
                $duplicity->description = $item->description;

                $tmp = Collect::query()
                    ->leftJoin('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
                    ->leftJoin('responsibles as responsible_collects', function ($join) {
                        $join->on('collects.responsible_id', '=', 'responsible_collects.id');
                    })
                    ->leftJoin('responsibles as responsible_patrimonies', function ($join) {
                        $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
                    })
                    ->leftJoin('states as state_collects', function ($join) {
                        $join->on('collects.state_id', '=', 'state_collects.id');
                    })
                    ->leftJoin('states as state_patrimonies', function ($join) {
                        $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
                    })
                    ->leftJoin('users as user', function ($join) {
                        $join->on('collects.user_id', '=', 'user.id');
                    })
                    ->join('locals', 'collects.local_id', '=', 'locals.id')
                    ->select('collects.observation as observation', 'locals.value as local', 'collects.created_at as data')
                    ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
                    ->selectRaw('if( collects.tombo_old != 0 , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
                    ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
                    ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
                    ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
                    ->selectRaw('user.name as equipe')
                    ->where('collects.patrimony_id', '=', $duplicity->patrimony_id)->get();

                $txt = '';
//                    'Tombo Antigo' .
//                    '| Local ' .
//                    '| Responsável' .
//                    '| Estado ' .
//                    '| Observação|' .
//                    '| Equipe' .
//                    '| Data';
                foreach ($tmp as $t)
                {
                    $txt .= ' -> '.$t->local.
                        ' | '.$t->equipe.
                        ' | '.$t->data;
                }

                $duplicity->description .= $txt;

                foreach ($tmp as $t)
                    $duplicado->push($t);
                $duplicity->duplicados = $duplicado;
                $duplicados->push($duplicity);
            }

        }

        return Datatables::of($duplicados)
            ->make(true);
    }

    public function relatoryPerdido(Inventory $inventory)
    {

        return view('inventory.relatory.perdido')
            ->with('route', 'inventory.relatory.listPerdido')
            ->with('title', 'Relatório de itens não encontrados')
            ->with('inventory', $inventory);
    }

    public function listPerdido(Inventory $inventory)
    {
        $patrimonies = Patrimony::query()
            ->join('responsibles as responsible', function ($join) {
                $join->on('patrimonies.responsible_id', '=', 'responsible.id');
            })
            ->join('states as state', function ($join) {
                $join->on('patrimonies.state_id', '=', 'state.id');
            })
            ->join('locals as local', function ($join) {
                $join->on('patrimonies.local_id', '=', 'local.id');
            })
            ->select('local.value as local', 'patrimonies.tombo as tombo', 'patrimonies.tombo_old as tombo_old', 'patrimonies.description as description', 'responsible.value as responsible', 'state.value as estado')
            ->where('patrimonies.collected', '=', 0);
//            ->first();
//dd($patrimonies);
        return Datatables::of($patrimonies)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsible.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo', function ($query, $keyword) {
                $query->whereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo_old', function ($query, $keyword) {
                $query->whereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("patrimonies.description like ?", ["%$keyword%"]);
            })
            ->filterColumn('estado', function ($query, $keyword) {
                $query->whereRaw("state.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("local.value like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function relatoryAvariado(Inventory $inventory)
    {

        return view('inventory.relatory.final')
            ->with('route', 'inventory.relatory.listAvariado')
            ->with('title', 'Relatório de itens avariados')
            ->with('inventory', $inventory);
    }

    public function listAvariado(Inventory $inventory)
    {
        $collects = Collect::query()
            ->leftJoin('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
            ->leftJoin('responsibles as responsible_collects', function ($join) {
                $join->on('collects.responsible_id', '=', 'responsible_collects.id');
            })
            ->leftJoin('responsibles as responsible_patrimonies', function ($join) {
                $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
            })
            ->leftJoin('states as state_collects', function ($join) {
                $join->on('collects.state_id', '=', 'state_collects.id');
            })
            ->leftJoin('states as state_patrimonies', function ($join) {
                $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
            })
            ->join('locals', 'collects.local_id', '=', 'locals.id')
            ->select('collects.observation as observation', 'locals.value as local', 'collects.created_at as data')
            ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
            ->selectRaw('if( collects.tombo_old != 0 , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
            ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
            ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
            ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
            ->where('collects.inventory_id', '=', $inventory->id)
            ->where('collects.patrimony_id', '!=', null)
            ->where('collects.state_id', '!=', 1);
//        dd($collects);

        return Datatables::of($collects)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsible_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("responsible_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo', function ($query, $keyword) {
                $query->whereRaw("collects.tombo like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo_old', function ($query, $keyword) {
                $query->whereRaw("collects.tombo_old like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("collects.description like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.description like ?", ["%$keyword%"]);
            })
            ->filterColumn('estado', function ($query, $keyword) {
                $query->whereRaw("state_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("state_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('observation', function ($query, $keyword) {
                $query->whereRaw("collects.observation like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("locals.value like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function relatoryLocalizacao(Inventory $inventory)
    {
//        $collects = $inventory->collects->where('patrimony_id', '!=', null);
//
//        $myArray = array();
//        foreach ($collects as $collect)
//        {
//            if($collect->patrimony->local != $collect->local)
//            {
////                $myArray[] = Collect::find($collect->id);
//                $myArray[] = $collect->id;
//            }
//        }
        return view('inventory.relatory.localizacao')
            ->with('route', 'inventory.relatory.listLocalizacao')
            ->with('title', 'Relatório de itens com localização alterada')
            ->with('inventory', $inventory);
//            ->with('errados', $myArray);
    }

    public function listLocalizacao(Inventory $inventory)
    {
        $collects = $inventory->collects->where('patrimony_id', '!=' ,null);

//        dd($collects);
        $filtered = $collects->reject(function ($collect) {
            return $collect->local_id == $collect->patrimony->local_id;
        });

        return Datatables::of($filtered)
            ->make(true);
//            ->filterColumn('responsible', function ($query, $keyword) {
//                $query->whereRaw("responsible_collects.value like ?", ["%$keyword%"])
//                    ->orWhereRaw("responsible_patrimonies.value like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('tombo', function ($query, $keyword) {
//                $query->whereRaw("collects.tombo like ?", ["%$keyword%"])
//                    ->orWhereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('tombo_old', function ($query, $keyword) {
//                $query->whereRaw("collects.tombo_old like ?", ["%$keyword%"])
//                    ->orWhereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('description', function ($query, $keyword) {
//                $query->whereRaw("collects.description like ?", ["%$keyword%"])
//                    ->orWhereRaw("patrimonies.description like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('estado', function ($query, $keyword) {
//                $query->whereRaw("state_collects.value like ?", ["%$keyword%"])
//                    ->orWhereRaw("state_patrimonies.value like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('observation', function ($query, $keyword) {
//                $query->whereRaw("collects.observation like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('local_antigo', function ($query, $keyword) {
//                $query->whereRaw("local_antigo.value like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('local_novo', function ($query, $keyword) {
//                $query->whereRaw("local_novo.value like ?", ["%$keyword%"]);
//            })
//
//        $collects = Collect::query()
//            ->join('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
//            ->join('responsibles as responsible_collects', function ($join) {
//                $join->on('collects.responsible_id', '=', 'responsible_collects.id');
//            })
//            ->join('responsibles as responsible_patrimonies', function ($join) {
//                $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
//            })
//            ->join('states as state_collects', function ($join) {
//                $join->on('collects.state_id', '=', 'state_collects.id');
//            })
//            ->join('states as state_patrimonies', function ($join) {
//                $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
//            })
//            ->join('locals as local_antigo', 'patrimonies.local_id', '=', 'local_antigo.id')
//            ->join('locals as local_novo', 'collects.local_id', '=', 'local_novo.id')
//            ->select('collects.observation as observation', 'local_antigo.value as local_antigo', 'local_novo.value as local_novo', 'collects.created_at as data')
//            ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
//            ->selectRaw('if( collects.tombo_old != 0 , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
//            ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
//            ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
//            ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
//            ->where([
//                ['local_novo.id', '!=', ''],
//                ['collects.local_id', '!=', 'patrimonies.local_id'],
//                ['patrimonies.id', '!=', null],
//                ['collects.inventory_id', '=', $inventory->id]
//                ]);

    }

    public function relatoryObservacao(Inventory $inventory)
    {

        return view('inventory.relatory.final')
            ->with('route', 'inventory.relatory.listObservacao')
            ->with('title', 'Relatório de itens com observacões')
            ->with('inventory', $inventory);
    }


    public function listObservacao(Inventory $inventory)
    {
        $collects = Collect::query()
            ->leftJoin('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
            ->leftJoin('responsibles as responsible_collects', function ($join) {
                $join->on('collects.responsible_id', '=', 'responsible_collects.id');
            })
            ->leftJoin('responsibles as responsible_patrimonies', function ($join) {
                $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
            })
            ->leftJoin('states as state_collects', function ($join) {
                $join->on('collects.state_id', '=', 'state_collects.id');
            })
            ->leftJoin('states as state_patrimonies', function ($join) {
                $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
            })
            ->leftJoin('users', 'collects.user_id', '=', 'users.id')
            ->leftJoin('locals as local_antigo', 'patrimonies.local_id', '=', 'local_antigo.id')
            ->leftJoin('locals as local_novo', 'collects.local_id', '=', 'local_novo.id')
            ->select('local_antigo.value as local_antigo', 'local_novo.value as local_novo','collects.observation as observation', 'collects.created_at as data', 'users.name as coletor')
            ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
            ->selectRaw('if( collects.tombo_old != 0 , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
            ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
            ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
            ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
            ->where('collects.inventory_id', '=', $inventory->id)
            ->where('collects.observation', '!=', null);
//        dd($collects);

        return Datatables::of($collects)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsible_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("responsible_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo', function ($query, $keyword) {
                $query->whereRaw("collects.tombo like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo_old', function ($query, $keyword) {
                $query->whereRaw("collects.tombo_old like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("collects.description like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.description like ?", ["%$keyword%"]);
            })
            ->filterColumn('estado', function ($query, $keyword) {
                $query->whereRaw("state_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("state_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('observation', function ($query, $keyword) {
                $query->whereRaw("collects.observation like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("locals.value like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function relatoryGeral(Inventory $inventory)
    {
        $locals = Local::get();
        $states = State::get();
        $responsibles = Responsible::get();

        return view('inventory.relatory.geral')
            ->with('locals', $locals)
            ->with('states', $states)
            ->with('responsibles', $responsibles)
            ->with('title', 'Busca Personalizada')
            ->with('inventory', $inventory);
    }

    public function search(Request $request, Inventory $inventory)
    {
        $collects = Collect::query()
            ->leftJoin('patrimonies', 'collects.patrimony_id', '=', 'patrimonies.id')
            ->leftJoin('responsibles as responsible_collects', function ($join) {
                $join->on('collects.responsible_id', '=', 'responsible_collects.id');
            })
            ->leftJoin('responsibles as responsible_patrimonies', function ($join) {
                $join->on('patrimonies.responsible_id', '=', 'responsible_patrimonies.id');
            })
            ->leftJoin('states as state_collects', function ($join) {
                $join->on('collects.state_id', '=', 'state_collects.id');
            })
            ->leftJoin('states as state_patrimonies', function ($join) {
                $join->on('patrimonies.state_id', '=', 'state_patrimonies.id');
            })
            ->join('locals', 'collects.local_id', '=', 'locals.id')
            ->select('collects.observation as observation', 'locals.value as local', 'collects.created_at as data')
            ->selectRaw('if( collects.tombo != 0 , collects.tombo, patrimonies.tombo) as tombo')
            ->selectRaw('if( collects.tombo_old != \'\' , collects.tombo_old, patrimonies.tombo_old) as tombo_old')
            ->selectRaw('if( collects.description != \'\' , collects.description, patrimonies.description) as description')
            ->selectRaw('if( responsible_collects.value != \'\' , responsible_collects.value, responsible_patrimonies.value) as responsible')
            ->selectRaw('if( state_collects.value != \'\' , state_collects.value, state_patrimonies.value) as estado')
            ->where('collects.inventory_id', '=', $inventory->id);
        if ($request->input('tombo')) {
            $collects->Where('patrimonies.tombo', '=', $request->input('tombo'));
        }
        if ($request->input('tombo_old')) {
            $collects->where(function ($query) use ($request) {
                $query->where('patrimonies.tombo_old', '=', $request->input('tombo_old'))
                    ->orWhere('collects.tombo_old', '=', $request->input('tombo_old'));
            });
        }
        if ($request->input('description')) {
            $collects->where(function ($query) use ($request) {
                $query->where('patrimonies.description', 'like', '%' . $request->input('description') . '%')
                    ->orWhere('collects.description', 'like', '%' . $request->input('description') . '%');
            });
        }
        if ($request->input('observation')) {
            $collects->Where('collects.observation', 'like', '%' . $request->input('observation') . '%');
        }
        if ($request->input('local')) {
            $collects->Where('collects.local_id', '=', $request->input('local') );
        }
        if ($request->input('responsible')) {
            $collects->where(function ($query) use ($request) {
                $query->where('patrimonies.responsible_id', '=', $request->input('responsible'))
                    ->orWhere('collects.responsible_id', '=', $request->input('responsible'));
            });
        }
        if ($request->input('state')) {
            $collects->where(function ($query) use ($request) {
                $query->where('patrimonies.state_id', '=', $request->input('state'))
                    ->orWhere('collects.state_id', '=', $request->input('state'));
            });
        }

        return Datatables::of($collects)
            ->filterColumn('responsible', function ($query, $keyword) {
                $query->whereRaw("responsible_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("responsible_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo', function ($query, $keyword) {
                $query->whereRaw("collects.tombo like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo like ?", ["%$keyword%"]);
            })
            ->filterColumn('tombo_old', function ($query, $keyword) {
                $query->whereRaw("collects.tombo_old like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.tombo_old like ?", ["%$keyword%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("collects.description like ?", ["%$keyword%"])
                    ->orWhereRaw("patrimonies.description like ?", ["%$keyword%"]);
            })
            ->filterColumn('estado', function ($query, $keyword) {
                $query->whereRaw("state_collects.value like ?", ["%$keyword%"])
                    ->orWhereRaw("state_patrimonies.value like ?", ["%$keyword%"]);
            })
            ->filterColumn('observation', function ($query, $keyword) {
                $query->whereRaw("collects.observation like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("locals.value like ?", ["%$keyword%"]);
            })
            ->make(true);

    }
}
