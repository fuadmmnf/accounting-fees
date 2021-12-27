<?php

namespace App\Http\Controllers;

use App\Category;
use App\DefaultFee;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categories->load('defaultfees', 'defaultfees.field');
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $info = $request->validate([
            'name' => 'required',
        ]);


        \DB::beginTransaction();
        try {
            $category = Category::create(['name' => $info['name']]);
            $fields = ['2' => 100, '3' => 160, '7' => 240];
            foreach ($fields as $k => $v) {
                DefaultFee::create([
                    'category_id' => $category->id,
                    'field_id' => $k,
                    'unit' => 1,
                    'amount' => $v
                ]);

            }

        } catch (\Exception $exception) {
            \DB::rollBack();
            return response()->json($exception->getMessage(), 500);
        }
        \DB::commit();
    }

    public
    function show($id)
    {
        //
    }

    public
    function update(Request $request, $id)
    {
        //
    }

    public
    function destroy($id)
    {
        //
    }
}
