<?php

namespace App\Http\Controllers\Crud\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCrudController extends Controller
{
    public function index()
    {
        // return User::orderBy('id','DESC')->get();
        extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));
        $fields = ['id','name','email','created_at','updated_at'];
        $model =  new User;
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

    public function show($id)
    {
        return User::findOrFail($id);
    }
    public function destroy($id)
    {
        $role = User::find($id);
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
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $old_user = User::where('name' , $request->name)->where('email' , $request->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errors()],500);
        }   
    }

    public function store(Request $request)
    {
        try {
            $old_user = User::where('name' , $request->name)->where('email' , $request->display_name)->first();
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errors()],500);
        }    }

    
}
