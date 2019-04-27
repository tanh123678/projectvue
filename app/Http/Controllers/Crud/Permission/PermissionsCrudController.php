<?php

namespace App\Http\Controllers\Crud\Permission;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsCrudController extends Controller
{
    public function index()
    {
        extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));
        $fields = ['id','name','display_name','created_at','updated_at'];
        $model =  new Permission;
        $data = $model->select($fields);
        if (isset($query) && $query) {
            $data = $byColumn == 1 ?
                $this->filterByColumn($data, $query) :
                $this->filter($data, $query, $fields);
        }
        $count = $data->count();
        $data = $data->limit($limit)
            ->skip($limit * ($page - 1));

        if (isset($orderBy)) {
            $direction = $ascending == 0 ? 'ASC' : 'DESC';
            $data = $data->orderBy($orderBy, $direction);
        }

        $results = $data->get()->toArray();

        return [
            'data' => $results,
            'count' => $count,
        ];
    }
    public function destroy($id)
    {
        $role = Permission::find($id);
        $role->delete();
        return response()->json(['msg'=>'success'], 200);        
    }
    protected function filterByColumn($data, $queries)
    {
        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                if (is_string($query)) {
                    $q->where($field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', $query['start'])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $query['end'])->endOfDay();

                    $q->whereBetween($field, [$start, $end]);
                }
            }
        });
    }
    protected function filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }
    public function store(Request $request)
    {
        try {
            $old_Permission = Permission::where('name', $request->name)->where('display_name', $request->display_name)->first();
            $Permission = new Permission;
            $Permission->name = $request->name;
            $Permission->display_name = $request->display_name;
            $Permission->save();
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errors()], 500);
        }
    }
    public function create()
    {
        //
    }
    public function show($id)
    {
        return Permission::findOrFail($id);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        $Permission = Permission::find($id);
        $Permission->name = $request->name;
        $Permission->display_name = $request->display_name;
        $Permission->save();
    }

}
